<?php

namespace Tresle\Product\Model\Additional;


use Illuminate\Database\Eloquent\Model;

class Additional extends Model
{
    /**
     * @var array
     */
    protected $fillable = ["name", "status", "price", "product_additional_category_id"];

    /**
     * @var string
     */
    protected $table = "product_additional";

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo("Tresle\Product\Model\Additional\Category", "product_additional_category_id");
    }
}
