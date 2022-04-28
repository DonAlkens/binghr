<?php

namespace App\Http\Resources;

use App\Models\RolePermissions;
use App\Models\RoleType;
use App\Models\UserPermissions;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data["role_type"] = RoleType::find($this->role_type_id);
        $data["permissions"] = UserPermissions::where('user_id', $this->id)->get();
        $data["access_level"] = $data["role_type"]->permission;
        return $data;
    }
}
