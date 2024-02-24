<?php

namespace Modules\Auth\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $expiryTime = $this->token->expires_at;

        return [
            'access_token' => $this->accessToken,
            'expires_in' => $expiryTime->diffInMinutes(now()),
            'expires_at' => $expiryTime,
        ];
    }
}
