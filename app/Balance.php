<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{

    protected $guarded = [];

    /**
     * @param $currency
     * @return Model|null|static
     */
    public function getUserBalance($currency)
    {
        return $this
            ->where('user_id', '=', auth()->user()->id)
            ->where('currency_id', '=', $currency)
            ->first();
    }

}
