@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @if($booking->isEditable())
            <a class="section" href="{{ route('Laralum::patients') }}">{{ trans('laralum.patient_list') }}</a>
        @else
            <a class="section" href="{{ route('Laralum::archived.patients.list') }}">{{ trans('laralum.archived_patients') }}</a>
        @endif
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.lab_test') }}</div>
    </div>
@endsection
@section('title', 'Allot Lab Test')
@section('icon', "pencil")
@section('subtitle', 'Patient\'s Lab Test')

@section('content')
    <link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">

    <div class="ui one column doubling stackable">
        <div class="column admin_basic_detail1 admin_wrapper">
            <div class="ui main_wrapper">
                <div class="ui breadcrumb steps clearfix">
                    <ul>
                        <li> <a class="section" href="{{ route('Laralum::patient.show', ['token_id' => $booking->id]) }}">Personal Details</a> </li>
                        {{-- <a class="section" href="{{ route('Laralum::tokens') }}">Case History</a>
                         <i class="right angle icon divider"></i>--}}
                        <li> <a class="section" href="{{ route('Laralum::patient.vital_data', ['token_id' => $booking->id]) }}">Vital Data</a> </li>
                        <li> <div class="active section">Lab Tests</div></li>

                        <li><a class="section" href="{{ route('Laralum::patient.diagnosis', ['patient_id' => $booking->id]) }}">Provisional Diagnosis</a></li>

                        <li><a class="section" href="{{ route('Laralum::patient.treatment', ['patient_id' => $booking->id]) }}">Allot Treatments</a></li>

                        <li>  <a class="section" href="{{ route('Laralum::patient.diet-chart', ['patient_id' => $booking->id]) }}">Diet Chart</a></li>

                        <li><a class=" section"
                               href="{{ route('Laralum::attachments', ['booking_id' => $booking->id]) }}">Attachments
                            </a></li>
                        <li><a class="section"
                               href="{{ route('Laralum::summary', ['id' => $booking->id]) }}">Summary</a></li>

                        {{--@if(\Auth::user()->isAdmin()||(!empty($dept_model) && \Auth::user()->department->department_id==$dept_model->id))
                            <li><a class=" section"
                                   href="{{ route('Laralum::recommend-exercise.assign', ['patient_id' => $booking->id]) }}">Attachments
                                </a></li>
                            <li>
                        @endif--}}
                    </ul>
                </div>
            </div>
        </div>
    </div>

<div class="admin_wrapper signup">
    <div class="ui one column doubling stackable">
        {{--  <div>
              <button onclick="return window.history.go(-1);" class="btn btn-primary ui button blue">
                  Back
              </button>
          </div>--}}
        <div class="diagnosis_con column admin_basic_detail1">
            <div class="segment form_spacing_inn rell sp_no">

                <div class="about_sec signup_bg ">
                        <div class="column2 table_top_btn buttob_rgt_fix">
                            {{--<h3 class="title_3 title_inline">Lab Test</h3>--}}
                            <div class="btn-group pull-right">
                                <a href="{{ url('admin/patient/'.$booking->id.'/lab-tests') }}"class="ui btn button {{ \App\Http\Controllers\Laralum\Laralum::settings()->button_color }}">Lab Tests</a>
                            </div>
                        </div>
                @if($lab_test->id != "")
                    <form method="POST" id="lab_test"  action="{{ url("admin/patient/lab-test/".$lab_test->id."/edit") }}">
                @else
                <form method="POST" id="lab_test" action="{{ url("admin/patient/lab-test/".$booking->id."/add") }}">
                @endif
                    {{ csrf_field() }}
                    <input type="hidden" name="patient_id" value="{{ $patient->id }}" />
                    <input type="hidden" name="booking_id" value="{{ $booking->id }}" />
                    <div class="vital-data-wrap1">
                        <div class="vital-head">
                            <h2>{{ $booking->getProfile('first_name').' '.$booking->getProfile('last_name')}}</h2>
                        </div>
                        <div class="vital-row1 no_mar-top">
                            {{--<div class="vital-inner">
                                <label>Lab Name</label>
                                <input type="text" name="lab_name" class="form-control" value="{{ old('lab_name', $lab_test->lab_name) }}">
                            </div>--}}

                            <div class="vital-inner">
                                <label>Date</label>
                                <input type="text" name="date" class="form-control datepicker" @if( old('date', $lab_test->date)) value="{{ old('date', $lab_test->date) }}" @else  value="{{ date('d-m-Y') }}" @endif>
                            </div>

                            {{--<div class="vital-inner">
                                <label>Address</label>
                                <textarea type="text" name="address" class="form-control">{{ old('address',$lab_test->address) }}</textarea>
                            </div>
--}}

                            <div class="vital-inner">

                                    <label>Select Test</label>
                                    @foreach(\App\LabTest::getTests() as $test)
                                    <div class="check_box">
                                        <input type="checkbox" name="test_id[]" value="{{ $test->id }}" class="checkbox" {{ $lab_test->checkTest($test->id) ? 'checked' : '' }}> {{ $test->name }} <br>
                                    </div>
                                    @endforeach

                                {{--<input type="checkbox" id="test_id" name="test_id[]" value="other" class="checkbox"> Other

                                <div class="form-group" id="lab_test_name_div" style="display:none;">
                                    Please specify the Test here
                                    <input type="text" name="lab_test_name" id="lab_test_name" class="form-control" placeholder="Lab Test*" disabled />
                                </div>--}}
                            </div>

                            <div class="vital-inner">
                                <label>Result</label>
                                <textarea type="text" name="note" class="form-control">{{ old('note', $lab_test->note) }}</textarea>
                            </div>
                        </div>
                        <div class="vital-btn1"><button id="save-vital" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button> </div>
                    </div>

                </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section("js")
    <script>
        $( ".datepicker" ).datepicker({dateFormat: "dd-mm-yy", autoclose:true});
        $("#lab_test").submit(function(){
            if($("#lab_test_name").val() != "") {
                $("#test_id").prop('disabled', 'disabled');
            }
            return true;
        })
        $("#test_id").change(function(){
            if ($(this).is(":checked")) {
                $("#test_id").prop("required", false);
             //   $("#test_id").prop("disabled", 'disabled');
                $("#lab_test_name").prop("required", true);
                $("#lab_test_name").prop("disabled", false);
                $("#lab_test_name_div").show();
            }else{
                $("#lab_test_name").val("");
                $("#test_id").prop("required", true);
                $("#lab_test_name").prop("required", false);
                $("#lab_test_name").prop("disabled", 'disabled');
                $("#lab_test_name_div").hide();
            }
        });
    </script>
@endsection