<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VipLevels extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $table = "vip_levels";
    protected $primaryKey = "vip_level_id";
    protected $fillable = ["level_value", "name", "description", "created_at"];
    protected $dates = ['deleted_at'];

    public function users()
    {
        return $this->hasMany(Users::class, 'vip_level_id', 'vip_level_id');
    }
}