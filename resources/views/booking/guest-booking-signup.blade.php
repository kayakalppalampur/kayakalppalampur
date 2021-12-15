@extends('layouts.front.web_layout')
@section('content')
<div class="admin_wrapper signup">
    <header>
        <div class="logo_wrapper wow fadeInDown">
            <a href="{{ url("/home") }}"> <h1>Kayakalp</h1> </a>
        </div>
        {{--<div class="item">
            <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                <i class="globe icon"></i>
                <span class="text responsive-text"> {{ trans('laralum.language') }}</span>
                <div class="menu">
                    @foreach(Laralum::locales() as $locale => $locale_info)
                        @if($locale_info['enabled'])
                            <a href="{{ route('Laralum::locale', ['locale' => $locale]) }}" class="item">
                                @if($locale_info['type'] == 'image')
                                    <img class="ui image"  height="11" src="{{ $locale_info['type_data'] }}">
                                @elseif($locale_info['type'] == 'flag')
                                    <i class="{{ $locale_info['type_data'] }} flag"></i>
                                @endif
                                {{ $locale_info['name'] }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>--}}
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
                @if (session('status') == 'danger')
                    <div class="alert alert-danger">
                        {!! session('message') !!}
                    </div>
                @endif
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
                {!! Form::open(array('route' => 'guest.booking.signup', 'id' => 'bookingProcessForm','files'=>true,'method'=>'post')) !!}
                    {{--<form id="bookingProcessForm" action="{{ route('guest.booking') }}" method="post">--}}
                    {{ csrf_field() }}
                    <section>
                        <div class="pro_main_content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="about_sec white_bg signup_bg">
                                        <h3 class="title_3">Booking Process</h3>
                                        {{--
                                        <div class="form-group col-md-6">
                                            <input class="user_name form-control required" required type="text" value="{{ old('user.first_name') }}" name="user[first_name]" id="first_name" placeholder="First Name" autofocus>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <input  class="user_last form-control required" required type="text" value="{{ old('user.last_name') }}" name="user[last_name]" id="last_name" placeholder="Last Name">
                                        </div>
                                        --}}
                                        <div class="form-group col-md-6">
                                            <input class="user_name form-control required" required type="text" value="{{ old('user.username', $user->name) }}" name="user[username]" id="username" placeholder="Username" autofocus>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-md-6">
                                            <input id="email-id" class="user_email form-control required" required type="email" name="user[email]" value="{{ old('user.email', $user->email) }}" id="email" placeholder="Email Id">
                                            <div id="check-email-result"></div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-md-6">
                                            <input class="user_password form-control required" required type="password" name="user[password]" id="password" placeholder="Password">
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-md-6">
                                            <input class="user_confirm form-control required" required type="password" name="user[password_confirmation]" id="confirm_password" placeholder="Confirm Password">
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-md-6">
                                            <a href="{{ url('password/reset') }}" class="forgot"> Forgot Password? </a>
                                        </div><div class="clearfix"></div><br>
                                        <input type="hidden" id="user_id" name="user_id" value="{{ old('user_id') }}"/>
                                        <div class="form-group col-md-6">
                                            <button type="submit" class="save_btn_signup form-control">Next »  </button>
                                            <a href="{{ url('guest/booking/personal_details?skip=1') }}" class="save_btn_signup {{ Laralum::settings()->button_color }} form-control">{{ trans('laralum.skip') }}  »</a>
                                        </div>
                                        <div class="clearfix"></div>
                                        <br>
                                        
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
@section('script')
    <script>
        $("#email-id").change(function(){
            console.log('sds');
            $.ajax({
                url:"{{ url("check-email") }}",
                type:"POST",
                data:{'email':$(this).val(), '_token':"{{ csrf_token() }}"},
                success:function (response) {
                    if (response.success == true) {
                        $("#check-email-result").html("<i class='fa fa-check'></i>");
                    }else{
                        $("#check-email-result").html("<i class='fa fa-info'></i> This email already exists, if it belongs to you then please input the password below or try using another email.");
                        $("#user_id").val(response.user_id);
                    }
                }
            })
        });
    </script>
@endsection