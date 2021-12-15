@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Treatment List</div>
    </div>
@endsection
@section('title', 'Treatments')
@section('icon', "pencil")
@section('subtitle', 'List of all treatments')
@section('content')
    <div class="ui one column doubling stackable">
        <div class="column">
            <div class="ui very padded segment table_header_row table-responsive" id="department_list">
                @include('laralum.treatment._list')
            </div>
        </div>
    </div>
@endsection


