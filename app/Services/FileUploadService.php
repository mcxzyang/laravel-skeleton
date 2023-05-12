<?php
/**
 * User: cherrybeal
 * Date: 2018/11/11
 * Time: 5:01 PM
 */

namespace App\Services;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Image;

class FileUploadService
{
    /**
     * Filesystem instance.
     *
     * @var \Illuminate\Filesystem\FilesystemManager
     */
    protected $filesystem;

    /**
     * Create a new ImageUploadService instance.
     *
     * @param \Illuminate\Filesystem\FilesystemManager $filesystem
     */
    public function __construct(FilesystemManager $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Construct the data URL for the JSON body.
     *
     * @param string $mime
     * @param string $content
     *
     * @return string
     */
    protected function getDataUrl($mime, $content)
    {
        $base = base64_encode($content);

        return 'data:'.$mime.';base64,'.$base;
    }

    /**
     * Handle the file upload. Returns the response body on success, or false
     * on failure.
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param string                                              $disk
     * @param string                                              $filename
     * @param string                                              $dir
     *
     * @return array|bool
     */
    public function store(UploadedFile $file, $disk, $filename, $dir = '', $width = 0)
    {
        $hashName = str_ireplace('.jpeg', '.jpg', $filename);

        $dir = $this->formatDir($dir);

        $mime = $file->getMimeType();

        if ($width > 0) {
            $this->reduceSize($file->getRealPath(), $width);
        }

        $path = $this->filesystem->disk($disk)->putFileAs($dir, $file, $hashName);

        if (!$path) {
            throw new \Exception('Failed to store file.');
        }

        return [
            'success' => true,
            'filename' => $hashName,
            'original_name' => $file->getClientOriginalName(),
            'mime' => $mime,
            'size' => $file->getSize(),
            'storage_path' => $path,
            'relative_url' => '/storage/'.$path,
            'url' => Storage::disk($disk)->url($path),
        ];
    }

    public function storeBase64($base64, $res, $disk, $filename, $dir)
    {
        $hashName = str_ireplace('.jpeg', '.jpg', $filename);

        $dir = $this->formatDir($dir);

        // 临时文件
        $tmpName = tempnam("/tmp/", "FOO");
        $handle = fopen($tmpName, "w");
        if (fwrite($handle, base64_decode(str_replace($res[1], '', $base64)))) {
//            $path = $this->filesystem->disk($disk)->putFileAs($dir, $tmpName, $hashName);

            $realPath = $dir.'/'.$hashName;
            $this->filesystem->disk($disk)->put($realPath, file_get_contents($tmpName));

            fclose($handle);
            unlink($tmpName);

            return [
                'success' => true,
                'filename' => $hashName,
                'storage_path' => $realPath,
                'url' => Storage::disk($disk)->url($realPath),
            ];
        }
    }

    public function storeFilm(UploadedFile $file, $disk, $filename, $dir = '', $width = 0)
    {
        $hashName = str_ireplace('.jpeg', '.jpg', $filename);

        $dir = $this->formatDir($dir);

        $mime = $file->getMimeType();

        $path = $this->filesystem->disk($disk)->putFileAs($dir, $file, $hashName);

        if (!$path) {
            throw new \Exception('Failed to store file.');
        }

        return [
            'success' => true,
            'filename' => $hashName,
            'original_name' => $file->getClientOriginalName(),
            'mime' => $mime,
            'size' => $file->getSize(),
            'storage_path' => $path,
            'relative_url' => '/storage/'.$path,
            'url' => Storage::disk($disk)->url($path),
            'dataURL' => $this->getDataUrl($mime, $this->filesystem->disk($disk)->get($path)),
        ];
    }

    protected function reduceSize($file_path, $max_width)
    {
        // 先实例化，传参是文件的磁盘物理路径
        $image = Image::make($file_path);

        // 进行大小调整的操作
        $image->resize($max_width, null, function ($constraint) {
            // 设定宽度是 $max_width，高度等比例双方缩放
            $constraint->aspectRatio();

            // 防止裁图时图片尺寸变大
            $constraint->upsize();
        });

        // 对图片修改后进行保存
        $image->save();
    }

    /**
     * Replace date variable in dir path.
     *
     * @param string $dir
     *
     * @return string
     */
    protected function formatDir($dir)
    {
        $replacements = [
            '{Y}' => date('Y'),
            '{m}' => date('m'),
            '{d}' => date('d'),
            '{H}' => date('H'),
            '{i}' => date('i'),
        ];

        return str_replace(array_keys($replacements), $replacements, $dir);
    }

    /**
     * Delete a file from disk.
     *
     * @param string $path
     *
     * @return array
     */
    public function delete($path, $disk)
    {
        if (0 === stripos($path, 'storage')) {
            $path = substr($path, strlen('storage'));
        }

        $this->filesystem->disk($disk)->delete($path);
    }
}
