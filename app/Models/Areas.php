<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Areas extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $table = "areas";
    protected $primaryKey = "area_id";
    protected $fillable = ["city_id", "name", "created_at"];
    protected $dates = ['deleted_at'];

    public function city()
    {
        return $this->belongsTo(Cities::class, 'city_id', 'city_id');
    }

    public function stores()
    {
        return $this->hasMany(Stores::class, 'area_id', 'area_id');
    }
}