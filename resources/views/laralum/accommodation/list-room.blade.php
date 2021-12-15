@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::buildings') }}">{{ trans('laralum.buildings') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{ trans('laralum.room_list') }}</div>
    </div>
@endsection
@section('title', trans('laralum.room_list'))
@section('icon', "building")
@section('content')
    <div class="ui one column doubling stackable">
        <div class="column">
            <div class="ui very padded segment table_header_row table-responsive" id="department_list">
                @include('laralum.accommodation._list-room')
            </div>
        </div>
    </div>
@endsection
