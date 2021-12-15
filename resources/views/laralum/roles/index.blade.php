@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">{{ trans('laralum.roles_title') }}</div>
    </div>
@endsection
@section('title', trans('laralum.roles_title'))
@section('icon', "star")
@section('subtitle', trans('laralum.roles_subtitle'))
@section('content')
  <div class="ui one column doubling stackable grid">
  	<div class="column">
  		<div class="ui very padded segment">
            <div class="ui very padded segment table_header_row" id="department_list">
                @include('laralum.roles._index')
            </div>



  		</div>
        <br>
  	</div>
  </div>
@endsection
