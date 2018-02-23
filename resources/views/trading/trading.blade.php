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
        <panel title="معاملات">
            <div slot="body">
                <div class="tabbable">
                    <ul class="nav nav-tabs nav-tabs-bottom nav-justified">
                        <li>
                            <a href="#order-history-tab" data-toggle="tab">معاملات کاربر</a>
                        </li>
                        <li class="active">
                            <a href="#order-book-tab" data-toggle="tab">کل معاملات</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane" id="order-history-tab">
                            <order-history :user="{{auth()->user()}}"></order-history>
                        </div>
                        <div class="tab-pane active" id="order-book-tab">
                            <order-book></order-book>
                        </div>
                    </div>
                </div>
            </div>
        </panel>
    </div>
@endsection
