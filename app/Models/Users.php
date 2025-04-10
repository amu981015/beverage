<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Users extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $table = "users";
    protected $primaryKey = "user_id";
    protected $fillable = ["store_id", "username", "password", "email", "vip_level_id", "Uid01", "created_at"];
    protected $dates = ['deleted_at'];

    public function store()
    {
        return $this->belongsTo(Stores::class, 'store_id', 'store_id');
    }

    public function vipLevel()
    {
        return $this->belongsTo(VipLevels::class, 'vip_level_id', 'vip_level_id');
    }
}
