@extends('layouts.admin.panel') @section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::bookings') }}">{{ trans('laralum.booking_list') }}</a>
        <i class="right angle icon divider"></i>

        @php
            if($booking->patient_type == \App\Booking::PATIENT_TYPE_IPD)
               $route = route('Laralum::booking.show', ['booking_id' => $booking->id]);
            else
               $route = route('Laralum::opd.booking.show', ['booking_id' => $booking->id]);
        @endphp
        <a class="section"
           href="{{ $route }}">{{ trans('laralum.booking_details') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Generate Patient Card</div>
    </div>
@endsection @section('title', 'Bookings')
@section('icon', "pencil")
@section('subtitle', 'Generate Patient Card')
@section('content')
    <div class="ui one column doubling stackable">
        <div class="ui one column doubling stackable">
            <div class="admin_wrapper signup">
                <div class="main_wrapper">
                    @include('laralum.booking.topbar')
                    <div class="column admin_basic_detail1">
                        <div class="segment form_spacing_inn">
                            <div class="about_sec signup_bg">
                                <div class="page_title">
                                    <h3 class="title_3">Print Patient Card</h3>
                                </div>
                                <section class="booking_filter booking_search_patient ui padded segment">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="about_sec signup_bg">
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
                                                                    <div class="patient-card-detail"><label>Full
                                                                            Name:</label> <span
                                                                                class="user-nm">{{ $booking->getProfile('first_name').' '.$booking->getProfile('last_name')  }}</span>
                                                                    </div>
                                                                    <div class="patient-card-detail age-patient-outer">
                                                                        <div class="patient-age-sex-wrap">
                                                                            <label>Age: </label><span
                                                                                    class="user-age">{{ $booking->getProfile('age') }}</span>
                                                                        </div>
                                                                        <div class="patient-age-sex-wrap">
                                                                            <label>Sex:</label> <span
                                                                                    class="user-sex">{{ \App\UserProfile::getGenderOptions($booking->getProfile('gender')) }}</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="patient-card-detail age-patient-outer">
                                                                        <div class="patient-age-sex-wrap">
                                                                            <label style="width:30%;">Patient UHID: </label><span style="width:70%;"
                                                                                    class="user-age">{{ $booking->getProfile('uhid') }}</span>
                                                                        </div>

                                                                        <div class="patient-age-sex-wrap">
                                                                            <label style="width:25%;">Mobile:</label> <span style="width:75%;"
                                                                                    class="user-sex">{{ $booking->getProfile('mobile') }}</span>
                                                                        </div>
                                                                    </div>


                                                                </div>

                                                            </div>

                                                        </form>
                                                        <div class="form-button_row">
                                                            <a class="ui button no-disable blue"
                                                               href="{{ url('admin/booking/print/patient-card/'.$booking->id) }}">PRINT
                                                                PATIENT CARD</a>
                                                        </div>
                                                    </div>
                                                    @if(isset($id)) </div>
                                            </div>@endif
                                        </div>
                                    </div>
                            </div>
                            </section>
                        </div>
                    </div>
                </div>
                {{--
                <div class="column">

                    <div class="ui very padded segment">

                    </div>
                </div>--}}
            </div>
            @endsection
            @section('js')
                <script>

                </script>
@endsection