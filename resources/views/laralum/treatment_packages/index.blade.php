@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Treatment Package List</div>
    </div>
@endsection
@section('title', 'Treatment Packages')
@section('icon', "pencil")
@section('subtitle', 'List of all Treatment Packages')
@section('content')

  <br><br>
  <div class="column">
      <section class="booking_filter booking_search_patient ui padded segment">
          <div class="row">
              <div class="col-md-12">
                  <div class="about_sec white_bg signup_bg">
                      <div class="patient_head2">
                          <h3 class="title_3">SEARCH TREATMENT PACKAGE</h3>
                          <h4></h4>
                      </div>
                      <form id="bookingFilter" method="POST">
                          {{ csrf_field() }}
                          <div class="form-group">
                              <input class="user_namer form-control required" type="text" id="filter_package_name" value="{{ @$_REQUEST['filter_package_name'] }}" name="filter_package_name" placeholder="Package Name">
                          </div>
                          <div class="form-group">
                              <select name="filter_department_id" class="user_namer form-control required" >
                                  <option>All</option>
                                  @foreach(\App\Department::all() as $dept)

                                      <option {{ @$_REQUEST['filter_department_id'] == $dept->id ? "selected" : ""}} value="{{ $dept->id }}"> {{ $dept->title }}</option>
                                  @endforeach
                              </select>
                          </div>

                          <div class="form-button_row"><button class="ui button no-disable blue">Search</button></div>
                      </form>
                  </div>
              </div>
          </div>
      </section>
  </div>
 {{-- <div class="column">
      <div class="btn-group pull-right">
          <div class="item no-disable">
              <a style="color:white" href="{{ url("admin/lab-tests/print/") }}"> <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"> <i class="print icon"></i><span class="text responsive-text">Print</span></div></a>
              <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                  <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                  <div class="menu">
                      <a id="clicked" class="item no-disable" href="{{ url('/admin/lab-tests/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}" >Export as CSV
                      </a>
                      <a id="clicked" class="item no-disable" href="{{ url('/admin/lab-tests/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}" >Export as PDF
                      </a>
                      <a id="clicked" class="item no-disable" href="{{ url('/admin/lab-tests/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}" >Export as Excel
                      </a>
                  </div>
              </div>
          </div>
      </div>
  </div>--}}
  <div class="ui one column doubling stackable grid container">
      <div class="column">
          <div class="pull-left"><a href="{{ url("admin/treatment-packages/add") }}" class="btn btn-primary ui button blue">Create Treatment Package </a></div>

           <div class="ui very padded segment" id="treatment_packages_list">
            @include('laralum.treatment_packages._list')
          </div>
      </div>
  </div>
@endsection


