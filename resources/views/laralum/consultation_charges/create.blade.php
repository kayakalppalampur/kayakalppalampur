@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Set Consultation Charge</div>
    </div>
@endsection
@section('title', 'Set Consultation Charge')
@section('icon', "plus")
@section('content')
    <div class="ui one column doubling stackable grid">
        <div class="column">
            <div class="about_sec ui very padded segment">
                <form method="POST" class="form consult_form" action="{{ route('Laralum::admin.consultation_charges.store') }}">
                    {{ csrf_field() }}
                    <div class="ui stackable">
                        <div class="wide column">
                            <div class="form-group1">
                                <label class="" for="charges">Consultation charge <span class="required">*</span></label>
                                <input name="charges" type="text" value="{{ old('charges', $model->charges) }}" required="required" class="form-control">
                            </div>
                        </div>
                    </div>

                    {{--<div class="form-group">
                        <div class="col-2"><label> Select Department</label></div>
                        <div class="col-10">
                            <select name="department_id" id="department_id" class="form-control" required>
                                <option value="">Select i.e. Ayurveda / Naturopathy</option>
                                @foreach(\App\Department::all() as $department)
                                    <option value="{{ $department->id }}">{{ $department->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>--}}
                    <div class="form-group btn_signup_con">
                        <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection