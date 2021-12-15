@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @if($booking->isEditable())
            <a class="section" href="{{ route('Laralum::patients') }}">{{ trans('laralum.patient_list') }}</a>
        @else
            <a class="section"
               href="{{ route('Laralum::archived.patients.list') }}">{{ trans('laralum.archived_patients') }}</a>
        @endif
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.view_token') }}</div>
    </div>
@endsection
@section('title', 'Patient Details')
@section('icon', "pencil")
@section('subtitle', 'Patient Details')

@section('content')
    <div class="doubling stackable">
        <div class="column admin_basic_detail1 admin_wrapper">
            <div class="segment main_wrapper">
                <div class="ui breadcrumb steps clearfix">
                    <ul>
                        <li>
                            <div class="active section">Personal Details</div>
                        </li>
                        {{-- <i class="right angle icon divider"></i>
                         <a class="section" href="{{ route('Laralum::patient.show',  ['token_id' => $token->id]) }}">Case History</a>--}}

                        <li><a class="section"
                               href="{{ route('Laralum::patient.vital_data', ['token_id' => $booking->id]) }}">Vital
                                Data</a></li>
                        <li><a class="section"
                               href="{{ route('Laralum::patient_lab_test.index', ['patient_id' => $booking->id]) }}">Lab
                                Tests</a></li>
                        <li><a class="section"
                               href="{{ route('Laralum::patient.diagnosis', ['patient_id' => $booking->id]) }}">Provisional
                                Diagnosis</a></li>

                        <li><a class="section"
                               href="{{ route('Laralum::patient.treatment', ['patient_id' => $booking->id]) }}">Allot
                                Treatments</a></li>
                        <li><a class="section"
                               href="{{ route('Laralum::patient.diet-chart', ['patient_id' => $booking->id]) }}">Diet
                                Chart</a></li>
                        <li><a class="section"
                               href="{{ route('Laralum::discharge.patient', ['token_id' => $booking->id]) }}">Discharge
                                Patient</a></li>
                        @php
                            $dept_model=\App\Department::where('title','like',"%Physiotherapy%")->first();
                        @endphp

                        <li><a class=" section"
                               href="{{ route('Laralum::attachments', ['booking_id' => $booking->id]) }}">Attachments
                            </a></li>
                        <li><a class="section"
                               href="{{ route('Laralum::summary', ['id' => $booking->id]) }}">Summary</a></li>
                        {{--@if(\Auth::user()->isAdmin()||(!empty($dept_model) && \Auth::user()->department->department_id==$dept_model->id))
                            <li><a class=" section"
                                   href="{{ route('Laralum::recommend-exercise.assign', ['patient_id' => $booking->id]) }}">Attachments
                                </a>
                            </li>
                        @endif--}}

                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="ui one column doubling stackable">
        {{--  <div>
              <button onclick="return window.history.go(-1);" class="btn btn-primary ui button blue">
                  Back
              </button>
          </div>--}}
        <div class="column">
            <div class="ui very padded segment table_sec2">
                <div class="page_title table_top_btn">
                    <h2 class="pull-left">Basic Details</h2>
                    <div class="pull-right btn-group">
                        @if(Laralum::loggedInUser()->hasPermission('discharge_patients'))
                            <a class="btn btn-primary ui button no-disable"
                               href="{{ url('admin/patient/discharge/'.$booking->id) }}">Discharge Patient</a>
                        @endif

                        {{--       <a class="btn btn-primary ui button blue" href="{{ url('admin/token/first-visit/'.$token->id) }}">First Visit</a>--}}
                    </div>
                </div>

                <div class="table_head_lft">
                    <table class="ui table table_cus_v bs">
                        <tr>
                            <th>UHID</th>
                            <td>{{ $booking->user->uhid}}</td>
                            <th>Patient Id</th>
                            <td>{{ $booking->getProfile('kid') }}</td>
                            <th>Booking Id</th>
                            <td>{{ $booking->booking_id }}</td>
                        </tr>
                    </table>
                </div>

                <div class="table_head_lft">
                    <table class="ui table table_cus_v bs">
                        <thead>
                        <tbody>

                        <tr>
                            <th>Name</th>
                            <td style="border-right:1px solid #ddd">{{ $booking->getProfile('first_name').' '.$booking->getProfile('last_name')}}</td>
                            <th>Type</th>
                            <td>{{ $booking->patient_type != null ? $booking->getPatientType($booking->patient_type) : "" }}</td>
                        </tr>
                        <tr>
                            <th>S/o, D/o, W/o</th>
                            <td style="border-right:1px solid #ddd">{{ $booking->getProfile('relative_name')}}</td>
                            <th>Gender</th>
                            <td>{{ \App\UserProfile::getGenderOptions($booking->getProfile('gender')) }}</td>
                        </tr>
                        <tr>
                            <th>Age</th>
                            <td style="border-right:1px solid #ddd">{{ $booking->getProfile('age') }}</td>
                            <th>Contact Number</th>
                            <td>{{ $booking->getProfile('mobile') }}</td>
                        </tr>
                        <tr>
                            <th>Landline Number</th>
                            <td style="border-right:1px solid #ddd">{{ $booking->getProfile('landline_number') }}</td>
                            <th>Whatsapp Number</th>
                            <td>{{ $booking->getProfile('whatsapp_number') }}</td>
                        </tr>
                        <tr>
                            <th>Marital Status</th>
                            <td style="border-right:1px solid #ddd">{{ $booking->getProfile('marital_status') != null ? \App\UserProfile::getMaritalStatus($booking->getProfile('marital_status')) : ""}}</td>
                            <th>Profession</th>
                            <td>{{ $booking->getProfile('profession_id') != null ? \App\UserProfile::getProfessionType($booking->getProfile('profession_id')) : "" }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td style="border-right:1px solid #ddd">{{ $booking->getStatusOptions($booking->status) }}</td>
                            <th>Date of admission</th>
                            <td>{{ date('d-m-Y', strtotime($booking->getDate('check_in_date'))) }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                {{--<div class="row">
                    <div class="col-md-12">
                        <div class="title">
                            <div class="space10"></div>
                            <div class="page_title"><h2>Health Issues</h2></div>
                            <div class="divider space10"></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table ui">
                                <tbody>
                                <tr>
                                    <th width="20%">Health Issues</th>
                                    <td width="80%">{{ $booking->getProfile()->health_issues }}</td>

                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>--}}

                <div class="table_head_lft">
                    <div class="table_listing_row ">
                        <div class="title">
                            <div class="space10"></div>
                            <div class="page_title"><h2>Details to be filled by doctor</h2></div>
                            <div class="divider space10"></div>
                        </div>
                        <div class="form_doct detail_page">
                            <div class="doct_form_lft about_sec1">
                                <form method="POST" class="form" id="patient-detail-form">
                                    {{ csrf_field() }}
                                    <input type="hidden" value="{{ $patient->id }}" name="patient_id"/>
                                    <input type="hidden" value="{{ $booking->id }}" name="booking_id"/>
                                    <input type="hidden" value="{{ $token->id }}" name="token_id"/>
                                    <div class="form-group">
                                        <label>Pulse (bpm)</label>
                                        <input type="text" id="pulse" name="pulse"
                                               value="{{ old('pulse', $patient_detail->pulse) }}" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>BP (mm Hg)</label>
                                        <input type="text" id="bp" name="bp"
                                               value="{{ old('bp', $patient_detail->bp) }}" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Blood Group</label>
                                        <input type="text" id="blood_group" name="blood_group"
                                               value="{{ old('blood_group', $patient_detail->blood_group) }}"
                                               class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>Height (in cm)</label>
                                        <input type="text" id="height" name="height"
                                               value="{{ old('height', $patient_detail->height) }}"
                                               class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>Weight (Kgs)</label>
                                        <input type="text" id="weight" name="weight"
                                               value="{{ old('weight', $patient_detail->weight) }}"
                                               class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>BMI</label>
                                        <input type="text" disabled id="bmi" name="bmi"
                                               value="{{ old('bmi', $patient_detail->bmi) }}" class="form-control">
                                    </div>
                                </form>

                                <div class="form-group btn_signup_con1">
                                    @if($booking->isEditable() && ($patient_detail->id == null || $patient_detail->status == \App\PatientDetails::STATUS_PENDING))
                                        <button id="save-personal"
                                                class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                                    @endif

                                    @if($patient_detail->id != null && $patient_detail->status != \App\PatientDetails::STATUS_PENDING)
                                        <button class="ui disabled {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.locked') }}</button>
                                    @endif
                                </div>
                            </div>

                            <div class="button_rgt_prev">
                                @if($booking->isEditable() && ($patient_detail->id == null || $patient_detail->status == \App\PatientDetails::STATUS_PENDING))
                                    <button id="copy"
                                            class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.copy_from_previous_visit') }}</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table_head_lft">
                    <div class="table_listing_row ">
                        <div class="title">
                            <div class="page_title"><h2>Address Details</h2></div>
                        </div>
                        <div class="table-responsive">
                            <table class="ui table table_cus_v bs">
                                <tbody>
                                <tr>
                                    <th width="30%">Address</th>
                                    <td width="70%">{!! $booking->getAddress('address1').', '.$booking->getAddress('address2').'<br>'.$booking->getAddress('city').', '.$booking->getAddress('zip').'<br>'.$booking->getAddress('state').'<br>'.$booking->getAddress('country') !!}</td>
                                </tr>
                                <tr>
                                    <th width="30%">Referral Source</th>
                                    <td width="70%">{{ $booking->getAddress('referral_source')}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{--@if($booking->id != null)

                    <div class="row">
                        <div class="col-md-12">
                            <div class="title">
                                <div class="space10"></div>
                                <div class="page_title"><h2>Accomodation Details</h2></div>
                                <div class="divider space10"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table ui">
                                    <tbody>
                                    <tr>
                                        <th>Booking From</th>
                                        <td>{!! date('d-m-Y', strtotime($patient->booking->check_in_date)) !!}</td>
                                        <th>Booking End</th>
                                        <td>{!! date('d-m-Y', strtotime($patient->booking->check_out_date)) !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Building Name</th>
                                        <td>{!! $patient->booking->room->building->name !!}</td>
                                        <th>Booking Type</th>
                                        <td>{!! $patient->booking->getBookingType($patient->booking->booking_type)!!}</td>

                                    </tr>
                                    <tr>
                                        <th>Room No</th>
                                        <td>{!! $patient->booking->room->room_number !!}</td>
                                        <th>Room Type</th>
                                        <td>{!! $patient->booking->room->roomType->name !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif--}}

            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $("#copy").click(function () {
            $.ajax({
                url: "{{ url('admin/token/get-patient-details/'.$booking->id) }}",
                type: "POST",
                data: {'_token': "{{ csrf_token() }}", 'patient_id': '{{ $patient->id }}' },
                success: function (data) {
                    if (data.status == 'OK') {
                        $("#pulse").val(data.pulse);
                        $("#bp").val(data.bp);
                        $("#height").val(data.height);
                        $("#weight").val(data.weight);
                        $("#blood_group").val(data.blood_group);
                        $("#bmi").val(data.bmi);
                    }
                    else{
                        alert("No Previous Visit Saved");
                    }
                }

            });
        });
        $("#save-personal").click(function () {
            $.ajax({
                url: "{{ url('admin/token/post-patient-details/'.$booking->id) }}",
                type: "POST",
                data: $("#patient-detail-form").serialize(),
                success: function (data) {
                    if (data.status == 'OK') {
                        alert("Successfully Saved");
                        if (data.details_status == "{{ \App\PatientDetails::STATUS_COMPLETED }}") {
                            $("#save-personal").html('Locked');
                            $("#save-personal").addClass('disabled');
                            $("#copy").hide();
                        }

                        $("#pulse").val(data.pulse);
                        $("#bp").val(data.bp);
                        $("#height").val(data.height);
                        $("#weight").val(data.weight);
                        $("#blood_group").val(data.blood_group);
                        $("#bmi").val(data.bmi);
                    }
                }

            });
        });
        $("#height").change(function () {
            updateBmi();
        })
        $("#weight").change(function () {
            updateBmi();
        })

        function updateBmi() {
            var h = $("#height").val();
            h = h / 100;
            var w = $("#weight").val();
            var bmi = w / (h * h);
            // var bmi = bmi;
            var bmi = bmi.toFixed(2);
            $("#bmi").val(bmi);
        }

    </script>
@endsection