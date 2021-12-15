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

    <section class="booking_filter booking_search_patient ui padded segment booking_print">
        <div class="row" style="height: 182px">
            <div class="col-md-12">
                <div class="patient_outer_most" style="text-align: center">
                    <div class="btn_area clearfix" style="width:100%;max-width:800px;margin:15px auto 20px;">
                        <a href="{{ url('admin/daily-building-status', $date) }}" id="back"
                           class="btn btn-primary pull-left" style="background-color:#2185d0;border:none;padding:0.785714em 1.5em;border-radius:0.285714rem;line-height:1em;
    text-transform:uppercase;"> Back</a>
                        <button class="ui button no-disable blue" id="print">PRINT</button>
                    </div>
                    <div class="about_sec white_bg signup_bg" style="max-width:800px;margin:0 auto;">
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
                                <div class="ui very padded segment table_sec2">
                                    <div class="page_title table_top_btn">
                                        <h2 class="pull-left">Date- {{ $date }}</h2>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 table_head_lft">
                                            <table class="ui table table_cus_v bs">
                                                <tbody>
                                                @php $male_count = 0; $female_count = 0;$total_count = 0; @endphp
                                              {{--  @foreach(\App\Building::all() as $building)--}}
                                                    @php
                                                        $males = $building->getMaleCount($date);
                                                        $females = $building->getFemaleCount($date);
                                                        $total = $males + $females;
                                                        $male_count = $male_count+$males; $female_count = $female_count+$females;$total_count = $total_count + $total;
                                                     $bookings = $building->getBookings($date);@endphp
                                                    <thead>
                                                    <tr>
                                                        <th>Building Name: {{ $building->name }}</th>
                                                        <th>Male: {{ $males }}</th>
                                                        <th>Female: {{ $females }}</th>
                                                        <th>Total:{{ $total }}</th>
                                                    </tr>
                                                    </thead>
                                                    @if ($bookings->count() > 0)
                                                        <tr>
                                                            <td colspan="4">
                                                                <table class="ui table table_cus_v bs">
                                                                    <thead>
                                                                    <th>Floor</th>
                                                                    <th>Room No.</th>
                                                                    <th>Booked By</th>
                                                                    <th>Check In Date</th>
                                                                    <th>Check Out Date</th>
                                                                    </thead>
                                                                    <tbody>
                                                                    @foreach($bookings as $booking)
                                                                        <tr>
                                                                            <td>{{ $booking->room->floor_number }}</td>
                                                                            <td>{{ $booking->room->room_number  }}</td>
                                                                            <td>{{ $booking->alloted_to }}</td>
                                                                            <td>{{ $booking->check_in_date }}</td>
                                                                            <td>{{ $booking->check_out_date }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                    </tbody>
                                                                </table>

                                                            </td>
                                                        </tr>
                                                    @else
                                                        <tr>
                                                            <td colspan="4">No Bookings in this building</td>
                                                        </tr>
                                                    @endif
                                               {{-- @endforeach--}}
                                               {{-- <tr>
                                                    <td>Total</td>
                                                    <td>{{ $male_count }}</td>
                                                    <td>{{ $female_count }}</td>
                                                    <td>{{ $total_count }}</td>
                                                </tr>--}}
                                                </tbody>
                                            </table>
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
