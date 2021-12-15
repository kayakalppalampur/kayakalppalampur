@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">{{ trans('laralum.stock_list') }}</div>
    </div>
@endsection
@section('title',  trans('laralum.stocks'))
@section('icon', "pencil")
@section('subtitle', 'List of all stock Items')
@section('content')
    <div class="ui one column doubling stackable">
        <div class="column">
            <div class="ui very padded segment table_header_row table-responsive" id="department_list">
                @include('laralum.stock._list')
            </div>
        </div>
    </div>
@endsection


