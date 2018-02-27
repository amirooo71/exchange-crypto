<?php

namespace App\Http\Controllers\Api\V1;

use App\Asset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TickersController extends Controller
{
    /**
     * TickersController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTickers()
    {
        $tickers = Asset::all();
        return response()->json($tickers, 200);
    }
}
