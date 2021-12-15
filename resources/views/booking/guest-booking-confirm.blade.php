@extends('layouts.front.web_layout')
@section('content')
    <div class="admin_wrapper signup">
        <header>
            <div class="logo_wrapper wow fadeInDown">
                <a href="{{ url("/home") }}"> <h1>Kayakalp</h1> </a>
            </div>
        </header>
        <div class="main_wrapper">
            <div class="sideNavBar wow fadeInLeft">
                <div>
                    <div class="footer_logo">Kayakalp</div>
                </div>
                @include('booking.sidebar')
            </div>
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
                {!! Form::open(array('route' => 'guest.booking.confirm', 'id' => 'bookingProcessForm','files'=>true,'method'=>'post')) !!}
                    {{--<form id="bookingProcessForm" action="{{ route('guest.booking') }}" method="post">--}}
                    {{ csrf_field() }}
                    <h3><i class="fa fa-check" aria-hidden="true"></i>Booking Process</h3>
                    <section>
                        <div class="pro_main_content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="about_sec white_bg signup_bg">
                                        {{--<h3 class="title_3">Booking Process</h3>--}}

                                        <div class="form_preview-con">
                                            <div class="form-group">
                                                <div class="preview_title">Confirm your details</div>
                                                <div class="preview_inner">
                                                    <div class="step1_wrapper">
                                                        <h2 class="main-title">Basic Details</h2>
                                                        <div class="preview-form-content">
                                                            <div class="preview-row">
                                                                <label>UHID</label>
                                                                <p> <span id="first_name_data">{{ old('user.registration_id', $user->uhid) }}</span> </p>
                                                            </div>
                                                            <!-- <div class="preview-row">
                                                                <label>Registration Id</label>
                                                                <p> <span id="first_name_data">{{ old('user.kid', $booking->getProfile('kid')) }}</span> </p>
                                                            </div> -->
                                                            <div class="preview-row">
                                                                <label>Booking Id</label>
                                                                <p> <span id="first_name_data">{{ old('user.booking_id', $booking->booking_id) }}</span></p>
                                                            </div>
                                                            <div class="preview-row">
                                                                <label>Name</label>
                                                                <p> <span id="first_name_data">{{  $user->name }}</span> </p>
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
                                                                <p id="patient_type_data">{{ old('userProfile.patient_type', $booking->getProfile('patient_type')) != null ? \App\UserProfile::getPatientType(old('userProfile.patient_type', $booking->getProfile('patient_type'))) : "" }}</p>
                                                            </div>
                                                            <div class="preview-row">
                                                                <label>Name</label>
                                                                <p><span id="first_name_profile_data">{{$booking->getProfile('first_name')  }}</span> <span id="last_name_profile_data">{{ $booking->getProfile('last_name')}}</span></p>
                                                            </div>
                                                            <div class="preview-row">
                                                                <label>S/o, D/o, W/o</label>
                                                                <p id="relative_name_data" >{{ old('userProfile.relative_name', $booking->getProfile('relative_name')) }}</p>
                                                            </div>
                                                            <div class="preview-row">
                                                                <label>Age</label>
                                                                <p id="age_data">{{ old('userProfile.age', $booking->getProfile('age')) }}</p>
                                                            </div>
                                                            <div class="preview-row">
                                                                <label>Sex</label>
                                                                <p id="gender_data">{{ old('userProfile.gender', $booking->getProfile('gender')) != null ? \App\UserProfile::getGenderOptions(old('userProfile.gender', $booking->getProfile('gender'))) : "" }}</p>
                                                            </div>
                                                            <div class="preview-row">
                                                                <label>Profession</label>
                                                                <p id="profession_id_data">{{ old('userProfile.profession_id', $booking->getProfile('profession_id')) != null  ? \App\UserProfile::getProfessionType(old('userProfile.profession_id', $booking->getProfile('profession_id'))) : "" }}</p>
                                                            </div>
                                                            <div class="preview-row">
                                                                <label>Marital Status</label>
                                                                <p id="marital_status_data">{{ \App\UserProfile::getMaritalStatus(old('marital_status', $booking->getProfile('marital_status'))) }}</p>
                                                            </div>

                                                            <div class="preview_subtitle">Contact Details</div>

                                                            <div class="preview-row">
                                                                <label>Landline No.</label>
                                                                <p id="landline_number_data">{{ old('landline_number', $booking->getProfile('landline_number')) }}</p>
                                                            </div>
                                                            <div class="preview-row">
                                                                <label>Mobile No.</label>
                                                                <p id="mobile_data">{{ old('mobile', $booking->getProfile('mobile')) }}</p>
                                                            </div>
                                                            <div class="preview-row">
                                                                <label>WhatsApp No.</label>
                                                                <p id="whatsapp_number_data">{{  old('whatsapp_number', $booking->getProfile('whatsapp_number'))}}</p>
                                                            </div>

                                                            <div class="preview_subtitle">Address</div>

                                                            <div class="preview-row">
                                                                <label>Address Line-1</label>
                                                                <p id="address1_data">{{ old('userAddress.address1', $booking->getAddress('address1')) }}</p>
                                                            </div>
                                                            <div class="preview-row">
                                                                <label>Address Line-2</label>
                                                                <p id="address2_data">{{ old('userAddress.address2', $booking->getAddress('address2')) }}</p>
                                                            </div>
                                                            <div class="preview-row">
                                                                <label>City / Town / Village</label>
                                                                <p id="city_data">{{ old('userAddress.city', $booking->getAddress('city')) }}</p>
                                                            </div>
                                                            <div class="preview-row">
                                                                <label>Pin / Zip Code</label>
                                                                <p id="zip_data">{{ old('userAddress.zip', $booking->getAddress('zip')) }}</p>
                                                            </div>
                                                            <div class="preview-row">
                                                                <label>Country</label>
                                                                <?php $countries = Laralum::countries(); ?>
                                                                <p id="country_data">{{ $countries[old('userAddress.country', $booking->getAddress('country'))] }}</p>
                                                            </div>


                                                            <div class="preview-row">
                                                                <label>Referral Source</label>
                                                                <p id="referral_source_data"> {{ old('referral_source', $booking->getAddress('referral_source')) }}</p>
                                                            </div>

                                                            <div class="preview-row">
                                                                <label>Photograph</label>
                                                                <div class="profoile_top_sec_form">
                                                                    <div class="profile_img_Sec">
                                                                        @if($booking->getProfile('profile_picture') != null)
                                                                            <img src="{{ \App\Settings::getImageUrl($booking->getProfile('profile_picture')) }}">
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
                                                                @foreach(\App\DocumentType::getDocuments() as $document) @if($profile->getDocument($document->id))
                                                                    <label>{{ $document->title }}</label>


                                                                        <p id="health_issues_data">        <a title="Download" href="{{ \App\Settings::getDownloadUrl($profile->getDocument($document->id, 'file'), $profile->getDocument($document->id, 'name')) }}" class="no-disable">{{ $profile->getDocument($document->id, 'id_number') }} <i class="fa fa-cloud-download"></i> {{ $profile->getDocument($document->id, 'file_name') }} </a></p><br/>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="step1_wrapper">
                                                        <h2 class="main-title">Health Issues</h2>
                                                        <div class="preview-form-content">
                                                            <div class="preview-row">
                                                                <label>Health Issues</label>
                                                                <p id="health_issues_data">{{ old('health_issues', $healthIssues->health_issues) }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                   @if($booking->checkAccommodation())
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
                                                                    <label>Building Name</label>
                                                                    <p id="health_issues_data">{!! $booking->building->name !!}</p>
                                                                </div>
                                                                <div class="preview-row">
                                                                    <label>Booking Type</label>
                                                                    <p id="health_issues_data">{!! $booking->getBookingType($booking->booking_type) !!}</p>
                                                                </div>
                                                                <div class="preview-row">
                                                                    <label>Floor</label>
                                                                    <p id="health_issues_data">{!! \App\Room::getFloorNumber($booking->floor_number) !!}</p>
                                                                </div>

                                                                <div class="preview-row">
                                                                    <label> Requested External Services </label>
                                                                    <p id="requested_external_service_data">
                                                                        @php echo implode(', ', \App\ExternalService::whereIn('id', explode(',', $booking->external_services))->pluck('name', 'id')->toArray()) @endphp
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="step1_wrapper">
                                                            <h2 class="main-title">Members Details</h2>
                                                            @foreach($booking->members as $member)
                                                                <div class="preview-form-content">
                                                                    <div class="col-md-2 pull-left">
                                                                        <label>Name</label><br/>
                                                                        {!! $member->name !!}
                                                                    </div>
                                                                    <div class="col-md-2 pull-left">
                                                                        <label>Age</label><br>
                                                                        {!! $member->age !!}
                                                                    </div>
                                                                    <div class="col-md-2 pull-left">
                                                                        <label>Gender</label><br>
                                                                        {!!  $member->getGenderOptions($member->gender) !!}
                                                                    </div>
                                                                    <div class="col-md-2 pull-left">
                                                                        <label>Id proof</label><br/>
                                                                        @if($member->id_proof != null)
                                                                        <a class="no-disable" href="{{ \App\Settings::getDownloadUrl($member->id_proof) }}">Download</a> @else -- @endif
                                                                    </div>
                                                                    <div class="col-md-2 pull-left">
                                                                        <label>Building Name</label><br>{!! $member->building->name !!}    - {!! \App\Booking::getBookingType($member->booking_type) !!}
                                                                    </div>
                                                                    @if($member->room != null)
                                                                    <div class="col-md-2 pull-left">
                                                                        <label>Room No</label><br>{!! $member->room->room_number !!}
                                                                    </div>
                                                                    <div class="col-md-2 pull-left">
                                                                        <label>Room Type</label><br>{!! $member->room->roomType->name !!}
                                                                    </div>
                                                                @endif
                                                                <div class="col-md-2 pull-left">
                                                                    <label>Children</label><br/>
                                                                    {!! $member->is_child != null ? $member->child_count : '' !!}

                                                                </div>
                                                                <div class="col-md-2 pull-left">
                                                                @if($member->room != null)
                                                                    <div class="col-md-2 pull-left">
                                                                        <label>Room No</label><br>{!! $member->room->room_number !!}
                                                                    </div>
                                                                    <div class="col-md-2 pull-left">
                                                                        <label>Room Type</label><br>{!! $member->room->roomType->name !!}
                                                                    </div>
                                                                @endif    <label>Drivers</label><br/>
                                                                    {!! $member->is_driver != null ? $member->driver_count : '' !!}

                                                                </div>


                                                                @if($member->getRoomDates() != "")
                                                                    <div class="col-md-2 pull-left">
                                                                        <span class="memb_head">Room Dates</span>
                                                                        <span class="memb_content">{!! $member->getRoomDates() !!} </span>
                                                                    </div>
                                                                    <div class="col-md-2 pull-left">
                                                                        <span class="memb_head">Room Details</span>
                                                                        <span class="memb_content">{!! $member->getRoomDetails() !!} </span>
                                                                        <span class="memb_head">Services:</span>
                                                                        <span class="memb_content">{!! $member->getServiceDetails()  !!}</span>
                                                                    </div>
                                                                    <div class="col-md-2 pull-left">
                                                                        <span class="memb_head">Total Price</span>
                                                                        <span class="memb_content">{!! $member->daysPrice(null, true, false) !!} </span>
                                                                    </div>

                                                                @endif
                                                                </div>
                                                            @endforeach
                                                        </div>

                                                    @endif

                                                    <div class="step1_wrapper">
                                                        <h2 class="main-title">Payment Details</h2>
                                                        <div class="preview-form-content">
                                                            <div class="preview-row">
                                                                <label>Payment Method</label>
                                                                <p id="payment_method">{{ !empty($booking->paymentDetail->payment_method) ? $booking->paymentDetail->getTypeOptions($booking->paymentDetail->payment_method) : \App\PaymentDetail::getTypeOptions(\App\PaymentDetail::PAYMENT_METHOD_WALLET )}}</p>
                                                            </div>
                                                            <div class="preview-row">
                                                                <label>Already Paid</label>
                                                                <p id="payment_method">{{ $booking->getPaidAmount() }}</p>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                        <div class="form-group">
                                            <button type="submit" class="save_btn_signup form-control">YEAH, IT IS ALL FINE, GO AHEAD »  </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    @include('layouts.front.booking_footer')
</div>
@endsection
@section('script')
<script>

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

    getPrice();

    function getPrice() {
        var booking_amount = $("#booking_price").val();
        console.log("Df");
        var service_amount = 0;
        $("[id^=service_]").each(function () {
            service_amount = eval(service_amount) + eval($(this).val());
        })
        if (isNaN(service_amount))
            service_amount = 0;


        var basic_price = $("#basic_price_val").val();
        var discount_amount = $("#discount_amount_val").val();
        if (typeof discount_amount == 'undefined')
            discount_amount = 0;
        console.log("booking"+booking_amount);
        if (typeof booking_amount == 'undefined')
            booking_amount = 0;
        console.log("service_amount"+service_amount);
        console.log("basic_price"+basic_price);

        var total_amount = eval(booking_amount) + eval(service_amount) + eval(basic_price);
        console.log("total_amount"+total_amount);
        $("#total_amount_val").val(total_amount);
        $("#total_amount").html(total_amount);
        console.log("discount_amount"+discount_amount);
        var payable_amount = total_amount;
        if (typeof discount_amount != "undefined" )
            if (discount_amount != "" )
                payable_amount = eval(total_amount) - eval(discount_amount);


        console.log("payable_amount"+payable_amount);
        $("#payable_amount_val").val(payable_amount);
        $("#payable_amount").html(payable_amount);
    }

    $("#discount_code").change(function(){
        var val = $(this).val();
        var total_price = $("#total_amount_val").val();
        $.ajax({
            url:"{{ url("get-discount-code") }}",
            type:"POST",
            data:{"code":val, "price" : total_price, "_token":"{{ csrf_token() }}" },
            success:function (data) {
                $("#discounted_amount").html(data.amount);
                $("#discount_amount_val").val(data.amount);
                getPrice();
                $("#discounted_amount_div").show();
                $("#discount_id").val(data.id)
            }
        });
    })
</script>
@endsection