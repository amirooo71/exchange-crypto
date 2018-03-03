<?php

namespace App\Http\Controllers\Api\V1\UDF;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RequestProcessorController extends Controller
{

    /**
     * @return array
     */
    public function history()
    {
//        $inputs = $request->only(['symbol', 'from', 'to', 'resolution']);
        return [
            "datatable" => [
                "data" => [
                    [
                        "BTCUSD",
                        "1980-12-12",
                        28.75,
                        28.87,
                        28.75,
                        28.75,
                        2093900,
                    ],
                    [
                        "BTCUSD",
                        "1980-12-15",
                        27.38,
                        27.38,
                        27.25,
                        27.25,
                        785200,
                    ],
                    [
                        "BTCUSD",
                        "1980-12-16",
                        25.37,
                        25.37,
                        25.25,
                        25.25,
                        472000,
                    ], [
                        "BTCUSD",
                        "1980-12-17",
                        25.87,
                        26,
                        25.87,
                        25.87,
                        385900,
                    ], [
                        "BTCUSD",
                        "1980-12-18",
                        26.63,
                        26.75,
                        26.63,
                        26.63,
                        327900,
                    ], [
                        "BTCUSD",
                        "1980-12-19",
                        28.25,
                        28.38,
                        28.25,
                        28.25,
                        217100,
                    ], [
                        "BTCUSD",
                        "1980-12-22",
                        29.63,
                        29.75,
                        29.63,
                        29.63,
                        166800,
                    ], [
                        "BTCUSD",
                        "1980-12-23",
                        30.88,
                        31,
                        30.88,
                        30.88,
                        209600,
                    ], [
                        "BTCUSD",
                        "1980-12-26",
                        35.5,
                        35.62,
                        35.5,
                        35.5,
                        248100,
                    ], [
                        "BTCUSD",
                        "1980-12-29",
                        36,
                        36.13,
                        36,
                        36,
                        415900,
                    ], [
                        "BTCUSD",
                        "1980-12-30",
                        35.25,
                        35.25,
                        35.12,
                        35.12,
                        307500,
                    ],
                    [
                        "BTCUSD",
                        "1980-12-31",
                        34.25,
                        34.25,
                        34.13,
                        34.13,
                        159600,
                    ],
                ],
                "columns" => [
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
                ],
            ],
            [
                "next_cursor_id" => null
            ],
        ];
    }

}
