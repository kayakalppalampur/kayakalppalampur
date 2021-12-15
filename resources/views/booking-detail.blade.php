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
<div class="main_content_area">
    <div class="preview_title">
        <h2>Booking Details</h2>
    </div>
<form id="profile-form" action="{{ url('user/profile/update') }}" method="POST">
{{ csrf_field() }}
    <div class="pro_main_content">
        <div class="row">
            <div class="col-md-12">
                <div class="about_sec white_bg signup_bg">
                    {{--<h3 class="title_3">Booking Process</h3>--}}

                    <div class="pull-right"><a href="{{ url("user/booking/personal_details/".$user->id) }}" class="btn btn-primary">@if($booking->status == \App\Booking::STATUS_COMPLETED || $booking->status == \App\Booking::STATUS_PENDING) Edit Booking Details @else New Booking @endif</a></div>
                    <div class="form_preview-con">
                        <div class="form-group">
                            <div class="preview_inner">
                                <div class="step1_wrapper">
                                    <h2 class="main-title">Basic Details</h2>
                                    <div class="preview-form-content">
                                        <div class="preview-row">
                                            <label>Booking Id</label>
                                            <p> <span>{{ $booking->booking_id }}</span></p>
                                        </div>
                                        <div class="preview-row">
                                            <label>Registration Id</label>
                                            <p> <span>{{ $user->registration_id }}</span></p>
                                        </div>
                                        <div class="preview-row">
                                            <label>Patient Id</label>
                                            <p> <span>{{ $user->userProfile->kid }}</span></p>
                                        </div>
                                        <div class="preview-row">
                                            <label>Name</label>
                                            <p> <span id="first_name_data">{{ old('user.first_name', $user->userProfile->first_name) }}</span> <span id="last_name_data"> {{ old('user.last_name') }}</span></p>
                                        </div>
                                        <div class="preview-row">
                                            <label>Email</label>
                                            <p id="email_data">{{ old('user.email',  $user->email) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="step1_wrapper">
                                    <h2 class="main-title">Personal Details</h2>
                                    <div class="preview-form-content">
                                        <div class="preview-row">
                                            <label>Patient Type</label>
                                            <p id="patient_type_data">{{ old('userProfile.patient_type', $user->userProfile->patient_type) != null ? \App\UserProfile::getPatientType(old('userProfile.patient_type', $user->userProfile->patient_type)) : "" }}</p>
                                        </div>
                                        <div class="preview-row">
                                            <label>Name</label>
                                            <p><span id="first_name_profile_data">{{ old('userProfile.last_name') }}</span> <span id="last_name_profile_data">{{ old('userProfile.last_name', $user->userProfile->last_name) }}</span></p>
                                        </div>
                                        <div class="preview-row">
                                            <label>S/o, D/o, W/o</label>
                                            <p id="relative_name_data" >{{ old('userProfile.relative_name', $user->userProfile->relative_name) }}</p>
                                        </div>
                                        <div class="preview-row">
                                            <label>Age</label>
                                            <p id="age_data">{{ old('userProfile.age', $user->userProfile->getAge()) }}</p>
                                        </div>
                                        <div class="preview-row">
                                            <label>Sex</label>
                                            <p id="gender_data">{{ old('userProfile.gender', $user->userProfile->gender) != null ? \App\UserProfile::getGenderOptions(old('userProfile.gender', $user->userProfile->gender)) : "" }}</p>
                                        </div>
                                        <div class="preview-row">
                                            <label>Profession</label>
                                            <p id="profession_id_data">{{ old('userProfile.profession_id', $user->userProfile->profession_id) != null  ? \App\UserProfile::getProfessionType(old('userProfile.profession_id', $user->userProfile->profession_id)) : "" }}</p>
                                        </div>
                                        <div class="preview-row">
                                            <label>Marital Status</label>
                                            <p id="marital_status_data">{{ \App\UserProfile::getMaritalStatus(old('marital_status', $user->userProfile->marital_status)) }}</p>
                                        </div>

                                        <div class="preview_subtitle">Contact Details</div>

                                        <div class="preview-row">
                                            <label>Landline No.</label>
                                            <p id="landline_number_data">{{ old('landline_number', $user->userProfile->landline_number) }}</p>
                                        </div>
                                        <div class="preview-row">
                                            <label>Mobile No.</label>
                                            <p id="mobile_data">{{ old('mobile', $user->userProfile->mobile) }}</p>
                                        </div>
                                        <div class="preview-row">
                                            <label>WhatsApp No.</label>
                                            <p id="whatsapp_number_data">{{  old('whatsapp_number', $user->userProfile->whatsapp_number)}}</p>
                                        </div>

                                        <div class="preview_subtitle">Address</div>

                                        <div class="preview-row">
                                            <label>Address Line-1</label>
                                            <p id="address1_data">{{ old('userAddress.address1', $user->address->address1) }}</p>
                                        </div>
                                        <div class="preview-row">
                                            <label>Address Line-2</label>
                                            <p id="address2_data">{{ old('userAddress.address2', $user->address->address2) }}</p>
                                        </div>
                                        <div class="preview-row">
                                            <label>City / Town / Village</label>
                                            <p id="city_data">{{ old('userAddress.city', $user->address->city) }}</p>
                                        </div>
                                        <div class="preview-row">
                                            <label>Pin / Zip Code</label>
                                            <p id="zip_data">{{ old('userAddress.zip', $user->address->zip) }}</p>
                                        </div>
                                        <div class="preview-row">
                                            <label>Country</label>
                                            <?php $countries = Laralum::countries(); ?>
                                            <p id="country_data">{{ $countries[old('userAddress.country', $user->address->country)] }}</p>
                                        </div>
                                        <div class="preview-row">
                                            <label>Referral Source</label>
                                            <p id="referral_source_data"> {{ old('referral_source', $user->address->referral_source) }}</p>
                                        </div>
                                        <div class="preview-row">
                                            <label>Photograph</label>
                                            <div class="profoile_top_sec_form">
                                                <div class="profile_img_Sec">
                                                    @if(isset($user->userProfile->profile_picture))
                                                        <img src="{{ \App\Settings::getImageUrl($user->userProfile->profile_picture) }}">
                                                    @else
                                                        Not Uploaded
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="step1_wrapper">
                                    <h2 class="main-title">Documents</h2>
                                    <div class="preview-form-content">
                                        <div class="preview-row">
                                            @foreach($user->documents as $document)
                                                <label>{{ $document->document->title }}</label>
                                                <p id="health_issues_data"> {{ $document->id_number }} <a href="{{ \App\Settings::getDownloadUrl($document->file, $document->file_name) }}"><i class="fa fa-cloud-download"></i> </a></p><br/>

                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="step1_wrapper">
                                    <h2 class="main-title">Health Issues</h2>
                                    <div class="preview-form-content">
                                        <div class="preview-row">
                                            <label>Health Issues</label>
                                            <p id="health_issues_data">{{ old('health_issues', $user->userProfile->health_issues) }}</p>
                                        </div>
                                    </div>
                                </div>
                                @if($user->checkAccommodation())
                                    @if($booking)
                                    <div class="step1_wrapper">
                                        <h2 class="main-title">Accommodation Details</h2>
                                        <div class="preview-form-content">
                                            <div class="preview-row">
                                                <label>Booking From</label>
                                                <p id="health_issues_data">{!! date('d-m-Y', strtotime($booking->check_in_date)) !!}</p>
                                            </div>
                                            <div class="preview-row">
                                                <label>Booking End</label>
                                                <p id="health_issues_data">{!! date('d-m-Y', strtotime($booking->check_out_date)) !!}</p>
                                            </div>
                                            <div class="preview-row">
                                                <label>Booking Status</label>
                                                <p id="health_issues_data">{!! $booking->getStatusOptions($booking->status) !!}</p>
                                            </div>
                                            <div class="preview-row">
                                                <label>Building Name</label>
                                                <p id="health_issues_data">{!! $booking->building->name !!}</p>
                                            </div>
                                            <div class="preview-row">
                                                <label>Booking Type</label>
                                                <p id="health_issues_data">{!! $booking->getBookingType($booking->booking_type) !!}</p>
                                            </div>

                                            @if($booking->room != null)
                                            <div class="preview-row">
                                                <label>Room No</label>
                                                <p id="health_issues_data">{!! $booking->room->getFloorNumber($booking->room->floor_number) !!} - {!! $booking->room->room_number !!}</p>
                                            </div>
                                            <div class="preview-row">
                                                <label>Room Type</label>
                                                <p id="health_issues_data">{!! $booking->roomType->name !!}</p>
                                            </div>
                                                @endif
                                        </div>
                                    </div>
                                    @endif
                                @endif
                                {{--@if($user->getTransaction())
                                    <div class="step1_wrapper">
                                        <h2 class="main-title">Account
                                            Details</h2>
                                        <div class="preview-form-content">
                                            @if($user->getTransaction()->booking != null)
                                                <div class="preview-row">
                                                    <label>Booking Price: </label>
                                                    <p id="health_issues_data">{!! $user->getTransaction()->booking->daysPrice() !!}</p>
                                                </div>
                                                @foreach($user->getTransaction()->booking->services as $service)
                                                    <div class="preview-row">

                                                        <label>{{ $service->service->name }}</label>
                                                        <p id="health_issues_data">{{ $service->service->price }}</p>
                                                    </div>                                                                                                       @endforeach
                                            @endif
                                            <div class="preview-row">
                                                <label>Basic Charges </label>
                                                <p id="old_basic_price">{{ \App\Settings::BASIC_PRICE }}</p>
                                            </div>
                                            <div class="preview-row">
                                                <label>Total Price</label>
                                                <p id="old_total_amount">{{ $user->getTransaction()->amount }}</p>
                                            </div>
                                            <div class="preview-row" id="old_discounted_amount_div" style="display:{{ $user->getTransaction()->discount_id != 0 ? "block;" : "none;" }};">
                                                <label>Discount Amount</label>
                                                <p id="old_discounted_amount">{{ $user->getTransaction()->discount_amount  }}</p>
                                            </div>
                                                <div class="preview-row">
                                                    <label>Payable Amount</label>
                                                    <p id="old_payable_amount">{{ $user->getTransaction()->payable_amount }}</p>
                                                </div>
                                            <div class="preview-row">
                                                <label>Paid Amount</label>
                                                <p id="old_payable_amount">{{ $user->getTotalAmount(\App\Wallet::TYPE_PAID) }}</p>
                                            </div>
                                                <div class="preview-row">
                                                    <label>Refundable Amount</label>
                                                    <p id="old_payable_amount">{{ $user->getTotalAmount(\App\Wallet::TYPE_REFUND) }}</p>
                                                </div>
                                        </div>
                                    </div>
                                @endif--}}
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
</div>

@endsection
{{--
@section('script')
    <script>
        function update(type) {
            if (!$("#profile-form")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $myForm.find(':submit').click()
            }else {
                var formData = new FormData($("#profile-form")[0]);

                $.ajax({
                    url: $("#profile-form").attr('action'),
                    type: 'POST',
                    data: formData,
                    async: false,
                    success: function (response) {
                        $("#" + type).hide();
                        $("#" + type).parent().find(".data").show();
                        updateFields(response.data);
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });

                return false;
            }
        }

        function showImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#profile_picture img').attr('src', e.target.result);
                    $('.user_img_sec img').attr('src', e.target.result);

                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#profile_picture_input").change(function(){
            showImage(this);
        });

        $('.cancel').click(function () {

            $(this).parent().parent().hide();
            $(this).parent().parent().parent().find('.data').show();
        })


        $(".edit").click(function () {
            $data = $(this).parent().parent().find(".data");
            $form = $(this).parent().parent().find(".form");
            $all_form = $(".form").not($form);
            $all_data = $(".data").not($data);

            $data.toggle();
            $form.toggle();
            $all_form.hide();
            $all_data.show();

        })

        function updateFields(data) {
            console.log(data);
            //Set data
            if( data.user_profile.profile_picture != '') {
                $("#profile-picture img").attr('src', data.user_profile.profile_picture);
            }

            $("#username_data").html(data.name);
            $("#name_data").html(data.user_profile.first_name + ' '+data.user_profile.last_name);
            $("#email_data").html(data.email);
            $("#mobile_data").html(data.user_profile.mobile);
            $("#office_data").html(data.user_profile.office);
            $("#gender_data").html(data.user_profile.gender);
            $("#dob_data").html(data.user_profile.dob);
            $("#location_data").html(data.user_profile.location);
            $("#about_data").html(data.user_profile.about);
            $("#designation_data").html(data.user_profile.designation);
            $("#facebook_url_data").attr('href',data.user_profile.facebook_url);
            $("#twitter_url_data").attr('href',data.user_profile.twitter_url);
            $("#linkedin_url_data").attr('href',data.user_profile.linkedin_url);
            $("#google_plus_url_data").attr('href',data.user_profile.google_plus_url);
            $("#landline_number_data").html(data.user_profile.landline_number);
            $("#whatsapp_number_data").html(data.user_profile.whatsapp_number);

            //Set values
            $("#username").val(data.name);
            $("#first_name").val(data.user_profile.first_name);
            $("#designation").val(data.user_profile.designation);
            $("#last_name").val(data.user_profile.last_name);
            $("#email").val(data.email);
            $("#mobile").val(data.user_profile.mobile);
            $("#office").val(data.user_profile.office);
            $("#gender").val(data.user_profile.gender);
            $("#dob").val(data.user_profile.dob);
            $("#location").val(data.user_profile.location);
            $("#about").val(data.user_profile.about);
            $("#facebook_url").val(data.user_profile.facebook_url);
            $("#twitter_url").val(data.user_profile.twitter_url);
            $("#linkedin_url").val(data.user_profile.linkedin_url);
            $("#google_plus_url").val(data.user_profile.google_plus_url);
            $("#landline_number").val(data.user_profile.landline_number);
            $("#whatsapp_number").val(data.user_profile.whatsapp_number);
        }
    </script>

@endsection

--}}
