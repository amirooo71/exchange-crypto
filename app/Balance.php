<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{

    protected $guarded = [];

    protected $with = ['currency'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function currency()
    {
        return $this->hasOne(Currency::class, 'id');
    }

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

    /**
     * @param $id
     * @param $currency
     * @return Model|null|static
     */
    public function getUserBalanceByUserId($id, $currency)
    {
        return $this
            ->where('user_id', '=', $id)
            ->where('currency_id', '=', $currency)
            ->first();
    }

    /**
     * @param $id
     * @param $amount
     */
    public function updateBalance($id, $amount)
    {
        $balance = $this->getUserBalanceByUserId($id,1);
        $balance->update([
            'amount' => $amount,
        ]);
    }


}
