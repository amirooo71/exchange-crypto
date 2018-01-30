<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    /**
     * @var string
     */
    protected $table = 'currency';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function balance()
    {
        return $this->belongsTo(Balance::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
