<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'permission_id'
    ];

    public function permission()
    {
        return $this->hasOne(RolePermissions::class, 'id', 'permission_id');
    }
}
