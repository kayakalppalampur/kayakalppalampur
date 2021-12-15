@extends('layouts.front.web_layout')
@section('content')
    <style type="text/css" media="print">
        @page {
            size: auto;   /* auto is the initial value */
            margin: 0mm;  /* this affects the margin in the printer settings */
        }

        .date {
            background-color: #ccc;
            float: left;
            padding: 8px;
            width: 75%;
        }
    </style>
    <style>
        #mySelector.token-receipt {
            max-width: 650px;
        }

        .token-detail-box {
            display: inline-block;

            width: 100%;

            margin: 0px 0px 10px;
        }
    </style>

    <div class="ui one column doubling stackable grid container">

        <div class="column">

        </div>
    </div>


    <div class="token-receipt" id="mySelector" style="width:1000px;max-width:1000px;">
        <div class="receipt-for">
            <p>@if(!isset($print)) <a id="print" class="btn btn-primary ui button blue">Print</a> <a id="back" class="btn btn-primary ui button blue" href="{{ isset($back_url) ? $back_url : url('/admin/booking/allot-accommodation/'.$booking->id) }}">Back</a>  @endif
            </p>
        </div>

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
        <div class="table_head_lft">
            <table class="ui table table_cus_v">
                <tbody>
                <tr>
                    <th>Booking Id</th>
                    <td>{{ $user->registration_id}}</td>
                    <th>Patient Id</th>
                    <td>{{ $booking->getProfile('kid') }}</td>
                    <th>Registration Id</th>
                    <td>{{ $booking->booking_id }}</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ $booking->user->name }}</td>
                    <th>Email</th>
                    <td>{{ $booking->user->email }}</td>
                    <th>Patient's Name</th>
                    <td>{{ $booking->getProfile('first_name').' '.$booking->getProfile('last_name') }}</td>

                <tr>
                    <th>S/o, D/o, W/o</th>
                    <td>{{ $booking->getProfile('relative_name') }}</td>

                    <th>Gender</th>
                    <td>{{ $booking->getProfile('gender') != null ? \App\UserProfile::getGenderOptions($booking->getProfile('gender')) : "" }}</td>
                    <th>Age</th>
                    <td>{{ $booking->getProfile('age') }}</td>
                </tr>
                <tr>
                    <th>Contact Number</th>
                    <td>{{ $booking->getProfile('mobile') }}</td>
                    <th>Marital Status</th>
                    <td>{{ $booking->getProfile('marital_status') != null ? \App\UserProfile::getMaritalStatus($booking->getProfile('marital_status')) : ""}}</td>
                    <th>Profession</th>

                    <td>{{ @\App\UserProfile::getProfessionType($booking->getProfile('profession_id')) }}</td>

                </tr>

                </tbody>

            </table>
        </div>

        @if(!empty($booking->getCurrentBooking()))
            <div class="row">
                <div class="">
                    <div class="title">
                        <div class="space10"></div>
                        <div class="page_title"><h2>Accommodation Details</h2></div>
                        <div class="divider space10"></div>
                    </div>
                </div>
                <div class="">
                    <div class="table-responsive">
                        <table class="ui table table_cus_v">
                            <tr>
                                <th>Room Details</th>
                                <th>Dates</th>
                                <th>Services Details</th>
                                <th>Price</th>
                            </tr>

                            @foreach($booking->bookingRooms as $booked_room)
                                <tr class="row_booked_room-{{ $booked_room->id }}">
                                    <td>{{ $booked_room->roomDetails() }}</td>

                                    <td>{{ date('d-m-Y', strtotime($booked_room->check_in_date)).' to '.date('d-m-Y', strtotime($booked_room->check_out_date)) }}</td>
                                    <td>
                                        <div style="max-height: 70px!important;overflow: auto;">{!! $booked_room->serviceDetails()  !!}</div>
                                    </td>

                                    <td>{{ $booked_room->allDaysPrice(null, true, false) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if(empty($booking->getCurrentBooking()) && $booking->checkAccommodation())
            <div class="row">
                <div class="">
                    <div class="title">
                        <div class="space10"></div>
                        <div class="page_title"><h2>Accommodation Request</h2></div>
                        <div class="divider space10"></div>
                    </div>
                </div>
                <div class="">
                    <div class="table-responsive">
                        <table class="table ui">
                            <tbody>
                            <tr>
                                <th>Building</th>
                                <th>Floor</th>
                                <th>Booking Type</th>
                                <th>Check In Date</th>
                                <th>Check Out Date</th>
                                <th>Action</th>
                            </tr>

                            <tr>
                                <td>{{ $booking->building->name }}</td>
                                <td>{{ \App\Room::getFloorNumber($booking->floor_number) }}</td>
                                <td>{{ $booking->getBookingType($booking->booking_type) }}</td>
                                <td>{{ date("d-m-Y",strtotime($booking->check_in_date)) }}</td>
                                <td>{{ date("d-m-Y",strtotime($booking->check_out_date)) }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if($booking->members->count() > 0)
            <div class="row">
                <div class="">
                    <div class="title">
                        <div class="space10"></div>
                        <div class="page_title"><h2>Members</h2></div>
                        <div class="divider space10"></div>
                    </div>
                </div>
                <div class="">
                    <div class="table-responsive">
                        <table class="ui table table_cus_v">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Id Proof</th>
                                <th>Dates</th>
                                <th>Allotted Room</th>
                                <th>Total Price</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($booking->members as $member)
                                <tr>
                                    <td>{{ $member->name }}</td>
                                    <td>{{ $member->age }}</td>
                                    <td>{{ $member->getGenderOptions($member->gender) }}</td>
                                    <td>@if($member->id_proof != null) <a
                                                href="{{  \App\Settings::getDownloadUrl($member->id_proof)}}">Download</a> @else
                                            -- @endif</td>
                                    @if($member->getRoomDates())
                                        <td> {{ $member->getRoomDates() }}</td>
                                    @else
                                        <td>{{ date("d-m-Y",strtotime($booking->check_in_date)) }}
                                            - {{ date("d-m-Y",strtotime($booking->check_out_date)) }}</td>
                                    @endif

                                    <td>{!! $member->getRoomDetails()  !!}
                                        <br/>Services:<br/> {!! $member->getServiceDetails()  !!}
                                    </td>
                                    <td>Rs.{{ $member->daysPrice(null, true, false) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('script')
    <script>
        $("#print").click(function () {
            $(this).hide();
            $("#back").hide();
            window.print();
            $(this).show();
            $("#back").show();

        });
        /* $("#mySelector").printThis({
             debug: false,               /!* show the iframe for debugging*!/
             importCSS: true,            /!* import page CSS*!/
             importStyle: true,       /!* import style tags*!/
             printContainer: true,      /!* grab outer container as well as the contents of the selector*!/
             loadCSS: [
                 "{{ asset('/css/style.css') }}",
            "{{ asset('js/bootstrap.min.js') }}",
            "{{ asset('/css/font-awesome.min.css')}}",
            "{{ asset('/css/animate.css') }}",
            "{{ asset('css/jquery.steps.css') }}"],  /!* path to additional css file - use an array [] for multiple*!/
        pageTitle: "",              /!* add title to print page*!/
        removeInline: false,       /!* remove all inline styles from print elements*!/
        printDelay: 333,           /!* variable print delay; depending on complexity a higher value may be necessary*!/
        header: null,              /!* prefix to html*!/
        footer: null,              /!* postfix to html*!/
        base: false     ,           /!* preserve the BASE tag, or accept a string for the URL*!/
        formValues: true,            /!* preserve input/form values*!/
        canvas: false ,             /!* copy canvas elements (experimental)*!/
        doctypeString: ""        /!* enter a different doctype for older markup*!/
    });*/
        function PrintElem(elem) {
            Popup($('<div/>').append($(elem).clone()).html());
        }

        function Popup(data) {
            var mywindow = window.open('', 'my div', 'height=400,width=600');
            mywindow.document.write('<html><head><title>my div</title>');
            mywindow.document.write('<link href="http://122.180.254.6:8082/Kayakalp/public/css/font-awesome.min.css" rel="stylesheet" type="text/css" /><link href="http://122.180.254.6:8082/Kayakalp/public/css/bootstrap.min.css" rel="stylesheet" type="text/css" /><link href="http://122.180.254.6:8082/Kayakalp/public/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" /><link href="http://122.180.254.6:8082/Kayakalp/public/css/animate.css " rel="stylesheet" type="text/css" /><link href="http://122.180.254.6:8082/Kayakalp/public/css/style.css" rel="stylesheet" type="text/css" /><link href="http://122.180.254.6:8082/Kayakalp/public/css/jquery.steps.css" rel="stylesheet" type="text/css" />');
            mywindow.document.write('</head><body >');
            mywindow.document.write(data);
            mywindow.document.write('</body></html>');

            mywindow.print();
            //  mywindow.close();

            return true;
        }
    </script>
@endsection

