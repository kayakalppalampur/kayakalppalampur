@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Treatment Tokens List</div>
    </div>
@endsection
@section('title', 'Treatment Tokens List')
@section('icon', "pencil")
@section('subtitle', 'List of all Treatment Tokens List')
@section('content')
    <div class="ui one column doubling stackable grid">
        <div class="ui one column doubling stackable grid">
            <div class="column">
                <div class="ui very padded segment table_header_row" id="department_list">
                    @include('laralum.booking._treatment-tokens')
                </div>
            </div>
        </div>
    </div>
@endsection



