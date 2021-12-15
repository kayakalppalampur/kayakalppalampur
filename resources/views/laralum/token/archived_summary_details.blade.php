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
@section('title', 'Patient Summary')
@section('icon', "pencil")
@section('subtitle', 'Patient Summary')

@section('content')
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
                        <a style="color:white" href="{{ url('admin/print_archived_summary/'.$booking->id) }}">
                        <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                    class="print icon"></i><span class="text responsive-text">Print</span></div>
                        </a>
                    </div>

                    <div class="pull-right btn-group">
                    </div>

                    <div class="clearfix"></div>

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
<style>
.segment table.ui.table td {
    font-size: 1em;
    min-width: 100px;
    word-break: break-all;
}
</style>
