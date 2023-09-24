<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BaseResourceCollection extends AnonymousResourceCollection
{
    public static $wrap = 'list';

    /**
     * @var array
     */
    protected $withoutFields = [];

    public function toArray($request)
    {
        return $this->filterFields(parent::toArray($request));
    }

    public function hide(array $fields)
    {
        $this->withoutFields = $fields;
        return $this;
    }

    protected function filterFields($array)
    {
        return collect($array)->map(function ($item) {
            return collect($item)->forget($this->withoutFields);
        })->toArray();
    }

    public function paginationInformation($request, $paginated, $default): array
    {
        return [
            'total' => $paginated['total']
        ];
//        return [
//            'pagination' => [
//                // 'currentPage' => $paginated['current_page'],
//                // 'from' => $paginated['from'],
//                // 'lastPage' => $paginated['last_page'],
//                // 'perPage' => $paginated['per_page'],
//                // 'to' => $paginated['to'],
//                'total' => $paginated['total'],
//            ]
//        ];
    }
}
