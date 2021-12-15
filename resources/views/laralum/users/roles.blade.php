@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::users') }}">{{ trans('laralum.user_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{ trans('laralum.users_edit_roles_title') }}</div>
    </div>
@endsection
@section('title', trans('laralum.users_edit_roles_title'))
@section('icon', "star")
@section('subtitle', trans('laralum.users_edit_roles_subtitle', ['email' => $user->email]))
@section('content')
<div class="ui doubling stackable">
    <div class="about_sec hospital_info role_edt">
        <div class="ui very padded segment">
            <form method="POST" class="form">
                {{ csrf_field() }}
                @include('laralum.forms.roles')
                <div class="form-button_row">
                    <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
