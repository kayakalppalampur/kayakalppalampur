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
                        <li><a class="section"
                               href="{{ route('Laralum::patient.show', ['token_id' => $booking->id]) }}">Personal
                                Details</a></li>

                        {{--<a class="section" href="{{ route('Laralum::patient.show',  ['token_id' => $token->id]) }}">Case History</a>--}}

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

                        <li>
                            <div class="active section">Summary</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="ui one column doubling stackable">
        {{--<div>--}}
        {{--<button onclick="return window.history.go(-1);" class="btn btn-primary ui button blue">--}}
        {{--Back--}}
        {{--</button>--}}
        {{--</div>--}}
        <div class="column">
            <div class="ui very padded segment table_sec2">
                <div class="page_title table_top_btn">
                    <h2 class="pull-left">Summary</h2>
                    <div class="pull-right btn-group">
                        <button onclick="return window.history.go(-1);" class="btn btn-primary ui button blue">
                            Back
                        </button>
                        <a class="btn btn-primary ui button blue no-disable"
                           href="{{ url('admin/print-summary/'.$booking->id) }}">Print</a>
                        <a class="btn btn-primary ui button blue no-disable" id="send_in_email_btn" href="javascript:void(0)">Send In Email</a>
                    </div>

                    <div class="pull-right btn-group">
                        @if(Laralum::loggedInUser()->hasPermission('discharge_patients'))
                            <a class="btn btn-primary ui button no-disable"
                               href="{{ url('admin/patient/discharge/'.$booking->id) }}">Discharge Patient</a>
                        @endif

                        {{--<a class="btn btn-primary ui button blue" href="{{ url('admin/token/first-visit/'.$token->id) }}">First Visit</a>--}}
                    </div>

                    <div class="clearfix"></div>

                    <div class="pull-right" id="send_in_email_div" style="display:none;">
                        <form id="send-in-mail-attachment-form"
                              action="{{ url('/admin/summary/'.$booking->id.'/send-in-mail') }}"
                              method="POST">
                            <span class="col-md-2">Send to Email:</span>
                            <div class="col-md-6">

                                <input name="send_to_email" type="email" class="form-control" id="send_to_email"
                                       value="{{ $booking->user->email }}" required>
                                <input type="hidden" name="ids" id="selected_files" value="">
                                {{ csrf_field() }}
                            </div>
                            <div class="col-md-2">
                                <button id="save-send-in-mail" content="pull-right"
                                        class="no-disable ui {{ Laralum::settings()->button_color }} submit button">Send
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="clearfix"></div>
                    <br>
                </div>
                @include('laralum.token._summary_data')
                @if($attachments->count() > 0)
                    <div class="table_head_lft">
                        <table class="ui table table_cus_v bs">
                            <tbody>
                            <!-- <tr>
                                <h3 style="border-top: 1px solid #ddd; border-right: 1px solid #ddd; border-left: 1px solid #ddd">
                                    <center>Attachments</center>
                                </h3>
                            </tr> -->
                             <tr><th colspan="7"><h5>Attachments</h5></th></tr>
                            <tr>
                                <th>Attachment Name</th>
                                <th>Uploaded By</th>
                                <th>File Size</th>
                                <th>Actions</th>
                            </tr>
                            @foreach($attachments as $attachment)
                                <tr>
                                    <td>
                                        {{$attachment->file_name}}
                                    </td>
                                    <td>
                                        {{ $attachment->uploaded_by_department }}
                                    </td>
                                    <td>
                                        {{ number_format($attachment->file_size / 1048576, 2) }} MB
                                    </td>

                                    <td><a class="no-disable"
                                           href="{{  \App\Settings::getDownloadUrl($attachment->disk_name)}}">Download</a>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>

        $("#send_in_email_btn").click(function () {
            $("#send_in_email_div").show();
        })

        $("#copy").click(function () {
            url: "{{ url('admin/token/get-patient-details/'.$booking->id) }}",
                $.ajax({
                    type: "POST",
                    data: {'_token': "{{ csrf_token() }}"},
                    success: function (data) {
                        if (data.status == 'OK') {

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

        $("[id^=download_report_]").click(function () {
            setInterval(hideloader2, 1000);
        })
        
        function hideloader2(){
            location.reload();
        }

    </script>
@endsection
<style>
.segment table.ui.table td {
    font-size: 1em;
    min-width: 100px;
    word-break: break-all;
}
</style>