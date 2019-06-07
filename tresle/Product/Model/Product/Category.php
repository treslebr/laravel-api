<?php

namespace Tresle\Product\Model\Product;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * @var array 
     */
    protected $fillable = ["name", "status"];

    /**
     * @var string
     */
    protected $table = "product_categories";
}
