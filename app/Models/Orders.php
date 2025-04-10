<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orders extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $table = "orders";
    protected $primaryKey = "order_id";
    protected $fillable = ["store_id", "user_id", "status", "order_date"];
    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'user_id');
    }

    public function store()
    {
        return $this->belongsTo(Stores::class, 'store_id', 'store_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class, 'order_id', 'order_id');
    }

    public function totalPrice()
    {
        return $this->orderDetails->sum(fn($detail) => $detail->quantity * $detail->price);
    }
}
