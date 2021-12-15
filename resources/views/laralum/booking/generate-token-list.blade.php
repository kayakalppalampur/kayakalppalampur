@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Tokens List</div>
    </div>
@endsection
@section('title', 'Tokens')
@section('icon', "pencil")
@section('subtitle', 'List of all tokens')
@section('content')
    <div class="ui one column doubling stackable grid">
        <div class="ui one column doubling stackable grid">
            <div class="column">
                <div class="ui very padded segment table_header_row" id="department_list">
                    @include('laralum.booking._tokens')
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

