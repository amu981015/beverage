<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cities extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $table = "cities";
    protected $primaryKey = "city_id";
    protected $fillable = ["name", "created_at"];
    protected $dates = ['deleted_at'];

    public function areas()
    {
        return $this->hasMany(Areas::class, 'city_id', 'city_id');
    }
}