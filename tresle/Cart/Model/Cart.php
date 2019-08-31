<?php


namespace Tresle\Cart\Model;


use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    /**
     * @var string
     */
    protected $table = "cart";

    /**
     * @var array
     */
    protected $fillable = ["customer_id", "qty", "obs", "product_id", "additionals_id"];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(
            "\Tresle\Customer\Model\Customer\Customer",
            "customer_id"
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(
            "\Tresle\Product\Model\Product\Product",
            "product_id"
        );
    }

}
