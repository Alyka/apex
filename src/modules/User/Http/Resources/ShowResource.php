<?php

namespace Modules\User\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Core\Helpers\Helper;
use Modules\Role\Facades\RoleService;

class ShowResource extends JsonResource
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
            'email' => $this->email,
            'roles' => RoleService::getRoles($this->resource),
            'created_at' => Helper::timeFormat($this->created_at),
        ];
    }
}
