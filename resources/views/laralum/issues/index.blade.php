@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">@if(isset($title)) {{ $title }} @else Issues @endif</div>
    </div>
@endsection
@section('title', isset($title) ? $title : 'Issues')
@section('icon', "pencil")
@section('subtitle', 'List of all '.isset($title) ? $title : 'Issues')
@section('content')

    <div class="ui one column doubling stackable grid">
        <div class="column">
            <div class="ui very padded segment table_header_row table-responsive bk_table" id="department_list">
                @include('laralum.issues._list')
            </div>
        </div>
    </div>
@endsection


