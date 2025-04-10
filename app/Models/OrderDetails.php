<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    public $timestamps = false;
    protected $table = "order_details";
    protected $primaryKey = "detail_id";
    protected $fillable = ["detail_id", "order_id", "menu_id", "quantity"];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menus::class, 'menu_id');
    }
}
