@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::admin.hospital_bank_account') }}">Bank Acounts</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Edit Bank Account</div>
    </div>
@endsection
@section('title', 'Edit Bank Account')
@section('icon', "edit")
@section('subtitle','Edit Bank Account: '.$model->bank_name)
@section('content')
<div class="ui doubling stackable grid container">
    <div class="three wide column"></div>
    <div class="ten wide column">
        <div class="ui very padded segment">
            <form class="ui form" method="POST">
                {{ csrf_field() }}
                <div class="field ">
                    <label class="" for="charges">Hospital Name <span class="required">*</span>
                    </label>
                    <input name="hospital_name" type="text" value="{{ old('hospital_name', $model->hospital_name) }}" required="required" class="">
                </div>

                <div class="field ">
                    <label class="" for="address">Address <span class="required">*</span>
                    </label>
                    <textarea required="required"  name="address" type="text">{{ old('address', $model->address) }}</textarea>
                </div>

                <div class="field ">
                    <label class="" for="city">City<span class="required">*</span>
                    </label>
                    <input name="city" type="text" value="{{ old('city', $model->city) }}" required="required" class="">
                </div>


                <div class="field ">
                    <label class="" for="state">State <span class="required">*</span>
                    </label>
                    <input name="state" type="text" value="{{ old('state', $model->state) }}" required="required" class="">
                </div>


                <div class="field ">
                    <label class="" for="pincode">Pincode<span class="required">*</span>
                    </label>
                    <input name="pincode" type="text" value="{{ old('pincode', $model->pincode) }}" required="required" class="">
                </div>

                <div class="field ">
                    <label class="" for="phone_no">Phone no<span class="required">*</span>
                    </label>
                    <input name="phone_no" type="text" value="{{ old('phone_no', $model->phone_no) }}" required="required" class="">
                </div>
                <div class="field ">
                    <label class="" for="mobile_no">Mobile no<span class="required">*</span>
                    </label>
                    <input name="mobile_no" type="text" value="{{ old('mobile_no', $model->mobile_no) }}" required="required" class="">
                </div>
                <div class="field ">
                    <label class="" for="fax">Fax<span class="required">*</span>
                    </label>
                    <input name="fax" type="text" value="{{ old('fax', $model->fax) }}" required="required" class="">
                </div>
                <div class="field ">
                    <label class="" for="email">Email<span class="required">*</span>
                    </label>
                    <input name="email" type="email" value="{{ old('email', $model->email) }}" required="required" class="">
                </div>
                <div class="field ">
                    <label class="" for="website">website<span class="required">*</span>
                    </label>
                    <input name="website" type="text" value="{{ old('website', $model->website) }}" required="required" class="">
                </div>
                <br>
                <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
            </form>
        </div>
    </div>
    <div class="three wide column"></div>
</div>
@endsection
