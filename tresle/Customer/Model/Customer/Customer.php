<?php


namespace Tresle\Customer\Model\Customer;

class Customer extends \Tresle\User\Model\User
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses(){
        return $this->hasMany(
            "\Tresle\Customer\Model\Address\Address",
            "customer_id"
        );
    }
}
