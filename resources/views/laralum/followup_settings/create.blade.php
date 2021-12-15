@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ url("admin/followup-settings") }}">{{ trans('laralum.followup_settings_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.followup_settings_create') }}</div>
    </div>
@endsection
@section('title', 'Add Followup Setting')
@section('icon', "plus")
@section('subtitle', 'Followup Setting'))
@section('content')
    <div class="ui one column doubling stackable grid container">
        <div class="column">
            <div class="ui very padded segment">
                <form method="POST" class="ui form" enctype="multipart/form-data" action="{{ url('admin/followup-settings') }}">
                    {{ csrf_field() }}
                    <div class="ui stackable grid">
                        <div class="three wide column"></div>
                        <div class="ten wide column">
                            <div class="field ">
                                <label class="" for="name">Period <span class="required">*</span>
                                </label>
                                <input name="period" type="text" value="{{ old('period') }}" required="required" class="">
                            </div>

                            <div class="field ">
                                <label class="" for="name">Period in (Minutes/Hours/Days) <span class="required">*</span>
                                </label>
                                <select name="period_type">
                                    @foreach(\App\FollowupSetting::getPeriodTypeOptions() as $id => $value)
                                    <option value="{{ $id }}">{{ $value }}</option>
                                        @endforeach
                                </select>
                            </div>

                            <div class="field ">
                                <label class="" for="name">Template  <span class="required">*</span>
                                </label>
                                <select name="template_id">
                                    @foreach(\App\EmailTemplate::getTemplates(\App\EmailTemplate::GROUP_FOLLOWUP) as $template)
                                        <option value="{{ $template->id }}">{{ $template->handle }}</option>
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
    </div>
@endsection