@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @if($booking->isEditable())
            <a class="section" href="{{ route('Laralum::patients') }}">{{ trans('laralum.patient_list') }}</a>
        @else
            <a class="section" href="{{ route('Laralum::archived.patients.list') }}">{{ trans('laralum.archived_patients') }}</a>
        @endif
        <i class="right angle icon divider"></i>
        <div class="active section">Treatment History</div>
    </div>
@endsection
@section('title', 'Allot Treatment')
@section('icon', "pencil")
@section('subtitle', '')
@section('content')
    {{--<div class="ui one column doubling stackable grid container">
        <div class="column admin_basic_detail1 admin_wrapper">

        </div>
    </div>--}}


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
                       {{-- @php
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

    <div class="admin_wrapper signup history_wrapper2">
        <div class="main_wrapper">
            <div class="ui one column doubling stackable">
                <div class="column admin_basic_detail1">
                    <div class="segment allot_treatment_con">
                       
                       
                       <div class="column2 table_top_btn">
							<div class="pull-left">
                                @if ($booking->isEditable())
								<a class="btn_allot2" href="{{ url("admin/patient-treatment/".$booking->id) }}">Allot Treatment</a>
                                    @endif
							</div>
                           <div class="btn-group pull-right">
                               @if ($booking->isEditable())
                                <a href="{{ url('admin/patient/lab-test/'.$booking->id.'/add') }}"class="ui btn button {{ \App\Http\Controllers\Laralum\Laralum::settings()->button_color }}">Add Lab Test</a>
                               @endif
                           </div>
						</div>


                       
                       
                        <div class="white_bg signup_bg discharge_form">

                            <div class="vital-head">{{ $booking->getProfile('first_name').' '.$booking->getProfile('last_name')}}</div>

                            
                            <div class="history_table1">
                                @if($treatments->count() > 0)
                            	<table class="ui table table_cus_v last_row_bdr">
								  <thead>
									  <tr>
										  <th>Date</th>
										  <th>Department</th>
										  <th>Treatments</th>
                                          <th>Note</th>
										  <th>Ratings</th>
										  <th>Brief Feedback</th>
										  <th>Doctor Remark</th>
                                          <th>Actions</th>
									  </tr>
								  </thead>
								  <tbody>
								  @foreach($treatments as $treatment)
									  <tr class="{{ $treatment->isSpecial() ? "special-treatment" : "" }}">
										  <td>    @if( $treatment->isSpecial()) <strong>(Special Treatment)</strong><br>@endif{{ date('d-m-Y',strtotime($treatment->treatment_date)) }} {{ date('h:i a',strtotime($treatment->created_at)) }}
											  <br/>
										      <span>Weight: {{ $treatment->weight }}</span><br/>
											  <span>BP: {{ $treatment->bp }}</span>
											  <span>Pulse: {{ $treatment->pulse }}</span>
										  </td>
										  <td>{{ $treatment->department->title }}</td>
										  <td>

											  @foreach($treatment->treatments as $pat_treat)
												  <span>{{ $pat_treat->treatment->title." (".$pat_treat->treatment->getDuration().')' }}</span><br/>
											  @endforeach
											  </td>
                                          <td>{{ $treatment->note }}</td>
										  <td>  @foreach($treatment->treatments as $pat_treat)
												  <span class="col-lg-fg">
                                                      @if($treatment->department_id == \Auth::user()->department->department_id) <input class="txt-fld" id="rating_{{ $pat_treat->id }}" value="{{ $pat_treat->ratings }}" name="ratings_{{ $pat_treat->id }}"/> @else {{ $pat_treat->ratings }} @endif</span><br/>
											  @endforeach</td>

										  <td> @if($treatment->department_id == \Auth::user()->department->department_id) <textarea class="token_field_{{$treatment->id }}" id="feedback_{{ $treatment->id }}" name="feedback">{{  $treatment->feedback }}  @else {{ $treatment->feedback }} @endif</textarea></td>
										  <td>@if($treatment->department_id == \Auth::user()->department->department_id)<textarea class="token_field_{{$treatment->id }}" id="doctor_remark_{{ $treatment->id }}" name="doctor_remark">{{  $treatment->doctor_remark }}</textarea> @else {{ $treatment->doctor_remark }} @endif</td>
                                          <td>
                                              <a title="Print"  href="{{ url("admin/patient/print-treatment/".$treatment->id) }}"><i class="fa fa-print"></i> </a>
                                              @if($treatment->isEditable())
                                              <a title="Edit" href="{{ url("admin/patient/edit-treatment/".$treatment->id) }}"><i class="fa fa-pencil"></i> </a>
                                                  <a title="Delete" href="{{ route('Laralum::treatment_token.delete', ['id' => $treatment->id]) }}" class="item no-disable">
                                                      <i class="trash bin icon"></i>
                                                  </a>
                                                  @endif
                                          </td>

									  </tr>
								  @endforeach
                                  </tbody>
							    </table>
                                    @else
                                    <div class="ui negative icon message">
                                        <i class="frown icon"></i>
                                        <div class="content">

                                            <p>There are currently no treatment allotted to this patient</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                        </div>
                        
                        </div>
                    </div>
                </div>
          <div class="column">
              

          </div>

        </div>
    </div>
@endsection
@section("js")
    <script>
        $("#bp").click(function () {
            console.log("D");
            $(".patient-details-form").show();
            $(".bp").show();
            $(".pulse").show();
            $(".weight").val("").hide();
        });

    $("#weight").click(function () {
        $(".patient-details-form").show();
        $(".bp").val("").hide();
        $(".pulse").val("").hide();
        $(".weight").show();
    });

        $(".patient-field").change(function () {
            submit();
        })
        function submit() {
            var bp = $(".bp").val();
            var pulse = $(".pulse").val();
            var weight = $(".weight").val();
            if((bp != "" && pulse != "") || weight != "") {
                $.ajax({
                    url: "{{ url("admin/patient-detail-ajax/".$booking->id) }}",
                    type: "POST",
                    data: {
                        'bp': bp,
                        'pulse':pulse,
                        'weight': weight,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function () {
                        alert("Successfully Saved");
                    }
                })
            }
        }

        $("[id^=rating_]").change(function(){
            var id = $(this).attr("id").split("rating_")[1];
            patUpdate(id);
        })
        $("[id^=feedback_]").change(function(){
            var id = $(this).attr("class").split("token_field_")[1];
            updateFeedback(id);
        })
        $("[id^=doctor_remark_]").change(function(){
            var id = $(this).attr("class").split("token_field_")[1];
            updateRemark(id);
        })
        function patUpdate(id) {
            var ratings = $("#rating_" + id).val();
            console.log('rat'+ratings);
            if (ratings != "") {
                $.ajax({
                    url: "{{ url("admin/pat-treatment-update-ajax/") }}" + "/" + id,
                    type: "POST",
                    data: {
                        'ratings': ratings,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        $("#rating_" + response.id).css("width", "90%").parent().append("<i id='ratings_save_" + response.id + "' class='fa fa-check'></i>");

                        setTimeout(function () {
                            $('#ratings_save_' + response.id).remove();
                            $('#rating_' + response.id).css("width", "100%");
                        }, 3000);
                    }
                })
            }
        }

        function updateFeedback(id) {
            var feedback = $("#feedback_" + id).val();
            if (feedback != "") {
                $.ajax({
                    url: "{{ url("admin/treatment-token-update-ajax/") }}" + "/" + id,
                    type: "POST",
                    data: {
                        'feedback': feedback,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        $("#feedback_" + response.id).css("width", "90%").parent().append("<i id='feedback_save_" + response.id + "' class='fa fa-check'></i>");

                        setTimeout(function () {
                            $('#feedback_save_' + response.id).remove();
                            $('#feedback_' + response.id).css("width", "100%");
                        }, 3000);
                    }
                })
            }
        }

        function updateRemark(id) {
            var doctor_remark = $("#doctor_remark_" + id).val();
            if (doctor_remark != "") {
                $.ajax({
                    url: "{{ url("admin/treatment-token-update-ajax/") }}" + "/" + id,
                    type: "POST",
                    data: {
                        'doctor_remark': doctor_remark,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        $("#doctor_remark_" + response.id).css("width", "90%").parent().append("<i id='doctor_remark_save_" + response.id + "' class='fa fa-check'></i>");

                        setTimeout(function () {
                            $('#doctor_remark_save_' + response.id).remove();
                            $('#doctor_remark_' + response.id).css("width", "100%");
                        }, 1000);
                    }
                })
            }
        }

    </script>
@endsection