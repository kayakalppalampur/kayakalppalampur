@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @if($user_type == \App\User::USER_TYPE_DOCTORS )
            <a class="section" href="{{ route('Laralum::doctors') }}">{{ trans('laralum.doctors_list') }}</a>
        @else
            <a class="section" href="{{ route('Laralum::users') }}">{{ trans('laralum.user_list') }}</a>
        @endif
        <i class="right angle icon divider"></i>
        <div class="active section">{{ $user_type == \App\User::USER_TYPE_DOCTORS ? "Create Doctor" : trans('laralum.users_create_title') }}</div>
    </div>
@endsection
@section('title', $user_type == \App\User::USER_TYPE_DOCTORS ? "Create Doctor" : trans('laralum.users_create_title'))
@section('icon', "plus")
@section('content')
<div class="about_sec hospital_info role_edt">
    <form class="form" method="POST">
        <input type="hidden" name="user_type" value="{{ $user_type }}" />
        <div class="ui doubling stackable grid">
            <div class="row">
                <div class="eight wide column">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}
                        @include('laralum/forms/master')
                    </div>
                </div>
                <div class="eight wide column">
                        <div class="ui very padded segment">
                            @include('laralum.forms.roles')
                        </div>
                   
                    <div class="ui very padded segment radio_con" id="department_list" @if($user_type != 2)style="display: none;" @endif >
                        @include('laralum.forms.departments')
                    </div>
                    <div class="ui very padded segment">
                        <div class="inline field">
                            <div class="ui slider checkbox">
                                <input type="checkbox" id="active" name="active" tabindex="0" class="hidden" value="{{ old('active') }}">
                                <label>{{ trans('laralum.users_activate_user') }}</label>
                            </div>
                        </div>
                        <div class="inline field">
                            <div class="ui slider checkbox">
                                <input type="checkbox" id="send_activation" name="send_activation" tabindex="0" class="hidden">
                                <label>{{ trans('laralum.users_send_activation') }}</label>
                            </div>
                        </div>
                        <div class="inline field">
                            <div class="ui slider checkbox">
                                <input type="checkbox" id="mail_checkbox" name="mail" tabindex="0" class="hidden">
                                <label>{{ trans('laralum.users_welcome_email') }}</label>
                            </div>
                        </div>

                        <div class="form-button_row">
                             <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $("#country_code").val('IN');
    $("#country_code_dropdown").parent().hide();
    $('#active').change(function(){
        if(this.checked) {
            $('#send_activation').prop('checked', false);
        }
    });
    $('#send_activation').change(function(){
        if(this.checked) {
            $('#active').prop('checked', false);
        }
    });
    $( "label:contains('Doctor')" ).parent().find('input[type=checkbox]').change(function(){
        if ($(this).is(":checked")) {
            $("#department_list").show();
        }else{
            $("#department_list").hide();
        }
    })
    @if($role != "")
    $("[name={{ $role }}]").prop('checked', true).trigger('change');
    $("[name={{ $role }}]").parent().parent().parent().hide();
    @endif
</script>
@endsection
