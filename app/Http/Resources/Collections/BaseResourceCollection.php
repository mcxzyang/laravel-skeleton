<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseResourceCollection extends ResourceCollection
{
    private $total;

    public function __construct($resource)
    {
        if (!request()->has('paging') || request('paging') > 0) {
            $this->total = $resource->total();
        }

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
        if (!request()->has('paging') || request('paging') > 0) {
            return [
                'list' => $this->collection,
                'total' => $this->total
            ];
        }
        return parent::toArray($request);
    }
}
