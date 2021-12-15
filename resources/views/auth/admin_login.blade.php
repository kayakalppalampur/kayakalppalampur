@extends('layouts.front.web_layout')
@section('content')
    <div class="login_wrapper">
        <div class="login_left_con">
            <div class="login_lefT_inner">
                <h1>Welcome to <span>Kayakalp</span></h1>
                {{--<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500</p>--}}
                <h6>© 2013-2016 Kayakalp Inc. </h6>
            </div>
        </div>
        <div class="login_right_con">
            <div class="login_right_inner">
                <div class="login_heading"> <i class="fa fa-unlock-alt"></i>Admin Login</div>

                <form id="login-form" class="form-horizontal" role="form" method="POST" action="{{ url('/admin/login') }}">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <input type="email" class="form-control" placeholder="Email" id="email"  name="email" value="{{ old('email') }}" required autofocus>
                        @if ($errors->has('email'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" id="password" name="password" required>
                        @if ($errors->has('password'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 ">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember"> Remember Me
                                </label>
                            </div>
                        </div>
                        <a class="pull-right forgot_pass" href="{{ url('/password/reset') }}">Forgot password?</a>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
<?php /*
{{--
@extends('layouts.main')
@section('content')
    <div class="title m-b-md">
        {{ trans('laralum.login_title') }}
    </div>
    <form method="POST">
        {{ csrf_field() }}
        <input name='email' type="email" placeholder="{{ trans('laralum.email') }}">
        <input name='password' type="password" placeholder="{{ trans('laralum.password') }}">
        <input type="checkbox" name="remember"> Remember Me<br><br>
        <a href="{{ url('password/reset') }}">{{ trans('laralum.forgot_password') }}</a>
        <br><br>
        @if(config('services.facebook'))
            <a class="social" href="{{ route('Laralum::social', ['provider' => 'facebook']) }}">{{ trans('laralum.social_login_with', ['provider' => 'facebook']) }}</a>
        @endif
        @if(config('services.twitter'))
            <a class="social" href="{{ route('Laralum::social', ['provider' => 'twitter']) }}">{{ trans('laralum.social_login_with', ['provider' => 'twitter']) }}</a>
        @endif
        @if(config('services.google'))
            <a class="social" href="{{ route('Laralum::social', ['provider' => 'google']) }}">{{ trans('laralum.social_login_with', ['provider' => 'google']) }}</a>
        @endif
        @if(config('services.github'))
            <a class="social" href="{{ route('Laralum::social', ['provider' => 'github']) }}">{{ trans('laralum.social_login_with', ['provider' => 'github']) }}</a>
        @endif
        <br><br><br>
        <button class="button button5">{{ trans('laralum.submit') }}</button>
    </form>
@endsection

--}} */?>