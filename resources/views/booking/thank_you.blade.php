@extends('layouts.front.web_layout')
@section('content')
    <div class="thanks_page">
        <header>
            <div class="logo_wrapper wow fadeInDown" style="visibility: visible; animation-name: fadeInDown;">
                <h1>Kayakalp</h1>
            </div>
        </header>
        <div class="content_Box">
            <div class="content_BoxIN">
                <img src="https://cdn0.iconfinder.com/data/icons/round-ui-icons/128/tick_green.png" class="tick_icon">
                <h1>Thank you for your booking</h1>
                <p>Please check your email to activate your account.</p>
                <div class="btns_div">
                    <a href="{{ url('/') }}"><i class="fa fa-arrow-left"></i>Back to Home</a>
                    <a href="{{ url('guest/booking/signup') }}">New Booking   <i class="fa fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        @include('layouts.front.booking_footer')
    </div>
@endsection
