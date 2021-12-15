@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Department List</div>
    </div>
@endsection
@section('title', 'Departments')
@section('icon', "pencil")
@section('subtitle', 'List of all departments')
@section('content')
    <div class="ui one column doubling stackable grid">
        <div class="column">
            <div class="ui very padded segment table_header_row table-responsive" id="department_list">
               @include('laralum.departments._list')
            </div>
        </div>
    </div>
@endsection


