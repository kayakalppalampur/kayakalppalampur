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
                {!! Form::open(array('route' => 'guest.booking.payment.store', 'id' => 'bookingProcessForm','files'=>true,'method'=>'post')) !!}
                    {{--<form id="bookingProcessForm" action="{{ route('guest.booking') }}" method="post">--}}
                    {{ csrf_field() }}
                    <h3><i class="fa fa-check" aria-hidden="true"></i>Booking Process</h3>
                    <section>
                        <div class="pro_main_content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="about_sec white_bg signup_bg">
                                        <div id="go-nxt" class="form_preview-con">
                                            <div class="form-group">
                                                <div class="preview_title">Payment Details</div>
                                                <div class="aggreement-details">
                                                    <input type="hidden" name="amount" value="{{  \App\AdminSetting::getSettingPrice('advance_payment') }}">
                                                    <input disabled type="text" class="form-control" id="amount" value="{{  \App\AdminSetting::getSettingPrice('advance_payment') }}" placeHolder="Amount to be paid"
                                                           max="{{ \App\ConsultationCharge::getConsultFees() }}" style="width:50%" name="amount"/>
                                                <p>PAYMENT OPTIONS (CCAVENUE or Better Payment Gateway) </p>
                                                <p>
                                                    <input type="radio" disabled name="payment_method" value="{{ \App\Transaction::PAYMENT_METHOD_CREDIT }}"/>Credit Card
                                                </p>
                                                <p>
                                                            <input type="radio" disabled name="payment_method" value="{{ \App\Transaction::PAYMENT_METHOD_DEBIT }}"/>Debit Card
                                                </p>
                                                <p>
                                                    <input type="radio" value="{{ \App\Transaction::PAYMENT_METHOD_NET_BANKING }}" name="payment_method" disabled />Net Banking
                                                </p>
                                                <p>
                                                    <input value="{{ \App\Transaction::PAYMENT_METHOD_MOBILE_PAYMENTS }}" type="radio" disabled name="payment_method"/>Mobile Payments
                                                </p>
                                                <p>
                                                    <input type="radio" value="{{ \App\Transaction::PAYMENT_METHOD_WALLET }}" name="payment_method" checked/>Cash
                                                </p>

                                                </div>
                                                <p class="col-md-4 col-md-offset-8">
                                                    <button class="save_btn_signup form-control" type="submit"> Agree & GO TO NEXT »  </button></p>
                                                <div>
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
    @include('layouts.front.booking_footer')
</div>
@endsection
@section('script')
<script src="{{ asset('js/jquery.steps.js') }}"></script>

<script>

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

</script>
@endsection