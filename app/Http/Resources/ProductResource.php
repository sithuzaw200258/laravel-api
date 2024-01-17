<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function stockStatus($stockCount) {
        $status = "";
        if ($stockCount > 10) {
            $status = "available";
        }elseif ($stockCount > 0 && $stockCount < 10) {
            $status = "few";
        }elseif ($stockCount == 0) {
            $status = "out of stock";
        }
        return $status;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'show_price' => $this->price." mmk",
            'stock' => $this->stock,
            'stock_status' => $this->stockStatus($this->stock),
            // 'owner' => $this->user->name,
            'owner' => new UserResource($this->user),
            // 'photos' => $this->photos,
            'photos' => PhotoResource::collection($this->photos),
            'date' => $this->created_at->format('d M Y'),
            'time' => $this->created_at->format('g:i A'),
        ];
    }
}
