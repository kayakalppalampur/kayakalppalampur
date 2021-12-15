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
                                                                <p id="age_data">{{ old('userProfile.age', $user->userProfile->age) }}</p>
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
                                                        <h2 class="main-title">Health Issues</h2>
                                                        <div class="preview-form-content">
                                                            <div class="preview-row">
                                                                <label>Health Issues</label>
                                                                <p id="health_issues_data">{{ old('health_issues', $user->userProfile->health_issues) }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if($user->checkAccommodation())
                                                        <div class="step1_wrapper">
                                                            <h2 class="main-title">Accommodation Details</h2>
                                                            <div class="preview-form-content">
                                                                <div class="preview-row">
                                                                    <label>Booking From</label>
                                                                    <p id="health_issues_data">{!! date('Y-m-d', strtotime($user->booking->check_in_date)) !!}</p>
                                                                </div>
                                                                <div class="preview-row">
                                                                    <label>Booking End</label>
                                                                    <p id="health_issues_data">{!! date('Y-m-d', strtotime($user->booking->check_out_date)) !!}</p>
                                                                </div>
                                                                <div class="preview-row">
                                                                    <label>Building Name</label>
                                                                    <p id="health_issues_data">{!! $user->booking->room->building->name !!}</p>
                                                                </div>
                                                                <div class="preview-row">
                                                                    <label>Booking Type</label>
                                                                    <p id="health_issues_data">{!! $user->booking->getBookingType($user->booking->booking_type) !!}</p>
                                                                </div>
                                                                <div class="preview-row">
                                                                    <label>Floor</label>
                                                                    <p id="health_issues_data">{!! $user->booking->room->floor_number !!}</p>
                                                                </div>
                                                                <div class="preview-row">
                                                                    <label>Room No</label>
                                                                    <p id="health_issues_data">{!! $user->booking->room->room_number !!}</p>
                                                                </div>
                                                                <div class="preview-row">
                                                                    <label>Room Type</label>
                                                                    <p id="health_issues_data">{!! $user->booking->room->roomType->name !!}</p>
                                                                </div>
                                                                @foreach($user->booking->services as $service)
                                                                    <div class="preview-row">
                                                                        <label>Service: </label><p>{{ $service->service->name }}</p>
                                                                    </div>
                                                                    @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="step1_wrapper">
                                                        <h2 class="main-title">Account
                                                            Details</h2>
                                                        <div class="preview-form-content">
                                                            @if($user->checkAccommodation())
                                                            <div class="preview-row">
                                                                <label>Booking Price: </label>
                                                                <p id="health_issues_data">{!! $user->booking->daysPrice() !!}</p>
                                                                <input type="hidden" id="booking_price" value="{{ $user->booking->daysPrice() }}">
                                                            </div>
                                                                                               @foreach($user->booking->services as $service)
                                                            <div class="preview-row">

                                                                <label>{{ $service->service->name }}</label>
                                                                <p id="health_issues_data">{{ $service->service->price }}</p>      <input type="hidden" id="service_{{ $service->id }}" value="{{ $service->service->price }}">
                                                            </div>                                                                                                       @endforeach
                                                                @endif
                                                                <div class="preview-row">
                                                                    <label>Basic Charges </label>
                                                                    <input type="hidden" name="basic_price" id="basic_price_val" value="{{ \App\Settings::BASIC_PRICE }}">
                                                                    <p id="basic_price">{{ \App\Settings::BASIC_PRICE }}</p>
                                                                </div>
                                                                <div class="preview-row">
                                                                    <label>Total Price</label>
                                                                    <input type="hidden" name="total_amount" id="total_amount_val">
                                                                    <p id="total_amount"></p>
                                                                </div>
                                                                <div class="preview-row">
                                                                    <label>Discount</label>
                                                                   <p> <input type="text" class="form-control" value="" placeHolder="Have Discount Code?"  style="width:50%" id="discount_code" /> </p>
                                                                </div>
                                                                <div class="preview-row" id="discounted_amount_div" style="display:none;">
                                                                    <label>Discount Amount</label>
                                                                    <input type="hidden" name="discount_amount" id="discount_amount_val"><input type="hidden" name="discount_id" id="discount_id">
                                                                    <p id="discounted_amount"></p>
                                                                </div>
                                                                <div class="preview-row">
                                                                    <label>Payable Amount</label>
                                                                    <input type="hidden" name="payable_amount" id="payable_amount_val">
                                                                    <p id="payable_amount"></p>
                                                                </div>                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

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
        var basic_price = $("#basic_price_val").val();
        var discount_amount = $("#discount_amount_val").val();
        console.log("booking"+booking_amount);
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