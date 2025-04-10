<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    public $timestamps = false;
    protected $table = "users";
    protected $primaryKey = "user_id";
    protected $fillable = ["user_id", "store_id", "username", "password", "email", "vip_level", "order_count", "Uid01", "created_at"];

    // 定義與 Stores 的關聯
    public function store()
    {
        return $this->belongsTo(Stores::class, 'store_id', 'store_id');
    }
}
