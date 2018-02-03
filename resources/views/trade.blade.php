@extends('layouts.base')

@section('body')
    <div class="col-md-3">
        <balance></balance>
        <user-order user="{{auth()->user()}}"></user-order>
    </div>
    <div class="col-md-3">
        <tickers></tickers>
        <order-book></order-book>
    </div>
    <div class="col-md-6">
        <panel title="نمودار">
            <div slot="body">
                <graph></graph>
            </div>
        </panel>
        <exchange></exchange>
    </div>
@endsection
