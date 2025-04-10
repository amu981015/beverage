<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stores extends Model
{
    public $timestamps = false;
    protected $table = "stores";
    protected $primaryKey = "store_id";
    protected $fillable = ["store_id", "name", "city", "area", "address", "tel", "photo", "latitude", "longitude", "created_at"];
}
