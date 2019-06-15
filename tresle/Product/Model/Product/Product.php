<?php

namespace Tresle\Product\Model\Product;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * @var array
     */
    protected $fillable = ["name", "status", "price", "product_additional_category_id"];

    /**
     * @var string
     */
    protected $table = "product";
}
