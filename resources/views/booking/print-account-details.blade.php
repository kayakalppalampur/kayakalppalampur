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
            <p>@if(!isset($print)) <a id="print" class="btn btn-primary ui button blue">Print</a> <a id="back" href="{{ !empty($back_url) ? $back_url  : ''}}">Back</a>  @endif
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
            <p class="visit-dr">Patient Account Details</p>
            <div class="table_head_lft">
                <table class="ui table table_cus_v bs">
                    <tr>
                        <th>Patient Id</th>
                        <td>{{ $booking->getProfile('kid') }}</td>
                        <th>Registration Id</th>
                        <td>{{ $booking->booking_id }}</td>
                        <th>Booking Id</th>
                        <td>{{ $booking->user->registration_id }}</td>
                    </tr>
                </table>
            </div>
            <div class="form-group">
                <div class="col-2"><label>Accommodation</label></div>
                <div class="col-10">
                    <p>{{ $booking->getAccomodationAmount(true) }}</p>
                    <div class="row">
                        <div class="table-responsive">
                            <table class="ui table table_cus_v">
                                <tr>
                                    <th>Person Name</th>
                                    <th>Is Patient</th>
                                    <th>Room Details</th>
                                    <th>Dates</th>
                                    <th>Services Details</th>
                                    <th>Price</th>
                                </tr>

                                @foreach($booking->bookingRoomsAll as $booked_room)
                                    <tr class="row_booked_room-{{ $booked_room->id }}">
                                        <td>{{ $booked_room->getName() }}</td>
                                        <td>{{ $booked_room->checkIfPatient() }}</td>
                                        <td>{{ $booked_room->roomDetails() }}</td>

                                        <td>{{ date('d-m-Y', strtotime($booked_room->check_in_date)).' to '.date('d-m-Y', strtotime($booked_room->check_out_date)) }}</td>
                                        <td>
                                            <div>{!! $booked_room->serviceDetails()  !!}</div>
                                        </td>

                                        <td>{{ $booked_room->allDaysPrice(null, true, false) }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>

                </div>
            </div>

            <div class="form-group">
                <div class="col-2"><label> Diet </label></div>
                <div class="col-10">
                    <p>{{  $booking->getDietAmount() }}</p>
                    @if($booking->diets->count() > 0)
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table ui">
                                    <tr>
                                        <th>Date</th>
                                        <th>Price</th>
                                    </tr>
                                    @foreach($booking->diets as $diet)
                                        @if(count($diet->dailyDiets) > 0)
                                            @foreach($diet->dailyDiets as $daily_diet)
                                                <tr>
                                                    <td>{{ $daily_diet->date }}</td>
                                                    <td>
                                                        @if($loop->iteration == 1)
                                                            <input type="hidden"
                                                                   id="daily_diet_id"
                                                                   value="{{  $daily_diet->id }}"> @endif
                                                        <span>{{ $daily_diet->getTotalAmount() }}</span>

                                                    </td>
                                                    <td>
                                                        @php
                                                            $daily_diet = \App\DietDailyStatus::find($daily_diet->id);
$html = "";
if ($daily_diet != null) {
$html = "<table>";
foreach (\App\DietChartItems::getTypeOptions() as $type_id => $type) {
if ($daily_diet->checkType($type_id)) {
$items = \App\DietChartItems::where([
'diet_id' => $daily_diet->diet_id,
'type_id' => $type_id
])->get();
$items_html = "";
foreach ($items as $item) {
$price = $item->item_price;
if ($type_id == \App\DietChartItems::TYPE_BREAKFAST) {
if ($daily_diet->is_breakfast == 0) {
$price = 0;
}
} elseif ($type_id == \App\DietChartItems::TYPE_LUNCH) {
if ($daily_diet->is_lunch == 0) {
$price = 0;
}
} elseif ($type_id == \App\DietChartItems::TYPE_POST_LUNCH) {
if ($daily_diet->is_post_lunch == 0) {
$price = 0;
}
} elseif ($type_id == \App\DietChartItems::TYPE_DINNER) {
if ($daily_diet->is_dinner == 0) {
$price = 0;
}
} elseif ($type_id == \App\DietChartItems::TYPE_SPECIAL) {
if ($daily_diet->is_special == 0) {
$price = 0;
}
}

$items_html .= $item->item->name . "  => " . $price . "<br/>";
}

$html .= "<tr><th>" . $type . "</th><td>" . $items_html . "</td></tr>";
}
}
$html .= "</table>";
}                                                                                                    @endphp
                                                        {!! $html !!}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div class="col-2"><label> Treatments </label></div>
                <div class="col-10 token-amount">
                    @foreach(\App\Department::all() as $department)
                        <p>{{ $department->title }}</p>
                        <p>{{  $booking->getTreatmentsAmount($department->id) }}</p>

                        @if($booking->getTreatments($department->id)->count() > 0)
                            <div class="row">
                                <div class="table-responsive">
                                    <div class="content_BoxIN">
                                        @foreach($booking->treatments as $treatment)
                                            @foreach($treatment->treatments as $pat_treatment)
                                                @if($pat_treatment->status == \App\PatientTreatment::STATUS_COMPLETED)
                                                    <p>
                                                        <span style="margin:10px ;">Treatment: {{ $pat_treatment->treatment->title }}</span><span> Date: {{ $treatment->treatment_date }}</span><span> Price: {{ $pat_treatment->treatment->price }}</span>
                                                    </p>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                        <br>
                    @endforeach
                </div>
            </div>

<div class="col-2"><label> Admission / Consultation Charges </label></div>
                                                            <div class="col-10 token-amount">
                                                            <div class="total_misc_price" >


{{  $booking->getMiscAmount() }}</div> 
                                                                

                                                                                                                  
                                                                       


                                                                    
                                                            </div>
                                                        </div>


            <div class="form-group">
                <div class="col-2"><label> Lab </label></div>
                <div class="col-10">
                    <p>{{ $booking->getLabAmount() }}</p>

                    @if($booking->labTests->count() > 0)
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table ui">
                                    <tr>
                                        <th>Date</th>
                                        <th>Tests</th>
                                        <th>Note</th>
                                        <th>Price</th>
                                    </tr>
                                    @foreach($booking->labTests as $lab_test)
                                        <tr>
                                            <td>{{ $lab_test->date }}</td>
                                            <td>{{ $lab_test->getTestsName() }}</td>
                                            <td>{{ $lab_test->note }}</td>
                                            <td>{{ $lab_test->getPrice() }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    @endif

                    {{--<div class="right-acc">
                        <button id="lab_details">See Details</button>
                    </div>--}}
                </div>
            </div>

            <div class="form-group">
                <div class="col-2"><label> Total Amount </label></div>
                <div class="col-10">
                    <p>{{ $booking->getTotalAmount(true) }}</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-2"><label> Discounts </label></div>
                <div class="col-10 token-amount">
                    <p>{{ $booking->getDiscountsAmount() }}</p>
                    <div class="right-acc">
                        @if($booking->discounts->count() > 0)
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table ui">
                                        <tr>
                                            <th>Discount code</th>
                                            <th>Discount Amount</th>
                                            <th>Discount Reason</th>
                                            <th>Dated</th>
                                            <th>Action</th>
                                        </tr>
                                        @foreach($booking->discounts as $d)
                                            <tr>
                                                <td>{{ $d->discount != null ? $d->discount->code : "" }}</td>
                                                <td>{{ $d->discount_amount }}</td>
                                                <td>{{ $d->description }}</td>
                                                <td>{{ $d->created_at }}</td>
                                                <td>
                                                    <a href="{{ url("admin/booking/delete-discount/".$d->id) }}"
                                                       id="delete-discount_{{ $d->id }}"><i
                                                                class="fa fa-trash"></i>
                                                    </a></td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-2"><label> Payable Amount </label></div>
                <div class="col-10">
                    <p>{{ $booking->getPayableAmount(true) }}</p></div>
            </div>
            <div class="form-group">
                <div class="col-2"><label> Already Paid </label></div>
                <div class="col-10">

                    <p>{{ $booking->getPaidAmount() }}</p>
                    @if($booking->paidItems->count() > 0)
                        <div class="row">
                            <div class="table-responsive">
                                @foreach($booking->paidItems as $item)
                                    <p>
                                        <span style="margin:10px ;">Paid Amount: {{ $item->amount }}</span><span>Paid Date: {{ date('d-m-Y h:i a', strtotime($item->created_at)) }}</span>
                                    </p>
                                @endforeach

                            </div>
                            @endif
                        </div>
                </div>

                @if($booking->getPaidAmount(true) > 0)
                    <div class="form-group">
                        <div class="col-2"><label> Total Refunded </label>
                        </div>
                        <div class="col-10">
                            <p>{{ $booking->getPaidAmount(true)  }}</p>
                        </div>
                    </div>
                @endif
                <div class="form-group">
                    <div class="col-2"><label> Total Due </label></div>
                    <div class="col-10">
                        <p>{{ $booking->getPendingAmount(true) }}</p></div>
                </div>

                <div class="form-group">
                    <div class="col-2"><label> Total Refundable </label>
                    </div>
                    <div class="col-10">
                        <p>{{ $booking->getRefundAmount(true)  }}</p></div>
                </div>

                <p>Thank you!</p>
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

