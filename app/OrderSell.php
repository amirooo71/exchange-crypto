<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderSell extends Model
{
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
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
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function orderBook($price)
    {
        return $this
            ->whereRaw('fill < amount')
            ->where('price', '<=',$price)
            ->orderBy('price', 'asc')
            ->get();
    }

    public static function storeOrder()
    {
        $order = self::create([
            'user_id' => auth()->id(),
            'currency_id' => \request('currency_id'),
            'price' => \request('price'),
            'amount' => \request('amount'),
        ]);

        $order['type'] = 'فروش';
        return $order;
    }
}
