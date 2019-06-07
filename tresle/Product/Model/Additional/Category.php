<?php

namespace Tresle\Product\Model;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    /**
     * @var array 
     */
    protected $fillable = ["name", "status"];

    /**
     * @var string
     */
    protected $table = "product_additional_category";
}
