@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Booking List</div>
    </div>
@endsection
@section('title', 'Bookings')
@section('icon', "pencil")
@section('subtitle', 'List of all IPD Patients ')
@section('content')
    <div class="ui one column doubling stackable">
        <div class="ui one column doubling stackable">
            <div class="column">
                <div class="ui very padded segment table_header_row" id="department_list">

                    @include('laralum.booking._patient_with_acc')
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $(".filter_datepicker").datepicker({dateFormat: "dd-mm-yy", autoclose: true});
    </script>
@endsection