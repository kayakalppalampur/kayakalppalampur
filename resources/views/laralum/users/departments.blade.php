@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::doctors') }}">{{ trans('laralum.doctors_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{ trans('laralum.users_edit_department_title') }}</div>
    </div>
@endsection
@section('title', trans('laralum.users_edit_department_title'))
@section('icon', "star")
@section('subtitle', trans('laralum.users_edit_department_subtitle', ['email' => $user->email]))
@section('content')
<div class="about_sec hospital_info role_edt">
    <div class="eight wide column">
        <div class="ui very padded segment">
            <form method="POST" class="form">
                {{ csrf_field() }}
                @include('laralum.forms.departments')

                <div class="form-button_row">
                    <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
