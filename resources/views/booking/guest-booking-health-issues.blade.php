@extends('layouts.front.web_layout')
@section('content')
<div class="admin_wrapper signup">
    <header>
        <div class="logo_wrapper wow fadeInDown">
            <a href="{{ url("/home") }}"> <h1>Kayakalp</h1> </a>
        </div>
    </header>
    <div class="main_wrapper">
        <div class="sideNavBar wow fadeInLeft">
            <div>
                <div class="footer_logo">Kayakalp</div>
            </div>
            @include('booking.sidebar')
        </div>
        <div class="main_content_area">
            <div id="signup_wizard">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <h4>Please check the errors below:</h4>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                    @if (session('status') == 'success')
                        <div class="alert alert-success">
                           {!! session('message') !!}
                        </div>
                    @endif
                {!! Form::open(array('route' => 'guest.booking.health_issues', 'id' => 'bookingProcessForm','files'=>true,'method'=>'post')) !!}
                    {{--<form id="bookingProcessForm" action="{{ route('guest.booking') }}" method="post">--}}
                    {{ csrf_field() }}
                    <section>
                        <div class="pro_main_content">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="about_sec white_bg signup_bg">
                                        <h3 class="title_3">Booking Process</h3>
                                        <h4>Mention chief complaints / health issues that you want to treatment for</h4>
                                        <div class="form-group">
                                            <label>Describe your Health Issues (Chief Complaints) here:</label>
                                            <textarea required id="health_issues" style="height: auto!important;" rows="5" class="user_namer form-control complaints required" name="health_issues" type="text" placeholder="Mention the issue(s) in simple words." autofocus>{{ old('UserProfile.health_issues', $healthIssues->health_issues) }}</textarea>
                                        </div>
                                        <p class="note" style="width:100% !important;">Note: It is recommended to mention your issues  now so that doctor can diagnose you quickly when you arrive.</p>
                                        <div class="form-group">
                                            <button type="submit" class="save_btn_signup form-control">Next »  </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    @include('layouts.front.booking_footer')
</div>
@endsection