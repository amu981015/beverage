<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menus extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $table = "menus";
    protected $primaryKey = "menu_id";
    protected $fillable = ["name", "price", "category_id", "status", "created_at"];
    protected $dates = ['deleted_at'];

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id', 'category_id');
    }
}