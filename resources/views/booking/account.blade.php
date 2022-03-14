@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
       <a class="section" href="{{ url('user/bookings') }}">{{ trans('laralum.booking_list') }}</a>

        <i class="right angle icon divider"></i>
        <a class="section"
           href="{{ url('booking/'.$booking->id.'/booking-detail') }}">{{ trans('laralum.booking_details') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Booking's Account</div>
    </div>
@endsection
@section('title', 'Booking Account')
@section('icon', "pencil")
@section('subtitle', 'Booking Account')

@section('content')

    <div class="ui one column doubling stackable grid container">

        <div class="column">
            <div class="page_title">
                <h2 class="pull-left">Basic Details</h2>

                <!--div class="pull-right">
                    <a class="btn btn-primary ui button blue no-disable"
                       href="{{ url('bookings/'.$booking->id.'/print-account') }}">Print</a>

                </div-->


            </div>
            <section class="booking_filter booking_search_patient ui padded segment">
                <div class="row">
                    <div class="col-md-12">
                        <div class="about_sec white_bg signup_bg">
                            {{-- <div class="patient_head2">
                                 <h3 class="title_3">SEARCH PATIENT</h3>
                                 <h4>(Through Anyone Option)</h4>
                             </div>--}}
                            <div class="head-sec2">
                                <h3>Patient Account</h3>
                            </div>
                            <div class="form-wrap">

                                @if($booking->isEditable() && Laralum::loggedInUser()->hasPermission('generate.token'))
                                    {{-- <div class="search-patient-wrap">
                                         <div class="head-tag-search">
                                             <p>SEARCH PATIENT</p>
                                         </div>
                                         @if($booking->isEditable())
                                         <form id="bookingFilter"
                                               action="{{ route('Laralum::bookings.discharge-patient-billing-search') }}"
                                               method="POST">
                                             {{ csrf_field() }}
                                             @else
                                                 <form id="bookingFilter"
                                                       action="{{ route('Laralum::bookings.archived-patient-search', ['view' => 'account']) }}"
                                                       method="POST">
                                                     {{ csrf_field() }}
                                             @endif
                                             --}}{{-- <div class="form-group">
                                              <label>Barcode</label>
                                                  <input class="user_namer form-control required" type="text" id="filter_bar_code" value="{{ @$_REQUEST['filter_bar_code'] }}" name="filter_bar_code" autofocus>
                                              </div>--}}{{--
                                             <div class="form-group">
                                                 <label>Patient ID</label>
                                                 <input class="user_last form-control required" type="text"
                                                        id="filter_patient_id" value="{{ @$_REQUEST['filter_patient_id'] }}"
                                                        name="filter_patient_id">
                                             </div>
                                             <div class="form-group">
                                                 <label>Mobile No.</label>
                                                 <input class="user_email form-control required" type="email"
                                                        id="filter_email" value="{{ @$_REQUEST['filter_email'] }}"
                                                        name="filter_email">
                                             </div>
                                             <div class="form-group">
                                                 <label>Email ID</label>
                                                 <input class="user_password form-control required" type="text"
                                                        name="filter_mobile" value="{{ @$_REQUEST['filter_mobile'] }}"
                                                        id="filter_mobile">
                                             </div>
                                             <div class="form-group">
                                                 <label>Name</label>
                                                 <input class="user_namee form-control required" type="text"
                                                        name="filter_name" id="filter_name"
                                                        value="{{ @$_REQUEST['filter_name'] }}">
                                             </div>
                                             <div class="form-button_row">
                                                 <button class="ui button no-disable blue">Search</button>
                                             </div>
                                         </form>
                                     </div>--}}
                                @endif
                                <div class="token-form-wrap">
                                    @if(isset($booking))
                                        @if($booking->isEditable())
                                            <form class="token" method="POST" id="print-form"
                                                  action="{{ url('/admin/booking/print-bill/'.$booking->id) }}">
                                                {!! csrf_field() !!}
                                                @else
                                                    <form class="token">
                                                        @endif
                                                        <div class="token-sec-form">
                                                            <div class="form-group">
                                                                <div class="col-2"><label>Patient's name</label></div>
                                                                <div class="col-10">
                                                                    <p>{{ $booking->getProfile('first_name').' '.$booking->getProfile('last_name') }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="col-2"><label>Booking ID</label></div>
                                                                <div class="col-10">
                                                                    <p>{{ $booking->booking_id }}</p></div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-2"><label>UHID</label></div>
                                                                <div class="col-10">
                                                                    <p>{{ $booking->getProfile('uhid') }}</p></div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-2"><label>Registration ID</label></div>
                                                                <div class="col-10">
                                                                    <p>{{ $booking->getProfile('kid') }}</p></div>
                                                            </div>
                                                        </div>

                                                        <div class="token-sec-form acc-only">
                                                            <div class="form-group">
                                                                <div class="col-2"><label>Account</label></div>

                                                            </div>
                                                        </div>


                                                        <div class="form-group">
                                                            <div class="col-2"><label>Consultation Charges</label></div>
                                                            <div class="col-10">
                                                                <p>{{ $booking->getConsultationAmount(true, true) }}</p>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="col-2"><label>Accommodation</label></div>
                                                            <div class="col-10">
                                                                <p>{{ $booking->getAccomodationAmount(false, true) }}</p>

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
                                                                                <th>Services Details</th>
                                                                                <th>Price</th>
                                                                            </tr>

                                                                            @foreach($booking->bookingRoomsAll as $booked_room)
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
                                                                                                        <input type="hidden" id="daily_diet_id" value="{{ $daily_diet->id }}"> @endif
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
                                                                    <p>{{ $booking->getTreatmentsAmount($department->id) }}</p>
                                                                    <br>

                                                                @endforeach

                                                                @if($booking->getTreatments()->count() > 0)
                                                                <br>
                                                                        <div class="right-acc">
                                                                            <button id="treatment_details">Show Treatments</button>
                                                                        </div>
                                                                    @endif

                                                                <!-- @if($booking->getTreatments($department->id)->count() > 0)
                                                                    <div class="right-acc">
                                                                        <button id="treatment_details">See Details</button>
                                                                    </div>
                                                                @endif -->
                                                                    
                                                            </div>
                                                        </div>

<div class="form-group">
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
                                                                                        <td>{{ $lab_test->getPrice() }}</td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                @endif -->

                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="col-2"><label> Total Amount </label></div>
                                                            <div class="col-10">
                                                                <p>{{ $booking->getTotalAmount() }}</p>
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

                                                                    {{--<button id="discount_details">See Details
                                                                    </button>--}}

                                                                    @if($booking->isEditable() && Laralum::loggedInUser()->hasPermission('generate.token'))
                                                                        <button id="add_discount">Add Discount
                                                                        </button>@endif
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="col-2"><label> Payable Amount </label></div>
                                                            <div class="col-10">
                                                                <p>{{ $booking->getPayableAmount() }}</p></div>
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
                                                                    <p>{{ $booking->getPendingAmount() }}</p></div>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="col-2"><label> Total Refundable </label>
                                                                </div>
                                                                <div class="col-10">
                                                                    <p>{{ $booking->getRefundAmount()  }}</p></div>
                                                            </div>
                                                            @if($booking->isEditable() && Laralum::loggedInUser()->hasPermission('generate.token'))
                                                                <div class="form-button_row">
                                                                    <button value="pay" id="pay"
                                                                            class="ui button no-disable blue">
                                                                        Pay/Refund
                                                                    </button>
                                                                </div>

                                                                @if(\Route::getCurrentRoute()->getPath() == "admin/booking/discharge-patient-billing/".$booking->id)

                                                                    <div class="form-button_row">
                                                                        <button id="print"
                                                                                class="ui button no-disable blue">Print
                                                                            the
                                                                            Bill
                                                                        </button>
                                                                    </div>
                                                                    <div class="form-button_row">
                                                                        <button id="feedback-form"
                                                                                class="ui button no-disable blue">
                                                                            Fill Feedback Form
                                                                        </button>
                                                                    </div>
                                                                    <input type="hidden" name="type" id="type">
                                                                    <div class="form-button_row pull-right">
                                                                        <button id="noc" name="type"
                                                                                value="{{ \App\Booking::PRINT_NOC }}"
                                                                                class="ui button no-disable blue">NOC
                                                                        </button>
                                                                    </div>
                                                        {{-- @else
                                                         <div class="form-button_row">
                                                             <a href="{{ url('admin/booking/discharge-patient-billing/'.$booking->id) }}" class="ui button no-disable blue">Discharge Patient
                                                             </a>
                                                         </div>--}}
                                                        @endif
                                                    </form>
                                                @endif
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
                </div>
            </section>
        </div>


    </div>
    <div class="modal fade" id="bookingModal" role="dialog" data-backdrop="static">
        <div class="modal-dialog">
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
            var url = '{{ url('/admin/booking/get-services-billing-details/'.$booking->id) }}';
            var title = "Extra Services Details";
            openModal(url, title);
        });

        $("#accommodation_details").click(function (e) {
            e.preventDefault();
            console.log("asdsa");
            // var pageName = $(this).attr('pageName');
            var url = '{{ url('/admin/booking/get-accommodation-billing-details/'.$booking->id) }}';
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
            var url = '{{ url('/admin/user/get-feedback/'.$user->id) }}';
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
        $("#discount_details").click(function (e) {
            e.preventDefault();
            // var pageName = $(this).attr('pageName');
            var url = '{{ url('/admin/booking/get-discount-details/'.$booking->id) }}';
            var title = "Discount Details";
            openModal(url, title);
        });


        /*$("#treatment_details").click(function (e) {
            e.preventDefault();
            console.log("asdsa");
            // var pageName = $(this).attr('pageName');
            var url = '{{ url('/admin/user/get-treatments-details/'.$booking->id) }}';
            var title = "Treatments Details";
            openModal(url, title);
        });*/

        $("#add_discount").click(function (e) {
            e.preventDefault();
            var url = '{{ url('/admin/booking/add-discount/'.$booking->id) }}';
            var title = "Add Discount";
            openModal(url, title);
        })

        $("#noc").click(function (e) {
            e.preventDefault();
            var url = '{{ url('/admin/user/get-noc/'.$user->id) }}';
            var title = "Print Noc";
            openModal(url, title);
            /*  if(confirm("Generating NOC will mark this patient as discharged.")){
             $("#type").val("{{ \App\Booking::PRINT_NOC }}");
             $("#print-form").submit();
             }*/
        });

        $("#pay").click(function (e) {
            e.preventDefault();
            var url = '{{ url('/admin/booking/pay/'.$booking->id) }}';
            var title = "Pay Due Amount";
            openModal(url, title);
        });

        $("#treatment_details").click(function (e) {
            e.preventDefault();
            console.log("asdsa");
            // var pageName = $(this).attr('pageName');
            var url = '{{ url('/admin/user/get-treatments-details/account/'.$booking->id) }}';
            var title = "Treatments Details";
            openModal(url, title);
        });

        $("#lab_details").click(function (e) {
            e.preventDefault();
            var url = '{{ url('/admin/user/get_all_lab_details/account/'.$booking->id) }}';
            var title = "Lab Details";
            openModal(url, title);
        });

       /* $("#lab_details").click(function (e) {
            e.preventDefault();
            console.log("asdsa");
            // var pageName = $(this).attr('pageName');
            var url = '{{ url('/admin/user/get-lab-details/'.$booking->id) }}';
            var title = "Lab Details";
            openModal(url, title);
        });*/

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



