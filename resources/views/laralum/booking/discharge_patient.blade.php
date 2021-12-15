@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::bookings') }}">{{ trans('laralum.booking_list') }}</a>
        <i class="right angle icon divider"></i>
        @if($booking->id != "")
            <a class="section"
               href="{{ route('Laralum::booking.show', ['booking_id' => $booking->id]) }}">{{ trans('laralum.booking_details') }}</a>
            <i class="right angle icon divider"></i>
        @endif
        <div class="active section">{{  trans('laralum.discharge_patient') }}</div>
    </div>
@endsection
@section('title', 'Discharge Patient')
@section('icon', "pencil")
@section('subtitle', 'Discharge Patient')
@section('content')

    <div class="ui one column doubling stackable">

        <div class="column">
            <section class="booking_filter booking_search_patient ui padded segment">
                <div id="dis-patient" class="row">
                    <div class="search_patient_con  signup_bg">
                        {{-- <div class="patient_head2">
                             <h3 class="title_3">SEARCH PATIENT</h3>
                             <h4>(Through Anyone Option)</h4>
                         </div>--}}

                        <h3 class="title_3">DISCHARGE DAY / LEAVING</h3>

                        <div class="form-wrap">
                            <div class="search-patient-wrap" style="width:100%;">
                                <div class="head-tag-search">
                                    <p>SEARCH PATIENT</p>
                                </div>
                                <form id="bookingFilter"
                                      action="{{ route('Laralum::bookings.discharge-patient-billing-search') }}"
                                      method="POST">
                                    {{ csrf_field() }}
                                    {{-- <div class="form-group">
                                     <label>Barcode</label>
                                         <input class="user_namer form-control required" type="text" id="filter_bar_code" value="{{ @$_REQUEST['filter_bar_code'] }}" name="filter_bar_code" autofocus>
                                     </div>--}}
                                    <div class="row">
                                        <div class="col-md-4" style="width: 33%">
                                            <div class="form-group">
                                                <label>Registration ID</label>
                                                <input class="user_last form-control required" type="text"
                                                       id="filter_patient_id"
                                                       value="{{ @$_REQUEST['filter_patient_id'] }}"
                                                       name="filter_patient_id">
                                            </div>
                                            <div class="form-group">
                                                <label>UHID</label>
                                                <input class="user_last form-control required" type="text"
                                                       id="filter_uh_id" value="{{ @$_REQUEST['filter_uh_id'] }}"
                                                       name="filter_uh_id">
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="width: 33%">
                                            <div class="form-group">
                                                <label>Email ID</label>
                                                <input class="user_email form-control required" type="email"
                                                       id="filter_email" value="{{ @$_REQUEST['filter_email'] }}"
                                                       name="filter_email">
                                            </div>

                                            <div class="form-group">
                                                <label>Mobile No.</label>
                                                <input class="user_password form-control required" type="text"
                                                       name="filter_mobile" value="{{ @$_REQUEST['filter_mobile'] }}"
                                                       id="filter_mobile">
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="width: 33%">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input class="user_namee form-control required" type="text"
                                                       name="filter_name" id="filter_name"
                                                       value="{{ @$_REQUEST['filter_name'] }}">
                                            </div>
                                            <div class="form-group">
                                                <label></label>
                                            <div class="form-button_row">
                                                <button class="ui button no-disable blue">Search</button>
                                            </div>
                                            </div>
                                        </div>
                                    </div>

                                </form>
                            </div>

                            <div class="token-form-wrap">

                                <div class="token_wrapper_con">
                                    @if(isset($booking->user->name))
                                        <form class="token" method="POST" id="print-form"
                                              action="{{ url('/admin/booking/print-bill/'.$booking->id) }}">
                                            {!! csrf_field() !!}
                                            <div class="token-sec-form">
                                                <div class="form-group">
                                                    <div class="col-2"><label>Allocate token to</label></div>
                                                    <div class="col-10">
                                                        <p>{{ $booking->getProfile('first_name'). ' '.$booking->getProfile('last_name') }}</p>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-2"><label>Registration ID</label></div>
                                                    <div class="col-10"><p>{{ $booking->getProfile('kid') }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-2"><label>UH ID</label></div>
                                                <div class="col-10"><p>{{ $booking->getProfile('uhid') }}</p>
                                                </div>
                                            </div>

                                            <div class="token-sec-form acc-only">
                                                <div class="form-group">
                                                    <h2 class="label_tit">Account</h2>
                                                </div>
                                            </div>

                                            {{--                                                                                    <div class="form-group">
                                                                                        <div class="col-2"><label>Consultation Price</label></div>
                                                                                        <div class="col-10"><p>{{ \App\ConsultationCharge::getConsultFees()  }}</p></div>
                                                                                    </div>--}}
                                            <div class="form-group">
                                                <div class="col-2"><label>Consultation Charges</label></div>
                                                <div class="col-10">
                                                    <p>{{ $booking->getConsultationAmount(true, true) }}</p>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-2"><label>Accommodation</label></div>
                                                <div class="col-10">
                                                    <p>{{ $booking->getAccomodationAmount(true, true) }}</p>

                                                    {{--<div class="right-acc">
                                                        <button id="accommodation_details">See Details
                                                        </button>
                                                    </div>--}}
                                                    <div class="row">
                                                        <div class="table-responsive">
                                                            <table class="ui table table_cus_v">
                                                                <tr>
                                                                    <th>Room Details</th>
                                                                    <th>Dates</th>
                                                                    <th>Discharge Date</th>
                                                                    <th>Services Details</th>
                                                                    <th>Price</th>
                                                                </tr>

                                                                @foreach($booking->bookingRoomsAll as $booked_room)
                                                                    <tr class="row_booked_room-{{ $booked_room->id }}">
                                                                        <td>{{ $booked_room->roomDetails() }}</td>

                                                                        <td>{{ date('d-m-Y', strtotime($booked_room->check_in_date)).' to '.date('d-m-Y', strtotime($booked_room->check_out_date)) }}</td>
                                                                        <td> {{ $discharge_date > $booked_room->check_out_date ? $booked_room->check_out_date : date("d-m-Y", strtotime($discharge_date)) }} </td>
                                                                        <td>
                                                                            <div style="max-height: 70px!important;overflow: auto;">{!! $booked_room->serviceDetails()  !!}</div>
                                                                        </td>

                                                                        <td>{{ $booked_room->allDaysPrice(null, true, true) }}</td>
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
                                                                                            <input type="hidden" id="daily_diet_id" value="{{  $daily_diet->id }}"> @endif
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
                                                    {{--<div class="right-acc">
                                                        <button id="diet_details">See Details</button>
                                                    </div>--}}
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-2"><label> Treatments </label></div>
                                                <div class="col-10 token-amount">
                                                <div class="total_treatment_price" style="display: none;">{{  $booking->getTreatmentsAmount() }}</div>
                                                    @foreach(\App\Department::all() as $department)
                                                        <p>{{ $department->title }}</p>
                                                        <p>{{  $booking->getTreatmentsAmount($department->id) }}</p>

                                                        <!-- @if($booking->getTreatments($department->id)->count() > 0)
                                                            <div class="row">
                                                                <div class="table-responsive">
                                                                    <div class="content_BoxIN">
                                                                        @foreach($booking->getTreatments($department->id) as $treatment)
                                                                            @foreach($treatment->treatments as $pat_treatment)
                                                                                @if($pat_treatment->status == \App\PatientTreatment::STATUS_COMPLETED)
                                                                                    <p>
                                                                                        <span style="margin:10px ;">Treatment: {{ $pat_treatment->treatment->title }}</span><span> Date: {{ $treatment->treatment_date_date }}</span><span> Price: {{ $pat_treatment->price }}</span>
                                                                                    </p>
                                                                                @endif
                                                                            @endforeach
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif -->
                                                        
                                                        <br>
                                                    @endforeach
                                                    @if($booking->getTreatments()->count() > 0)<br>
                                                        <div class="right-acc">
                                                            <button id="treatment_details">Show Treatments</button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-2"><label> Lab </label></div>
                                                <div class="col-10">
                                                    <p>{{ $booking->getLabAmount() }}</p>

                                                     @if($booking->labTests->count() > 0)
                                                                <br>
                                                                        <div class="right-acc">
                                                                            <button id="lab_details">Show Lab Tests</button>
                                                                        </div>
                                                                    @endif

                                                    <!-- @if($booking->labTests->count() > 0)
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
                                                                            <td>{{ $lab_test->date_date }}</td>
                                                                            <td>{{ $lab_test->getTestsName() }}</td>
                                                                            <td>{{ $lab_test->note }}</td>
                                                                            <td>{{ $lab_test->getPrice(true) }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </table>
                                                            </div>
                                                        </div>
                                                    @endif -->

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
                                                                                <td>{{ date("d-m-Y", strtotime($d->created_at)) }}</td>
                                                                                <td>
                                                                                    <a class='no-disable' href="javascript:void(0);"
                                                                                       id="delete-discount_{{ $d->id }}"><i
                                                                                                class="fa fa-trash no-disable"></i>
                                                                                    </a></td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        {{--<button id="discount_details">See Details
                                                        </button>--}}

                                                        @if($booking->isEditable())
                                                            <button id="add_discount">Add Discount
                                                            </button>@endif
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

                                                            {{-- <div class="right-acc">


                                                               <button id="already_paid_details">See Details
                                                                </button>

                                                    </div>--}}
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


                                                <div class="allocate_token_con">

                                                    <button value="pay" id="pay" class="ui button no-disable">
                                                        Pay/Refund
                                                    </button>
                                                    <button id="print" class="ui button no-disable">Print the Bill
                                                    </button>
                                                    <button id="feedback-form" class="ui button no-disable">Fill
                                                        Feedback Form
                                                    </button>
                                                    <input type="hidden" name="type" id="type">
                                                    <button id="noc" name="type" value="{{ \App\Booking::PRINT_NOC }}"
                                                            class="ui button no-disable"> NOC
                                                    </button>
                                                </div>

                                            </div>
                                        </form>
                                </div>

                                @elseif($search == true )
                                    <div class="ui negative icon message">
                                        <i class="frown icon"></i>
                                        <div class="content">
                                            <div class="header">
                                                {{ $error }}
                                            </div>
                                            <p>There are currently no results</p>
                                        </div>
                                    </div>
                                @endif


                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </div>


    </div>
    <div class="modal fade" id="bookingModal" role="dialog" data-backdrop="static">
        <div class="modal-dialog" style="width:800px;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="modal-close close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Booking Wizard</h4>
                </div>
                <div class="modal-body">
                    <p>Some text in the modal.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default modal-close" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <style type="text/css" media="print">
        @page {
            size: auto;   /* auto is the initial value */
            margin: 0mm;  /* this affects the margin in the printer settings */
        }
    </style>
@endsection
@section('js')
    <script>
        $("#extra_details").click(function (e) {
            e.preventDefault();
            console.log("asdsa");
            // var pageName = $(this).attr('pageName');
            var url = '{{ url('/admin/booking/get-services-billing-details/'.$booking->id.'/'.true) }}';
            var title = "Extra Services Details";
            openModal(url, title);
        });

        $("#accommodation_details").click(function (e) {
            e.preventDefault();
            console.log("asdsa");
            // var pageName = $(this).attr('pageName');
            var url = '{{ url('/admin/booking/get-accommodation-billing-details/'.$booking->id.'/'.true) }}';
            var title = "Accommodation Details";
            openModal(url, title);
        });

        $("#already_paid_details").click(function (e) {
            e.preventDefault();
            console.log("asdsa");
            // var pageName = $(this).attr('pageName');
            var url = '{{ url('/admin/booking/get-paid-billing-details/'.$booking->id) }}';
            var title = "Already Paid";
            openModal(url, title);
        });

        $("#feedback-form").click(function (e) {
            e.preventDefault();
            console.log("asdsa");
            // var pageName = $(this).attr('pageName');
            var url = '{{ url('/admin/user/get-feedback/'.$booking->id) }}';
            var title = "Give your feedback";
            openModal(url, title);
        });

        $("#diet_details").click(function (e) {
            e.preventDefault();
            console.log("asdsa");
            // var pageName = $(this).attr('pageName');
            var url = '{{ url('/admin/user/get-diet-details/'.$booking->id) }}';
            var title = "Diet Price Chart";
            openModal(url, title);
        });

        $("#treatment_details").click(function (e) {
            e.preventDefault();
            console.log("asdsa");
            // var pageName = $(this).attr('pageName');
            var url = '{{ url('/admin/user/get-treatments-details/discharge/'.$booking->id) }}';
            var title = "Treatments Details";
            openModal(url, title);
        });

        $("#lab_details").click(function (e) {
            e.preventDefault();
            var url = '{{ url('/admin/user/get_all_lab_details/discharge/'.$booking->id) }}';
            var title = "Lab Details";
            openModal(url, title);
        });

        /*$("#lab_details").click(function (e) {
            e.preventDefault();
            console.log("asdsa");
            // var pageName = $(this).attr('pageName');
            var url = '{{ url('/admin/user/get-lab-details/'.$booking->id.'/'.true) }}';
            var title = "Lab Details";
            openModal(url, title);
        });*/


        $("#noc").click(function (e) {
           // alert('hdfgsdjsgdfjhgdsfj');
            e.preventDefault();
            var url = '{{ url('/admin/user/get-noc/'.$booking->id) }}';
            var title = "Print Noc";
            openModal(url, title);
            /*  if(confirm("Generating NOC will mark this patient as discharged.")){
             $("#type").val("{{ \App\Booking::PRINT_NOC }}");
             $("#print-form").submit();
             }*/
        });


        $("#pay").click(function (e) {
            e.preventDefault();
            var url = '{{ url('/admin/booking/pay/'.$booking->id.'/'.true) }}';
            var title = "Pay Due Amount";
            openModal(url, title);
        });

        $("#add_discount").click(function (e) {
            e.preventDefault();
            var url = '{{ url('/admin/booking/add-discount/'.$booking->id) }}';
            var title = "Add Discount";
            openModal(url, title);
        })

        $("[id^=delete-discount_]").click(function (e) {
            e.preventDefault();
            var id = $(this).attr('id').split('delete-discount_')[1];
            if (confirm("Are you sure want to  delete this discount?")) {
                $.ajax({
                    url: "{{ url("/admin/booking/delete-discount") }}" + "/" + id,
                    type: "POST",
                    data: {"_token": "{{csrf_token()}}", 'booking_id': "{{ $booking->id }}"},
                    success: function (data) {
                        if (data.success == "OK") {
                            $(".modal-body").html(data.html);
                            location.reload();
                        } else {
                            alert("Something went wrong!!! Try again later");
                        }
                    }
                });
            }
        });

        $("#discount_details").click(function (e) {
            e.preventDefault();
            // var pageName = $(this).attr('pageName');
            var url = '{{ url('/admin/booking/get-discount-details/'.$booking->id) }}';
            var title = "Discount Details";
            openModal(url, title);
        });

        function openModal(url, title) {
            $(".modal .modal-title").html(title);
            $(".modal .modal-body").html("Content loading please wait...");
            $(".modal").modal("show");
            $(".modal").modal({
                backdrop: 'static',
                keyboard: false,
            });
            $(".modal .modal-body").load(url);
        }

        $(".modal-close").click(function () {
            $(".modal").modal("hide");
        });

        $(document).ready(function () {
            var total_treatment_price = $('.total_treatment_price').html();
            setInterval(checkstatus, 2000);
        
            function checkstatus(){
                var booking_id = {{ $booking->id }};
                $.ajax({
                    url:"{{ url('/admin/user/check_treatment_status') }}"+"/"+booking_id,
                    type: 'GET', 
                    success: function (data) {
                        if(data != total_treatment_price){
                            location.reload(true);
                        }
                        else{
                            console.log('same');
                        }
                    },
                });
            };

        });

        /*$("#print").click(function (e) {
         /!*e.preventDefault();*!/
         $(".search-patient-wrap").hide();
         $(".head-sec2").hide();
         $(".content-title").hide();
         $(".page-footer").hide();
         $("#menu-div").hide();
         $(".sidebar").hide();
         $("body").removeClass("top-main-cls dimmable pushable scrolling");
         $(".booking_filter").removeClass("ui");
         $("button").hide();
         $("a").hide();

         window.print();
         $(".search-patient-wrap").show();
         $(".head-sec2").show();
         $(".content-title").show();
         $(".page-footer").show();
         $("#menu-div").show();
         $(".sidebar").show();
         $("body").addClass("top-main-cls dimmable pushable scrolling");
         $(".booking_filter").addClass("ui");
         $("button").show();
         $("a").show();
         });
         */

    </script>
@endsection



