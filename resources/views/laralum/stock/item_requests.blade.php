@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::stock') }}">{{ trans('laralum.stock_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{ trans('laralum.item_request_list') }}</div>
    </div>
@endsection
@section('title',  trans('laralum.item_requests'))
@section('icon', "pencil")
@section('subtitle', 'List of all Item Requested')
@section('content')

    <div class="ui one column doubling stackable">
        <div class="column">
            <div class="ui very padded segment table_header_row table-responsive" id="department_list">
                @include('laralum.stock._item_requests')
            </div>
        </div>
    </div>
@endsection



