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
                <div id="tv_chart_container"></div>
                {{--<graph></graph>--}}
            </div>
        </panel>

        <div id="tv_chart_container"></div>
        <order-book></order-book>
        <order-history :user="{{auth()->user()}}"></order-history>
    </div>
@endsection
