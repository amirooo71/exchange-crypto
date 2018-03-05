<?php

namespace App\Http\Controllers\Api\V1\UDF;

use App\TvSymbol;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\DB;

class RequestProcessorController extends Controller
{

    /**
     * @return array
     */
    public function sendConfig()
    {
        return [
            "supports_search" => true,
            "supports_group_request" => false,
            "supports_marks" => false,
            "supports_timescale_marks" => true,
            "supports_time" => true,
            "exchanges" => [
                [
                    "value" => "",
                    "name" => "All Exchanges",
                    "desc" => "",
                ],
//                [
//                    "value" => "XETRA",
//                    "name" => "XETRA",
//                    "desc" => "XETRA",
//                ],
//                [
//                    "value" => "NSE",
//                    "name" => "NSE",
//                    "desc" => "NSE",
//                ],
            ],
            "symbolsTypes" => [
                [
                    "name" => "All types",
                    "value" => "",
                ],
                [
                    "name" => "Stock",
                    "value" => "stock",
                ],
                [
                    "name" => "Index",
                    "value" => "index",
                ],
            ],
            "supported_resolutions" => ["1", "5","15", "30", "60", "1D", "1W", "1M"],
        ];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function sendSendSymbolInfo(Request $request)
    {
        $input = $request->only('symbol');

        return [
            "name" => $input['symbol'], //Name of symbol
            "exchange-traded" => "XETRA", //Short name of exchange
            "exchange-listed" => "XETRA", //Short name of exchange
            "timezone" => "Asia/Tehran", //Exchange timezone
            "minmov" => 1, //These three keys have different meaning when using for common prices and for fractional prices.
            "minmov2" => 0, //These three keys have different meaning when using for common prices and for fractional prices.
            "pointvalue" => 1,
            "session" => "0930-1630", //Trading hours for this symbol
            "has_intraday" => true,
            "has_no_volume" => false,
            "description" => "Bitcoin Exchange", //Description of a symbol
            "type" => "stock", //Optional type of the instrument.
            "supported_resolutions" => ["1", "5", "15", "30", "60", "1D", "1W", "1M"],
            "pricescale" => 100,
            "ticker" => $input['symbol'], //It's an unique identifier for this symbol in your symbology,
        ];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function sendSymbolSearchResult(Request $request)
    {
        $inputs = $request->only(['query', 'type', 'exchange', 'limit']);
        $symbols = DB::table('tv_symbols');

        if (isset($inputs['query'])) {
            $symbols = $symbols->where('symbol', $inputs['query']);
        } elseif (isset($inputs['type'])) {
            $symbols = $symbols->where('type', $inputs['type']);
        } elseif (isset($inputs['exchange'])) {
            $symbols = $symbols->where('exchange', $inputs['exchange']);
        } elseif (isset($inputs['limit'])) {
            $symbols = $symbols->take($inputs['limit']);
        }

        return $symbols->get();

    }

    /**
     * @param Request $request
     * @return array
     */
    public function sendSymbolHistory(Request $request)
    {
        $inputs = $request->only(['symbol', 'from', 'to', 'resolution']);
        $histories = $this->getHistories($inputs['resolution'], $inputs['from'],$inputs['to']);
        return $this->convertHistoryDataToUDFFormat($histories);
    }

    /**
     * @return int
     */
    public function sendTime()
    {
        return time();
    }

    /**
     * @return mixed
     */
    private function getHistories($resolution, $from, $to)
    {
        $from *= 1000;
        $to *= 1000;

        switch ($resolution) {
            case 1:
                return DB::select("SELECT COUNT(*) as count, t1 as t , (select price from transactions WHERE t1 = t ORDER by id LIMIT 1) as o ,(select price from transactions WHERE t1 = t ORDER BY id DESC LIMIT 1) as c , min(price) as l, max(price) as h, SUM(ABS(amount)) as v FROM `transactions` WHERE timestamp BETWEEN $from AND $to GROUP BY t1 order BY t");
            case 5:
                return DB::select("SELECT COUNT(*) as count, t5 as t , (select price from transactions WHERE t5 = t ORDER by id LIMIT 1) as o ,(select price from transactions WHERE t5 = t ORDER BY id DESC LIMIT 1) as c , min(price) as l, max(price) as h, SUM(ABS(amount)) as v FROM `transactions` WHERE timestamp BETWEEN $from AND $to GROUP BY t5 order BY t");
            case 15:
                return DB::select("SELECT COUNT(*) as count, t15 as t , (select price from transactions WHERE t15 = t ORDER by id LIMIT 1) as o ,(select price from transactions WHERE t15 = t ORDER BY id DESC LIMIT 1) as c , min(price) as l, max(price) as h, SUM(ABS(amount)) as v FROM `transactions` WHERE timestamp BETWEEN $from AND $to GROUP BY t15 order BY t ");

        }
//        return DB::select('SELECT COUNT(*) as count, CAST(timestamp / 60 as INT) as t, (select price from transactions where CAST(timestamp / 60 as INT) = t ORDER by id LIMIT 1) as o ,(select price from transactions where CAST(timestamp / 60 as INT) = t ORDER by id DESC LIMIT 1) as c , min(price) as l, max(price) as h, SUM(amount) as v FROM `transactions` GROUP BY CAST(timestamp / 60 as INT) order by t LIMIT 200');
    }

    /**
     * @param $histories
     * @return array
     */
    private function convertHistoryDataToUDFFormat($histories): array
    {
        $t = [];
        $o = [];
        $c = [];
        $l = [];
        $h = [];
        $v = [];
        $counts = [];


        foreach ($histories as $history) {
            $t[] = $history->t * 60;
            $o[] = $history->o;
            $c[] = $history->c;
            $l[] = $history->l;
            $h[] = $history->h;
            $v[] = $history->v;
            $counts[] = $history->count;
        }


        return [
            "t" => $t,
            "o" => $o,
            "c" => $c,
            "l" => $l,
            "h" => $h,
            "v" => $v,
            "s" => "ok",
            "count" => $counts
        ];
    }
}
