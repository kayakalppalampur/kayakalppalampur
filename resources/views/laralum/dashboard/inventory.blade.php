@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">{{ trans('laralum.dashboard') }}</div>
    </div>
@endsection
@section('title', trans('laralum.dashboard'))
@section('icon', "dashboard")
@section('subtitle')
    {{ trans('laralum.welcome_user', ['name' => Laralum::loggedInUser()->name]) }}
@endsection
@section('content')
    <div class="ui doubling stackable one column">

        <div class="column_row">

            <div class="column_list">
                <div class="dashboard-block">
                    <div class='stackable one colum'>
                        <a href="{{ url("admin/stock") }}">
                            <div class="icon_dashboard iconn7"></div>
                            <div class='ui statistic'>
                                <h2>Manage Stock</h2>
                            </div>
                        </a>
                    </div>
                </div>
                <br>
            </div>
            <div class="column_list">
                <div class="dashboard-block">
                    <div class='stackable one colum'>
                        <a href="{{ url("admin/stock/requests") }}">
                            <div class="icon_dashboard iconn7"></div>
                            <div class='ui statistic'>
                                <h2>Item Requests</h2>
                            </div>
                        </a>
                    </div>
                </div>
                <br>
            </div>

            {{--<div class="column_list">
                <div class="dashboard-block">
                    <div class='stackable one colum'>
                        <a href="{{ url("admin/stock/requests") }}">
                            <div class="icon_dashboard iconn7"></div>
                            <div class='ui statistic'>
                                <h2>Item Requests</h2>
                            </div>
                        </a>
                    </div>
                </div>
                <br>
            </div>--}}
        </div>
    </div>
@endsection
