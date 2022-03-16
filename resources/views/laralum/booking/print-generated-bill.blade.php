@extends('layouts.front.web_layout')
@section('content')
    <style type="text/css" media="print">
        @page {
            size: auto;   /* auto is the initial value */
            margin: 0mm;  /* this affects the margin in the printer 

 */
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


    <div class="detail_container" id="mySelector" style="width:1000px;max-width:1000px;" >
        <div class="receipt-for">
            <p>
                @if(!isset($print)) 
                    <a id="print" class="btn btn-primary ui button blue">Print</a> 

                   
                    <a id="back" class="btn btn-primary ui button blue" @if($back == 'bills') 
                    href="{{ url('/admin/bills') }}" @elseif($back == 'opd-token-list') href="{{ url('admin/opd-token-list')}}"  @else href="{{ url('/admin/booking/discharge-patient-billing/'.$booking->id) }}" @endif>Back</a>  
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
                                <div class="patient-card-detail" style="margin-bottom: 10px;"><label
                                            style="width:20%">No:</label> <span class="user-nm"
                                                                                  style="width:80%">{{ $bill->bill_no}}</span>
                                </div>
                            </div>
                            <div class="profile-details" style="float:right;width:40%">
                                <div class="patient-card-detail" style="margin-bottom: 10px;"><label
                                            style="width:20%">Date:</label> <span class="user-nm"
                                                                                  style="width:80%">{{ $bill->bill_date }}</span>
                                </div>
                            </div>
                            <div class="profile-details pull-right" style="width:100%">
                                <div class="age-patient-outer-row" style="margin: 0px -15px;">
                                    <div class="patient-card-detail"
                                         style="float:left;width:100%;padding:0px 15px;margin-bottom:10px;">
                                       
                                       @if($bill->opdToken)
                                       <label style="width:30%;">Received from Mr./Mrs./Ms.:</label> <span class="user-nm"
                                                                                      style="width:70%;">{{ $bill->opdToken->first_name.' '.$bill->opdToken->last_name  }}</span>
                                       @else
                                         <label style="width:30%;">Received from Mr./Mrs./Ms.:</label> <span class="user-nm"
                                                                                      style="width:70%;">{{ $bill->booking->getProfile('first_name').' '.$bill->booking->getProfile('last_name')  }}</span>
                                    @endif
                                                                                    </div>
                                </div>
                            </div>
                            <div class="profile-details" style="width:100%">
                                <div class="patient-card-detail" style="margin-bottom: 10px;"><label
                                            style="width:15%">Rupees:</label> <span class="user-nm"
                                                                                        style="width:85%">{{ App\Settings::amountInWords($bill->bill_amount)  }}</span>
                                </div>
                            </div>

                            <div>
                            <div class="add_ph_outer">
                                <div class="profile-details" style="width:100%">
                                    <div class="patient-card-detail"><label
                                                style="width:100%">On Account of:</label>

                            <div class="col-md-12">

        <div class="col-sm-6 col-md-6 col-xs-6">
        @if($bill->opd_token_id)   
        <div class="row" style="padding:10px;">
            <div class="col-sm-6 col-md-6 col-xs-6">OPD Consultation</div>
            <div class="col-sm-6 col-md-6 col-xs-6" style="border-bottom:1px solid;height: 20px;">{{  $bill->opd_consultation }}</div>
    </div>
        @endif
        <div class="row" style="padding:10px;">
            <div class="col-sm-6 col-md-6 col-xs-6">Physiotherapy</div>
            <div class="col-sm-6 col-md-6 col-xs-6" style="border-bottom:1px solid;height: 20px;">{{  $bill->physiotherapy }}</div>
        </div>

        <div class="row" style="padding:10px;">
            <div class="col-sm-6 col-md-6 col-xs-6">Naturopathy and Yoga</div>
            <div class="col-sm-6 col-md-6 col-xs-6" style="border-bottom:1px solid;height: 20px;">{{  $bill->naturopathy_and_yoga }}</div>
        </div>

        <div class="row" style="padding:10px;">
            <div class="col-sm-6 col-md-6 col-xs-6">Ayurveda</div>
            <div class="col-sm-6 col-md-6 col-xs-6" style="border-bottom:1px solid;height: 20px;">{{  $bill->ayurveda }}</div>
        </div>

        <div class="row" style="padding:10px;">
            <div class="col-sm-6 col-md-6 col-xs-6">Lab Charges</div>
            <div class="col-sm-6 col-md-6 col-xs-6" style="border-bottom:1px solid;height: 20px;">{{  $bill->lab }}</div>
        </div>
        <div class="row" style="padding:10px;">
        <div class="col-sm-6 col-md-6 col-xs-6">Room Rent</div>
            <div class="col-sm-6 col-md-6 col-xs-6" style="border-bottom:1px solid;height: 20px;">{{  $bill->room_rent }}</div>
      
    </div>

        </div>
        <div class="col-sm-6 col-md-6 col-xs-6">
            
        <div class="row" style="padding:10px;">
            <div class="col-sm-6 col-md-6 col-xs-6">Discount</div>
            <div class="col-sm-6 col-md-6 col-xs-6" style="border-bottom:1px solid;height: 20px;">{{  $bill->discount }}</div>
        </div>

        <div class="row" style="padding:10px;">
            <div class="col-sm-6 col-md-6 col-xs-6">Tax</div>
            <div class="col-sm-6 col-md-6 col-xs-6" style="border-bottom:1px solid;height: 20px;">&nbsp;</div>
        </div>

        <div class="row" style="padding:10px;">
            <div class="col-sm-6 col-md-6 col-xs-6">Tax Amount</div>
            <div class="col-sm-6 col-md-6 col-xs-6" style="border-bottom:1px solid;height: 20px;">&nbsp;</div>
        </div>

        <div class="row" style="padding:10px;">
            <div class="col-sm-6 col-md-6 col-xs-6">Package</div>
            <div class="col-sm-6 col-md-6 col-xs-6" style="border-bottom:1px solid;height: 20px;">&nbsp;</div>
        </div>
        <div class="row" style="padding:10px;">
        <div class="col-sm-6 col-md-6 col-xs-6">Admission / Consultation Charges</div>
               <div class="col-sm-6 col-md-6 col-xs-6" style="border-bottom:1px solid;height: 20px;">{{ $bill->misc }}</div>
        
    </div>      
    </div>
        
    </div>

                            </div>

                            <div class="add_ph_outer">
                                <div class="profile-details" style="width:100%">
                                   
                                    <div class="profile-details" style="width:100%">
                                        <div class="patient-card-detail"><label
                                                    style="width:35%">By Cash/Bakers Cheque/D.D. NO</label> <span
                                                    class="user-nm"
                                                    style="width:45%">&nbsp;</span>
                                            <label
                                                    style="width:8%">Dated</label> <span
                                                    class="user-nm"
                                                    style="width:12%">&nbsp;</span>
                                        </div>
                                    </div>

                                    <div class="profile-details" style="width:100%;padding-top:40px;">
                                        <div class="patient-card-detail"><label
                                                    style="width:50%;">
                                                <span style="padding: 15px;border: 1px solid;width: 50%;"><b>Rs.{{ $bill->bill_amount  }}/-</b></span></label>
                                            <label
                                                    style="width:50%;text-align: right;margin-top:20px;">Accountant/Cashier</label>
                                        </div>
                                    </div>
                                    <div class="profile-details" style="width:35%">
                                        <div class="patient-card-detail"><label
                                                    style="width:50%">Remaining Payable</label> <span
                                                    class="user-nm"
                                                    style="width:45%">{{ $bill->remaining_amount }}</span>
                                        </div>

                                        <div class="patient-card-detail"><label
                                                    style="width:25%">Advance </label> <span
                                                    style="width:35%">{{ $bill->advance_amount }}</span>
                                         <span style="width:40%">Cheque/DD No</span>
                                        </div>

                                        <div class="patient-card-detail"><label
                                                   style="width:50%">Total Refundable </label> <span
                                                    style="width:45%">{{ $bill->refundable_amount }}</span>
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

