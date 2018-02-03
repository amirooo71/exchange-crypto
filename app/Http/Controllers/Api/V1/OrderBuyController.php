<?php

namespace App\Http\Controllers\Api\V1;

use App\OrderBuy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderBuyController extends Controller
{
    /**
     * OrderBuyController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'currency_id' => 'required',
            'price' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        OrderBuy::create([
            'user_id' => auth()->id(),
            'currency_id' => \request('currency_id'),
            'price' => \request('price'),
            'amount' => \request('amount'),
        ]);

        return response()->json(['success' => true], 200);
    }
}
