@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">{{ trans('laralum.permissions_title') }}</div>
    </div>
@endsection
@section('title', trans('laralum.permissions_title'))
@section('icon', "lightning")
@section('subtitle', trans('laralum.permissions_subtitle'))
@section('content')
    <div class="ui one column doubling stackable grid">
        <div class="column">
            <div class="ui very padded segment table_header_row table-responsive bk_table">
               @include('laralum.permissions._index')
            </div>

            <br>
        </div>
    </div>

@endsection
