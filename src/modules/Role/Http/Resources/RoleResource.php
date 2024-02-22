<?php

namespace Modules\Role\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Core\Helpers\Helper;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'users_count' => $this->users_count,
            'created_at' => Helper::timeFormat($this->created_at),
        ];
    }
}
