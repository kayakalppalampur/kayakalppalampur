@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">{{ trans('laralum.group_items_list') }}</div>
    </div>
@endsection
@section('title',  trans('laralum.group_items'))
@section('icon', "pencil")
@section('subtitle', 'List of all Group Items')
@section('content')
    <div class="ui one column doubling stackable">
        <div class="column">
            <div class="ui very padded segment table_header_row table-responsive" id="department_list">
                @include('laralum.group-items._list')
            </div>
        </div>
    </div>
@endsection

