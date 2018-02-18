<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderBuy extends Model
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
    public function getValidOrderBuys()
    {
        return $this
            ->where('status', '=', 'in_progress')
            ->orWhere('status', '=', 'partial')
            ->orderBy('price', 'desc')
            ->get();
    }

    /**
     * @return $this|Model
     */
    public static function storeOrder()
    {
        $order = self::create([
            'user_id' => auth()->id(),
            'currency_id' => \request('currency_id'),
            'price' => \request('price'),
            'amount' => \request('amount'),
        ]);
        $order['type'] = 'خرید';
        return $order;
    }
}
