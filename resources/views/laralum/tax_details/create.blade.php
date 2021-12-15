@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::admin.tax_details') }}">Tax Details</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Create Tax Type</div>
    </div>
@endsection
@section('title', 'Add Tax Detail')
@section('icon', "plus")
@section('subtitle', 'Tax Detail')
@section('content')
    <link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">
    <div class="ui one column doubling stackable">
        <div class="ui very padded segment">
            <div class="column about_sec hospital_info role_edt">
                <form method="POST" class="form form form_cond_lft">
                    {{ csrf_field() }}
                    <div class="field ">
                        <label class="" for="tax_type">Tax Type<span class="required">*</span>
                        </label>
                        <input name="tax_type" type="text" value="{{ old('tax_type', $model->tax_type) }}" required="required" class="form-control">
                    </div>

                    <div class="field ">
                        <label class="" for="tax_amount">Tax Amount (%)<span class="required">*</span>
                        </label>
                        <input name="tax_amount" type="text" value="{{ old('tax_amount', $model->tax_amount) }}" required="required" class="form-control">
                    </div>
                    <div class="field ">
                        <label class="" for="date">Date<span class="required">*</span>
                        </label>
                        <input name="date" type="text" value="{{ old('date', $model->date) }}" required="required" class="datepicker form-control">
                    </div>

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