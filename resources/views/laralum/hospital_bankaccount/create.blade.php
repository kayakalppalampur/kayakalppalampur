@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::admin.hospital_bank_account') }}">Bank Account List</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Create Bank Account</div>
    </div>
@endsection
@section('title', 'Add Bank Account')
@section('icon', "plus")
@section('subtitle', 'Bank Account')
@section('content')

    <link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">
    <div class="ui one column doubling stackable">
        <div class="ui very padded segment">
            <div class="column about_sec hospital_info role_edt">
                <form method="POST" class="form form form_cond_lft">
                    {{ csrf_field() }}
                    <div class="field ">
                        <label class="" for="bank_name">Bank Name<span class="required">*</span> </label>
                        <input name="bank_name" type="text" value="{{ old('bank_name', $model->bank_name) }}" required="required" class="form-control">
                    </div>

                    <div class="field ">
                        <label class="" for="account_no">Account No<span class="required">*</span> </label>
                        <input name="account_no" type="text" value="{{ old('account_no', $model->account_no) }}" required="required" class="form-control">
                    </div>

                    <div class="field ">
                        <label class="" for="date">Date<span class="required">*</span> </label>
                        <input name="date" type="text" value="{{ old('date', $model->date) }}" required="required" class="datepicker form-control">
                    </div>

                    <div class="field ">
                        <label class="" for="opening_balance">Opening Balance<span class="required">*</span> </label>
                        <input name="opening_balance" type="text" value="{{ old('opening_balance', $model->opening_balance) }}" required="required" class="form-control">
                    </div>

                    <div class="field ">
                        <label class="" for="account_type">Account Type<span class="required">*</span> </label>
                        <select name="account_type" class="form-control" required>
                            <option>All</option>
                        @foreach(\App\HospitalBankaccount::getTypeOptions() as $key => $val)
                            <option value="{{ $key }}"> {{ $val }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field ">
                        <label class="" for="branch">Branch<span class="required">*</span> </label>
                        <input name="branch" type="text" value="{{ old('branch', $model->branch) }}" required="required" class="form-control">
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