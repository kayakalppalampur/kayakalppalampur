@extends('layouts.user.auth_layout')
@section('content')
    <div class="main_content_area">
        <div class="preview_title">
            <h2>Booking Details</h2>
        </div>
        <div class="pro_main_content">
            <div class="row">
                <div class="col-md-12">
                    <div class="about_sec white_bg signup_bg">
                        {{--<h3 class="title_3">Booking Process</h3>--}}
                        <div class="pull-right"><a href="{{ url("user/personal_details/".$user->id) }}" class="btn btn-primary">Edit Booking Details</a></div>
                        <div class="form_preview-con">
                            <div id="signup_wizard">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <h4>Please check the errors below:</h4>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
                @if (session('status') == 'success')
                    <div class="alert alert-success">
                       {!! session('message') !!}
                    </div>
                @endif
            {!! Form::open(array('route' => 'guest.booking.personalDetails', 'id' => 'bookingProcessForm','files'=>true,'method'=>'post')) !!}
                {{--<form id="bookingProcessForm" action="{{ route('guest.booking') }}" method="post">--}}
                {{ csrf_field() }}
                <section>
                    <div class="pro_main_content">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="about_sec white_bg signup_bg">
                                    <?php
                                    $countries = Laralum::countries();
                                    ?>
                                    <?php $no_flags = Laralum::noFlags() ?>
                                    <h3 class="title_3">Booking Process</h3>
                                    <div class="form-group">
                                        <label>Patient Type*</label>
                                        <select class="form-control" required id="patient_type" name="userProfile[patient_type]" id="patient_type" autofocus>
                                            <option value="">Patient Type</option>
                                            @foreach(\App\UserProfile::getPatientType() as $key => $patient_type)
                                                <option {{ old('userProfile.patient_type', $profile->patient_type) ==  $key ? "selected" : "" }} value="{{ $key }}">{{ $patient_type }}</option>
                                            @endforeach
                                        </select>
                                        <small><strong>IPD</strong> = Indoor Patient Department, Treatment & Stay</small><br>
                                        <small><strong>OPD </strong>= Outdoor Patient Department, Only Treatment</small>
                                    </div>
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input required class="user_last form-control required" type="text" value="{{ old('userProfile.first_name', $profile->first_name) }}" name="userProfile[first_name]" placeholder="First Name*" id="first_name_profile">
                                    </div>
                                    <div class="form-group">
                                        <input required class="user_email form-control required"  type="text" value="{{ old('userProfile.last_name', $profile->last_name) }}" name="userProfile[last_name]" placeholder="Last/Sir Name*" id="last_name_profile">
                                    </div>
                                    <div class="form-group">
                                        <input required class="user_password form-control" type="text" value="{{ old('userProfile.relative_name', $profile->relative_name) }}" id="relative_name" name="userProfile[relative_name]" placeholder="S/o, D/o, W/o">
                                    </div>
                                        <div class="form-group">
                                            <input required class="user_confirm datepicker form-control required" type="text" value="{{ old('userProfile.dob', $profile->dob) }}" max="100" id="dob" name="userProfile[dob]" placeholder="Date of Birth*">
                                        </div>
                                        {{--
                                    <div class="form-group">
                                        <input required class="user_confirm form-control required" type="text" value="{{ old('userProfile.age', $profile->age) }}" max="100" maxlength="3" min="1" id="age" name="userProfile[age]" placeholder="Age*">
                                    </div>--}}
                                    <div class="form-group">
                                        {!! Form::select('userProfile[gender]', ['' => 'Sex*'] + \App\UserProfile::getGenderOptions(), old('userProfile.gender', $profile->gender),['class'=>'form-control required', 'id' => 'gender', 'required' => 'required'])  !!}
                                    </div>
                                        <div class="form-group">
                                            {!! Form::select('userProfile[profession_id]',  \App\Profession::getDepartmentsDropdown()->toArray() + ['other' => 'Other'], old('userProfile.profession_id', $profile->profession_id),['class'=>'form-control required', 'id' => 'profession_id', 'placeholder' => 'Profesion*', 'required' => 'required'])  !!}

                                        </div>

                                        <div class="form-group" id="profession_name_div" style="display:none;">
                                            Please specify your profession here
                                            <input type="text" name="userProfile[profession_name]" id="profession_name" class="form-control" placeholder="Profession Name*"  />
                                        </div>
                                    <div class="form-group">
                                        {!! Form::select('userProfile[marital_status]', [''=>'Marital Status*','1' => 'Unmarried', '2' => 'Married'], old('userProfile.marital_status', $profile->marital_status),['id' => 'marital_status', 'class'=>'form-control required', 'required' => 'required'])  !!}
                                    </div>
                                    <div class="form-group">
                                        <label>Contact Details*</label>
                                        <input id="landline_number" class="user_confirm form-control" type="text" value="{{ old('userProfile.landline_number', $profile->landline_number) }}" name="userProfile[landline_number]" placeholder="Landline No.">
                                    </div>
                                    <div class="form-group">
                                        <input required id="mobile" class="user_confirm form-control required" type="text" value="{{ old('userProfile.mobile', $profile->mobile) }}" name="userProfile[mobile]" id="mobile" placeholder="Mobile No.*">
                                    </div>
                                    <div class="form-group">
                                        <input id="whatsapp_number" class="user_confirm form-control" type="text" name="userProfile[whatsapp_number]" value="{{ old('userProfile.whatsapp_number', $profile->whatsapp_number) }}" id="whatsapp_number" placeholder="WhatsApp No.">
                                    </div>
                                    <div class="form-group">
                                        <label>Address*</label>
                                        <input required class="user_confirm form-control required" type="text" name="userAddress[address1]" value="{{ old('userAddress.address1', $address->address1) }}" id="address1" placeholder="Address Line-1*">
                                    </div>
                                    <div class="form-group">
                                        <input class="user_confirm form-control" type="text" name="userAddress[address2]" value="{{ old('userAddress.address2', $address->address2) }}"  id="address2" placeholder="Address Line-2">
                                    </div>
                                    <div class="form-group">
                                        <input required class="user_confirm form-control required" type="text" name="userAddress[city]" value="{{ old('userAddress.city', $address->city) }}"  id="city" placeholder="City / Town / Village*">
                                    </div>
                                    <div class="form-group">
                                        <input required class="user_confirm form-control required" type="text" name="userAddress[state]" value="{{ old('userAddress.state', $address->state) }}"  id="city" placeholder="State*">
                                    </div>
                                    <div class="form-group">
                                        <input required class="user_confirm form-control required" type="text" name="userAddress[zip]" value="{{ old('userAddress.zip', $address->zip) }}"  id="zip" placeholder="Pin / Zip Code*">
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="user[country_code]" id="user_country_code" value="IN">
                                        <input type="hidden" name="userProfile[country_code]" id="country_code" value="IN">
                                        <select required name="userAddress[country]" class="form-control required" id="country">
                                            <option value="">Select Country</option>
                                            @foreach($countries as $country)
                                                <?php $cc_field_value = array_search($country, $countries); ?>
                                                <option {{ old('userAddress.country') == $cc_field_value ||  $address->country == $cc_field_value ? 'selected' : "" }} class="item" value="{{ $cc_field_value }}">{{ $country }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                        <div class="form-group">
                                            <label>Documents*</label><br>
                                            <div id="document-div" style="display:{{ old('userAddress.country') == "IN" ||  old('userAddress.country') == ""  ? "" : "none" }};">
                                                @foreach(\App\DocumentType::getDocuments() as $document)
                                                    {{ $document->title }}
                                                    <input id="document-{{ $document->id }}"  class="user_confirm form-control required" type="text" name="document_id_{{ $document->id }}" value="{{ old('document_id_'.$document->id) }}"   placeholder="{{ $document->title }} ID*">
                                                    <input id="document_file-{{ $document->id }}" type="file" name="document_{{ $document->id }}">
                                                    <small>Please upload files less than 2M size.</small>
                                                @endforeach
                                            </div>
                                            <div id="document-div-foreign" style="display:{{ old('userAddress.country') == "IN" ||  old('userAddress.country') == ""  ? "none" : "" }};">
                                                @foreach(\App\DocumentType::getDocuments(\App\DocumentType::STATUS_FOREIGN_CLIENT) as $document)
                                                    {{ $document->title }}
                                                    @if($document->is_downloadable == \App\DocumentType::IS_DOWNLOADABLE)   <a href="{{ \App\Settings::getDownloadUrl($document->file, $document->file_name) }}">Download {{ $document->title }}</a>
                                                    @endif
                                                    <input id="foreign_document-{{ $document->id }}"  class="user_confirm form-control required" type="text" name="foreign_document_id_{{ $document->id }}" value="{{ old('document_id_'.$document->id) }}"   placeholder="{{ $document->title }} ID*">



                                                    {{--<input required class="user_confirm form-control required" type="text" name="document_id_{{ $document->id }}" value="{{ old('document_id_'.$document->id) }}"  id="city" placeholder="{{ $document->title }} ID*">--}}
                                                    <input id="foreign_document_file-{{ $document->id }}" type="file" name="foreign_document_{{ $document->id }}">
                                                    <small>Please upload files less than 2M size.</small>
                                                @endforeach
                                            </div>
                                        </div>


                                        {{--<input type="hidden" name="userAddress[country]" id="user_country_data">--}}
                                    <div class="form-group">
                                        <input id="referral_source" class="user_confirm form-control required" type="text" name="userAddress[referral_source]" value="{{ old('userAddress.referral_source', $address->referral_source) }}" placeholder="Referral Source">
                                        <small>i.e. Walk-in, Google, Friend, Relative, Etc.</small>
                                    </div>

                                    <div class="form-group">
                                        <label>Photograph</label>Take Latest Photo<br>
                                        <div class="preview_profile"><input id="profile_picture" type="file" name="profile_picture" size="40"></div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="save_btn_signup form-control">Next »  </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            {!! Form::close() !!}
        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/jquery.steps.js') }}"></script>

    <script>
        $( ".datepicker" ).datepicker({format: "dd-mm-yy", autoclose:true, startDate:"+0d"});
        $("#country option[value='IN']").attr('selected', 'selected');
        var form = $("#bookingProcessForm").show();

        form.steps({
            headerTag: "h2",
            bodyTag: "section",
            transitionEffect: "slideLeft",
            stepsOrientation: "vertical",
            onStepChanging: function (event, currentIndex, newIndex)
            {
                // Always allow previous action even if the current form is not valid!
                if (currentIndex > newIndex)
                {
                    return true;
                }

                // Needed in some cases if the user went back (clean up)
                if (currentIndex < newIndex)
                {
                    // To remove error styles
                    form.find(".body:eq(" + newIndex + ") label.error").remove();
                    form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                }
                form.validate().settings.ignore = ":disabled,:hidden";
                //console.log("changing");
                return form.valid();
            },
            onFinishing: function (event, currentIndex)
            {
                form.validate().settings.ignore = ":disabled";
                //console.log("finishing");
                return form.valid();
            },
            onFinished: function (event, currentIndex)
            {
                //console.log("finished");
                form.submit();
            }
        }).validate({
            errorPlacement: function errorPlacement(error, element) { element.after(error); },
            rules: {
                confirm_password: {
                    equalTo: "#password"
                },
                'user[email]': {
                    remote: {
                        url: "{{ route('user/checkMail') }}",
                        type: "post",
                        data: {
                            _token: "{{ csrf_token() }}",
                            email: function () {
                                return $("#email").val();
                            }
                        },
                        dataFilter: function(response) {

                            if(response == 'true'){
                                return true;
                            }else {
                                alert("This email is already registered.")
                                $("#email").attr("placeholder",$("#email").val());
                                $("#email").val("");
                                return false;
                            }
                            return false;
                        }
                    }
                }
            },
            messages: {
                'user[email]': {
                    remote: "This email is already been taken."
                }
            }

        });

        $("#first_name").change(function () {
            $("#first_name_profile").val($(this).val());
            $("#first_name_profile_data").text($(this).val());
        });

        $("#last_name").change(function () {
            $("#last_name_profile").val($(this).val());
            $("#first_name_profile_data").text($(this).val());
        });

        $("#country").change(function () {
            var country = $("#country").val();
            var country_name = $("#country option :selected").text();
            $("#country_code").val(country);
            $("#user_country_code").val(country);
            $("#user_country_data").val(country_name);
            if (country == "IN") {
                $("#document-div").show();
                $("#document-div-foreign").hide();
                $("[id^=foreign_document-]").each(function () {
                    $(this).val("");
                })
                $("[id^=foreign_document_file-]").each(function () {
                    $(this).val("");
                })
            }else {
                $("#document-div").hide();
                $("#document-div-foreign").show();
                $("[id^=document-]").each(function () {
                    $(this).val("");
                })
                $("[id^=document_file-]").each(function () {
                    $(this).val("");
                })
            }
        });

        $("input, textarea").change(function (){
            var val = $(this).val();
            var attr = $(this).attr('id');
            $("#"+attr+"_data").text(val);
        });

        $("select").change(function () {
            var val = $(this).find(':selected').text();
            var attr = $(this).attr('id');
            $("#"+attr+"_data").text(val);
        });

        function showImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    var img = '<img src="'+e.target.result+'" alt="">';
                    $('.profile_img_Sec').html(img);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#profile_picture").change(function(){
            showImage(this);
        });
        $("#profession_id").change(function(){
            if ($(this).val() == "other") {
                $("#profession_id").prop("required", false);
                $("#profession_name").prop("required", true);
                $("#profession_name_div").show();
            }else{
                $("#profession_name").val("");
                $("#profession_id").prop("required", true);
                $("#profession_name").prop("required", false);
                $("#profession_name_div").hide();
            }
        })
    </script>
@endsection