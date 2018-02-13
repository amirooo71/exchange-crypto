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

}
