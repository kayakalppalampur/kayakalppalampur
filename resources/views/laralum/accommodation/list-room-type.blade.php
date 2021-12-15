@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">{{ trans('laralum.room_type_list') }}</div>
    </div>
@endsection
@section('title', trans('laralum.room_type_list'))
@section('icon', "building")
@section('content')
    <div class="ui one column doubling stackable grid">
        <div class="column">
            <div class="ui very padded segment table_header_row table-responsive" id="department_list">
            @include('laralum.accommodation._list_list-room')
            </div>
            <br>
        </div>
    </div>
@endsection
