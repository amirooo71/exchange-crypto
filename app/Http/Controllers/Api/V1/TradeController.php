<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TradeController extends Controller
{
    /**
     * @var \App\Services\Request
     */
    protected $request;

    /**
     * TickerController constructor.
     * @param \App\Services\Request $request
     */
    public function __construct(\App\Services\Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTickersInfo()
    {
        $response = $this->request->get("/ticker?limit=10");
        return response()->json($response, 200);

    }
}
