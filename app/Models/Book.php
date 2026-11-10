<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['title','type', 'author', 'publisher', 'year', 'stock'];
}
