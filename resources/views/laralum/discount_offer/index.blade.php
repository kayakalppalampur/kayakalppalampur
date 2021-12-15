@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Discount Offer List</div>
    </div>
@endsection
@section('title', 'Discount Offers')
@section('icon', "pencil")
@section('subtitle', 'List of all discount offers')
@section('content')
    <div class="ui one column doubling stackable">
        <div class="column">
            <div class="ui very padded segment table_header_row table-responsive" id="department_list">
                @include('laralum.discount_offer._list')
            </div>
        </div>
    </div>
@endsection


