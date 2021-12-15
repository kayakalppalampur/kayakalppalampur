@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">OPD Consultation Slips</div>
    </div>
@endsection
@section('title', 'OPD Consultation Slips')
@section('icon', "pencil")
@section('subtitle', 'List of all OPD Consultation Slips')
@section('content')
    <div class="ui one column doubling stackable grid">
        <div class="ui one column doubling stackable grid">
            <div class="column">
                <div class="ui very padded segment table_header_row" id="department_list">
                    @include('laralum.booking._opdtokens')
                </div>
            </div>
        </div>
    </div>
@endsection



