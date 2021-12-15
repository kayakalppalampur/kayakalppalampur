@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::admin.staff_departments') }}">Staff Departments</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Create Staff Department</div>
    </div>
@endsection
@section('title', 'Add Staff Department')
@section('icon', "plus")
@section('subtitle', 'Staff Department')
@section('content')
    <div class="ui one column doubling stackable">
        <div class="column ui padded segment">
            <div class="about_sec hospital_info role_edt">
                <form method="POST" class="form form_cond_lft">
                    {{ csrf_field() }}
                    <div class="ui stackable">
                        <div class="field ">
                            <label class="" for="title">Title<span class="required">*</span>
                            </label>
                            <input name="title" type="text" value="{{ old('title', $model->title) }}" required="required" class="form-control">
                        </div>
                        <div class="field ">
                            <label class="" for="description">Description<span class="required">*</span>
                            </label>
                            <textarea required="required"  class="form-control" name="description" type="text" >{{ old('description', $model->description) }}</textarea>
                        </div>
                    </div>
                    <div class="form-button_row">
                        <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
