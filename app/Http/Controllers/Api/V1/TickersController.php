<?php

namespace App\Http\Controllers\Api\V1;

use App\Ac;
use App\Asset;
use App\Pair;
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
        $tickers = $this->getPairs();
        return response()->json($tickers, 200);
    }

    /**
     * @return array
     */
    private function getPairs()
    {
        $assets = Ac::where('is_asset', true)->get();
        $assetArr = [];
        foreach ($assets as $asset) {
            $assetArr[] = $this->assetSerializer($asset);
        }

        return $assetArr;
    }

    /**
     * @param $data
     * @return array
     */
    private function serializer($data)
    {

        return [
            'id' => $data->id,
            'symbol' => $data->symbol,
        ];

    }

    /**
     * @param $data
     * @return array
     */
    private function assetSerializer($data)
    {
        return [
            'id' => $data->id,
            'symbol' => $data->symbol,
            'currencies' => $this->currencySerializer($data->id),
        ];
    }

    /**
     * @param $id
     * @return array
     */
    private function currencySerializer($id)
    {
        $currenciesArr = [];
        $currencies = Pair::where('asset_id', '=', $id)->get();
        foreach ($currencies as $currency) {
            $curr = Ac::find($currency->currency_id);
            $currenciesArr[] = $this->serializer($curr);
        }

        return $currenciesArr;
    }

}
