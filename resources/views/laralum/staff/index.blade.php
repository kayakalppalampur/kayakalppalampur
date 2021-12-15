@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Staff List</div>
    </div>
@endsection
@section('title', 'Staff')
@section('icon', "pencil")
@section('content')

    <div class="ui one column doubling stackable grid">
        <div class="column">
            <div class="ui very padded segment table_header_row table-responsive" id="department_list">
                @include('laralum.staff._list')
            </div>
        </div>
    </div>
@endsection


