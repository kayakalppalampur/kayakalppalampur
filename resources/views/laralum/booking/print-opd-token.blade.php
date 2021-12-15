@extends('layouts.front.web_layout')
@section('content')
    <style type="text/css" media="print">
        @page {
            size: auto;   /* auto is the initial value */
            margin: 0mm;  /* this affects the margin in the printer settings */
        }

        body {
            font-family: Arial;
            color: #000;
        }

    </style>

    <section class="booking_filter booking_search_patient ui padded segment" style="padding:20px 15px;">
        <div class="row" style="height: 182px">
            <div class="col-md-12">
                <div class="patient_outer_most" style="text-align: center">
                    <div class="btn_area clearfix" style="width:800px;max-width:800px;margin:15px auto 20px;">
                        <a href="{{ $back_url != "" ? $back_url : url('admin/booking/generate-opd-token') }}" id="back"
                           class="btn btn-primary pull-left" style="background-color:#2185d0;border:none;padding:0.785714em 1.5em;border-radius:0.285714rem;line-height:1em;
    text-transform:uppercase;"> Back</a>
                        <button class="ui button no-disable blue" id="print">PRINT TOKEN RECEIPT</button>
                    </div>
                    <div class="about_sec white_bg signup_bg" style="width:800px;margin:0 auto;">
                        <div class="pull-right">HRIYN-F-7</div>
                        <div class="patient_form_wrap" style="text-align: center;display: inline-block;width: 100%;">
                            <h2 style="text-transform: uppercase;font-size:16px;margin-top:0;font-weight:600;line-height:22px;margin-bottom:0;">
                                Vivekanand medical research trust, holta, palampur, (Regd.)<br> Distt. Kangra, Himachal pradesh -176062
                            </h2>
                            <div class="logo_kaya" style="position: relative;min-height: 95px;">
                                <div class="logo_form" style="float: left;">
                                    <img width="100px" src="{{asset('images/slip_left_logo.jpg')}}">
                                </div>
                                <div class="center_head" style="position: absolute;left: 50%;transform: translateX(-50%)">
                                    <h3 style="text-transform: uppercase;margin: 0;font-weight: bold;font-size:30px;">Kayakalp</h3>
                                    <p style="text-transform: uppercase;font-size:16px;line-height:20px;">Himalayan research
                                        institute<br> for yoga and naturopathy</p>
                                </div>
                                <div class="form_phone_detail" style="float: right;text-align:right;">
                                    <img width="100px" src="{{ asset('images/slip_right_logo.jpg') }}">
                                    <span style="display: block;font-size:16px;margin-top:10px;">Phone: (01894) 235676</span>
                                    <span style="display: block;font-size:16px;">Tele Fax: (01894) 235666</span>
                                    <span style="display: block;font-size:16px;">Mobile No: 7807310891</span>
                                </div>
                            </div>

                        </div>
                        <h4 class="print-head"
                            style="background: transparent;padding: 10px 20px;text-align: center;border: 2px solid #000;border-radius: 5px;display: inline-block;font-weight: 900;color: #000 !important;">
                            APPLICATION FOR CONSULTATION / O.P.D</h4>
                        <h4 class="print-head"
                            style="background: transparent;padding: 10px 20px;text-align: center;border: 2px solid #000;border-radius: 5px;display: inline-block;font-weight: 900;color: #000 !important;">
                            Reference Number - {{ $token->reference_number }}</h4>

                        <div class="form-wrap">

                            <div class="token-form-wrap patient-card-only">
                                <form id="print-kid">
                                    {!! csrf_field() !!}
                                    <div class="patient-card-wrap patient_outer">
                                        <div class="profile-details" style="float:right;width:40%">
                                            <div class="patient-card-detail" style="margin-bottom: 10px;"><label
                                                        style="width:20%">Date:</label> <span class="user-nm" style="width:80%">{{ date('d-m-Y')  }}</span>
                                            </div>
                                        </div>
                                        <div class="profile-details pull-right" style="width:100%">
                                            <div class="age-patient-outer-row" style="margin: 0px -15px;">
                                                <div class="patient-card-detail"
                                                     style="float:left;width:50%;padding:0px 15px;margin-bottom:10px;">
                                                    <label style="width:15%;">Name:</label> <span class="user-nm" style="width:85%;">{{ $token->first_name.' '.$token->last_name  }}</span>
                                                </div>
                                                <div class="patient-card-detail"
                                                     style="float:left;width:25%;padding:0px 15px;margin-bottom:10px;">
                                                    <label style="width:20%;">Sex:</label> <span class="user-sex" style="width:80%;">{{ \App\UserProfile::getGenderOptions($token->gender) }}</span>
                                                </div>
                                                <div class="patient-card-detail"
                                                     style="float:left;width:25%;padding:0px 15px;margin-bottom:10px;">
                                                    <label style="width:20%;">Age: </label><span class="user-age" style="width:80%;">{{ $token->getAge() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="profile-details" style="width:100%">
                                            <div class="patient-card-detail" style="margin-bottom: 10px;"><label
                                                        style="width:15%">Occupation:</label> <span class="user-nm" style="width:85%">{{ $token->professionName->name  }}</span>
                                            </div>
                                        </div>
                                        <div class="add_ph_outer">
                                            <div class="profile-details" style="width:100%">
                                                <div class="patient-card-detail" style="margin-bottom: 10px;"><label
                                                            style="width:20%">Permanent Address:</label> <span
                                                            class="user-nm"
                                                            style="width:80%">{{ $token->address  }}, {{$token->city}}, {{$token->state}}, {{$token->country}}</span>
                                                </div>
                                                <span class="user-nm"
                                                      style="width:100%;border-bottom: 1px solid #000;float:left;margin-bottom: 10px;min-height:20px;"></span>
                                                <span class="user-nm"
                                                      style="width:40%;border-bottom: 1px solid #000;float:left;margin-bottom: 10px;min-height:20px;"></span>
                                                <div class="profile-details" style="width:60%">
                                                    <div class="patient-card-detail" style="margin-bottom: 10px;"><label
                                                                style="width:26%">Phone Number:</label> <span
                                                                class="user-nm"
                                                                style="width:74%">{{ $token->mobile  }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="profile-details" style="width:100%; margin-top: 25px;">
                                            <div class="patient-card-detail"
                                                 style="margin-bottom: 10px;min-height:1310px;"><label
                                                        style="width:14%; border-bottom: 1px solid #000">Complaints
                                                    of: </label>
                                                <span class="user-nm"
                                                      style="width:84%;float: right; border-bottom: none; padding-left: 10px;">{{ $token->complaints }}</span>
                                            </div>
                                            <span class="user-nm"
                                                  style="width:100%;float:left;margin-bottom: 10px;min-height:20px;"></span>
                                            <span class="user-nm"
                                                  style="width:100%;float:left;margin-bottom: 10px;min-height:20px;"></span>
                                            <span class="user-nm"
                                                  style="width:50%;float:left;margin-bottom: 10px;min-height:20px;"></span>
                                        </div>

                                    </div>

                                </form>

                            </div>
                            @if(isset($id)) </div>
                    </div>
                </div>
                @endif
            </div>
        </div>


        <div class="row" style="height: 182px">
            <div class="about_sec white_bg signup_bg" style="width:800px;margin:0 15px;">
                {{--<div style="min-height:1300px;max-height:1300px;"></div>--}}
                <div class="col-md-12">

                    <div class="patient_outer_most" style="text-align: center">

                        <div class="form-wrap">
                            <h4 class="pull-right">Signature of Physician</h4>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-wrap text-right" style="margin-top: 10px">
                            Colon Hydrotherapy is a unique treatment for your intestinal system, and it detoxifies the
                            whole body as well.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>





@endsection
@section('script')
    <script>
        $("#print").click(function () {
            $("#print").hide();
            $("#back").hide();
            $(".booking_filter").css("margin", "auto");
            window.print();
            $(".booking_filter").css("margin", "0px");
            $("#print").show();
            $("#back").show();
        })
    </script>
@endsection