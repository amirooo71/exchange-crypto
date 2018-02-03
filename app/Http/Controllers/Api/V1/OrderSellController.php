<?php

namespace App\Http\Controllers\Api\V1;

use App\OrderSell;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderSellController extends Controller
{
    /**
     * OrderSellController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'currency_id' => 'required',
            'price' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        OrderSell::create([
            'user_id' => auth()->id(),
            'currency_id' => \request('currency_id'),
            'price' => \request('price'),
            'amount' => \request('amount'),
        ]);

        return response()->json(['success' => true], 200);

    }

}
