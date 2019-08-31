<?php

namespace Tresle\Product\Model\Additional;

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
    protected $table = "product_additional_category";

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function additionals(){
        return $this->hasMany("\Tresle\Product\Model\Additional\Additional", "product_additional_category_id");
    }
}
