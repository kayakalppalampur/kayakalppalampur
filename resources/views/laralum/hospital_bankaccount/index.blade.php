@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Bank Account List</div>
    </div>
@endsection
@section('title', 'Bank Accounts')
@section('icon', "pencil")
@section('content')
    <div class="ui one column doubling stackable grid">
        <div class="column">
            <div class="ui very padded segment table_header_row table-responsive" id="department_list">
                @include('laralum.hospital_bankaccount._list')
            </div>
        </div>
    </div>
@endsection



@section('js')
    <script>
        $(".filter_datepicker").datepicker({dateFormat: "dd-mm-yy", autoclose: true});
    </script>
@endsection