<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wastes extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'name',
        'price_per_kg',
    ];
}
