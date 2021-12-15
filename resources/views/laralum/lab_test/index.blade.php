@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Lab Test List</div>
    </div>
@endsection
@section('title', 'Lab Tests')
@section('icon', "pencil")
@section('subtitle', 'List of all Lab Tests')
@section('content')

    <div class="ui one column doubling stackable">
        <div class="column">
            <div class="ui very padded segment table_header_row table-responsive bk_table" id="department_list">
                @include('laralum.lab_test._list')
            </div>
        </div>
    </div> 
@endsection


