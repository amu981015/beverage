<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stores extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $table = "stores";
    protected $primaryKey = "store_id";
    protected $fillable = ["name", "area_id", "address", "tel", "photo", "latitude", "longitude", "created_at"];
    protected $dates = ['deleted_at'];

    public function area()
    {
        return $this->belongsTo(Areas::class, 'area_id', 'area_id');
    }
}
