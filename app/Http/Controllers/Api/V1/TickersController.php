<?php

namespace App\Http\Controllers\Api\V1;

use App\Ac;
use App\Asset;
use App\Pair;
use App\Ticker;
use App\Http\Controllers\Controller;

class TickersController extends Controller
{
    /**
     * TickersController constructor.
     */
    public function __construct()
    {
//        $this->middleware('auth:api');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDefaultTicker()
    {
        $ticker = $this->getTicker(1);
        return response()->json($ticker, 200);
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
     * @param $price
     * @param $pChange
     * @param $pColor
     * @param $min
     * @param $max
     * @param $volume
     * @return array
     */
    private function serializer($data, $price, $pChange, $pColor, $min, $max, $volume)
    {

        return [
            'id' => $data->id,
            'symbol' => $data->symbol,
            'price' => $price,
            'pChange' => $pChange,
            'pColor' => $pColor,
            'min' => $min,
            'max' => $max,
            'volume' => $volume,
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
        $pairs = Pair::where('asset_id', '=', $id)->get();
        foreach ($pairs as $pair) {
            $curr = Ac::find($pair->currency_id);
            $ticker = $this->getTicker($pair->id);
            if ($ticker) {
                $price = $ticker->price;
                $pChange = $ticker->percent_change;
                $pColor = $ticker->percent_color;
                $min = $ticker->min;
                $max = $ticker->max;
                $volume = $ticker->volume;
            } else {
                $price = rand(1000, 7000);
                $pChange = rand(1, 99);
                $pColor = '#7a9c4a';
                $min = rand(1000, 7000);
                $max = rand(1000, 7000);
                $volume = rand(1, 100);
            }
            $currenciesArr[] = $this->serializer($curr, $price, $pChange, $pColor, $min, $max, $volume);
        }

        return $currenciesArr;
    }

    /**
     * @param $pairId
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    private function getTicker($pairId)
    {
        return Ticker::where('pair_id', $pairId)->first();
    }

}
