@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Profession List</div>
    </div>
@endsection
@section('title', 'Profession')
@section('icon', "pencil")
@section('subtitle', 'List of all professions')
@section('content')

    <div class="ui one column doubling stackable grid">
        <div class="column">
            <div class="ui very padded segment table_header_row table-responsive" id="department_list">
                @include('laralum.professions._list')
            </div>
        </div>
    </div>
@endsection


