@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::departments') }}">{{ trans('laralum.department_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{ trans('laralum.departments_edit_title') }}</div>
    </div>
@endsection
@section('title', trans('laralum.departments_edit_title'))
@section('icon', "edit")
@section('subtitle', trans('laralum.departments_edit_title_name', ['name' => $row->title]))
@section('content')
<div class="column about_sec hospital_info role_edt">
    <div class="column">
        <div class="ui very padded segment">
            <form class="form form form_cond_lft" method="POST">
                {{ csrf_field() }}
                @include('laralum/forms/master')
                <div class="form-button_row">
                      <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
