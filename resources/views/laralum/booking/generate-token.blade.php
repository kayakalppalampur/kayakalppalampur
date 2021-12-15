@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        {{--<a class="section" href="{{ route('Laralum::bookings') }}">{{ trans('laralum.booking_list') }}</a>
        <i class="right angle icon divider"></i>
        @php
            if($booking->patient_type == \App\Booking::PATIENT_TYPE_IPD)
               $route = route('Laralum::booking.show', ['booking_id' => $booking->id]);
            else
               $route = route('Laralum::opd.booking.show', ['booking_id' => $booking->id]);
        @endphp

        <a class="section"
           href="{{ $route }}">{{ trans('laralum.booking_details') }}</a>
        <i class="right angle icon divider"></i>--}}
        <a class="section" href="{{ route('Laralum::token.list') }}">Tokens List</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.generate_token') }}</div>
    </div>
@endsection
@section('title', 'Bookings')
@section('icon', "pencil")
@section('subtitle', 'Generate Tokens')
@section('content')
    <div class="ui one column doubling stackable">

        <div class="column">
            <section class="booking_filter booking_search_patient ui padded segment">
                <div class="row">
                    <div class="search_patient_con  signup_bg">


                        <h3 class="title_3">SEARCH PATIENT TO GENERATE TOKEN</h3>

                        <div class="form-wrap">

                            <div class="search-patient-wrap">
                                <div class="head-tag-search">
                                    <p>SEARCH PATIENT</p>
                                </div>
                                <form id="bookingFilter" action="{{ route('Laralum::bookings.generate_token') }}"
                                      method="POST">
                                    {{ csrf_field() }}
                                    {{--  <div class="form-group">
                                      <label>Barcode</label>
                                          <input class="user_namer form-control required" type="text" id="filter_bar_code" value="{{ @$_REQUEST['filter_bar_code'] }}" name="filter_bar_code" autofocus>
                                      </div>--}}
                                    <div class="form-group">
                                        <label>UHID</label>
                                        <input class="user_last form-control required" type="text"
                                               id="filter_uh_id" value="{{ @$_REQUEST['filter_uh_id'] }}"
                                               name="filter_uh_id">
                                    </div>
                                    <div class="form-group">
                                        <label>Registration ID</label>
                                        <input class="user_last form-control required" type="text"
                                               id="filter_patient_id" value="{{ @$_REQUEST['filter_patient_id'] }}"
                                               name="filter_patient_id">
                                    </div>

                                    {{-- <div class="form-group">
                                         <label>Booking ID</label>
                                         <input class="user_last form-control required" type="text"
                                                id="filter_patient_id" value="{{ @$_REQUEST['filter_patient_id'] }}"
                                                name="filter_booking_id">
                                     </div>--}}
                                    <div class="form-group">
                                        <label>Email ID</label>
                                        <input class="user_email form-control required" type="email" id="filter_email"
                                               value="{{ @$_REQUEST['filter_email'] }}" name="filter_email">
                                    </div>
                                    <div class="form-group">
                                        <label>Mobile No.</label>
                                        <input class="user_password form-control required" type="text"
                                               name="filter_mobile" value="{{ @$_REQUEST['filter_mobile'] }}"
                                               id="filter_mobile">
                                    </div>
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input class="user_namee form-control required" type="text" name="filter_name"
                                               id="filter_name" value="{{ @$_REQUEST['filter_name'] }}">
                                    </div>
                                    <div class="form-button_row">
                                        <button class="ui button no-disable blue">Search</button>
                                    </div>
                                </form>
                            </div>

                            <div class="token-form-wrap">
                                <table class="table ui padded">
                                    <thead>
                                    <tr>
                                        <th style="padding: 7px !important; background-color: #ddebf7;">Department</th>
                                        @foreach(\App\Department::all() as $department)
                                            <th style="padding: 7px !important; background-color: #ddebf7;">{{ $department->title }}</th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <th>Previous Token</th>

                                        @foreach(\App\Department::all() as $department)
                                            <th>
                                                @foreach($department->getDoctors() as $doctor)
                                                    <p>{{ $doctor->name }}- {{ $doctor->getLastTokenNo() }}</p>
                                                @endforeach
                                            </th>
                                        @endforeach
                                    </tr>
                                    </tbody>
                                </table>
                                @if(isset($booking->id))
                                    <form class="token patient_grn_token" method="POST"
                                          action="{{ url('/admin/booking/print-token/'.$booking->id) }}">
                                        {!! csrf_field() !!}
                                        <div class="form-group">
                                            <div class="col-2"><label>Allocate token to</label></div>
                                            <div class="col-10">
                                                <p>{{ $booking->getProfile('first_name') ? $booking->getProfile('first_name').' '.$booking->getProfile('last_name') : "" }}</p>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-2"><label>Registration ID</label></div>
                                            <div class="col-10">
                                                <p>{{ $booking->getProfile('kid') ? $booking->getProfile('kid') : ""}}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-2"><label>UHID</label></div>
                                            <div class="col-10">
                                                <p>{{ $booking->getProfile('uhid') ? $booking->getProfile('uhid') : ""}}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-2"><label> Select Department</label></div>
                                            <div class="col-10">
                                                <select name="department_id" class="form-control" id="department_id"
                                                        required>
                                                    <option value="">Select i.e. Ayurveda / Naturopathy</option>
                                                    @foreach(\App\Department::all() as $department)
                                                        <option value="{{ $department->id }}">{{ $department->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-2"><label> Select Doctor</label></div>
                                            <div class="col-10">
                                                <select class="form-control" name="doctor_id" id="doctor_id" required>
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                        </div>
                                        {{--<div class="form-group">
                                        <div class="col-2">	 <label> Token Number is</label></div>
                                            <input type="hidden" name="token_no" value="{{ $token_no }}"/>
                                            <input type="hidden" name="patient_id" value="{{ $user->id}}"/>
                                              <div class="col-10">  {{ $token_no }}</div>
                                        </div>--}}
                                        <input type="hidden" name="patient_id" value="{{ $user->id}}"/>
                                        <input type="hidden" name="booking_id" value="{{ $booking->id}}"/>
                                        @if($user->id != null)
                                            <div class="form-button_row btnmx">
                                                <button class="ui button no-disable blue ">PRINT TOKEN RECIEPT</button>
                                            </div>
                                        @else
                                            <div class="form-button_row">
                                                <button class="ui button no-disable blue">PRINT TOKEN RECIEPT</button>
                                            </div>
                                        @endif

                                    </form>
                                @elseif($search == true)
                                    <div class="ui negative icon message">
                                        <i class="frown icon"></i>
                                        <div class="content">
                                            <div class="header"> {{ $error }} </div>
                                            <p>There are currently no patients</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
    {{--
            <div class="column">

                <div class="ui very padded segment">

                </div>
            </div>--}}

@endsection
@section('js')
    <script>

        function updateDropdown(department_id) {
            console.log('department_id : ' + department_id);
            $.ajax({
                type: 'POST',
                url: "{{ url('get_department_doctors') }}",
                data: {'department_id': department_id, "_token": "{{ csrf_token() }}"},
                success: function (data) {
                    $('#doctor_id').html(data);
                }
            });
        }

        var val = $("#department_id").val();
        updateDropdown(val);

        $("#department_id").change(function () {
            var val = $(this).val();
            console.log('dep' + val);
            updateDropdown(val);
        })
    </script>
@endsection


