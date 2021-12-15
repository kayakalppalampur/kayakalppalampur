@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ url("admin/followup-settings") }}">{{ trans('laralum.followup_settings_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.followup_settings_edit_title') }}</div>
    </div>
@endsection
@section('title', trans('laralum.followup_settings_edit_title'))
@section('icon', "edit")
@section('subtitle', trans('laralum.followup_settings_edit_title_name'))
@section('content')
<div class="ui doubling stackable grid container">
    <div class="three wide column"></div>
    <div class="ten wide column">
        <div class="ui very padded segment">
            <form method="POST" class="ui form" enctype="multipart/form-data" action="{{ url('admin/followup-settings/'.$model->id) }}">
                {{ csrf_field() }}
                <input type="hidden" value="PUT" name="_method">
                <div class="ui stackable grid">
                    <div class="three wide column"></div>
                    <div class="ten wide column">
                        <div class="field ">
                            <label class="" for="name">Period <span class="required">*</span>
                            </label>
                            <input name="period" type="text" value="{{ old('period', $model->period) }}" required="required" class="">
                        </div>

                        <div class="field ">
                            <label class="" for="name">Period in (Minutes/Hours/Days) <span class="required">*</span>
                            </label>
                            <select name="period_type">
                                @foreach(\App\FollowupSetting::getPeriodTypeOptions() as $id => $value)
                                    <option value="{{ $id }}" {{ $model->period_type == $id ? "Selected" : "" }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field ">
                            <label class="" for="name">Template  <span class="required">*</span>
                            </label>
                            <select name="template_id">
                                @foreach(\App\EmailTemplate::all() as $template)
                                    <option value="{{ $template->id }}" {{ $model->template_id == $template->id ? "Selected" : "" }}>{{ $template->handle }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="three wide column"></div>
                </div>
                <br><br>
                <br>
                <div class="field">
                    <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
    <div class="three wide column"></div>
</div>
@endsection
