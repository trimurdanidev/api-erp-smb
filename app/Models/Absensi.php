<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Absensi extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'absensi';

    protected $fillable = [
        'user_id', 'date', 'time_in', 'longitude_in', 'latitude_in', 'images_in',
        'time_out', 'longitude_out', 'latitude_out', 'images_out', 'absensi_ref', 'created_by'
    ];

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(MasterUser::class, 'user', 'user');
    }
}
