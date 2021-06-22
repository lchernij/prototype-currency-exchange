<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);

        $data['token'] = $this->createToken('MyAuthApp')->plainTextToken;

        unset($data['created_at']);
        unset($data['updated_at']);

        return $data;
    }
}
