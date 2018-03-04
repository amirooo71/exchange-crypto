<?php

namespace App\Http\Controllers\Api\V1\UDF;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\DB;

class RequestProcessorController extends Controller
{


    public function history(Request $request)
    {
//        $inputs = $request->only(['symbol', 'from', 'to', 'resolution']);
//
//        dd($inputs);


        $bars = $this->getBars();

        return $this->sendResult($bars);

    }

    /**
     * @param $bar
     * @return array
     */
    private function UDFSerializer($bar)
    {
        return ["BTCUSD", Carbon::createFromTimestamp($bar->t * 60)->format('Y-m-d'), $bar->o, $bar->h, $bar->l, $bar->c, $bar->v];
    }

    /**
     * @return mixed
     */
    private function getBars()
    {
        return DB::select('SELECT COUNT(*) as count, CAST(timestamp / 60 as INT) as t, (select price from transactions where CAST(timestamp / 60 as INT) = t ORDER by id LIMIT 1) as o ,(select price from transactions where CAST(timestamp / 60 as INT) = t ORDER by id DESC LIMIT 1) as c , min(price) as l, max(price) as h, SUM(amount) as v FROM `transactions` GROUP BY CAST(timestamp / 60 as INT) order by t');
    }

    /**
     * @return array
     */
    private function getColumns()
    {
        return [
            [
                "name" => "ticker",
                "type" => "String"
            ],
            [
                "name" => "date",
                "type" => "Date"
            ],
            [
                "name" => "open",
                "type" => "BigDecimal(34,12)"
            ],
            [
                "name" => "high",
                "type" => "BigDecimal(34,12)"
            ],
            [
                "name" => "low",
                "type" => "BigDecimal(34,12"
            ],
            [
                "name" => "close",
                "type" => "BigDecimal(34,12)"
            ],
            [
                "name" => "volume",
                "type" => "BigDecimal(37,15)"
            ],
        ];
    }

    /**
     * @param $bars
     * @return array
     */
    private function sendResult($bars): array
    {
        $data = [];

        foreach ($bars as $bar) {

            $data[] = $this->UDFSerializer($bar);
        }
        return [
            "datatable" => [
                "data" => $data,
                "columns" => $this->getColumns(),
            ]
        ];
    }


}
