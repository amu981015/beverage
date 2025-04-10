<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categories extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $table = "categories";
    protected $primaryKey = "category_id";
    protected $fillable = ["name", "created_at"];
    protected $dates = ['deleted_at'];

    public function menus()
    {
        return $this->hasMany(Menus::class, 'category_id', 'category_id');
    }
}
