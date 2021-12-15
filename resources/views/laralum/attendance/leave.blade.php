@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::attendance.leaves') }}">Leave list</a>
    </div>
@endsection
@section('title', 'Leave List')
@section('icon', "plus")
@section('subtitle', 'Attendance')
@section('content')
    <link rel="stylesheet" type="text/css" media="screen"
          href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">
    <div class="ui one column doubling stackable grid">
        <div class="column">

            <div class="ui very padded segment table_header_row table-responsive bk_table" id="attendance_list">
                @include('laralum.attendance._leave')
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script>
    {{--<script  src="{{ asset('/laralum_public/js/bootstrap.datetimepicker.js') }}"></script>--}}
    <script src="{{ asset('/js/jquery.timepicker.js') }}"></script>
    <script>

        $(function () {
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': $('[name="_token"]').val()}
            });
        });
    </script>

    <script>
        $(".datepicker").datepicker({dateFormat: "dd-mm-yy", autoclose: true});
    </script>
@endsection