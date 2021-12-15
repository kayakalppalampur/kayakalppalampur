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
    {{-- <div class="ui doubling stackable two column grid container">
     <div class="column">
           <div class="ui padded segment">
               {!! Laralum::widget('basic_stats_1') !!}
           </div>
           <br>
           <div class="ui padded segment">
               {!! Laralum::widget('latest_users_graph') !!}
           </div>
           <br>
           <div class="ui padded segment">
               {!! Laralum::widget('latest_posts_graph') !!}
           </div>
           <br>
       </div>
       <div class="column">
           <div class="ui padded segment">
               {!! Laralum::widget('basic_stats_2') !!}
           </div>
           <br>
           <div class="ui padded segment">
               {!! Laralum::widget('users_country_geo_graph') !!}
           </div>
           <br>
           <div class="ui padded segment">
               {!! Laralum::widget('roles_users') !!}
           </div>
           <br>
       </div>
   </div>
   --}}

    <div class="ui doubling stackable one column">
        <div class="column_row">
            @if(Laralum::loggedInUser()->hasPermission('admin.room_types'))
              <div class="column_list">
                  <div class="dashboard-block">
                      <div class='stackable one column'>
                          <a href="{{ url("admin/accommodation/buildings") }}">
                              <div class="icon_dashboard iconn1"></div>
                              <div class='ui statistic'>
                                  <h2>Accommodation</h2>
                                  <p>(Buildings/Rooms/Room Types/Services)</p>
                              </div>
                          </a>
                      </div>
                  </div>
              </div>
            @endif
            @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.treatments'))
              <div class="column_list">
                  <div class="dashboard-block">
                      <div class='stackable one column'>
                          <a href="{{ url("admin/treatments") }}">
                              <div class="icon_dashboard iconn2"></div>
                              <div class='ui statistic'>
                                  <h2>Treatments</h2>
                                  <p>(Treatments List to be shown on doctor's panel)</p>
                              </div>
                          </a>
                      </div>
                  </div>
              </div>
            @endif
            @if(Laralum::loggedInUser()->hasPermission('admin.kitchen_items'))
              <div class="column_list">
                  <div class="dashboard-block">
                      <div class='stackable one column'>
                          <a href="{{ url("admin/kitchen-items") }}">
                              <div class="icon_dashboard iconn3"></div>
                              <div class='ui statistic'>
                                  <h2>Kitchen Items</h2>
                                  <p>(Kitchen Menu Items List)</p>
                              </div>
                          </a>
                      </div>
                  </div>
              </div>
            @endif
            @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.feedback_questions'))
              <div class="column_list">
                  <div class="dashboard-block">
                      <div class='stackable one column'>
                          <a href="{{ url("admin/feedback-questions") }}">
                              <div class="icon_dashboard iconn4"></div>
                              <div class='ui statistic'>
                                  <h2>Feedback Questions</h2>
                                  <p>(Feedback questions to be asked from users)</p>
                              </div>
                          </a>
                      </div>
                  </div>
              </div>
            @endif
            @if(Laralum::loggedInUser()->hasPermission('admin.attendance.list'))
              <div class="column_list">
                  <div class="dashboard-block">
                      <div class='stackable one column'>
                          <a href="{{ url("admin/attendances") }}">
                              <div class="icon_dashboard iconn5"></div>
                              <div class='ui statistic'>
                                  <h2>Staff Attendance</h2>
                                  <p>(Manage staff attendance/Mark Attendance)</p>
                              </div>
                          </a>
                      </div>
                  </div>
              </div>
            @endif
            <div class="column_list">
                <div class="dashboard-block">
                    <div class='stackable one column'>
                        <a href="{{ url("admin/issues") }}">
                            <div class="icon_dashboard iconn6"></div>
                            <div class='ui statistic'>
                                <h2>Issues/Queries</h2>
                                <p>(Support issues reported by users)</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="column_list">
                <div class="dashboard-block">
                    <div class='stackable one column'>
                        <a href="{{ url("admin/daily-situation-report") }}">
                            <div class="icon_dashboard iconn6"></div>
                            <div class='ui statistic'>
                                <div class='ui statistic'>
                                    <h2>Daily Situation Report</h2>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="column_list">
                <div class="dashboard-block">
                    <div class='stackable one column'>
                        <a href="{{ url("admin/daily-building-status") }}">
                            <div class="icon_dashboard iconn6"></div>
                            <div class='ui statistic'>
                                <h2>Daily Building Status</h2>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
