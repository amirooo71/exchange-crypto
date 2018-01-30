@extends('layouts.base')

@section('body')
    <!-- User Wallet & Trade History -->
    <div class="col-md-3">
        <balance></balance>
        <user-trade></user-trade>
    </div>
    <!-- /User Wallet & Trade History -->

    <!-- Tickers & Trades History -->
    <div class="col-md-3">
        <tickers></tickers>
        <panel title="معاملات در حال انجام"></panel>
    </div>
    <!-- /Tickers & Trades History -->

    <!-- Chart & Exchange -->
    <div class="col-md-6">
        <panel title="نمودار">
            <div slot="body">
                <graph></graph>
            </div>
        </panel>
        <exchange></exchange>
    </div>
    <!-- /Chart & Exchange -->
@endsection
