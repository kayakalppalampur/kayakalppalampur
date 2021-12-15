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
    <div class="ui one column doubling stackable grid container">

        <div class="column">

        </div>
    </div>


    <div class="detail_container" id="mySelector" style="width:1000px;max-width:1000px;">
        <div class="receipt-for">
            <p>
                @if(!isset($print)) 
                    <a id="print" class="btn btn-primary ui button blue">Print</a> 
                    <a id="back" class="btn btn-primary ui button blue" 
                    @if($page == 'account')
                    href="{{ url('/admin/ipd-bookings/account/'.$booking->id) }}" @else href="{{ url('/admin/booking/discharge-patient-billing/'.$booking->id) }}" @endif>Back</a>  
                @endif
            </p>
        </div>

        <div class="token-detail-box" id="">
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

            <div class="form-wrap">

                <div class="patient_form_wrap patient-card-only">
                    <form id="print-kid">
                        {!! csrf_field() !!}
                        <div class="patient_form_wrap  patient_outer">
                            <div class="profile-details" style="float:left;width:40%">
                                <div class="patient-card-detail" style="margin-bottom: 10px;"><label style="width:20%">Date:</label> 
                                    <span class="user-nm" style="width:80%">
                                        {{ date('d-m-Y')  }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="profile-details pull-right" style="width:100%">
                                <div class="age-patient-outer-row" style="margin: 0px -15px;">
                                    <div class="patient-card-detail" style="float:left;width:100%;padding:0px 15px;margin-bottom:10px;">
                                            <label style="width:30%;">Patient Name:</label> <span class="user-nm" style="width:70%;">
                                                {{ $booking->getProfile('first_name') }} {{ $booking->getProfile('last_name')  }}
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-details" style="float:left;width:25%">
                                <div class="patient-card-detail" style="margin-bottom: 10px;"><label style="width:20%">Age:</label> 
                                    <span class="user-nm" style="width:80%">
                                        {{ $booking->getProfile('age') }}
                                    </span>
                                </div>
                            </div>
                            <div class="profile-details" style="float:left;width:25%">
                                <div class="patient-card-detail" style="margin-bottom: 10px;"><label style="width:20%">UHID:</label> 
                                    <span class="user-nm" style="width:80%">
                                        {{ $booking->getProfile('uhid') }}
                                    </span>
                                </div>
                            </div>
                            <div class="profile-details" style="float:left;width:50%">
                                <div class="patient-card-detail" style="margin-bottom: 10px;"><label style="width:20%">Registration Id:</label> 
                                    <span class="user-nm" style="width:80%">
                                        {{ $booking->getProfile('kid') }}
                                    </span>
                                </div>
                            </div>

                            <div class="add_ph_outer">
                                <div class="profile-details" style="width:100%">
                                    <div class="patient-card-detail">
                                        <label style="width:100%">Lab Test Details:</label>
                                        <table class="table ui">
                                            <tr>
                                                <th>Sr. No</th>
                                                <th>Date</th>
                                                <th>Tests</th>
                                                <th>Note</th>
                                                <th>Price</th>
                                            </tr>
                                            @php $i = 1; @endphp
                                            @foreach($all_lab_tests as $lab_test)
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>{{ $lab_test->date_date }}</td>
                                                    <td>{{ isset($lab_test->test->name) ? $lab_test->test->name : "" }}</td>
                                                    <td>{{ $lab_test->note }}</td>
                                                    <td>{{ $lab_test->price }}</td>
                                                </tr>
                                            @php $i++; @endphp
                                            @endforeach
                                        </table>
                                        
                                    </div>
                                   
                                    <div class="profile-details" style="width:100%;padding-top:40px;">
                                        <div class="patient-card-detail"><label
                                                    style="width:50%;">
                                                <span style="padding: 15px;border: 1px solid;width: 50%;"><b>Rs.{{ $booking->getLabAmount() }}/-</b></span></label>
                                         
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="profile-details" style="width:100%; margin-top: 25px;">
                            </div>

                        </div>

                    </form>

                </div>

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

