<?php

namespace App\Http\Resources;

class AdminUserResource extends BaseResource
{
    public function toArray($request)
    {
        return $this->filterFields(array_merge(parent::toArray($request), ['roles' => ['admin']]));
    }
}
