<?php


namespace Tresle\Product\Model\Product;


use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /**
     * @var array
     */
    protected $fillable = ["product_id", "src"];

    /**
     * @var string
     */
    protected $table = "product_image";
}
