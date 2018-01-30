@extends('layouts.base')

@section('body')
    <!-- User Wallet & Trade History -->
    <div class="col-md-3">
        <balance></balance>
        <panel title="تاریخچه معملات"></panel>
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
        <panel title="خرید و فروش">
            <div slot="body" class="row">
                <div class="col-md-6">
                    <!-- Basic layout-->
                    <form action="#" class="form-horizontal">
                        <div class="panel ng-bg-dark">
                            <div class="panel-heading">
                                <h5 class="panel-title">خرید</h5>
                            </div>

                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">قیمت:</label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label">مقدار:</label>
                                    <div class="col-lg-9">
                                        <input type="password" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label"></label>
                                    <div class="col-lg-9">
                                        <input type="password" class="form-control">
                                    </div>
                                </div>

                                <div class="text-right" style="padding-top: 20px;">
                                    <button type="submit" class="btn btn-success">تایید خرید <i
                                                class="icon-arrow-left13 position-right"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- /basic layout -->
                </div>
                <div class="col-md-6">
                    <!-- Basic layout-->
                    <form action="#" class="form-horizontal">
                        <div class="panel ng-bg-dark">
                            <div class="panel-heading">
                                <h5 class="panel-title">فروش</h5>
                            </div>

                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">قیمت:</label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label">مقدار:</label>
                                    <div class="col-lg-9">
                                        <input type="password" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label"></label>
                                    <div class="col-lg-9">
                                        <input type="password" class="form-control">
                                    </div>
                                </div>

                                <div class="text-right" style="padding-top: 20px;">
                                    <button type="submit" class="btn btn-danger">تایید فروش <i
                                                class="icon-arrow-left13 position-right"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- /basic layout -->
                </div>
            </div>
        </panel>
    </div>
    <!-- /Chart & Exchange -->
@endsection
