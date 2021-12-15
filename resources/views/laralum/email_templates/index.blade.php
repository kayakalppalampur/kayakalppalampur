@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Email Templates</div>
    </div>
@endsection
@section('title', 'Email Templates')
@section('icon', "pencil")
@section('subtitle', 'List of all Email Templates')
@section('content')

    <div class="ui one column doubling stackable">
        <div class="column">
            <div class="ui very padded segment table_header_row table-responsive" id="department_list">
                @include('laralum.email_templates._list')
            </div>
        </div>
    </div>
@endsection