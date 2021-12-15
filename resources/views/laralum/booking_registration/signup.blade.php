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
            @include('laralum.booking_registration.topbar')
            <div class="ui one column doubling stackable">
                {{--  <div>
                      <button onclick="return window.history.go(-1);" class="btn btn-primary ui button blue">
                          Back
                      </button>
                  </div>--}}


                <div class="column admin_basic_detail1">
                    <div class="segment form_spacing_inn">


                        <form method="POST" class="">
                            {{ csrf_field() }}

                            @if(isset($reregister))
                                <input type="hidden" value="{{ $reregister }}" name="reregister" />
                            @endif

                            <div class="about_sec signup_bg">
                                <h3 class="title_3">Booking Process</h3>

                                @if (session('status') == 'danger')
                                    <div class="alert alert-danger">
                                        {!! session('message') !!}
                                    </div>
                                @endif

                                <input type="hidden" value="" id="user_id" name="user_id">
                                <div class="form-group">
                                    <input class="user_name form-control required" required type="text"
                                           value="{{ old('user.username', $user->name) }}" name="user[username]"
                                           id="username" placeholder="Username">
                                </div>
                                <div class="form-group">
                                    <input class="user_email form-control required" id="email-id" required type="email"
                                           name="user[email]" value="{{ old('user.email', $user->email) }}" id="email"
                                           placeholder="Email Id">
                                </div>
                                <div id="check-email-result"></div>
                                <div class="password_fields">
                                    <div class="form-group">
                                        <input class="user_password form-control required" required type="password"
                                               name="user[password]" id="password" placeholder="Password">
                                    </div>
                                    <div class="form-group">
                                        <input class="user_confirm form-control required" required type="password"
                                               name="user[password_confirmation]" id="confirm_password"
                                               placeholder="Confirm Password">
                                    </div>
                                </div>
                                <div class="form-group btn_signup_con" style="flex-direction: row;display: flex;">
                                    <button class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.next') }}</button>
                                    <a href="{{ url('admin/booking/registration/personal_details?skip=1') }}" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.skip') }}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


            </div>
            @endsection

            @section('js')
                <script>


                    var val = $("#email-id").val();

                    if (val != "") {
                        changedEmail(val);
                    }

                    $(document).delegate("#email-id", 'change', function () {
                        changedEmail($(this).val());
                    });
                    function changedEmail(val) {
                        $.ajax({
                            url: "{{ url("check-email") }}",
                            type: "POST",
                            data: {'email': val, '_token': "{{ csrf_token() }}"},
                            success: function (response) {
                                if (response.success == true) {
                                    $("#check-email-result").html("<i class='fa fa-check'></i>");
                                    $("#user_id").val("");
                                    $(".password_fields").show();
                                    $(".user_confirm").attr('disabled', false);
                                    $(".user_password").attr('disabled', false);
                                    $(".user_name").show();
                                    $(".user_name").attr('disabled', false);
                                } else {
                                    $("#check-email-result").html("<i class='fa fa-info'></i> This email already exists, please click on Next button to continue the booking or try another email.");
                                    $("#user_id").val(response.user_id);
                                    $(".password_fields").hide();
                                    $(".user_confirm").attr('disabled', 'disabled');
                                    $(".user_password").attr('disabled', 'disabled');
                                    $(".user_name").hide();
                                    $(".user_name").attr('disabled', 'disabled');
                                }
                            }
                        })
                    }
                </script>
@endsection