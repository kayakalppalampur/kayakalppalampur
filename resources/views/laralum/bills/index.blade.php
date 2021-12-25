@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Bills List</div>
    </div>
@endsection
@section('title', 'Bills')
@section('icon', "pencil")
@section('subtitle', 'List of all Bills')
@section('content')
    <div class="ui one column doubling stackable grid">
        <div class="column">
            <div class="ui very padded segment table_header_row table-responsive" id="department_list">
               @include('laralum.bills._list')
            </div>
        </div>
    </div>
@endsection


