<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseResourceCollection extends AnonymousResourceCollection
{
    public static $wrap = 'list';

    public function paginationInformation($request, $paginated, $default): array
    {
        return [
            'pagination' => [
                // 'currentPage' => $paginated['current_page'],
                // 'from' => $paginated['from'],
                // 'lastPage' => $paginated['last_page'],
                // 'perPage' => $paginated['per_page'],
                // 'to' => $paginated['to'],
                'total' => $paginated['total'],
            ]
        ];
    }
}
