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
        <user-order user="{{auth()->user()}}"></user-order>
        <order-book></order-book>
    </div>
@endsection
