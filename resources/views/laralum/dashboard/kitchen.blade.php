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
                        <a href="{{ url("admin/diet-chart") }}">
                            <div class="icon_dashboard iconn7"></div>
                            <div class='ui statistic'>
                               <h2>Diet Of The Patient</h2>
                            </div>
                        </a>
                    </div>
                </div>
                <br>
            </div>

            <div class="column_list">
                <div class="dashboard-block">
                    <div class='stackable one colum'>
                        <a href="{{ url("admin/kitchen-item/requirements") }}">
                            <div class="icon_dashboard iconn8"></div>
                            <div class='ui statistic'>
                                <h2>Diet Suggestions For Tommorrow</h2>
                            </div>
                        </a>
                    </div>
                </div>
                <br>
            </div>


            <div class="column_list">
                <div class="dashboard-block">
                    <div class='stackable one colum'>
                        <a href="{{ url("admin/meal-status") }}">
                            <div class="icon_dashboard iconn9"></div>
                            <div class='ui statistic'>
                                <h2>Status of the meal</h2>
                            </div>
                        </a>
                    </div>
                </div>
                <br>
            </div>

            <div class="column_list">
                <div class="dashboard-block">
                    <div class='stackable one colum'>
                        <a href="{{ url("admin/meal-servings") }}">
                            <div class="icon_dashboard iconn10"></div>
                            <div class='ui statistic'>
                                <h2>Export/Print Meal Serving List</h2>
                            </div>
                        </a>
                    </div>
                </div>
                <br>
            </div>
        </div>



        {{--<div class="column"  style="width:28% !important;">
            <div class="ui doubling stackable three column grid container">
                <div class="ui padded segment "style="min-height:unset">
                    <div class='ui doubling stackable one column grid container'>
                        <div class='column'>
                            <center>  <h3>Alerts</h3> </center>
                        </div>

                    </div>
                    <br/>
                </div>
            </div>
        </div>--}}


    </div>
@endsection
