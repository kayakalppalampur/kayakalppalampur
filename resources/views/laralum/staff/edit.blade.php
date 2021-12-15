@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::staff') }}">Staff</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Edit Staff</div>
    </div>
@endsection
@section('title','Edit Staff')
@section('icon', "edit")
@section('subtitle', 'Edit Staff: '.$model->name)
@section('content')
<link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">

    <div class="ui one column doubling stackable">
        <div class="ui very padded segment">
            <div class="column about_sec hospital_info role_edt">
                <form class="form form form_cond_lft" method="POST">
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


@section('js')
    <script>
        $( ".datepicker" ).datepicker({dateFormat: "dd-mm-yy", autoclose:true, minDate:0,})
    </script>
@endsection