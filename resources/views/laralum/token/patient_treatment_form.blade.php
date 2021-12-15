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
        <div class="active section">Allot Treatment</div>
    </div>
@endsection
@section('title', 'Allot Treatment')
@section('icon', "pencil")
@section('subtitle', '')
@section('content')
    <link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">
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
                               href="{{ route('Laralum::patient.diagnosis', ['patient_id' => $booking->id]) }}">Provisional Diagnosis</a>
                        </li>
                        <li>
                            <div class="active section">Allot Treatments</div>
                        </li>

                        <li><a class="section"
                               href="{{ route('Laralum::patient.diet-chart', ['patient_id' => $booking->id]) }}">Diet
                                Chart</a></li>
                        <li>
                            <a class="section"
                               href="{{ route('Laralum::discharge.patient', ['token_id' => $booking->id]) }}">Discharge
                                Patient</a></li>
                        <li><a class=" section"
                               href="{{ route('Laralum::attachments', ['booking_id' => $booking->id]) }}">Attachments
                            </a></li>
                        <li><a class="section"
                               href="{{ route('Laralum::summary', ['id' => $booking->id]) }}">Summary</a></li>

                        {{--@php
                            $dept_model=\App\Department::where('title','like',"%Physiotherapy%")->first();
                        @endphp

                        @if(\Auth::user()->isAdmin()||(!empty($dept_model) && \Auth::user()->department->department_id==$dept_model->id))
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
        <div class="main_wrapper">
            {!! Form::open(array('route' => ['Laralum::patient.treatment.store', 'patient_id' => $booking->id], 'id' => 'dischargeForm','files'=>true,'method'=>'post')) !!}
            <div class="ui two column doubling stackable admin_basic_detail1">

                <div class="about_sec signup_bg">
                    <div class="vital-head">Allot Treatment to: {{ $booking->getProfile('first_name').' '.$booking->getProfile('last_name')}}</div>

                    <div class="treatment_wrapper no_mar-top">
                        <div class="chart_row">
                            @if($booking->treatments->count() > 0)
                            <div class="treatment_lft" style="float:right !important;"><a href="{{ url("admin/patient/".$booking->id."/treatment_history") }}">View
                                    Treatment Chart</a></div>
                                @endif
                        </div>
                        <div class="treat_inner">
                            <div class="column">
                                <div class="segment">
                                    {{ csrf_field() }}
                                    <div class="white_bg signup_bg discharge_form">

                                        <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                        <div class="form-group">
                                            <label>DATE</label>
                                            <input type="text" name="treatment_date" placeholder="Default Date" required class="form-control datepicker" value="{{ date("d-m-Y") }}"/>
                                        </div>

                                        <div class="form-group">
                                            <div class="fm_block_3">
                                                <label>BP (mm Hg)</label>
                                                <input type="text" name="bp" required value="{{ old('bp', $patient_detail->bp) }}" placeholder="BP" class="form-control"/>
                                            </div>
                                            <div class="fm_block_3">
                                                <label>Pulse (bpm)</label>
                                                <input type="text" required name="pulse" value="{{ old('pulse',$patient_detail->pulse) }}" placeholder="Pulse" class="form-control"/>
                                            </div>

                                            <div class="fm_block_3">
                                                <label>Weight (Kgs)</label>
                                                <input type="text" required name="weight" value="{{ old('weight', $patient_detail->weight) }}" placeholder="Weight" class="form-control"/>
                                            </div>

                                        </div>

                                        <div class="form-group">
                                            <label>Note</label>
                                            <textarea name="note" rows="5"></textarea>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="column">
                                <div class="white_bg signup_bg discharge_form">
                                    <div class="column">
                                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                        <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                        <div class="form-group-chk checbox_ch">
                                            <input type="checkbox" name="is_special"/>
                                            <label>Special Treatment</label>
                                        </div>

                                        <div class="form-group">
                                            <div class="select-treatments">
                                                <div id="streatments_div" class="select_option">
                                                    <select name="ids[]" class="form-control" id="treatments">
                                                        <option value="">Select Treatment</option>
                                                        @foreach(\App\Treatment::getTreatments() as $treatment)
                                                            <option value="{{ $treatment->id }}">{{ $treatment->title." : ".$treatment->getDuration() }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <button id="add_more">Add More Treatment</button>
                                        <div style="clear:both;"></div>
                                    </div>

                                </div>
                            </div>

                            <div class="chart_row">

                                <div class="duration_rgt">
                                    <label>Total Duration:</label>
                                    <span id="total_duration"></span>
                                </div>
                            </div>


                            <div class="vital-btn1">
                                <button id="submit" class="ui orange submit button">Submit</button>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section("js")
    <script>
        $(".datepicker").datepicker({dateFormat: "dd-mm-yy", autoclose: true, minDate: 0});
        getDuration();

        function getDuration() {
            var treatments = [];
            $("[id^=treatments]").each(function () {
                treatments.push($(this).val());
            });
            var t_ids = treatments.join(",");
            $.ajax({
                url: "{{ url("admin/treatments/get-duration") }}",
                type: "Post",
                data: {"ids": t_ids, "_token": "{{ csrf_token() }}"},
                success: function (data) {
                    $("#total_duration").html(data);
                }
            });
        }

        $(document).delegate("[id^=treatments]", "change", function () {
            getDuration();
        })
        $("#add_more").hide();
        $("#treatments").change(function () {
            console.log('val' + $(this).val());
            if ($(this).val() != "")
                $("#add_more").show();
            else
                $("#add_more").hide();
        });
        $("#add_more").click(function (e) {
            e.preventDefault();
            var $div = $('[id^="streatments_div"]:last');
            console.log($div);

// Read the Number from that DIV's ID (i.e: 3 from "klon3")
// And increment that number by 1
            var id = parseInt($div.prop("id").match(/\d+/g), 10);
            if (isNaN(id))
                id = 0;
            var num = id + 1;
            $("#streatments_div").clone().prop('id', 'streatments_div' + num).appendTo(".select-treatments");
            $("<button id='remove" + num + "' class='remove'> <i class='fa fa-close'></i> </button>").appendTo('#streatments_div' + num);
            var valar = [];
            $('select[id^=treatments]').each(function () {
                console.log($(this).val());
                valar.push($(this).val());
            });
            console.log('valar' + valar);
            $("#streatments_div" + num).find('#treatments option').each(function () {
                console.log("thisval" + $(this).val());
                if (jQuery.inArray($(this).val(), valar) !== -1) {
                    $(this).remove();
                }

            });
            var length = $("#streatments_div" + num).find('#treatments option').length;
            if (length == 1) {
                $("#add_more").hide();
            } else {
                $("#add_more").show();
            }

            getDuration();

        })

        $(document).delegate("[id^=remove]", "click", function (e) {
            e.preventDefault();
            var id = $(this).attr("id").split("remove")[1];
            $("#streatments_div" + id).remove();
            $("#remove" + id).remove();
            var prev = id - 1;
            var length = $("#streatments_div" + prev).find('#treatments option').length;
            if (length == 1) {
                $("#add_more").hide();
            } else {
                $("#add_more").show();
            }
            getDuration();
        })
    </script>
@endsection