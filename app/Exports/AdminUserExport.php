<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AdminUserExport implements WithHeadings, FromCollection, ShouldAutoSize
{
    private $query;
    private $list = [];

    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $this->query->chunkById(100, function ($items) {
            foreach ($items as $adminUser) {
                $this->list[] = [
                    $adminUser->username,
                    $adminUser->name,
                    $adminUser->created_at
                ];
            }
        });
        return collect($this->list);
    }


    public function headings(): array
    {
        return [
            '用户名', '姓名', '创建时间'
        ];
    }
}
