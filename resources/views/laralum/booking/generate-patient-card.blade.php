@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::bookings') }}">{{ trans('laralum.booking_list') }}</a>
        <i class="right angle icon divider"></i>
        @if(isset($booking->id))
        <a class="section" href="{{ route('Laralum::booking.show', ['booking_id' => $booking->id]) }}">{{ trans('laralum.booking_details') }}</a>
        <i class="right angle icon divider"></i>
        @endif
        <div class="active section">{{  trans('laralum.generate_token') }}</div>
    </div>
@endsection
@section('title', 'Bookings')
@section('icon', "pencil")
@section('subtitle', 'Generate Tokens')
@section('content')
    <div class="ui one column doubling stackable grid container">
        <div class="column">
            <section class="booking_filter booking_search_patient ui padded segment">
                <div class="row">
                    <div class="col-md-12">
                        <div class="about_sec white_bg signup_bg">
                            <div class="patient_head2">
                                <h3 class="title_3">SEARCH PATIENT</h3>
                                <h4>(Through Anyone Option)</h4>
                            </div>
                            <form id="bookingFilter" method="POST">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <input class="user_namer form-control required" type="text" id="filter_first_name" value="{{ @$_REQUEST['filter_patient_id'] }}" name="filter_patient_id" placeholder="Patient Id" autofocus >
                                </div>
                                <div class="form-group">
                                    <input class="user_namer form-control required" type="text" id="filter_first_name" value="{{ @$_REQUEST['filter_first_name'] }}" name="filter_first_name" placeholder="First Name" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="user_last form-control required" type="text" id="filter_last_name" value="{{ @$_REQUEST['filter_last_name'] }}" name="filter_last_name" placeholder="Last Name">
                                </div>
                                <div class="form-group">
                                    <input class="user_email form-control required" type="email" id="filter_email" value="{{ @$_REQUEST['filter_email'] }}" name="filter_email" placeholder="Email Id">
                                </div>
                                <div class="form-group">
                                    <input class="user_password form-control required" type="text" name="filter_mobile" value="{{ @$_REQUEST['filter_mobile'] }}" id="filter_mobile" placeholder="Mobile Number">
                                </div>
                                <div class="form-button_row"><button class="ui button no-disable blue">Search</button></div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="column admin_basic_detail1">
            <div class="ui very padded segment">
                @if(isset($user->userProfile))
                    <div class="about_sec white_bg signup_bg">
                        <div class="page_title">
                            <h3 class="title_3">Print Patient Card</h3>
                        </div>
                        <section class="booking_filter booking_search_patient ui padded segment">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="about_sec white_bg signup_bg">
                                    <h4 class="print-head">PRINT K-ID (Patient ID of Kayakalp)</h4>
                                    <div class="form-wrap">
                                        <div class="token-form-wrap patient-card-only">
                                            <form id="print-kid" method="post" action="{{ url('admin/booking/print/patient-card/'.$booking->id) }}">
                                                {!! csrf_field() !!}
                                                <input type="hidden" name="backurl" value="{{ url("admin/booking/generate-patient-card") }}">
                                                <div class="patient-card-wrap">
                                                    <div class="profile-pic pull-left"><img src="{{ asset("images/img.png") }}"></div>
                                                    <div class="profile-details pull-right">
                                                        <div class="patient-card-detail"><label>Full Name:</label> <span class="user-nm">{{ $booking->getProfile('first_name').' '.$booking->getProfile('last_name') }}</span></div>
                                                        <div class="patient-card-detail age-patient-outer">
                                                            <div class="patient-age-sex-wrap"><label>Age: </label><span class="user-age">{{ $booking->getProfile('age') }}</span></div>
                                                            <div class="patient-age-sex-wrap"><label>Sex:</label> <span class="user-sex">{{ $user->userProfile->getGenderOptions($booking->getProfile('gender')) }}</span></div>
                                                        </div>
                                                        <div class="patient-card-detail"><label>Patient Id: </label><span class="user-pro">{{ $booking->getProfile('kid') }}</span></div>
                                                        <div class="bar-code-wrap">
                                                            <div class="bar-code1">
                                                                <img src="data:image/png;base64, {!! $barcode !!}" alt="barcode" />
                                                            </div>
                                                            <div class="bar-code2">
                                                                <img src="data:image/png;base64, {!! $qrcode !!}" alt="barcode" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-button_row">
                                                    <button class="ui button no-disable blue" type="submit">PRINT CARD</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    </div>
                @elseif($search == true)
                    <div class="ui negative icon message">
                        <i class="frown icon"></i>
                        <div class="content">
                            <div class="header">
                                {{ $error }}
                            </div>
                            <p>There are currently no patients</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection


