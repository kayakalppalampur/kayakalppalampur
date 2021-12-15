@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Tax Details List</div>
    </div>
@endsection
@section('title', 'Tax Details')
@section('icon', "pencil")
@section('content')
    <div class="ui one column doubling stackable grid">
        <div class="column">
            <div class="ui very padded segment table_header_row" id="department_list">
                @include('laralum.tax_details._list')
            </div>
        </div>
    </div>
@endsection


