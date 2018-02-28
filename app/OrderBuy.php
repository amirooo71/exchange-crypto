<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderBuy extends Model
{
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * @param $status
     */
    public function updateStatus($status)
    {
        $this->update(['status' => $status]);
    }

    /**
     * @param $amount
     */
    public function updateFill($amount)
    {
        $this->update(['fill' => $amount]);
    }

    /**
     * @return mixed
     */
    public function remainAmount()
    {
        return ($this->amount - $this->fill);
    }

    /**
     * @param $price
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function orderBook($price)
    {
        return $this
            ->whereRaw('fill < amount')
            ->where('price', '>=', $price)
            ->orderBy('price', 'desc')
            ->get();
    }

    /**
     * @return $this|Model
     */
    public static function storeOrder()
    {
        return self::create([
            'user_id' => auth()->id(),
            'asset_id' => request('asset_id'),
            'currency_id' => \request('currency_id'),
            'price' => \request('price'),
            'amount' => \request('amount'),
        ]);

    }


}
