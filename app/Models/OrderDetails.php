<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    public $timestamps = false;
    protected $table = "order_details";
    protected $primaryKey = "detail_id";
    protected $fillable = ["order_id", "menu_id", "quantity", "price"];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id', 'order_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menus::class, 'menu_id', 'menu_id');
    }
}
