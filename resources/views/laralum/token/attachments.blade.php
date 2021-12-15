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
        <div class="active section">Vital Data</div>
    </div>
@endsection
@section('title', 'Patient Details')
@section('icon', "pencil")
@section('subtitle', 'Patient Details')

@section('content')
    <link rel="stylesheet" type="text/css" media="screen"
          href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">
    <div class="ui one column doubling stackable">
        <div class="column admin_basic_detail1 admin_wrapper">
            <div class="segment  main_wrapper">
                <div class="ui breadcrumb steps clearfix">
                    <ul>
                        <li><a class="section"
                               href="{{ route('Laralum::patient.show', ['token_id' => $booking->id]) }}">Personal
                                Details</a></li>
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

                        <li>
                            <div class="active section">Attachments</div>
                        </li>
                        <li><a class="section"
                               href="{{ route('Laralum::summary', ['id' => $booking->id]) }}">Summary</a></li>


                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="ui one column doubling stackable vitual_data_wrapper">
        {{--  <div>
              <button onclick="return window.history.go(-1);" class="btn btn-primary ui button blue">
                  Back
              </button>
          </div>--}}
        <div class="column admin_basic_detail1">
            <div class="ui very padded segment about_sec1">
                <div class="page_title">
                    <h2 class="pull-left">Attachments</h2>
                </div>


                <div class="vital-data-wrap">
                    <div class="vital-head">
                        <h2>{{ $booking->getProfile('first_name').' '.$booking->getProfile('last_name') }} </h2>
                        <h2></h2>
                    </div>

                    <div class="vr_row_con">
                        <div class="vital-row">
                            <form method="POST" class="" enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <input type="hidden" name="booking_id" value="{{ $booking->id }}"/>
                                <div class="vital-inner"
                                     style="border:1px solid #eee; padding:10px; align-items: center;text-align: center;">
                                    <div class="vital-col-1">
                                        <label> Choose Attachment</label>
                                    </div>
                                    <div class="vital-col-2">

                                        <input type="file" name="attachments"/>

                                        <div class="vital-btn pull-right">
                                            @if($booking->isEditable())
                                                <button id="save-vital"
                                                        class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>


                            </form>
                        </div>

                        <div class="vital-row">
                            <div class="vital-inner">
                                <div class="vital-col-2 table-responsive table_sec_row">
                                    <table class="ui table table_cus_v last_row_bdr">
                                        <thead>
                                        <th>
                                            Attachment Name
                                        </th>
                                        <th>
                                           Uploaded By Department
                                        </th>
                                        <th>
                                            Operations
                                        </th>
                                        <th>
                                            Size
                                        </th>
                                        <th>
                                            Select to Send in email
                                        </th>
                                        </thead>
                                        <tbody>
                                        @foreach($attachments as $attachment)
                                            <tr>
                                                <td>
                                                    {{$attachment->file_name}}
                                                </td>
                                                <td>
                                                    {{ $attachment->uploaded_by_department }}
                                                </td>
                                                <td><a class="no-disable"
                                                       href="{{  \App\Settings::getDownloadUrl($attachment->disk_name)}}">Download</a>

                                                    @if($attachment->uploaded_by == \Auth::user()->id)
                                                        | <a href="javascript:void(0)" class="no-disable"
                                                             onclick="event.preventDefault(); event.stopPropagation();
                                                                     if(confirm('Are you sure you want to delete this attachment?')) {
                                                                     document.getElementById('delete-attachment-form-{{ $attachment->id }}').submit(); }else{

                                                                     }"
                                                        >
                                                            Delete
                                                        </a>
                                                    @endif

                                                    <form id="delete-attachment-form-{{ $attachment->id }}"
                                                          action="{{ url('/admin/attachment/'.$attachment->id.'/delete') }}"
                                                          method="POST" style="display: none;">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        {{ csrf_field() }}
                                                    </form>
                                                </td>
                                                <td>
                                                    {{ number_format($attachment->file_size / 1048576, 2) }} MB
                                                </td>
                                                <td>
                                                    <input type="checkbox" file-size="{{ $attachment->file_size }}"
                                                           name="send_in_email[]" class="send_in_email"
                                                           value="{{ $attachment->id }}"/>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="5">
                                                <div class="pull-right">

                                                    <form id="send-in-mail-attachment-form"
                                                          action="{{ url('/admin/attachment/'.$booking->id.'/send-in-mail') }}"
                                                          method="POST">
                                                        Send to Email:
                                                        <input type="email" name="email" class="form-control" id="send_to_email"
                                                               value="{{ $booking->user->email }}" required>
                                                        <input type="hidden" name="ids" id="selected_files" value="">
                                                        {{ csrf_field() }}

                                                    <br>
                                                    <button id="save-send-in-mail"
                                                       class="no-disable ui {{ Laralum::settings()->button_color }} submit button">Send Selected</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>


                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection

@section("js")
    <script>
        $(".datepicker").datepicker({dateFormat: "dd-mm-yy", autoclose: true,});

        $(document).ready(function () {
            $("#save-send-in-mail").click(function (e) {

                var items = [];
                var size = [];
                $(".send_in_email").each(function () {
                    if ($(this).is(":checked")) {
                        items.push($(this).val());
                        size = eval(size) + eval($(this).attr('file-size'));
                    }
                })

                var size_in_mb = size / 1048576;

                if (items.length > 10) {
                    e.preventDefault();
                    e.stopPropagation();
                    alert('you can only select maximum of 10 files.');
                } else if(items.length == 0) {
                    e.preventDefault();
                    e.stopPropagation();
                    alert('Please select file to be send.');
                }else{
                    if (size_in_mb > 20) {
                        e.preventDefault();
                        e.stopPropagation();
                        alert('you can only select maximum of 10 files.');
                    } else {
                        $("#selected_files").val(items);
                    }
                }
            })
        })

    </script>
@endsection