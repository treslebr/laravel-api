<?php


namespace Tresle\Order\Model;


use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * @var string
     */
    protected $table = "order";

    /**
     * @var array
     */
    protected $fillable = ["status"];

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

    public function items(){
        return $this->hasMany('\Tresle\Order\Model\OrderItem');
    }

}
