@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @if(!\Auth::user()->isUser())
            <a class="section" href="{{ route('Laralum::bookings') }}">{{ trans('laralum.booking_list') }}</a>
            <i class="right angle icon divider"></i>
        @endif
        @if($booking->booking_id != null)
            <a class="section"
               href="{{ route('Laralum::booking.show', ['booking_id' => $booking->id]) }}">{{ trans('laralum.booking_details') }}</a>
            <i class="right angle icon divider"></i>
        @endif
        <div class="active section">Booking</div>
    </div>
@endsection
@section('title', 'Booking')
@section('icon', "pencil")
@section('subtitle', 'Booking')
@section('content')
    <div class="ui one column doubling stackable">
        <div class="admin_wrapper signup">
            <div class="main_wrapper">
                <div class="sideNavBar wow fadeInLeft">
                    {{--@if($booking->status == \App\Booking::STATUS_COMPLETED)--}}
                    @include('booking.topbar')
                </div>
                <div class="main_content_area booking_confirm">
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
                            {!! Form::open(array('route' => [ 'Laralum::user.booking.confirm.store', 'user_id' => $booking->id ], 'id' => 'bookingProcessForm','files'=>true,'method'=>'post')) !!}
                        {{--<form id="bookingProcessForm" action="{{ route('guest.booking') }}" method="post">--}}
                        {{ csrf_field() }}


                        <h3><i class="fa fa-check" aria-hidden="true"></i>Confirm</h3>
                        <section>
                            <div class="about_sec white_bg signup_bg">
                                <div class="form_preview-con">
                                    <div class="form-group">
                                        <div class="preview_title">Confirm your details</div>
                                        <div class="preview_inner">
                                            <div class="step1_wrapper">
                                                <h2 class="main-title">Basic Details</h2>
                                                <div class="preview-form-content">
                                                    <div class="preview-row">
                                                        <label>Booking Id</label>
                                                        <p>
                                                            <span id="first_name_data">{{ old('user.booking_id', $booking->booking_id) }}</span>
                                                        </p>
                                                    </div>
                                                    <div class="preview-row">
                                                        <label>Registration Id</label>
                                                        <p>
                                                            <span id="first_name_data">{{ old('user.kid', $booking->getProfile('kid')) }}</span>
                                                        </p>
                                                    </div>
                                                    <div class="preview-row">
                                                        <label>UHID</label>
                                                        <p>
                                                            <span id="first_name_data">{{ old('booking.booking_id', $booking->getProfile('uhid')) }}</span>
                                                        </p>
                                                    </div>
                                                    <div class="preview-row">
                                                        <label>Name</label>
                                                        <p><span id="first_name_data">{{ $user->name }}</span></p>
                                                    </div>
                                                    <div class="preview-row">
                                                        <label>Email</label>
                                                        <p id="email_data">{{ $user->email }}</p>
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
                                                        <p>
                                                            <span id="first_name_profile_data">{{ $booking->getProfile('first_name') }}</span>
                                                            <span id="last_name_profile_data">{{  $booking->getProfile('last_name') }}</span>
                                                        </p>
                                                    </div>
                                                    <div class="preview-row">
                                                        <label>S/o, D/o, W/o</label>
                                                        <p id="relative_name_data">{{ old('userProfile.relative_name', $booking->getProfile('relative_name')) }}</p>
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
                                                        <label>State</label>

                                                        <p id="city_data">{{ $booking->getAddress('state') }}</p>
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
                                                        @if($booking->getProfile('profile_picture'))
                                                            <div class="profoile_top_sec">
                                                                <div class="profile_img_Sec">
                                                                    <img src="{{ \App\Settings::getImageUrl($booking->getProfile('profile_picture')) }}">
                                                                </div>
                                                            </div>
                                                        @else
                                                            Not Uploaded
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="step1_wrapper">
                                                    <h2 class="main-title">Documents</h2>
                                                    <div class="preview-form-content">
                                                        <div class="preview-row">
                                                            @foreach(\App\DocumentType::getDocuments() as $document)
                                                                @if($booking->userProfile->getDocument($document->id))
                                                                    <label>{{ $document->title }}</label>


                                                                    <p id="health_issues_data">
                                                                        Id: {{ $booking->userProfile->getDocument($document->id, 'id_number') }}
                                                                        <a title="Download"
                                                                           href="{{ \App\Settings::getDownloadUrl($booking->userProfile->getDocument($document->id, 'file'), $booking->userProfile->getDocument($document->id, 'file_name')) }}"
                                                                           class="no-disable"><i
                                                                                    class="fa fa-cloud-download"></i> {{ $booking->userProfile->getDocument($document->id, 'file_name') }}
                                                                        </a></p><br/>
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
                                                            <p id="health_issues_data">{{ old('health_issues', $booking->getProfile('health_issues')) }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($booking->checkAccommodation())
                                                    <div class="step1_wrapper">
                                                        <h2 class="main-title">Accommodation Details</h2>
                                                        @if($booking->getCurrentBooking())
                                                            <div class="preview-form-content">
                                                                <div class="preview-row">
                                                                    <label>Building</label>
                                                                    <p id="health_issues_data">  {{ $booking->getCurrentBooking('building_name') }}</p>
                                                                </div>
                                                                <div class="preview-row">
                                                                    <label>Room</label>
                                                                    <p id="health_issues_data">{{ $booking->getCurrentBooking('room_no').'('.\App\Room::getFloorNumber($booking->getCurrentBooking('floor_number')).')' }}</p>
                                                                </div>
                                                                <div class="preview-row">
                                                                    <label>Booking Type</label>
                                                                    <p id="health_issues_data">{{ $booking->getBookingType($booking->getCurrentBooking('booking_type')) }}</p>
                                                                </div>
                                                                <div class="preview-row">
                                                                    <label>Check In Date - Check Out Date</label>
                                                                    <p id="health_issues_data">{{ $booking->getCurrentBooking('dates') }}</p>
                                                                </div>
                                                                <div class="preview-row">
                                                                    <label>Services</label>
                                                                    <p id="health_issues_data">{{ $booking->getCurrentBooking('services') }}</p>
                                                                </div>

                                                                <div class="preview-row">
                                                                    <label>Total Price</label>
                                                                    <p id="health_issues_data">{{ $booking->getCurrentBooking('total_price', false) }}</p>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="preview-form-content">
                                                                <div class="preview-row">
                                                                    <label>Booking From</label>
                                                                    <p id="health_issues_data">{!! date('d-m-Y', strtotime($booking->check_in_date)) !!}</p>
                                                                </div>
                                                                <div class="preview-row">
                                                                    <label>Booking End</label>
                                                                    <p id="health_issues_data">{!! date('d-m-Y', strtotime($booking->check_out_date)) !!}</p>
                                                                </div>
                                                                @if(isset($booking->building->name))
                                                                    <div class="preview-row">
                                                                        <label>Building Name</label>
                                                                        <p id="health_issues_data">{!! $booking->building->name !!}</p>
                                                                    </div>
                                                                    <div class="preview-row">
                                                                        <label>Booking Type</label>
                                                                        <p id="health_issues_data">{!! $booking->booking_type != null ? $booking->getBookingType($booking->booking_type) :  $booking->getBookingType(\App\BookingRoom::BOOKING_TYPE_SINGLE_BED) !!}</p>
                                                                    </div>
                                                                    <div class="preview-row">
                                                                        <label> Requested External Services </label>
                                                                        <p id="requested_external_service_data">
                                                                            @php echo implode(', ', \App\ExternalService::whereIn('id', explode(',', $booking->external_services))->pluck('name', 'id')->toArray()) @endphp
                                                                        </p>
                                                                    </div>

                                                                    @if($booking->room != null)
                                                                        <div class="preview-row">
                                                                            <label>Room No</label>
                                                                            <p id="health_issues_data">{!! $booking->room->room_number !!}</p>
                                                                        </div>
                                                                        <div class="preview-row">
                                                                            <label>Room Type</label>
                                                                            <p id="health_issues_data">{!! $booking->room->roomType->name !!}</p>
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="step1_wrapper">
                                                        <h2 class="main-title">Members Details</h2>
                                                        @foreach($booking->members as $member)
                                                            <div class="preview-form-content">
                                                                <div class="col-md-2 pull-left">
                                                                    <span class="memb_head">Name</span>
                                                                    <span class="memb_content">{!! $member->name !!}</span>
                                                                </div>
                                                                <div class="col-md-2 pull-left">
                                                                    <span class="memb_head">Age</span>
                                                                    <span class="memb_content">{!! $member->age !!}</span>
                                                                </div>
                                                                <div class="col-md-2 pull-left">
                                                                    <span class="memb_head">Gender</span>
                                                                    <span class="memb_content">{!!  $member->getGenderOptions($member->gender) !!}</span>
                                                                </div>
                                                                <div class="col-md-2 pull-left">
                                                                    <span class="memb_head">Id proof</span>
                                                                    <span class="memb_content">
                                                                     @if($member->id_proof != null)
                                                                            <a class="no-disable"
                                                                               href="{{ \App\Settings::getDownloadUrl($member->id_proof) }}">Download</a> @else
                                                                            -- @endif
                                                                    </span>
                                                                </div>


                                                                <div class="col-md-2 pull-left">
                                                                    <label>Room Dates</label><br/>
                                                                    {!! $member->check_in_date.' to '.$member->check_out_date !!}
                                                                </div>
                                                                <div class="col-md-2 pull-left">
                                                                    <label>Building Name</label><br>{!! $member->building->name !!}    - {!! \App\Booking::getBookingType($member->booking_type) !!}                </div>

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
                                                                    <label>Drivers</label><br/>
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
                                    <div class="form-group btn_group_form">
                                        <button type="submit" class="save_btn_signup form-control">YEAH, IT IS ALL FINE,
                                            GO
                                            AHEAD »
                                        </button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('js/jquery.steps.js') }}"></script>

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

        $("input, textarea").change(function () {
            var val = $(this).val();
            var attr = $(this).attr('id');
            $("#" + attr + "_data").text(val);
        });

        $("select").change(function () {
            var val = $(this).find(':selected').text();
            var attr = $(this).attr('id');
            $("#" + attr + "_data").text(val);
        });

        function showImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    var img = '<img src="' + e.target.result + '" alt="">';
                    $('.profile_img_Sec').html(img);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#profile_picture").change(function () {
            showImage(this);
        });

        getPrice();

        function getPrice() {
            var booking_amount = $("#booking_price").val();
            if (typeof booking_amount == "undefined")
                booking_amount = 0;
            console.log("Df");
            var service_amount = 0;
            $("[id^=service_]").each(function () {
                service_amount = eval(service_amount) + eval($(this).val());
            })
            var basic_price = $("#basic_price_val").val();
            if (typeof basic_price == "undefined")
                basic_price = 0;
            var discount_amount = $("#discount_amount_val").val();
            console.log("booking" + booking_amount);
            console.log("service_amount" + service_amount);
            console.log("basic_price" + basic_price);

            var total_amount = eval(booking_amount) + eval(service_amount) + eval(basic_price);
            console.log("total_amount" + total_amount);
            $("#total_amount_val").val(total_amount);
            $("#total_amount").html(total_amount);
            console.log("discount_amount" + discount_amount);
            var payable_amount = total_amount;
            if (typeof discount_amount != "undefined")
                if (discount_amount != "")
                    payable_amount = eval(total_amount) - eval(discount_amount);
            console.log("payable_amount" + payable_amount);
            $("#payable_amount_val").val(payable_amount);
            $("#payable_amount").html(payable_amount);
            var paid_amount = 0;
            if (booking_amount != 0)
                paid_amount = $("#paid_amount_val").val();

            var user_amount = eval(payable_amount) - eval(paid_amount);
            $("#user_amount_val").val(user_amount);
            $("#user_amount").html(user_amount);
        }

        $("#discount_code").change(function () {
            var val = $(this).val();
            var total_price = $("#total_amount_val").val();
            $.ajax({
                url: "{{ url("get-discount-code") }}",
                type: "POST",
                data: {"code": val, "price": total_price, "_token": "{{ csrf_token() }}"},
                success: function (data) {
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