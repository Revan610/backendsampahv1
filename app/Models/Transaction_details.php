<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction_details extends Model
{
    use HasFactory;
    protected $fillable = [
        'weight',
        'price',
        'waste_id',
    ];
}
