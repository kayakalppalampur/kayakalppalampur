@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Followup Setting List</div>
    </div>
@endsection
@section('title', 'Followup Settings')
@section('icon', "pencil")
@section('subtitle', 'List of all followup settings')
@section('content')

  <br><br>
  <div class="ui one column doubling stackable grid container">
      <div class="column">
          <div class="pull-left"><a href="{{ url("admin/followup-settings/create") }}" class="btn btn-primary ui button blue">Create Followup Setting </a></div>

           <div class="ui very padded segment" id="followup_setting_list">
            @include('laralum.followup_settings._list')
          </div>
      </div>
  </div>
@endsection


