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
        "shipping_id",
        "customer_id"
    ];

    /**
     * @var string
     */
    protected $table = "customer_address";

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shipping(){
        return $this->belongsTo(
            "\Tresle\Shipping\Model\Shipping",
            "shipping_id"
        );
    }

}
