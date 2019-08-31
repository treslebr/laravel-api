<?php


namespace Tresle\Order\Model;


use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    /**
     * @var string
     */
    protected $table = "order_item";

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function additionals()
    {
        return $this->hasMany('\Tresle\Order\Model\OrderItemAdditional');
    }

}
