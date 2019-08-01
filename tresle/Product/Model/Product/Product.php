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

    public function additionals(){
        return $this->belongsToMany(
            "Tresle\Product\Model\Additional\Additional",
            "product_additional_relation",
            "product_id",
            "product_additional_id"
        );
    }
}
