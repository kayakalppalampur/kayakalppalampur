@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">{{ trans('laralum.external_service_list') }}</div>
    </div>
@endsection
@section('title', trans('laralum.external_service_list'))
@section('icon', "building")
@section('content')
    <div class="ui one column doubling stackable grid">
        <div class="column">
            <div class="ui very padded segment table_header_row table-responsive" id="department_list">
                @include('laralum.accommodation._list-external-services')
            </div>
            <br>
        </div>
    </div>
@endsection