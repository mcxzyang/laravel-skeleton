<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseResourceCollection extends ResourceCollection
{
    private $total;

    public function __construct($resource)
    {
        $this->total = $resource->total();

        parent::__construct($resource);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'list' => $this->collection,
            'total' => $this->total
        ];
    }

//    public function paginationInformation($request, $paginated, $default): array
//    {
//        return [
//            'page' => $paginated['current_page'],
//            'per_page' => $paginated['per_page'],
//            'total' => $paginated['total'],
//            'total_page' => $paginated['last_page'],
//        ];
//    }
}
