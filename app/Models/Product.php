<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{


    protected $fillable = [
        'name',
        'dec',
        'img',
        'category_id'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];


    use HasFactory;
}
