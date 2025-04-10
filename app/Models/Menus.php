<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
    public $timestamps = false;
    protected $table = "menus";
    protected $primaryKey = "menu_id";
    protected $fillable = ["menu_id", "name", "price", "category", "status", "created_at"];
}
