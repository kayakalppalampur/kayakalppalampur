@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">{{  trans('laralum.dashboard') }}</div>
    </div>
@endsection
@section('title', 'Dashboard')
@section('icon', "pencil")
@section('subtitle', 'Patients')
@section('content')

    <div class="ui one column doubling stackable grid container">

        <div class="column">
            <section class="booking_filter booking_search_patient ui padded segment">
                <div class="row">
                    <div class="col-md-12">
                        <div class="about_sec white_bg signup_bg">
                            <div><span  class="title_3"><b>SEARCH PATIENTS</b></span><br/>
                                (Through Anyone Option)
                            </div>
                            <form id="bookingFilter" method="POST">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <input class="user_namer form-control required" type="text" id="filter_patient_id" value="{{ @$_REQUEST['filter_patient_id'] }}" name="filter_patient_id" placeholder="Patient ID" autofocus>
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
                                <div class="form-button_row"><button class="ui button no-disable {{ \App\Http\Controllers\Laralum\Laralum::settings()->button_color }}">Search</button></div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        @if(!empty($booking))
        <div class="column">
            <div class="ui very padded segment">
                    <table class="ui table ">
                        <thead>
                        <tr>
                            <th>{{ trans('laralum.name') }}</th>
                            <th>{{ trans('laralum.patient_id') }}</th>
                            <th>{{ trans('laralum.email') }}</th>
                            <th>{{ trans('laralum.contact_no') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="text">
                                        <a>{{ $booking->getProfile('first_name').' '. $booking->getProfile('last_name') }}</a>
                                    </div>
                                </td>
                                <td>{{ $booking->getProfile('kid') }}</td>
                                <td>
                                    {{ $booking->user->email }}
                                </td>
                                <td>
                                    {{ $booking->getProfile('mobile') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                <div style="padding-left: 129px;">

                    <div class="form-button_row  col-md-2" style="width:unset">
                    <a href="{{ route('Laralum::patient.show', ['id' => $booking->id]) }}" class="ui button no-disable {{ \App\Http\Controllers\Laralum\Laralum::settings()->button_color }}">
                        <i class="fa fa-eye"></i>
                        First Visit
                    </a>
                    </div>
                    <div class="form-button_row  col-md-2" style="width:unset">
                    <a href="{{ route('Laralum::patient.diagnosis', ['id' => $booking->id]) }}" class="ui button no-disable {{ \App\Http\Controllers\Laralum\Laralum::settings()->button_color }}">
                        <i class="fa fa-eye"></i>
                        Provisional Diagnosis
                    </a>
                    </div>
                    <div class="form-button_row  col-md-2" style="width:unset">
                    <a href="{{ url("admin/patient-treatment/".$booking->id) }}" class="ui button no-disable {{ \App\Http\Controllers\Laralum\Laralum::settings()->button_color }}">
                        <i class="fa fa-eye"></i>
                        Allot Treatment
                    </a>

                    </div>
                    <div class="form-button_row  col-md-2" style="width:unset">
                    <a href="{{ url("admin/patient-diet-chart/".$booking->id) }}"  class="ui button no-disable {{ \App\Http\Controllers\Laralum\Laralum::settings()->button_color }}">
                        <i class="fa fa-eye"></i>
                        Allot Diet
                    </a>
                    </div>
                    <div class="form-button_row  col-md-2" style="width:unset">
                    <a href="{{ url("admin/patient/discharge/".$booking->id) }}" class="ui button no-disable {{ \App\Http\Controllers\Laralum\Laralum::settings()->button_color }}">
                        <i class="fa fa-eye"></i>
                        Discharge Patient
                    </a>

            </div>
            </div>
            <br>
        </div>
        @elseif($search == true )
            <div class="column">
                <div class="ui very padded segment">
                    <div class="ui negative icon message">
                        <i class="frown icon"></i>
                        <div class="content">
                            <div class="header">
                                {{  $error }}
                            </div>
                            <p>There are currently no bookings</p>
                        </div>
                    </div>
                    </div>
                </div>
        @endif
    </div>
    <div class="modal fade" id="bookingModal" role="dialog" data-backdrop="static">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="modal-close close"  data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Booking Wizard</h4>
                </div>
                <div class="modal-body">
                    <p>Some text in the modal.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default modal-close" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <style type="text/css" media="print">
        @page
        {
            size: auto;   /* auto is the initial value */
            margin: 0mm;  /* this affects the margin in the printer settings */
        }
    </style>
@endsection
@section('js')
    <script>


        function openModal(url, title ) {
            $(".modal .modal-title").html(title);
            $(".modal .modal-body").html("Content loading please wait...");
            $(".modal").modal("show");
            $(".modal").modal({
                backdrop: 'static',
                keyboard: false,
            });
            $(".modal .modal-body").load(url);
        }

        $(".modal-close").click(function() {
            $(".modal").modal("hide");
        });
        /*$("#print").click(function (e) {
         /!*e.preventDefault();*!/
         $(".search-patient-wrap").hide();
         $(".head-sec2").hide();
         $(".content-title").hide();
         $(".page-footer").hide();
         $("#menu-div").hide();
         $(".sidebar").hide();
         $("body").removeClass("top-main-cls dimmable pushable scrolling");
         $(".booking_filter").removeClass("ui");
         $("button").hide();
         $("a").hide();

         window.print();
         $(".search-patient-wrap").show();
         $(".head-sec2").show();
         $(".content-title").show();
         $(".page-footer").show();
         $("#menu-div").show();
         $(".sidebar").show();
         $("body").addClass("top-main-cls dimmable pushable scrolling");
         $(".booking_filter").addClass("ui");
         $("button").show();
         $("a").show();
         });
         */

    </script>
@endsection



