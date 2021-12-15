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
        <div class="active section">Discharge Patient</div>
    </div>
@endsection
@section('title', 'Discharge Patient')
@section('icon', "pencil")
@section('subtitle', '')
@section('content')
    <link rel="stylesheet" type="text/css" media="screen"
          href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">

    <div class="ui one column doubling stackable">
        <div class="column admin_basic_detail1 admin_wrapper">
            <div class="segment main_wrapper">
                <div class="ui breadcrumb steps clearfix">
                    <ul>
                        <li><a class="section"
                               href="{{ route('Laralum::patient.show', ['token_id' => $booking->id]) }}">Personal
                                Details</a>
                        </li>
                        {{-- <a class="section" href="{{ route('Laralum::tokens') }}">Case History</a>
                         <i class="right angle icon divider"></i>--}}
                        <li><a class="section"
                               href="{{ route('Laralum::patient.vital_data', ['token_id' => $booking->id]) }}">Vital
                                Data</a>
                        </li>
                        <li><a class="section"
                               href="{{ route('Laralum::patient_lab_test.index', ['patient_id' => $booking->id]) }}">Lab
                                Tests</a></li>
                        <li><a class="section"
                               href="{{ route('Laralum::patient.diagnosis', ['patient_id' => $booking->id]) }}">Provisional
                                Diagnosis</a>
                        </li>

                        <li><a class="section"
                               href="{{ route('Laralum::patient.treatment', ['patient_id' => $booking->id]) }}">Allot
                                Treatments</a></li>

                        <li><a class="section"
                               href="{{ route('Laralum::patient.diet-chart', ['patient_id' => $booking->id]) }}">Diet
                                Chart</a></li>
                        <li>
                            <div class="active section">Discharge Patient</div>
                        </li>

                        <li><a class=" section"
                               href="{{ route('Laralum::attachments', ['booking_id' => $booking->id]) }}">Attachments
                            </a></li>

                        <li><a class="section"
                               href="{{ route('Laralum::summary', ['id' => $booking->id]) }}">Summary</a></li>
                        {{--@php
                            $dept_model=\App\Department::where('title','like',"%Physiotherapy%")->first();
                        @endphp

                        @if(\Auth::user()->isAdmin()||(!empty($dept_model) && \Auth::user()->department->department_id==$dept_model->id))
                            <li><a 
                                   href="{{ route('Laralum::recommend-exercise.assign', ['patient_id' => $booking->id]) }}">Attachments
                                </a></li>
                            <li>
                        @endif--}}
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <div class="admin_wrapper signup discharge_patient_con">
        <div class="main_wrapper">
            <div class="ui one column doubling stackable">
                <div class="column admin_basic_detail1">
                    <div class="ui very padded segment">
                        {{--@if ($booking->isEditable())--}}
                            {!! Form::open(array('route' => ['Laralum::discharge.patient.store', 'token_id' => $booking->id], 'id' => 'dischargeForm','files'=>true,'method'=>'post')) !!}
                            {{ csrf_field() }}
                        {{--@endif--}}
                        <div class="signup_bg column2 table_top_btn">
                            <h3 class="title_3"> Discharge Form </h3>
                            <div class="pull-right">
                                {{--       <a class="btn btn-primary ui button blue" href="{{ url('admin/token/first-visit/'.$patient->id) }}">First Visit</a>--}}
                                       <a class="btn btn-primary ui button blue" href="{{ url('admin/print-patient-discharge/'.$booking->id) }}">Print</a>
                            </div>
                        </div>

                        <div class="about_sec signup_bg discharge_form">
                            <!--  <h3 class="title_3"></h3> -->
                            <div class="discharge-form-row form-new1">

                                <div class="section-1">
                                    <div class="form-dod">
                                        <div class="form-group col-4">
                                            <div class="discharge-row">
                                                <div class="col-5">
                                                    <label>DOA*</label>
                                                </div>
                                                <div class="col-5">{{ date('d-m-Y', strtotime($booking->getDate('check_in_date'))) }}</div>
                                            </div>
                                            <input type="hidden"
                                                   value="{{ date('d-m-Y', strtotime($booking->getDate('check_in_date'))) }}"
                                                   name="date_of_arrival"/>
                                            <input type="hidden" value="{{ date('d-m-Y') }}" name="date_of_discharge"/>
                                            <input type="hidden" value="{{ $patient->id }}" name="patient_id"/>
                                            <input type="hidden" value="{{ $patient->id }}" name="token_id"/>
                                            <input type="hidden" value="{{ $booking->id }}" name="booking_id"/>

                                        </div>
                                        <div class="form-group col-4">
                                            <div class="discharge-row">
                                                <div class="col-5"><label>DOD</label></div>

                                                <div class="col-5">  {{ $discharge_patient->date_of_discharge != null && $discharge_patient->date_of_discharge != "0000-00-00" ?  date('d-m-Y', strtotime($discharge_patient->date_of_discharge)) : date("d-m-Y") }} </div>

                                                <input type="hidden"
                                                       value="{{ $discharge_patient->date_of_discharge != null && $discharge_patient->date_of_discharge != "0000-00-00" ? $discharge_patient->date_of_discharge : date("d-m-Y") }}"
                                                       name="date_of_discharge"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-dod">
                                        <div class="form-group col-4">
                                            <div class="discharge-row">
                                                <div class="col-5"><label>Name of Patient</label></div>
                                                <div class="col-5">{{ $booking->getProfile('first_name').' '.$booking->getProfile('last_name')}}</div>
                                            </div>
                                        </div>
                                        <div class="form-group col-4">
                                            <div class="discharge-row">
                                                <div class="col-5"><label>Sex</label></div>
                                                <div class="col-5"> {{ \App\UserProfile::getGenderOptions($booking->getProfile('gender')) }}</div>
                                            </div>
                                        </div>
                                        <div class="form-group col-4">
                                            <div class="discharge-row">
                                                <div class="col-5"><label>Age</label></div>
                                                <div class="col-5"> {{ $booking->getProfile('age')}}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="section-2">
                                    <div class="form-group">
                                        <div class="col-2"><label>Diagnosis</label></div>
                                        <div class="col-10">
                                            @if($discharge_patient->isEditable())
                                                <textarea name="diagnosis"
                                                          value="{{ isset($diagnosis) ? $diagnosis->description != "" ? $diagnosis->description : $booking->getComplaints() : $booking->getComplaints()  }}">{!! isset($diagnosis) ? $diagnosis->description != "" ? $diagnosis->description : $booking->getComplaints() : $booking->getComplaints()  !!}</textarea>
                                            @else
                                                <p>
                                                    {!! isset($booking->provisional_diagnosis->description) ? $booking->provisional_diagnosis->description != "" ? $booking->provisional_diagnosis->description : $booking->getComplaints() : $booking->getComplaints()  !!}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-2"><label>Discharge Summary</label></div>
                                        <div class="col-10">
                                            @if($discharge_patient->isEditable())
                                                <textarea name="discharge_summary"
                                                          value="{{ $discharge_patient->discharge_summary }}">{{ $discharge_patient->discharge_summary }}</textarea>
                                            @else
                                                <p>
                                                    {!! $discharge_patient->discharge_summary !!}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-2"><label>Investigation Report (if any)</label></div>
                                        <div class="col-10">
                                            @if($discharge_patient->isEditable())
                                                <textarea name="investigation_report"
                                                          value="{{ $discharge_patient->investigation_report }}">{{ $discharge_patient->investigation_report }}</textarea>
                                            @else
                                                <p>
                                                    {!! $discharge_patient->investigation_report !!}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="section-3">
                                    <div class="vital-wrap">

                                        <div class="vital-head">
                                            <h2>Vital Report*</h2>
                                        </div>
                                        <div class="form-group">
                                            <label>On Admission</label>
                                        </div>
                                        <div class="form-group">
                                            <div class="discharge-form-row">
                                                <div class="col-2"><label>BP</label></div>
                                                <div class="col-10">  {{ $vital_data->bp }} </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="discharge-form-row">
                                                <div class="col-2"><label>PR</label></div>
                                                <div class="col-10"> {{ $vital_data->pulse }} </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="discharge-form-row">
                                                <div class="col-2"><label>WT</label></div>
                                                <div class="col-10"> {{ $vital_data->weight }} </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>On Discharge</label>
                                        </div>
                                        <div class="form-group">
                                            <div class="discharge-form-row">
                                                <div class="col-2"><label>BP</label></div>
                                                <div class="col-10">
                                                    @if($discharge_patient->isEditable())
                                                        <input type="text" name="bp"
                                                               value="{{ $discharge_vital->bp }}"/>
                                                    @else
                                                        {!! $discharge_vital->bp !!}
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="discharge-form-row">
                                                <div class="col-2"><label>PR</label></div>
                                                <div class="col-10">
                                                    @if($discharge_patient->isEditable())
                                                        <input type="text" name="pulse" value="{{ $discharge_vital->pulse }}"/>
                                                    @else
                                                        {!! $discharge_vital->pulse !!}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="discharge-form-row">
                                                <div class="col-2"><label>WT</label></div>
                                                <div class="col-10">
                                                    @if($discharge_patient->isEditable())
                                                        <input type="text" name="weight" value="{{ $discharge_vital->weight }}"/>    
                                                    @else
                                                        {!! $discharge_vital->weight !!}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="section-4">
                                    @foreach(\App\Department::all() as $department)
                                        <h4>{{ $department->title }}</h4>
                                        @php $department_discharge = \App\DepartmentDischargeBooking::where('booking_id', $booking->id)->where('department_id', $department->id)->first();
                                        if ($department_discharge == null) {
                                            $department_discharge = new \App\DepartmentDischargeBooking();
                                        }
                                        @endphp
                                        <div class="dis-summary">
                                            <div class="form-group">
                                                <label>Summary*</label>
                                                <div class="col-10">
                                                    <textarea
                                                            name="summary" {{ \Auth::user()->department->department_id != $department->id ? 'disabled' : '' }}>{{ $department_discharge->summary }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dis-summary">
                                            <div class="form-group">
                                                <label>Things To Avoid*</label>
                                                <div class="col-10">
                                                    <textarea
                                                            name="things_to_avoid" {{ \Auth::user()->department->department_id != $department->id ? 'disabled' : '' }}>{{ $department_discharge->things_to_avoid }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="dis-summary">
                                            <div class="form-group">
                                                <label>Follow up Advice*</label>
                                                <div class="col-10">
                                                    <textarea
                                                            name="follow_up_advice" {{ \Auth::user()->department->department_id != $department->id ? 'disabled' : '' }}>{{ $department_discharge->follow_up_advice }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="section-5">
                                <div class="dis-diet">
                                    <div class="form-group">
                                        <div class="vital-head"><h2>Diet Plan*</h2></div>
                                        <div class="col-12"> For Days

                                            @if($discharge_patient->isEditable())
                                                <input type="text" name="diet_plan_duration"
                                                       value="{{ $discharge_patient->diet_plan_duration }}"/> @else
                                                {{ $discharge_patient->diet_plan_duration }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="section-5">
                                <div class="dis-next">
                                    <div class="form-group">
                                        <div class="vital-head"><h2>Next Follow Up Plan*</h2></div>
                                        @if($discharge_patient->isEditable())
                                            <div class="col-12"> Patient Should visit again after
                                                <input type="text" name="followup_days"
                                                       value="{{ $discharge_patient->getFollowupDays() }}"
                                                       id="followup_days"/>
                                                days
                                                <span class="sep-row">Or</span>
                                                Select any future date
                                                <input type="text"
                                                       value="{{ $discharge_patient->getFollowupDate() }}"
                                                       class="datepicker future_date" name="followup_date"/>
                                                <br><br> 
                                <b>Incase of urgent care:</b><br><br>
                                Contact on 01894-235666/235676 Between 9 a.m. to 5 p.m.<br><br>
                                Or may walk into OPD Between 9 a.m. to 5 p.m. (on all week days)
                                            </div>
                                        @else

                                            <div class="col-12"> Patient Should visit again
                                                after {{ $discharge_patient->getFollowupDays() }}
                                                days <br><br> 
                                <b>Incase of urgent care:</b><br><br>
                                Contact on 01894-235666/235676 Between 9 a.m. to 5 p.m.<br><br>
                                Or may walk into OPD Between 9 a.m. to 5 p.m. (on all week days)
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{--<div class="section-5">
                                <div class="dis-diet">
                                    <div class="form-group">
                                        <div class="vital-head"><h2>Recommended Exercise*</h2></div>
                                        <div class="col-12">
                                            @foreach($booking->recommened_exercises as $exercise)
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        {{ $exercise->physiotherpy_exercise->name_of_excercise }}
                                                    </div>
                                                    <div class="col-md-6">
                                                        <a href="{{ route('Laralum::recommend-exercise.print',['exercise_id'=> $exercise->physiotherpy_exercise_id]) }}"
                                                           style="color:#444;">
                                                            <button type="button"
                                                                    class="btn btn-info btn-lg value_{{$exercise->physiotherpy_exercise_id}}">
                                                                Print
                                                            </button>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            </div>--}}

                            <div class="vital-btn1 discharge_btn_rw">
                               {{-- @if ($booking->isEditable())--}}
                                    <button class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                                {{--@endif--}}
                            </div>

                        </div>
                    </div>
                    {{--@if ($booking->isEditable())--}}
                        {!! Form::close() !!}
                    {{--@endif--}}
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@section("js")
    <script>
        $(".datepicker").datepicker({
            dateFormat: "dd-mm-yy", autoclose: true, minDate: 0, changeMonth: true,
            changeYear: true,
            onSelect: function (date, obj) {
                var date = new Date();
                var date_pick = $('.datepicker').datepicker('getDate');
                // end - start returns difference in milliseconds
                var diff = new Date(Date.parse(date_pick) - Date.parse(date));

// get days
                var days = diff / 1000 / 60 / 60 / 24;
                days = parseInt(days) + 1;

                $("#followup_days").val(days);
            }
        });

        $("#followup_days").blur(function () {
            var days = parseInt($(this).val());

            if (days > 0) {
                var date = new Date();
                date.setDate(date.getDate() + days);
                console.log('date' + date.getDate());

                $('.datepicker').datepicker('setDate', date);
            } else {
                $('.datepicker').val('');
            }
        })
    </script>
@endsection