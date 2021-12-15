@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @if($booking->isEditable())
            <a class="section" href="{{ route('Laralum::patients') }}">{{ trans('laralum.patient_list') }}</a>
        @else
            <a class="section" href="{{ route('Laralum::archived.patients.list') }}">{{ trans('laralum.archived_patients') }}</a>
        @endif
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.lab_test_list') }}</div>
    </div>
@endsection
@section('title', 'Lab Tests')
@section('icon', "pencil")
@section('subtitle', '')
@section('content')
    <div class="ui one column doubling stackable">
        <div class="column admin_wrapper">
            
        </div>
    </div>

    <div class="ui one column doubling stackable">
        <div class="column admin_wrapper">
            <div class="segment main_wrapper">
                <div class="ui breadcrumb steps clearfix">
                    <ul>
                        <li> <a class="section" href="{{ route('Laralum::patient.show', ['token_id' => $booking->id]) }}">Personal Details</a>
                        </li>
                        {{-- <a class="section" href="{{ route('Laralum::tokens') }}">Case History</a>
                         <i class="right angle icon divider"></i>--}}
                        <li>   <a class="section" href="{{ route('Laralum::patient.vital_data', ['token_id' => $booking->id]) }}">Vital Data</a>
                        </li>
                        <li>  <div class="active section">Lab Tests</div></li>
                        <li><a class="section"
                               href="{{ route('Laralum::patient.diagnosis', ['patient_id' => $booking->id]) }}">Provisional Diagnosis</a></li>

                        <li><a class="section"
                               href="{{ route('Laralum::patient.treatment', ['patient_id' => $booking->id]) }}">Allot
                                Treatments</a></li>


                        <li>  <a class="section" href="{{ route('Laralum::patient.diet-chart', ['patient_id' => $booking->id]) }}">Diet Chart</a></li>
                        <li>
                            <a class="section" href="{{ route('Laralum::discharge.patient', ['token_id' => $booking->id]) }}">Discharge Patient</a></li>

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
    <div class="admin_wrapper signup">
        <div class="main_wrapper">
            <div class="ui very padded segment">
                       

                       
                        <div class="white_bg signup_bg discharge_form">

                            <div class="page_title table_top_btn rell">
                                <div class="vital-head">{{ $booking->getProfile('first_name').' '.$booking->getProfile('last_name')}}</div>
                                <div class="pull-left btn-group">
                                 @if($lab_tests->count() > 0)
                                 @php $price = 0; @endphp
                                    @foreach($lab_tests as $lab_test) 
                                      @php $price +=  $lab_test->getAllPrice(); @endphp
                                    @endforeach
                                   <b> Total Price: </b> {{ $price }}
                                 @endif
                                </div>
                                <div class="pull-right btn-group">
                                    @if($booking->isEditable())
                                        <a href="{{ url('admin/patient/lab-test/'.$booking->id.'/add') }}"class="ui button no-disable btn {{ \App\Http\Controllers\Laralum\Laralum::settings()->button_color }}">Add Lab Test</a>@endif
                                </div>

                                {{-- <div class="pull-right">
                                     <button id="bp" class="ui btn {{ \App\Http\Controllers\Laralum\Laralum::settings()->button_color }}">BP|Pulse Rate</button>
                                     <button id="weight" class="ui btn {{ \App\Http\Controllers\Laralum\Laralum::settings()->button_color }}">Weight of the day</button>
                                 </div>
                                 <div style="clear: both"></div><br/>
                                 <div class="patient-details-form" style="display:none;">
                                     <div class="pull-right form-group col-md-6">
                                     <input  placeholder="bp" style="display:none;" class="form-control bp patient-field" type="text" name="bp"/>
                                     <input  placeholder="pulse" style="display:none;" class="form-control pulse patient-field" type="text" name="pulse"/>
                                     <input  placeholder="weight" style="display:none;" class="form-control weight patient-field" type="text" name="weight"/>
                                     </div>
                                 </div>--}}
                            </div>




                            
                            <div class="history_table1">
                                @if($lab_tests->count() > 0)
                            	<table class="ui table table_cus_v last_row_bdr">
								  <thead>
									  <tr>
										  <th>Date</th>
										  <th>Tests</th>
                                          <th>Result</th>
                                          <th>Price</th>
                                          <th>Actions</th>
									  </tr>
								  </thead>
								  <tbody>
								  @foreach($lab_tests as $lab_test)
									  <tr>
										  <td>{{ $lab_test->date_date }}</td>
                                          <td>{{ $lab_test->getTestsName() }}</td>
                                          <td>{{ $lab_test->note }}</td>
                                          <td>{{ $lab_test->getAllPrice() }}</td>
                                          {{--<td>{{ $lab_test->department->title }}</td>--}}
                                          <td>
                                                <a title="Print"  href="{{ url("admin/patient/print-lab-test/".$lab_test->id) }}"><i class="fa fa-print"></i> </a>

                                                @if($lab_test->test_status == 1)
                                                        &nbsp &nbsp
                                                    <a title="Download Report" id="download_report_{{ $lab_test->id }}" href="{{ url("admin/patient/download_report/".$lab_test->id) }}">
                                                        <i class="fa fa-download" aria-hidden="true"></i>
                                                    </a>

                                                @else

                                                    @if($lab_test->isEditable())
                                                        <a title="Edit" href="{{ url("admin/patient/lab-test/".$lab_test->id."/edit") }}"><i class="fa fa-pencil"></i> </a>
                                                        <a title="Delete" href="{{ route('Laralum::patient_lab_test.delete', ['id' => $lab_test->id]) }}" class="item no-disable">
                                                          <i class="trash bin icon"></i>
                                                        </a>
                                                    @else
                                                        <div class="ui disabled blue icon button">
                                                            <i class="lock icon"></i>
                                                        </div>
                                                    @endif

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

                                            <p>There are currently no tests assigned to this patient</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                        </div>

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

       $("[id^=download_report_]").click(function () {
            setInterval(hideloader2, 1000);
        })
        
        function hideloader2(){
            location.reload();
        }

    </script>
@endsection