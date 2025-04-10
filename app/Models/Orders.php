<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    public $timestamps = false;
    protected $table = "orders";
    protected $primaryKey = "order_id";
    protected $fillable = ["order_id", "store_id", "user_id", "status", "total_price", "order_date"];

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    public function store()
    {
        return $this->belongsTo(Stores::class, 'store_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class, 'order_id');
    }
}
