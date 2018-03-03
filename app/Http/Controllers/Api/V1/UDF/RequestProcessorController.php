<?php

namespace App\Http\Controllers\Api\V1\UDF;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RequestProcessorController extends Controller
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json("Datafeed is ok", 200);
    }

    /**
     * @return array
     */
    public function config()
    {
        $config = [
            "supports_search" => false,
            "supports_group_request" => true,
            "supported_resolutions" => ["1", "5", "15", "30", "60", "1D", "1W", "1M"],
            "supports_marks" => false,
            "supports_time" => true,
        ];

        return $config;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function symbolInfo(Request $request)
    {
        $input = $request->only(['group']);
        return $input;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function symbols(Request $request)
    {
        $input = $request->only(['symbol']);
        return $input;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function search(Request $request)
    {
        $inputs = $request->only(['query', 'type', 'exchange', 'limit']);
        return $inputs;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function history(Request $request)
    {
        $inputs = $request->only(['symbol', 'from', 'to', 'resolution']);
        return $inputs;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function marks(Request $request)
    {
        $inputs = $request->only(['symbol', 'from', 'to', 'resolution']);
        return $inputs;
    }

    /**
     * @return int
     */
    public function time()
    {
        return time();
    }


}
