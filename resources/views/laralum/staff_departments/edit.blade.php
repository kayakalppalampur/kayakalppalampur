@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::admin.staff_departments') }}">Staff Departments</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Edit Staff Department</div>
    </div>
@endsection
@section('title','Edit Staff Department')
@section('icon', "edit")
@section('subtitle', 'Edit Staff Department: '.$model->title)
@section('content')

    <link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">
<div class="ui doubling stackable">
    <div class="column ui padded segment">
        <div class="about_sec hospital_info role_edt">
            <form class="form form_cond_lft" method="POST">
                {{ csrf_field() }}
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

                <div  class="form-button_row">
                     <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@section('js')
    <script>
        $( ".datepicker" ).datepicker({dateFormat: "dd-mm-yy", autoclose:true, minDate:0,})
    </script>
@endsection