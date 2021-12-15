@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Booking</div>
    </div>
@endsection
@section('title', 'Booking')
@section('icon', "pencil")
@section('subtitle', 'Booking')
@section('content')

    <div class="admin_wrapper signup admin-booking">
        <div class="main_wrapper">
            <div class="main_content_area">
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

                        @if (session('status') == 'danger')
                            <div class="alert alert-danger">
                                {!! session('message') !!}
                            </div>
                        @endif
                    {!! Form::open(array('route' => 'guest.booking', 'id' => 'bookingProcessForm','files'=>true,'method'=>'post')) !!}
                    {{--<form id="bookingProcessForm" action="{{ route('guest.booking') }}" method="post">--}}
                    {{ csrf_field() }}
                        @if(isset($reregister))
                            <input type="hidden" value="{{ $reregister }}" name="reregister" />
                        @endif
                        <input type="hidden" name="admin" value="1">
                    <h2><i class="fa fa-sign-out"></i>Sign Up</h2>
                    <section>
                        <div class="pro_main_content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="about_sec white_bg signup_bg">
                                        <h3 class="title_3">Booking Process</h3>
                                        <div class="form-group">
                                            <input class="user_namer form-control required" type="text" value="{{ old('user.first_name') }}" name="user[first_name]" id="first_name" placeholder="First Name" autofocus>
                                        </div>
                                        <div class="form-group">
                                            <input class="user_last form-control required" type="text" value="{{ old('user.last_name') }}" name="user[last_name]" id="last_name" placeholder="Last Name">
                                        </div>
                                        <div class="form-group">
                                            <input class="user_email form-control required" type="email" name="user[email]" value="{{ old('user.email') }}" id="email" placeholder="Email Id">
                                        </div>
                                        <div class="form-group">
                                            <input class="user_password form-control required" type="password" name="user[password]" id="password" placeholder="Password">
                                        </div>
                                        <div class="form-group">
                                            <input class="user_confirm form-control required" type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <h2><i class="fa fa-user" aria-hidden="true"></i>Personal Detail</h2>
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
                                            <select class="form-control required" id="patient_type" name="userProfile[patient_type]" id="patient_type" autofocus>
                                                <option value="">Patient Type</option>
                                                @foreach(\App\UserProfile::getPatientType() as $key => $patient_type)
                                                    <option {{ old('userProfile.patient_type') ==  $key ? "selected" : "" }} value="{{ $key }}">{{ $patient_type }}</option>
                                                @endforeach
                                            </select>
                                            <small><strong>IPD</strong> = Indoor Patient Department, Treatment & Stay</small><br>
                                            <small><strong>OPD </strong>= Outdoor Patient Department, Only Treatment</small>
                                        </div>
                                        <div class="form-group">
                                            <label>Personal Detail</label>
                                            <input class="user_last form-control required" type="text" value="{{ old('userProfile.first_name') }}" name="userProfile[first_name]" placeholder="First Name*" id="first_name_profile">
                                        </div>
                                        <div class="form-group">
                                            <input class="user_email form-control required"  type="text" value="{{ old('userProfile.last_name') }}" name="userProfile[last_name]" placeholder="Last/Sur Name*" id="last_name_profile">
                                        </div>
                                        <div class="form-group">
                                            <input class="user_password form-control" type="text" value="{{ old('userProfile.relative_name') }}" id="relative_name" name="userProfile[relative_name]" placeholder="S/o, D/o, W/o">
                                        </div>
                                        <div class="form-group">
                                            <input class="user_confirm form-control required" type="text" value="{{ old('userProfile.age') }}" max="100" maxlength="3" min="1" id="age" name="userProfile[age]" placeholder="Age*">
                                        </div>
                                        <div class="form-group">
                                            {!! Form::select('userProfile[gender]', ['' => 'Sex*'] + \App\UserProfile::getGenderOptions(), old('userProfile.gender'),['class'=>'form-control required', 'id' => 'gender'])  !!}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::select('userProfile[profession_id]', ['' => 'Profession*'] + \App\Profession::getDepartmentsDropdown()->toArray(), old('userProfile.profession_id'),['class'=>'form-control required', 'id' => 'profession_id'])  !!}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::select('userProfile[marital_status]', [''=>'Marital Status*','1' => 'Unmarried', '2' => 'Married'], old('userProfile.marital_status'),['id' => 'marital_status', 'class'=>'form-control required'])  !!}
                                        </div>
                                        <div class="form-group">
                                            <label>Contact Details*</label>
                                            <input id="landline_number" class="user_confirm form-control" type="text" value="{{ old('userProfile.landline_number') }}" name="userProfile[landline_number]" placeholder="Landline No.">
                                        </div>
                                        <div class="form-group">
                                            <input id="mobile" class="user_confirm form-control required" type="text" value="{{ old('userProfile.mobile') }}" name="userProfile[mobile]" id="mobile" placeholder="Mobile No.*">
                                        </div>
                                        <div class="form-group">
                                            <input id="whatsapp_number" class="user_confirm form-control" type="text" name="userProfile[whatsapp_number]" value="{{ old('userProfile.whatsapp_number') }}" id="whatsapp_number" placeholder="WhatsApp No.">
                                        </div>
                                        <div class="form-group">
                                            <label>Address*</label>
                                            <input class="user_confirm form-control required" type="text" name="userAddress[address1]" value="{{ old('userAddress.address1') }}" id="address1" placeholder="Address Line-1*">
                                        </div>
                                        <div class="form-group">
                                            <input class="user_confirm form-control" type="text" name="userAddress[address2]" value="{{ old('userAddress.address2') }}"  id="address2" placeholder="Address Line-2*">
                                        </div>
                                        <div class="form-group">
                                            <input class="user_confirm form-control required" type="text" name="userAddress[city]" value="{{ old('userAddress.city') }}"  id="city" placeholder="City / Town / Village*">
                                        </div>
                                        <div class="form-group">
                                            <input class="user_confirm form-control required" type="text" name="userAddress[zip]" value="{{ old('userAddress.zip') }}"  id="zip" placeholder="Pin / Zip Code*">
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" name="user[country_code]" id="user_country_code">
                                            <input type="hidden" name="userProfile[country_code]" id="country_code">
                                            <select name="userAddress[country]" class="form-control required" id="country">
                                                <option value="">Select Country</option>
                                                @foreach($countries as $country)
                                                    <?php $cc_field_value = array_search($country, $countries); ?>
                                                    <option {{ old('userAddress.country') == $cc_field_value ? 'selected' : "" }} class="item no-disable" value="{{ $cc_field_value }}">{{ $country }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <input  id="referral_source" class="user_confirm form-control required" type="text" name="userAddress[referral_source]" value="{{ old('userAddress.referral_source') }}" placeholder="Referral Source*">
                                            <small>i.e. Walk-in, Google, Friend, Relative, Etc.</small>
                                        </div>

                                        <div class="form-group">
                                            <label>Photograph</label>Take Latest Photo<br>
                                            <div class="preview_profile"><input id="profile_picture" type="file" name="profile_picture" size="40"></div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <h2><i class="fa fa-medkit" aria-hidden="true"></i>Health Issues</h2>
                    <section>
                        <div class="pro_main_content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="about_sec white_bg signup_bg">
                                        <h3 class="title_3">Booking Process</h3>
                                        <h4>Mention cheif complaints / health issues that you want to treatment for</h4>
                                        <div class="form-group">
                                            <label>Describe your Health Issues (Chief Complaints) here:</label>
                                            <textarea id="health_issues" style="height: auto!important;" rows="5" class="user_namer form-control complaints required" name="userProfile[health_issues]" type="text" placeholder="Mention the issue(s) in simple words." autofocus>{{ old('UserProfile.health_issues') }}</textarea>
                                        </div>
                                        <p class="note">Note: It is recommended to mention your issues  now so that doctor can diagnose you quickly when you arrive.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <h2><i class="fa fa-check" aria-hidden="true"></i>Confirm</h2>
                    <section>
                        <div class="pro_main_content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="about_sec white_bg signup_bg">
                                        <h3 class="title_3">Booking Process</h3>

                                        <div class="form_preview-con">
                                            <div class="form-group">
                                                <div class="preview_title">Confirm your details</div>
                                                <div class="preview_inner">
                                                    <div class="step1_wrapper">
                                                        <h2 class="main-title">Basic Details</h2>
                                                        <div class="preview-form-content">
                                                            <div class="preview-row">
                                                                <label>Name</label>
                                                                <p> <span id="first_name_data">{{ old('user.first_name') }}</span> <span id="last_name_data"> {{ old('user.last_name') }}</span></p>
                                                            </div>
                                                            <div class="preview-row">
                                                                <label>Email</label>
                                                                <p id="email_data">{{ old('user.email') }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="step1_wrapper">
                                                        <h2 class="main-title">Personal Details</h2>
                                                        <div class="preview-form-content">
                                                            <div class="preview-row">
                                                                <label>Patient Type</label>
                                                                <p id="patient_type_data">{{ old('userProfile.patient_type') != null ? \App\UserProfile::getPatientType(old('userProfile.patient_type')) : "" }}</p>
                                                            </div>
                                                            <div class="preview-row">
                                                                <label>Name</label>
                                                                <p><span id="first_name_profile_data">{{ old('userProfile.first_name') }}</span> <span id="last_name_profile_data">{{ old('userProfile.last_name') }}</span></p>
                                                            </div>
                                                            <div class="preview-row">
                                                                <label>S/o, D/o, W/o</label>
                                                                <p id="relative_name_data" >{{ old('userProfile.relative_name') }}</p>
                                                            </div>
                                                            <div class="preview-row">
                                                                <label>Age</label>
                                                                <p id="age_data">{{ old('userProfile.age') }}</p>
                                                            </div>
                                                            <div class="preview-row">
                                                                <label>Sex</label>
                                                                <p id="gender_data">{{ old('userProfile.relative_name') != null ? \App\UserProfile::getGenderOptions(old('userProfile.relative_name')) : "" }}</p>
                                                            </div>
                                                            <div class="preview-row">
                                                                <label>Profession</label>
                                                                <p id="profession_id_data">{{ old('userProfile.profession_id') != null ? \App\UserProfile::getProfessionType(old('userProfile.profession_id')) : "" }}</p>
                                                            </div>
                                                            <div class="preview-row">
                                                                <label>Marital Status</label>
                                                                <p id="marital_status_data">{{ old('marital_status') }}</p>
                                                            </div>

                                                            <div class="preview_subtitle">Contact Details</div>

                                                            <div class="preview-row">
                                                                <label>Landline No.</label>
                                                                <p id="landline_number_data">{{ old('landline_number') }}</p>
                                                            </div>
                                                            <div class="preview-row">
                                                                <label>Mobile No.</label>
                                                                <p id="mobile_data">{{ old('mobile_data') }}</p>
                                                            </div>
                                                            <div class="preview-row">
                                                                <label>WhatsApp No.</label>
                                                                <p id="whatsapp_number_data">{{  old('whatsapp_number    ')}}</p>
                                                            </div>

                                                            <div class="preview_subtitle">Address</div>

                                                            <div class="preview-row">
                                                                <label>Address Line-1</label>
                                                                <p id="address1_data">{{ old('userAddress.address1') }}</p>
                                                            </div>
                                                            <div class="preview-row">
                                                                <label>Address Line-2</label>
                                                                <p id="address2_data">{{ old('userAddress.address2') }}</p>
                                                            </div>
                                                            <div class="preview-row">
                                                                <label>City / Town / Village</label>
                                                                <p id="city_data">{{ old('userAddress.city') }}</p>
                                                            </div>
                                                            <div class="preview-row">
                                                                <label>Pin / Zip Code</label>
                                                                <p id="zip_data">{{ old('userAddress.zip') }}</p>
                                                            </div>
                                                            <div class="preview-row">
                                                                <label>Country</label>
                                                                <p id="country_data">{{ old('userAddress.country') }}</p>
                                                            </div>
                                                            <div class="preview-row">
                                                                <label>Referral Source</label>
                                                                <p id="referral_source_data"> {{ old('referral_source') }}</p>
                                                            </div>

                                                            <div class="preview-row">
                                                                <label>Photograph</label>
                                                                <div class="profoile_top_sec_form">
                                                                    <div class="profile_img_Sec">
                                                                        Not Uploaded
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="step1_wrapper">
                                                        <h2 class="main-title">Health Issues</h2>
                                                        <div class="preview-form-content">
                                                            <div class="preview-row">
                                                                <label>Health Issues</label>
                                                                <p id="health_issues_data">{{ old('health_issues') }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
@endsection
<link href="{{ asset('css/jquery.steps.css') }}" rel="stylesheet" type="text/css" />
@section('js')
    <script src="{{ asset('js/jquery.steps.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>

    <script>

        /*$("document").ready(function () {
            alert(32);
        })*/
        var form = $("#bookingProcessForm").show();

        form.steps({
            headerTag: "h2",
            bodyTag: "section",
            transitionEffect: "slideLeft",
            stepsOrientation: "horizontal",
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
            var country_name = $("#country option:selected").text();
            console.log(country_name);
            $("#country_code").val(country);
            $("#user_country_code").val(country);
            $("#user_country_data").val(country_name);
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
                    var img = '<img src="'+e.target.result+'" alt="" width=100>';
                    $('.profile_img_Sec').html(img);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#profile_picture").change(function(){
            showImage(this);
        });

    </script>
@endsection