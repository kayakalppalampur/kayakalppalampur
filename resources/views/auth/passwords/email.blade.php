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
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="login_heading"> <i class="fa fa-unlock-alt"></i>Forgot Your Password ?</div>

                <form id="forget-form" class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
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
                        <a class="pull-right login-button" href="{{ url('/login') }}">Login?</a>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Send Password Reset Link
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

