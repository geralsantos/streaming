<?php

namespace App\Modules\Dashboard\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['nombre','codigo','padre_id','url','icon'];
    protected $table = 'menu';
}
