@extends('layouts.base')

@section('body')
    <div class="col-md-3">
        <tickers></tickers>
        <exchange></exchange>
        <balance></balance>
    </div>
    <div class="col-md-9">
        <panel title="نمودار">
            <div slot="body">
                <graph></graph>
            </div>
        </panel>
        <panel title="تاریخچه">
            <div slot="body">
                <div class="tabbable">
                    <ul class="nav nav-tabs nav-tabs-bottom nav-justified">
                        <li class="active">
                            <a href="#bottom-justified-tab1" data-toggle="tab">معاملات کاربر</a>
                        </li>
                        <li>
                            <a href="#bottom-justified-tab2" data-toggle="tab">کل معاملات</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="bottom-justified-tab1">
                            <user-order :user="{{auth()->user()}}"></user-order>
                        </div>
                        <div class="tab-pane" id="bottom-justified-tab2">
                            <order-book></order-book>
                        </div>
                    </div>
                </div>
            </div>
        </panel>
    </div>
@endsection
