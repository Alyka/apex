<?php

namespace Modules\User\Http\Resources;

use Core\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Role\Facades\RoleService;

class StoreResource extends JsonResource
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
            'date' => Helper::timeFormat($this->created_at),
        ];
    }
}
