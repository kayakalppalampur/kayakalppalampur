@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @if($row->isDoctor())
            <a class="section" href="{{ route('Laralum::doctors') }}">{{ trans('laralum.doctors_list') }}</a>
        @else
            <a class="section" href="{{ route('Laralum::users') }}">{{ trans('laralum.user_list') }}</a>
        @endif
        <i class="right angle icon divider"></i>
        <div class="active section">{{ $row->isDoctor() ? trans('laralum.doctors_edit_title')  : trans('laralum.users_edit_title') }}</div>
    </div>
@endsection
@section('title', $row->isDoctor() ? trans('laralum.doctors_edit_title')  : trans('laralum.users_edit_title') )
@section('icon', "edit")
@section('subtitle', $row->isDoctor() ? trans('laralum.doctors_edit_subtitle', ['email' => $row->email])  : trans('laralum.users_edit_subtitle', ['email' => $row->email]))
@section('content')
<div class="ui doubling stackable">
    <div class="about_sec hospital_info role_edt">
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

<script>
    $("#country_code").val('IN');
    $("#country_code_dropdown").parent().hide();

</script>
@endsection
