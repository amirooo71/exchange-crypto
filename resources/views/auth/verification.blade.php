@extends('layouts.auth-base')
@section('body')
    <div class="header header-primary text-center">
        <div class="logo-container">
            <img src="{{asset('app-assets/img/now-logo.png')}}" alt="">
        </div>
    </div>
    <div class="alert alert-info" role="alert">
        <p dir="rtl">
            شما با موفقیت ثبت نام شدید جهت فعال سازی حساب ایمیل خود را چک کنید.
        </p>
    </div>
@endsection