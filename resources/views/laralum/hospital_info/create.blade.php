@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Hospital Info</div>
    </div>
@endsection
@section('title', 'Hospital Info')
@section('icon', "plus")
@section('content')
    <div class="ui one column doubling stackable">
        <div class="column">
            <div class="ui very padded segment about_sec hospital_info">
                <form method="POST" class="form">
                    {{ csrf_field() }}
                    <div class="ui stackable">
                        <div class="column">

                            <div class="hs_info_main">
                                <div class="form_group_inn">
                                    <label class="" for="charges">Hospital Name <span class="required">*</span>
                                    </label>
                                    <input name="hospital_name" type="text" value="{{ old('hospital_name', $model->hospital_name) }}" required="required" class="form-control">
                                </div>

                                <div class="form_group_inn">
                                    <label class="" for="email">Email<span class="required">*</span> </label>
                                    <input name="email" type="email" value="{{ old('email', $model->email) }}" required="required" class="form-control">
                                </div>

                            </div>

                            <div class="hs_info_main">
                                <div class="form_group_inn">
                                    <label class="" for="city">City<span class="required">*</span> </label>
                                    <input name="city" type="text" value="{{ old('city', $model->city) }}" required="required" class="form-control">
                                </div>


                                <div class="form_group_inn">
                                    <label class="" for="state">State <span class="required">*</span> </label>
                                    <input name="state" type="text" value="{{ old('state', $model->state) }}" required="required" class="form-control">
                                </div>
                            </div>

                            <div class="hs_info_main">
                                <div class="form_group_inn">
                                    <label class="" for="pincode">Pincode<span class="required">*</span> </label>
                                    <input name="pincode" type="text" value="{{ old('pincode', $model->pincode) }}" required="required" class="form-control">
                                </div>

                                <div class="form_group_inn">
                                    <label class="" for="phone_no">Phone no<span class="required">*</span> </label>
                                    <input name="phone_no" type="text" value="{{ old('phone_no', $model->phone_no) }}" required="required" class="form-control">
                                </div>
                            </div>

                            <div class="hs_info_main">
                                <div class="form_group_inn">
                                    <label class="" for="mobile_no">Mobile no<span class="required">*</span> </label>
                                    <input name="mobile_no" type="text" value="{{ old('mobile_no', $model->mobile_no) }}" required="required" class="form-control">
                                </div>
                                <div class="form_group_inn">
                                    <label class="" for="fax">Fax<span class="required">*</span> </label>
                                    <input name="fax" type="text" value="{{ old('fax', $model->fax) }}" required="required" class="form-control">
                                </div>
                            </div>

                            <div class="hs_info_main">

                                <div class="form_group_inn">
                                    <label class="" for="website">website<span class="required">*</span> </label>
                                    <input name="website" type="text" value="{{ old('website', $model->website) }}" required="required" class="form-control">
                                </div>

                                <div class="form_group_inn">
                                    <label class="" for="address">Address <span class="required">*</span> </label>
                                    <textarea class="form-control" required="required"  name="address" type="text">{{ old('address', $model->address) }}</textarea>
                                </div>

                            </div>

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