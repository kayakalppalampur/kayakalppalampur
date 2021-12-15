@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">{{ trans('laralum.building_list') }}</div>
    </div>
@endsection
@section('title', trans('laralum.building_list'))
@section('icon', "building")
@section('content')
    <div class="ui one column doubling stackable grid">
        <div class="column">
            <div class="ui very padded segment table_header_row table-responsive" id="department_list">
                @include('laralum.accommodation._list-building')
            </div>
            <br>
        </div>
    </div>
@endsection

