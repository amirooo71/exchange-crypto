<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends BaseController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function trade()
    {
        return view('trade');
    }
}
