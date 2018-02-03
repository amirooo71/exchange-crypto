<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderBuy extends Model
{
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function currency()
    {
        return $this->hasOne(Currency::class, 'id');
    }
}
