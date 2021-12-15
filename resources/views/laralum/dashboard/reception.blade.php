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
    <div class="ui doubling stackable one column grid container custom_block">
        <div class="column col_1">
            <div class="ui doubling stackable three column grid container">
                <div class="column">
                    <div class="ui padded segment dashboard-block">
                        <div class='ui doubling stackable one column grid container'>
                            <div class='column'>
                                <center>
                                    <a href="{{ url("admin/booking/registration/signup") }}">
                                        <div class='ui statistic'>
                                            <p><b>NEW REGISTRATION</b></p>
                                            <p>(Fresh Entry)</p>
                                        </div>
                                    </a>
                                </center>
                            </div>

                        </div>
                    </div>
                    <br>
                </div>
                <div class="column">
                    <div class="ui padded segment dashboard-block">
                        <div class='ui doubling stackable one column grid container'>
                            <div class='column'>
                                <center>
                                    <a href="{{ url("admin/bookings") }}">
                                        <div class='ui statistic'>
                                            <p><b>ARRIVAL</b></p>
                                            <p>(Revisit or Online Booking)</p>
                                        </div>
                                    </a>
                                </center>
                            </div>

                        </div>
                    </div>
                    <br>
                </div>
                <div class="column">
                    <div class="ui padded segment dashboard-block">
                        <div class='ui doubling stackable one column grid container'>
                            <div class='column'>
                                <center>
                                    <a href="{{ url("admin/booking/generate-patient-card") }}">
                                        <div class='ui statistic'>
                                            <p><b> K-ID CARD</b></p>
                                            <p>(Generate & Print)</p>
                                        </div>
                                    </a>
                                </center>
                            </div>

                        </div>
                    </div>
                    <br>
                </div>
                <div class="column">
                    <div class="ui padded segment dashboard-block">
                        <div class='ui doubling stackable one column grid container'>
                            <div class='column'>
                                <center>
                                    <a href="{{ url("admin/token-list") }}">
                                        <div class='ui statistic'>
                                            <p><b>Generate Token</b></p>
                                            <p>(To visit / revisit Doctor)</p>
                                        </div>
                                    </a>
                                </center>
                            </div>

                        </div>
                    </div>
                    <br>
                </div>
                <div class="column">
                    <div class="ui padded segment dashboard-block">
                        <div class='ui doubling stackable one column grid container'>
                            <div class='column'>
                                <center>
                                    <a href="{{ url("admin/booking/discharge-patient-billing") }}">
                                        <div class='ui statistic'>
                                            <p><b>DISCHARGE SUMMARY</b></p>
                                            <p>(Last Day Process)</p>
                                        </div>
                                    </a>
                                </center>
                            </div>

                        </div>
                    </div>
                    <br>
                </div>

                {{--  <div class="column">
                    <div class="ui padded segment dashboard-block">
                        <div class='ui doubling stackable one column grid container'>
                            <div class='column'>
                                <center>
                                    <a href="{{url("admin/treatment/tokens") }}">
                                        <div class='ui statistic'>
                                            <p><b>Approve Treatments</b></p>
                                            <p>(Approve/Disapprove)</p>
                                        </div>
                                    </a>
                                </center>
                            </div>

                        </div>
                    </div>
                    <br>
                </div>
                --}}
                <div class="column">
                    <div class="ui padded segment dashboard-block">
                        <div class='ui doubling stackable one column grid container'>
                            <div class='column'>
                                <center>
                                    <a href="{{ url("admin/bookings/follow-ups") }}">
                                        <div class='ui statistic'>
                                            <p><b>FOLLOW UPS</b></p>
                                            <p>(To reminder call to revisit)</p>
                                        </div>
                                    </a>
                                </center>
                            </div>

                        </div>
                    </div>
                    <br>
                </div>
                <div class="column">
                    <div class="ui padded segment dashboard-block">
                        <div class='ui doubling stackable one column grid container'>
                            <div class='column'>
                                <center>
                                    <a href="{{ url("admin/accommodation/room-status") }}">
                                        <div class='ui statistic'>
                                            <p><b>Accommodation Status</b></p>
                                            <p>(Rooms status Month wise/Room wise)</p>
                                        </div>
                                    </a>
                                </center>
                            </div>

                        </div>
                    </div>
                    <br>
                </div>

                <div class="column">
                    <div class="ui padded segment dashboard-block">
                        <div class='ui doubling stackable one column grid container'>
                            <div class='column'>
                                <center>
                                    <a href="{{ url("admin/daily-building-status") }}">
                                        <div class='ui statistic'>
                                            <p><b>Daily Building Status</b></p>
                                        </div>
                                    </a>
                                </center>
                            </div>

                        </div>
                    </div>
                    <br>
                </div>

                <div class="column">
                    <div class="ui padded segment dashboard-block">
                        <div class='ui doubling stackable one column grid container'>
                            <div class='column'>
                                <center>
                                    <a href="{{ url("admin/daily-situation-report") }}">
                                        <div class='ui statistic'>
                                            <p><b>Daily Situation Report</b></p>
                                        </div>
                                    </a>
                                </center>
                            </div>

                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
        <div class="column col_2">
            <div class="ui doubling stackable three column grid container last_segment">
                <div class="ui padded segment">
                    <div class='ui doubling stackable one column grid container'>
                        <div class='column'>
                            <center><h3>Alerts</h3></center>
                            <center>
                                <a href="{{ url("admin/bookings") }}">
                                    <div class='ui statistic grey-btn btn'>
                                        New Confirm
                                        Bookings<span>{{ \App\Notification::getNotificationCount(get_class($user)) }}</span>
                                    </div>
                                </a>
                            </center>
                            <center>
                                <a href="{{ url("admin/queries") }}">
                                    <div class='ui statistic grey-btn btn'>
                                        New Online
                                        Queries<span>{{ \App\Notification::getNotificationCount(\App\Issue::class) }}</span>
                                    </div>
                                </a>
                            </center>
                            <center>
                                <a href="{{ url("admin/patient-list") }}">
                                    <div class='ui statistic grey-btn btn'>
                                        Search Patient
                                    </div>
                                </a>
                            </center>
                        </div>

                    </div>
                    <br/>
                </div>

            </div>
        </div>
    </div>
@endsection
