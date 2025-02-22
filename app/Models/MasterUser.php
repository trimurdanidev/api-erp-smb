<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class MasterUser extends Authenticatable implements JWTSubject
{
    use HasFactory,SoftDeletes;

    protected $table = 'master_user';
    protected $primaryKey = 'id';
    protected $fillable = ['user','description', 'password', 'username', 'phone', 'nik', 'departmentid', 'unitid', 'entryuser', 'entryip', 'updatetime', 'updateuser', 'updateip', 'avatar','created_by'];
    protected $hidden = ['password'];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
