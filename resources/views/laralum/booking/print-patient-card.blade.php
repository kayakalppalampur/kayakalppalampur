@extends('layouts.front.web_layout')
@section('content')
    <style type="text/css" media="print">
        @page
        {
            size: auto;   /* auto is the initial value */
            margin: 0mm;  /* this affects the margin in the printer settings */
        }
    </style>

    <section class="booking_filter booking_search_patient ui padded segment booking_print">
        <div class="row">
            <div class="col-md-12">
                @php
                    if($booking->patient_type == \App\Booking::PATIENT_TYPE_IPD)
                       $route =  url('admin/booking/print-kid/'.$booking->id);
                    else
                       $route =  url('admin/opd-bookings/print-kid/'.$booking->id);
                @endphp

                <div class="text-center"><a href="{{ $back_url != "" ? $back_url :$route }}"id="back" class="btn btn-primary"> Back</a></div>
                <br/>
                <div class="about_sec white_bg signup_bg">
                    <div class="patient_form_wrap" style="text-align: center;display: inline-block;width: 100%;">
                <h2 style="text-transform: uppercase;font-size:16px;margin-top:0;font-weight:600;line-height:22px;margin-bottom:0;">
                    Vivekanand medical research trust, holta, palampur, (Regd.)<br> Distt. Kangra, Himachal pradesh -176062
                </h2>
                <div class="logo_kaya" style="position: relative;min-height: 95px;">
                    <div class="logo_form" style="float: left;">
                        <img width="100px" src="{{asset('images/slip_left_logo.jpg')}}">
                    </div>
                    <div class="center_head" style="position: absolute;left: 50%;transform: translateX(-50%)">
                        <h3 style="text-transform: uppercase;margin: 0;font-weight: bold;font-size:30px;">Kayakalp</h3>
                        <p style="text-transform: uppercase;font-size:16px;line-height:20px;">Himalayan research
                            institute<br> for yoga and naturopathy</p>
                    </div>
                    <div class="form_phone_detail" style="float: right;text-align:right;">
                        <img width="100px" src="{{ asset('images/slip_right_logo.jpg') }}">
                        <span style="display: block;font-size:16px;margin-top:10px;">Phone: (01894) 235676</span>
                        <span style="display: block;font-size:16px;">Tele Fax: (01894) 235666</span>
                        <span style="display: block;font-size:16px;">Mobile No: 7807310891</span>
                    </div>
                </div>

            </div>
                <h4 class="print-head">PRINT ID (Patient UHID of Kayakalp)</h4>
                    <div class="form-wrap">

                        <div class="token-form-wrap patient-card-only">
                            <form id="print-kid">
                                {!! csrf_field() !!}
                                <div class="patient-card-wrap">
                                    <div class="profile-pic pull-left">
                                        @if($booking->getProfile('profile_picture'))
                                            <img src="{{ $booking->getProfile('profile_picture') }}">
                                        @else
                                            <img src="{{ asset('/images/img.png') }}">
                                    @endif
                                    </div>

                                    <div class="profile-details pull-right">
                                        <div class="patient-card-detail"><label>Full Name:</label> <span class="user-nm">{{ $booking->getProfile('first_name').' '.$booking->getProfile('last_name')  }}</span></div>
                                        <div class="patient-card-detail age-patient-outer">
                                         <div class="patient-age-sex-wrap"><label>Age: </label><span class="user-age">{{ $booking->getProfile('age') }}</span></div>
                                         <div class="patient-age-sex-wrap"><label>Sex:</label> <span class="user-sex">{{ \App\UserProfile::getGenderOptions($booking->getProfile('gender')) }}</span></div>
                                         </div>
                                         <div class="patient-card-detail age-patient-outer">
                                       <div class="patient-age-sex-wrap"><label>Patient UHID: </label><span class="user-pro">{{ $booking->getProfile('uhid') }}</span></div>
                                       <div class="patient-age-sex-wrap">
                                                                            <label style="width:25%;">Mobile:</label> <span style="width:75%;"
                                                                                    class="user-sex">{{ $booking->getProfile('mobile') }}</span>
                                                                        </div>
                                                                        </div>
                                            
                                    </div>

                                </div>

                            </form>
                            <div class="form-button_row">
                                <button class="ui button no-disable blue" id="print">PRINT PATIENT CARD</button>
                            </div>
                        </div>
                        @if(isset($id)) </div>
                </div>@endif
            </div>
        </div>
    </section>
@endsection
@section('script')
<script>
$("#print").click(function () {
    $("#print").hide();
    $("#back").hide();
    $(".booking_filter").css("margin","auto");
    window.print();
    $(".booking_filter").css("margin","130px");
    $("#print").show();
    $("#back").show();
})
</script>
@endsection