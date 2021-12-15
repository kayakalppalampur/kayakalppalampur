@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::departments') }}">{{ trans('laralum.department_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.department_create') }}</div>
    </div>
@endsection
@section('title', 'Add Department')
@section('icon', "plus")
@section('subtitle', 'Department')
@section('content')
    <div class="ui one column doubling stackable">
        <div class="about_sec hospital_info role_edt">
            <div class="ui very padded segment">
                <form method="POST" class="form form form_cond_lft">
                    {{ csrf_field() }}
                    <div class="ui stackable">
                            @include('laralum.forms.master')
                    </div>
                    <div class="form-button_row">
                        <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection