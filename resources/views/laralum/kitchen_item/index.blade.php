@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">{{ trans('laralum.kitchen_items_list') }}</div>
    </div>
@endsection
@section('title',  trans('laralum.kitchen_items'))
@section('icon', "pencil")
@section('subtitle', 'List of all Kitchen Items')
@section('content')

    <div class="ui one column doubling stackable">
        <div class="column">
            <div class="ui very padded segment table_header_row table-responsive bk_table" id="department_list">
                @include('laralum.kitchen_item._list')
            </div>
        </div>
    </div>
@endsection


