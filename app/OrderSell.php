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
    public function getValidOrderSells()
    {
        return $this
            ->where('status', '=', 'in_progress')
            ->orWhere('status', '=', 'partial')
            ->orderBy('price', 'asc')
            ->get();
    }
}
