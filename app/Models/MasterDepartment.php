<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterDepartment extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'master_department';
    protected $primaryKey = 'departmentid';
    public $incrementing = true;
    protected $keyType = 'string';
    public $timestamps = true;

    protected $fillable = ['departmentid', 'departmentcode', 'description','created_by'];
}
