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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products(){
        return $this->hasMany(
            "\Tresle\Product\Model\Product\Product",
            "product_category_id"
        );
    }
}
