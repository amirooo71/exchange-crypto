@extends('layouts.auth-base')
@section('body')

    <div class="header header-primary text-center">
        <div class="logo-container">
            <img src="{{asset('app-assets/img/now-logo.png')}}" alt="">
        </div>
    </div>
    <div class="alert alert-success" role="alert">
        <p dir="rtl">
            حساب شما با موفقیت فعال شد.
        </p>
        <a href="{{route('login')}}">
            <span class="btn btn-sm btn-default">
                ورود
            </span>
        </a>
    </div>
@endsection