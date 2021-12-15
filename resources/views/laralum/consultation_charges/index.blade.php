@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Consultation Charges List</div>
    </div>
@endsection
@section('title', 'Consultation Chargess')
@section('icon', "pencil")
@section('subtitle', 'List of all Consultation Chargess')
@section('content')

 {{-- <br><br>
  <div class="column">
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
          @if(\App\ConsultationCharge::count() == 0)
           <div class="pull-left"><a href="{{ url("admin/consultation-charges/add") }}" class="btn btn-primary ui button blue">Create Consultation Charges </a></div>
          @endif

           <div class="ui very padded segment" id="lab_test_list">
               @include('laralum.consultation_charges._list')
          </div>
      </div>
  </div>
@endsection


