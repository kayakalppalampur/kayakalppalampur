@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @if($booking->isEditable())
            <a class="section" href="{{ route('Laralum::patients') }}">{{ trans('laralum.patient_list') }}</a>
        @else
            <a class="section" href="{{ route('Laralum::archived.patients.list') }}">{{ trans('laralum.archived_patients') }}</a>
        @endif
        <i class="right angle icon divider"></i>
        <div class="active section">Edit Treatment</div>
    </div>
@endsection
@section('title', 'Edit Treatment')
@section('icon', "pencil")
@section('subtitle', '')
@section('content')
    <link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">
    <div class="ui one column doubling stackable grid container">
        <div class="column admin_basic_detail1 admin_wrapper">
            <div class="ui very padded segment main_wrapper">
                <div class="ui breadcrumb steps clearfix">
                    <ul>
                        <li> <a class="section" href="{{ route('Laralum::patient.show', ['token_id' => $booking->id]) }}">Personal Details</a>
                        </li>
                        {{-- <a class="section" href="{{ route('Laralum::tokens') }}">Case History</a>
                         <i class="right angle icon divider"></i>--}}
                        <li>   <a class="section" href="{{ route('Laralum::patient.vital_data', ['token_id' => $booking->id]) }}">Vital Data</a>
                        </li>
                        <li><a class="section"
                               href="{{ route('Laralum::patient_lab_test.index', ['patient_id' => $booking->id]) }}">Lab Tests</a></li>
                        <li><a class="section"
                               href="{{ route('Laralum::patient.diagnosis', ['patient_id' => $booking->id]) }}">Diagnosis</a></li>

                        <li>  <div class="active section">Allot Treatments</div></li>

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
        <div class="main_wrapper">
            {!! Form::open(array('route' => ['Laralum::patient.treatment_edit', 'treatment_id' => $treatment_token->id], 'id' => 'dischargeForm','files'=>true,'method'=>'post')) !!}
            <div class="ui two column doubling stackable grid container allot_treatment_main admin_basic_detail1">
                
                   <div class="white_bg signup_bg discharge_form row_full">
					  <div class="head-sec2">
						 <h3>Allot Treatment to: {{ $booking->getProfile('first_name').' '.$booking->getProfile('last_name') }}</h3>
					  </div>
                   </div>
                   
                   <div class="column">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}
                        <div class="white_bg signup_bg discharge_form">
                            <div class="ui stackable grid">
                                <div class="column">
                                    <input type="hidden" name="patient_id" value="{{ $treatment_token->patient->id }}">
                                   <div class="row">
                                       <div class="col-md-12">
                                           <label>DATE</label>
                                           <input type="text" name="treatment_date" placeholder="Default Date" class="form-control datepicker" value="{{ $treatment_token->treatment_date }}" />
                                       </div>
                                   </div>
                                    <div style="clear:both;"></div><br/>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>BP</label>
                                            <input type="text" name="bp" {{ $treatment_token->bp != "" ? "disabled" : "" }} value="{{ old('bp', $treatment_token->bp) }}" placeholder="BP" class="form-control" />
                                        </div>
                                        <div class="col-md-6">
                                            <label>Pulse</label>
                                            <input type="text" {{ $treatment_token->pulse != "" ? "disabled" : "" }} name="pulse" value="{{ old('pulse',$treatment_token->pulse) }}"placeholder="Pulse" class="form-control" />
                                        </div>
                                        <div style="clear:both;"></div><br/>
                                        <div class="col-md-6">
                                            <label>Weight</label>
                                            <input type="text" {{ $treatment_token->weight != "" ? "disabled" : "" }} name="weight" value="{{ old('weight', $treatment_token->weight) }}" placeholder="Weight" class="form-control" />
                                        </div>

                                    </div>
                                    <div style="clear:both;"></div><br/>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Note</label>
                                            <textarea name="note" rows="5">{{ $treatment_token->note  }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                    </div>
                </div>

                <div class="column snd_row">
                    <div class="ui very padded segment">
                        <div class="white_bg signup_bg discharge_form">
                            <div class="ui stackable grid">
                                <div class="column">
                                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                    <div class="row">
                                        <div class="col-md-12" >
                                            <div class="form-group">
                                                <input type="checkbox" name="is_special" {{ $treatment_token->is_special ? 'checked' : "" }}/> <span style="right: 7px;left: 37px;position: absolute;top: 17px;">Special Treatment</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 select-treatments" >
                                            <div id="alltreatments" style="display: none;" class="select_option">
                                                <select name="ids[]" class="form-control" id="treatments" >
                                                    <option value="">Select Treatment</option>
                                                    @foreach(\App\Treatment::getTreatments() as $treatment)
                                                        <option value="{{ $treatment->id }}">{{ $treatment->title." : ".$treatment->getDuration() }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @foreach($treatment_token->treatments as $treatment_token_patient)

                                                <div id="streatments_div{{ $loop->iteration }}" class="select_option">
                                                    <select name="ids[]" class="form-control" id="treatments" >
                                                        <option value="">Select Treatment</option>
                                                        @foreach(\App\Treatment::getTreatments() as $treatment)
                                                            <option {{ $treatment_token_patient->treatment_id == $treatment->id ? 'selected' : ""}} value="{{ $treatment->id }}">{{ $treatment->title." : ".$treatment->getDuration() }}</option>
                                                        @endforeach
                                                    </select>
                                                    <button id='remove{{ $loop->iteration }}' class='remove'> <i class='fa fa-close'></i> </button>

                                                </div>
                                            @endforeach


                                        </div>
                                    </div>
                                    <button id="add_more">Add More Treatment </button>
                                    <div style="clear:both;"></div><br/>
                                    
                                </div>
                            </div>
                           
                        </div>
                    </div>
                </div>
                
                <div class="chart_row">
                	
                	<div class="treatment_lft"> <a href="{{ url("admin/patient/".$booking->id."/treatment_history") }}">View Treatment Chart</a> </div>
                	
					<div class="duration_rgt">
						<label>Total Duration:</label>
						<span id="total_duration"></span>
					</div>
				</div>
               
               
               	<div class="form-button_row"><button id="submit" class="ui button no-disable blue">Submit</button></div>
              
                
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section("js")
    <script>
    $( ".datepicker" ).datepicker({dateFormat: "dd-mm-yy", autoclose:true, minDate:0});
    getDuration();
        function getDuration() {
            var treatments = [];
            $("[id^=treatments]").each(function(){
                treatments.push($(this).val());
            });
            var t_ids = treatments.join(",");
            $.ajax({
                url:"{{ url("admin/treatments/get-duration") }}",
                type:"Post",
                data:{"ids": t_ids, "_token":"{{ csrf_token() }}"},
                success:function(data) {
                    $("#total_duration").html(data);
                }
            });
        }

    $(document).delegate("[id^=treatments]", "change", function() {
        getDuration();
    })

    var current_div = $("[id^=streatments_div]").last();
    var current_num = current_div.attr('id').split('streatments_div')[1];
    updateDropdown(current_num);
    function updateDropdown(num) {
        var valar = [];

        console.log('valar'+valar);
        $('[id^=streatments_div]').each(function() {
            var valar = [];
            var current = $(this).find('#treatments');
            $('select[id^=treatments]').not(current).each(function(){
                console.log($(this).val());
                valar.push( $(this).val());
            });
            $(this).find('#treatments option').each(function () {
                console.log("thisval" + $(this).val());
                if ($(this).val() != "") {
                    if (jQuery.inArray($(this).val(), valar) !== -1) {
                        $(this).remove();
                    }
                }
            });
        })
    }


        $("#add_more").click(function (e) {
            e.preventDefault();
            var $div = $('[id^="streatments_div"]:last');
            console.log($div);

// Read the Number from that DIV's ID (i.e: 3 from "klon3")
// And increment that number by 1
            var id = parseInt( $div.prop("id").match(/\d+/g), 10 );
            if (isNaN(id))
                    id = 0;
            var num = id + 1;
            $( "#alltreatments").clone().prop('id', 'streatments_div'+num ).appendTo( ".select-treatments" ).show();
            $( "<button id='remove"+num+"' class='remove'> <i class='fa fa-close'></i> </button>" ).appendTo( '#streatments_div'+num );
            updateDropdown(num);
            var length = $("#streatments_div"+num).find('#treatments option').length;
            if (length == 2 ){
                $("#add_more").hide();
            }else{
                $("#add_more").show();
            }


            getDuration();

        })
        $(document).delegate("[id^=remove]", "click", function (e) {
            e.preventDefault();
            var id = $(this).attr("id").split("remove")[1];
             $("#streatments_div"+id).remove();
             $("#remove"+id).remove();
            var prev = id - 1;
            var length = $("#streatments_div"+prev).find('#treatments option').length;
            if (length == 2 ){
                $("#add_more").hide();
            }else{
                $("#add_more").show();
            }
            getDuration();
        })
    </script>
@endsection