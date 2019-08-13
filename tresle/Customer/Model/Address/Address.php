<?php


namespace Tresle\Customer\Model\Address;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        "postcode",
        "country",
        "state",
        "city",
        "region",
        "street_1",
        "street_2",
        "street_3",
        "street_4",
        "customer_id"
    ];

    /**
     * @var string
     */
    protected $table = "customer_address";

}
