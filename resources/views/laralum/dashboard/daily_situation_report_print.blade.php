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
                    <div class="btn_area clearfix" style="width:100%;max-width:800px;margin:15px auto 20px;">
                        <a href="{{ url('admin/daily-situation-report') }}" id="back"
                           class="btn btn-primary pull-left" style="background-color:#2185d0;border:none;padding:0.785714em 1.5em;border-radius:0.285714rem;line-height:1em;
    text-transform:uppercase;"> Back</a>
                        <button class="ui button no-disable blue" id="print">PRINT</button>
                    </div>
                    <div class="about_sec white_bg signup_bg" style="width:800px;margin:0 auto;">
                        <div class="patient_form_wrap" style="text-align: center;display: inline-block;width: 100%;">
                            <h2 style="text-transform: uppercase;font-size:16px;margin-top:0;font-weight:600;line-height:22px;margin-bottom:0;">
                                Vivekanand medical research trust, holta, palampur, (Regd.)<br> Distt. Kangra, Himachal
                                pradesh -176062</h2>
                            <div class="logo_kaya" style="position: relative;min-height: 95px;">
                                <div class="logo_form" style="float: left;">
                                    <img width="100px" src="{{asset('images/slip_left_logo.jpg')}}">
                                </div>
                                <div class="center_head"
                                     style="position: absolute;left: 50%;transform: translateX(-50%)">
                                    <h3 style="text-transform: uppercase;margin: 0;font-weight: bold;font-size:30px;">
                                        Kayakalp</h3>
                                    <p style="text-transform: uppercase;font-size:16px;line-height:20px;">Himalayan
                                        research institute<br> for yoga and naturopathy</p>
                                </div>
                                <div class="form_phone_detail" style="float: right;text-align:right;">
                                    <img width="100px" src="{{ asset('images/slip_right_logo.jpg') }}">
                                    <span style="display: block;font-size:16px;margin-top:10px;">Phone: (01894) 235676</span>
                                    <span style="display: block;font-size:16px;">Tele Fax: (01894) 235666</span>
                                    <span style="display: block;font-size:16px;">Mobile No: 7807310891</span>
                                </div>
                            </div>
                        </div>

                        <div class="ui one column doubling stackable grid">
                            <div class="column ">
                                <hr>
                                <div class="ui very padded segment table_sec2">
                                    <div class="page_title table_top_btn">
                                        <h5 class="">Date- {{ date("d-m-Y") }}</h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <p class="pull-left">
                                                1.Number of Cases received for consultation:
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="pull-right">
                                                    <p>General: {{ \App\OpdTokens::whereDate('date', date("Y-m-d"))->count() }}</p>
                                                    <p>Antyodaya:</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <hr>
                                        <div class="col-md-12">
                                            <div class="col-md-6 ">
                                                <p class="pull-left">
                                                2.Number of Opds:
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="pull-right">
                                                    <p>
                                                        General: {{ \App\Booking::where('patient_type', \App\Booking::PATIENT_TYPE_OPD)->whereDate('created_at', date("Y-m-d"))->count() }}</p>
                                                    <p>Antyodaya:</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <hr>
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="pull-left">
                                                <p style="margin-right: 45px;">3.Number of Yoga participants for morning and evening batches separatly:</p>
                                                    </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="pull-right">
                                                <p>Male:</p>
                                                <p>Female:</p>
                                                <p>Total:</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <hr>
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="pull-left">
                                                <p>
                                                4.Number of Indoor Patients as on: {{ date("Y-m-d") }}<br>
                                                both Male and Females
                                                </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">

                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="col-md-6">

                                            </div>
                                            <div class="col-md-6 table_head_lft">
                                                <table class="ui table table_cus_v bs" style="border: 1px solid #ddd;">
                                                    <thead>
                                                    <tr>
                                                        <th>Building Name</th>
                                                        <th>Male</th>
                                                        <th>Female</th>
                                                        <th>Total</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php $male_count = 0; $female_count = 0;$total_count = 0; @endphp
                                                    @foreach(\App\Building::all() as $building)
                                                        @php
                                                            $males = $building->getMaleCount();
                                                            $females = $building->getFemaleCount();
                                                            $total = $males + $females;
                                                            $male_count = $male_count+$males; $female_count = $female_count+$females;$total_count = $total_count + $total; @endphp
                                                        <tr>
                                                            <td>{{ $building->name }}</td>
                                                            <td>{{ $males }}</td>
                                                            <td>{{ $females }}</td>
                                                            <td>{{ $total }}</td>
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td>Total</td>
                                                        <td>{{ $male_count }}</td>
                                                        <td>{{ $female_count }}</td>
                                                        <td>{{ $total_count }}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <hr>
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                5.Advance booking cases with duration of stay
                                            </div>
                                            <div class="col-md-6">
                                            </div>
                                        </div>

                                        @for($i = 0; $i < 10; $i ++ )
                                            @php $date = date("Y-m-d", strtotime("+".$i." days")); @endphp
                                            <div class="col-md-12">
                                                <div class="col-md-6">
                                                    {{ date("d-m-Y", strtotime($date)) }}
                                                </div>
                                                <div class="col-md-6 table_head_lft">
                                                    <table class="ui table table_cus_v bs" style="border: 1px solid #ddd;">
                                                        <thead>
                                                        <tr>
                                                            <th>OPDs:</th>
                                                            <th>
                                                                {{ \App\OpdTokens::whereDate('date', date("Y-m-d"))->count() }}
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                    </table>

                                                    <table class="ui table table_cus_v bs" style="border: 1px solid #ddd;">
                                                        <thead>
                                                        <tr>
                                                            <th>Building Name</th>
                                                            <th>Male</th>
                                                            <th>Female</th>
                                                            <th>Total</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @php $male_count = 0; $female_count = 0;$total_count = 0; @endphp
                                                        @foreach(\App\Building::all() as $building)
                                                            @php
                                                                $males = $building->getMaleCount($date);
                                                                $females = $building->getFemaleCount($date);
                                                                $total = $males + $females;
                                                                $male_count = $male_count+$males; $female_count = $female_count+$females;$total_count = $total_count + $total; @endphp
                                                            <tr>
                                                                <td>{{ $building->name }}</td>
                                                                <td>{{ $males }}</td>
                                                                <td>{{ $females }}</td>
                                                                <td>{{ $total }}</td>
                                                            </tr>
                                                        @endforeach
                                                        <tr>
                                                            <td>Total</td>
                                                            <td>{{ $male_count }}</td>
                                                            <td>{{ $female_count }}</td>
                                                            <td>{{ $total_count }}</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>


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
