@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">{{ trans('laralum.patients_list') }}</div>
    </div>
@endsection
@section('title',  trans('laralum.patients'))
@section('icon', "pencil")
@section('subtitle', 'List of all Patients')
@section('content')

  <br><br>
  <div class="ui one column doubling stackable grid container">
      <div class="column">
          <section class="booking_filter booking_search_patient ui padded segment">
              <div class="row">
                  <div class="col-md-12">
                      <div class="about_sec white_bg signup_bg">
                          <div class="patient_head2">
                              <h3 class="title_3">SEARCH PATIENT</h3>
                              <h4>(Through Anyone Option)</h4>
                          </div>
                          <form id="bookingFilter" action="{{ route('Laralum::patient-history') }}" method="POST">
                              {{ csrf_field() }}
                              <div class="form-group">
                                  <input class="user_namer form-control required" type="text" id="filter_first_name" value="{{ @$_REQUEST['filter_patient_id'] }}" name="filter_patient_id" placeholder="Patient Id" autofocus >
                              </div>
                              <div class="form-group">
                                  <input class="user_namer form-control required" type="text" id="filter_first_name" value="{{ @$_REQUEST['filter_first_name'] }}" name="filter_first_name" placeholder="First Name" autofocus>
                              </div>
                              <div class="form-group">
                                  <input class="user_last form-control required" type="text" id="filter_last_name" value="{{ @$_REQUEST['filter_last_name'] }}" name="filter_last_name" placeholder="Last Name">
                              </div>
                              <div class="form-group">
                                  <input class="user_email form-control required" type="email" id="filter_email" value="{{ @$_REQUEST['filter_email'] }}" name="filter_email" placeholder="Email Id">
                              </div>
                              <div class="form-group">
                                  <input class="user_password form-control required" type="text" name="filter_mobile" value="{{ @$_REQUEST['filter_mobile'] }}" id="filter_mobile" placeholder="Mobile Number">
                              </div>
                              <div class="form-button_row"><button class="ui button no-disable blue">Search</button></div>
                          </form>
                      </div>
                  </div>
              </div>
          </section>
      </div>
      <div class="column">
           <div class="ui very padded segment" id="patient_list">
            @include('laralum.patient._list')
          </div>
      </div>
  </div>
@endsection


