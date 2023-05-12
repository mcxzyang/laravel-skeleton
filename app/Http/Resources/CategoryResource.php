<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $temps = Category::query()->where(['pid' => $this->id])->get();
        if ($temps && count($temps)) {
            return array_merge(parent::toArray($request), ['children' => $temps]);
        }
        return parent::toArray($request);
    }
}
