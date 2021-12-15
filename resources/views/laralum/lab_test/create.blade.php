@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::lab-tests') }}">{{ trans('laralum.lab_tests_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.lab_tests_create') }}</div>
    </div>
@endsection
@section('title', 'Add Lab Test')
@section('icon', "plus")
@section('subtitle', 'Lab Test')
@section('content')
    <div class="ui one column doubling stackable">
        <div class="ui very padded segment">
            <div class="column about_sec hospital_info role_edt">
                <form method="POST" class="form form form_cond_lft">
                    {{ csrf_field() }}
                    @include('laralum.forms.master')
                    <div class="form-button_row">
                        <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection