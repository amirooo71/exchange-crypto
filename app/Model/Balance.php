<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    /**
     * @var string
     */
    protected $table = 'balance';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function currency()
    {
        return $this->hasOne(Currency::class, 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id');
    }
}
