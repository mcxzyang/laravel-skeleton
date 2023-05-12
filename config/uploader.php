<?php

/*
 * This file is part of the overtrue/laravel-uploader.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

return [
    'strategies' => [
        /*
         * default strategy.
         */
        'default' => [
            'input_name' => 'file',
            'mimes' => ['image/jpeg', 'image/png', 'image/bmp', 'image/gif'],
            'disk' => 'qiniu',
            'directory' => 'uploads/{Y}/{m}/{d}', // directory,
            'max_file_size' => '2m',
            'filename_hash' => 'random', // random/md5_file/original
        ],
        'videos' => [
            'input_name' => 'film',
            'disk' => 'qiniu',
            'directory' => 'films/{Y}/{m}/{d}', // directory,
            'filename_hash' => 'random', // random/md5_file/original
        ],

        // avatar extends default
        'avatar' => [
            'directory' => 'avatars/{Y}/{m}/{d}',
        ],
    ],
];

// @uploader('file', ['strategy' => 'avatar', 'data' => [$product->images]])
