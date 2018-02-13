<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderBuy()
    {
        return $this->hasMany(OrderBuy::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderSale()
    {
        return $this->hasMany(OrderSell::class);
    }
}
