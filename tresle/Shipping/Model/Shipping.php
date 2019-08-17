<?php


namespace Tresle\Shipping\Model;


use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    /**
     * @var array
     */
    protected $fillable = ["location", "price"];

    /**
     * @var string
     */
    protected $table = "shipping";
}
