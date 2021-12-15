@extends('layouts.admin.panel')

@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">{{ $title }}</div>
    </div>
@endsection
@section('title', $title)
@section('icon', "users")
{{--@section('subtitle', trans('laralum.users_subtitle'))--}}
@section('content')
    <div class="ui one column doubling stackable grid">
        <div class="column">
            <div class="ui very padded segment table_header_row table-responsive" id="department_list">
                    @include('laralum.users._index')
                </div>
            </div>
        </div>
    </div>
@endsection
