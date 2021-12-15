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
            <div class="login_heading"> <i class="fa fa-unlock-alt"></i>Department Login</div>

            <form id="login-form" class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
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
            <a href="{{ url('patient/query') }}" class="btn btn-primary btn-block">
                Submit Patient Query
            </a>
        </div>
    </div>
</div>
@endsection