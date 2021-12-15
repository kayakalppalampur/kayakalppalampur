@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::bookings') }}">{{ trans('laralum.booking_list') }}</a>
        <i class="right angle icon divider"></i>
        @if($booking->booking_id != null)
            <a class="section" href="{{ route('Laralum::booking.show', ['booking_id' => $booking->id]) }}">{{ trans('laralum.booking_details') }}</a>
            <i class="right angle icon divider"></i>
        @endif
        <div class="active section">Booking</div>
    </div>
@endsection
@section('title', 'Booking')
@section('icon', "pencil")
@section('subtitle', 'Booking')
@section('content')

    <div class="ui one column doubling stackable">

    <div class="admin_wrapper signup">
        <div class="main_wrapper">
            @include('laralum.booking_registration.topbar')

            <div id="edit_details">
                    <div class="column admin_basic_detail1">
                        <div class="segment form_spacing_inn">
                            <div class="about_sec signup_bg">
                                <div  class="page_title">
                                    <h3 class="title_3">Health Issues</h3>
                                    Mention cheif complaints / health issues that you want to treatment for
                                </div>
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
                                    {!! Form::open(array('route' => [ 'Laralum::booking.registration.health_issues.store', 'user_id' => $booking->id], 'id' =>  'bookingProcessForm','class'=>'','files'=>true,'method'=>'post')) !!}
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label>Describe your Health Issues (Chief Complaints) here:</label>
                                    <textarea id="health_issues" style="height: auto!important;" rows="5" class="user_namer form-control complaints required" name="health_issues" type="text" placeholder="Mention the issue(s) in simple words." autofocus>{{ old('UserProfile.health_issues', $profile->health_issues) }}</textarea>
                                </div>
                                <p class="note">Note: It is recommended to mention your issues  now so that doctor can diagnose you quickly when you arrive.</p>
                                <div class="form-group">
                                    <button class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.next') }}</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
            <script>
                $("#edit_button").click(function () {
                    $("#show_details").hide();
                    $("#edit_details").show();
                });
                $("#show_button").click(function () {
                    $("#show_details").show();
                    $("#edit_details").hide();
                });
            </script>
@endsection