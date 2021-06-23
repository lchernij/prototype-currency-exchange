<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'value_trigger' => $this->value_trigger,
            'value_action' => $this->value_action,
            'currency' => [
                'uuid' => $this->currency->uuid,
                'symbol' => $this->currency->symbol,
                'description' => $this->currency->description,
            ]
        ];
    }
}
