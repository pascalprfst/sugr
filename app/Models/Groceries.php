<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Groceries extends Model
{
    protected $fillable = ['name' , 'sugar_per_100'];
}
