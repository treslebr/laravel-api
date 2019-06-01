<?php

namespace Modules\Core\Product\Model;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    /**
     * @var array 
     */
    protected $fillable = ["name", "status"];
}
