@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Create Booking</div>
    </div>
@endsection
@section('title', 'Create Booking')
@section('icon', "pencil")
@section('subtitle', 'Create new Booking')
@section('content')
    <div class="admin_wrapper signup">
        <div class="main_wrapper">
            @include('laralum.booking.topbar')
            <div class="ui one column doubling stackable grid container">
                {{--  <div>
                      <button onclick="return window.history.go(-1);" class="btn btn-primary ui button blue">
                          Back
                      </button>
                  </div>--}}
                <div class="column admin_basic_detail1">
                    <div class="ui very padded segment">

                        <form method="POST">
                            {{ csrf_field() }}
                            <div class="about_sec white_bg signup_bg">
                                <h3 class="title_3">Booking Process</h3>
                                <div class="tab">
                                    <button class="tablinks" onclick="openTab(event, 'NEWBOOKING')">London</button>
                                    <button class="tablinks" onclick="openTab(event, 'REVISIT')">Paris</button>
                                </div>

                                <div id="NEWBOOKING" class="tabcontent">
                                    <div class="form-group">
                                        <input class="user_name form-control required" required type="text"
                                               value="{{ old('user.username', $user->name) }}" name="user[username]"
                                               id="username" placeholder="Username" autofocus>
                                    </div>
                                    <div class="form-group">
                                        <input class="user_email form-control required" required type="email"
                                               name="user[email]" value="{{ old('user.email', $user->email) }}"
                                               id="email" placeholder="Email Id">
                                    </div>
                                    <div class="form-group">
                                        <input class="user_password form-control required" required type="password"
                                               name="user[password]" id="password" placeholder="Password">
                                    </div>
                                    <div class="form-group">
                                        <input class="user_confirm form-control required" required type="password"
                                               name="user[password_confirmation]" id="confirm_password"
                                               placeholder="Confirm Password">
                                    </div>
                                    <div class="form-group">
                                        <button class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.next') }}</button>
                                    </div>
                                </div>
                            </div>

                            <div id="REVISIT" class="tabcontent">
                                <div class="form-group">
                                    <input class="user_name form-control required" required type="text"
                                           value="{{ old('user.username', $user->name) }}" name="user[username]"
                                           id="username" placeholder="Username" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="user_email form-control required" required type="email"
                                           name="user[email]" value="{{ old('user.email', $user->email) }}" id="email"
                                           placeholder="Email Id">
                                </div>
                                <div class="form-group">
                                    <input class="user_password form-control required" required type="password"
                                           name="user[password]" id="password" placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <input class="user_confirm form-control required" required type="password"
                                           name="user[password_confirmation]" id="confirm_password"
                                           placeholder="Confirm Password">
                                </div>
                                <div class="form-group">
                                    <button class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.next') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>


    </div>

    <script>
        function openCity(evt, cityName) {
            var i, x, tablinks;
            x = document.getElementsByClassName("title_3");
            for (i = 0; i < x.length; i++) {
                x[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablink");
            for (i = 0; i < x.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" w3-red", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " w3-red";
        }
    </script>

@endsection

