@if(isset($services ))
    <div class="booking_info_page">
        <div class="content_Box">
            <div class="content_BoxIN">
                @if(!empty($booking))
                    <table class="table ui">
                        <tr>
                            <th>Person Name</th>
                            <th>Is Patient</th>
                            <th>Services</th>
                        </tr>
                        @foreach($booking->bookingRooms as $d)
                            <tr>
                                <td>{{ $d->getName() }}</td>
                                <td>{{ $d->checkIfPatient() }}</td>

                                @if (!empty($d->userServices))
                                    <td>
                                        @foreach($d->userServices as $service)

                                            <p>Service Name: {{ $service->service->name }}</p>

                                            <p>Service Start date: {{ date("d-m-Y", strtotime($service->service_start_date)) }}</p>
                                            <p>Service End
                                                Date: {{ $service->service_end_date > date("Y-m-d") ? "Till Date" : date("d-m-Y", strtotime($service->service_end_date)) }}</p>

                                            <p>Service
                                                Price: {{ $discharge == true ? $service->daysPrice() : $service->daysPrice(true) }}
                                                ({{ $service->price }}/day)</p>

                                            <br/>
                                        @endforeach
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </table>
                @endif
            </div>
        </div>
    </div>
@elseif(isset($discount))
    <div class="booking_info_page">
        <div class="content_Box">
            <div class="content_BoxIN">
                @if(!empty($booking))
                @php
                    if($discharge === true) {
                        $dicountsList = $booking->getDiscountsWithoutBill();
                    }else{
                        $dicountsList = $booking->discounts;
                    }
                @endphp
                    <table class="table ui">
                        <tr>
                            <th>Discount code</th>
                            <th>Discount Amount</th>
                            <th>Discount Reason</th>
                            <th>Dated</th>
                            <th>Action</th>
                        </tr>
                        @foreach($dicountsList as $d)
                            <tr>
                                <td>{{ $d->discount != null ? $d->discount->code : "" }}</td>
                                <td>{{ $d->discount_amount }}</td>
                                <td>{{ $d->description }}</td>
                                <td>{{ date("d-m-Y h:i a", strtotime($d->created_at)) }}</td>
                                <td><a id="delete-discount_{{ $d->id }}"><i class="fa fa-trash"></i> </a></td>
                            </tr>
                        @endforeach
                    </table>
                @endif
            </div>
        </div>
    </div>
@elseif(isset($addDiscount))
    <div class="booking_info_page">
        <div class="content_Box">
            <div class="content_BoxIN">
                <form method="POST" id="discount-form"
                      action="{{ url('/admin/booking/avail-discount/'.$booking->id) }}">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <input type="text" class="form-control" id="discount-code" value=""
                               placeHolder="Have Discount Code?" style="width:50%" name="code"/>
                        <b>Or</b>
                        <div class="clearfix"></div>
                        <input type="text" class="form-control" style="width:25%!important;" id="discount-flat" value=""
                               placeHolder="Flat Discount?" name="discount_flat"/>
                        <b>Or</b>
                        <div class="clearfix"></div>
                        <input type="text" class="form-control" style="width:25%!important;" id="discount-perc" value=""
                               placeHolder="Discount Percentage?" name="discount_perc"/>
                        <div class="clearfix"></div>
                        <br/>

                        <textarea cols="41" class="form-control" name="description" placeholder="Comments"></textarea>
                        <p>Total Amount: <span>{{ $booking->getPendingAmount(true) }}</span></p>
                        <p>Discount: <span id="discount_amount"></span></p>
                        <p>Discounted Amount: <span id="discounted_amount">{{ $booking->getPendingAmount(true) }}</span>
                        </p>
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}"/>
                        <input type="hidden" name="price" value="{{ $booking->getPendingAmount(true) }}">
                    </div>
                    <div class="form-button_row">
                        <button id="avail-discount" name="type" class="ui button no-disable blue">Submit</button>
                    </div>
                </form>


            </div>
        </div>
    </div>
@elseif(isset($pay))
    <div class="booking_info_page">
        <div class="content_Box">
            <div class="content_BoxIN">
                <form method="POST" id="pay-form" action="{{ url('/admin/booking/pay/'.$booking->id) }}">
                    {!! csrf_field() !!}
                    <p>Payable Amount: {{ $booking->getPendingAmountWithoutBill($discharge) }}</p>
                    <p>Refund Amount: {{ $booking->getRefundAmountWithoutBill($discharge) }}</p>

                    <input type="hidden" id="amount_paid" value="{{ $booking->getRefundAmountWithoutBill($discharge) }}"/>
                    <div class="form-group">
                        <input type="text" class="form-control" id="amount" value="" placeHolder="Amount to be paid"
                               max="{{ $booking->getPendingAmountWithoutBill($discharge) }}" style="width:50%" name="amount"/>
                        <br/>
                        <textarea cols="41" class="form-control" name="description" placeholder="Comments"></textarea>
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <div class="form-group">
                            <p>PAYMENT OPTIONS {{--(CCAVENUE or Better Payment Gateway)--}} </p>
                            <p>
                                <input type="radio" disabled name="payment_method"
                                       value="{{ \App\Transaction::PAYMENT_METHOD_CREDIT }}"/>Credit Card
                            </p>
                            <p>
                                <input type="radio" disabled name="payment_method"
                                       value="{{ \App\Transaction::PAYMENT_METHOD_DEBIT }}"/>Debit Card
                            </p>
                            <p>
                                <input type="radio" value="{{ \App\Transaction::PAYMENT_METHOD_NET_BANKING }}"
                                       name="payment_method" disabled/>Net Banking
                            </p>
                            <p>
                                <input value="{{ \App\Transaction::PAYMENT_METHOD_MOBILE_PAYMENTS }}" type="radio"
                                       disabled name="payment_method"/>Mobile Payments
                            </p>
                            <p>
                                <input type="radio" value="{{ \App\Transaction::PAYMENT_METHOD_WALLET }}"
                                       name="payment_method" checked/>Cash
                            </p>
                        </div>
                        <div class="form-group">
                            <p> PAY/REFUND </p>
                            <p id="payment-type">
                                <input type="radio" checked name="type" class="payment-type"
                                       value="{{ \App\Wallet::TYPE_PAID }}"/>PAY
                                <input type="radio" checked name="type" class="payment-type"
                                       value="{{ \App\Wallet::TYPE_REFUND }}"/>REFUND
                            </p>
                        </div>
                        {{--   <input type="hidden" name="type" value="{{ \App\Wallet::TYPE_PAID  }}"/>--}}
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}"/>
                    </div>
                    <div class="form-button_row">
                        <button id="pay-due-btn" name="type" class="ui button no-disable blue">Submit</button>
                    </div>
                </form>


            </div>
        </div>
    </div>
@elseif(isset($items))
    <div class="booking_info_page">
        <div class="content_Box">
            <div class="content_BoxIN">
                @if(!empty($items))
                    <p>Patient
                        Name: {{ $booking->getProfile('first_name') }} {{ $booking->getProfile('last_name')  }}</p>
                    @foreach($items as $item)
                        <p>
                            <span style="margin:10px ;">Paid Amount: {{ $item->amount }}</span><span>Paid Date: {{ date('d-m-Y h:i a', strtotime($item->created_at)) }}</span>
                        </p>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@elseif(isset($treatments))
    <!-- <div class="booking_info_page">
        <div class="content_Box">
            <div class="content_BoxIN">
                @if(!empty($treatments))

                    <p>Patient
                        Name: {{ $booking->getProfile('first_name') }} {{ $booking->getProfile('last_name')  }}</p>
                    @foreach($treatments as $treatment)
                        @foreach($treatment->treatments as $pat_treatment)
                            @if($pat_treatment->status == \App\PatientTreatment::STATUS_COMPLETED)
                                <p>
                                    <span style="margin:10px ;">Treatment: {{ $pat_treatment->treatment->title }}</span><span> Date: {{ $treatment->treatment_date_date }}</span><span> Price: {{ $pat_treatment->treatment->price }}</span>
                                </p>
                            @endif
                        @endforeach
                    @endforeach
                @endif
            </div>
        </div>
    </div> -->
    <div class="booking_info_page">
        <div class="content_Box">
            <div class="content_BoxIN">
                @if(!empty($treatments))

                   <div class="lab_header">
                        <span class="treat_name"><b> Name: </b> {{ $booking->getProfile('first_name') }} {{ $booking->getProfile('last_name')  }}  </span>
                        <span class="treat_age"> <b> Age: </b> {{ $booking->getProfile('age') }}  </span>
                        <span class="treat_uhid"> <b> UHID: </b> {{ $booking->getProfile('uhid') }}  </span>
                        <span class="treat_kid"> <b> Registration Id: </b> {{ $booking->getProfile('kid') }} </span> 
                        <span class="treat_price" ><b> Total Price: </b> {{ $booking->getTreatmentsAmount() }}
                    </div>
                    <br>
                    <!-- @foreach($treatments as $treatment)
                        @foreach($treatment->treatments as $pat_treatment)
                            @if($pat_treatment->status == \App\PatientTreatment::STATUS_COMPLETED)
                                <p>
                                    <span style="margin:10px ;">Treatment: {{ $pat_treatment->treatment->title }}</span><span> Date: {{ $treatment->treatment_date_date }}</span><span> Price: {{ $pat_treatment->treatment->price }}</span>
                                </p>
                            @endif
                        @endforeach
                    @endforeach -->
                    <table class="table ui">
                        <tr>
                            <th>Sr. No.</th>
                            <th>Date</th>
                            <th>Department</th>
                            <th>Treatments</th>
                            <th>Charge</th>
                            <th>Note</th>
                        </tr>
                        @php $i = 1; @endphp
                        
                        @foreach($treatments as $treatment)
                            @foreach($treatment->treatments as $pat_treatment)
                                @if($pat_treatment->status == \App\PatientTreatment::STATUS_COMPLETED)
                                    <tr>
                                        <td>{{ $i }}</th>
                                        <td>{{ $treatment->treatment_date_date }}</td>
                                        <td>{{ $treatment->department->title }}</td>
                                        <td>{{ $pat_treatment->treatment->title }}</td>
                                        <td>{{ $pat_treatment->treatment->price }}</td>
                                        <td>{{ $pat_treatment->not_attended_reason }}</td>
                                    </tr>
                                @endif
                                @if($pat_treatment->status == \App\PatientTreatment::STATUS_PENDING)
                                    <tr style="color: red !important">
                                        <td style="color: red !important">{{ $i }}</td>
                                        <td style="color: red !important">{{ $treatment->treatment_date_date }}</td>
                                        <td style="color: red !important">{{ $treatment->department->title }}</td>
                                        <td style="color: red !important">{{ $pat_treatment->treatment->title }}</td>
                                        <td style="color: red !important">-{{ $pat_treatment->treatment->price }}</td>
                                        <td style="color: red !important">{{ $pat_treatment->not_attended_reason }}</td>
                                    </tr>
                                @endif
                                @php $i++; @endphp
                            @endforeach  
                        @endforeach
                    </table>
                    <br>
                    <div class="right-acc">
                       <a class="btn btn-primary ui button blue no-disable"
                       href="{{ url('admin/user/treatmemt_detail_print/'.$page.'/'.$booking->id) }}">Print</a>
                    </div>
                    <!-- <p></p> -->
                @endif
            </div>
        </div>
    </div>
@elseif(isset($lab_tests))
    <div class="booking_info_page">
        <div class="content_Box">
            <div class="content_BoxIN">
                @if(!empty($lab_tests))

                    <p>Patient
                        Name: {{ $booking->getProfile('first_name') }} {{ $booking->getProfile('last_name')  }}</p>

                    <table>
                        <tr>
                            <th>Lab Name</th>
                            <th>Date</th>
                            <th>Address</th>
                            <th>Tests</th>
                            <th>Note</th>
                            <th>Price</th>
                        </tr>
                        @foreach($lab_tests as $lab_test)
                            <tr>
                                <td> {{ $lab_test->lab_name }}</td>
                                <td>{{ $lab_test->date_date }}</td>
                                <td>{{ $lab_test->address }}</td>
                                <td>{{ isset($lab_test->test->name) ? $lab_test->test->name : "" }}</td>
                                <td>{{ $lab_test->note }}</td>
                                <td>{{ $discharge == true  ? $lab_test->getPrice(true) :  $lab_test->getPrice() }}</td>
                            </tr>
                        @endforeach
                    </table>
                @endif
            </div>
        </div>
    </div>
@elseif(isset($all_lab_tests))
    <div class="booking_info_page">
        <div class="content_Box">
            <div class="content_BoxIN">
                @if(!empty($all_lab_tests))

                    <div class="lab_header">
                        <span class="treat_name"><b> Patient Name: </b> 
                            {{ $booking->getProfile('first_name') }} {{ $booking->getProfile('last_name')  }} 
                        </span>
                        <span class="treat_age"> <b> Age: </b> {{ $booking->getProfile('age') }}  </span>
                        <span class="treat_uhid"> <b> UHID: </b> {{ $booking->getProfile('uhid') }}  </span>
                        <span class="treat_kid"> <b> Registration Id: </b> {{ $booking->getProfile('kid') }} </span>
                        <span class="treat_price"><b>  Total Price:  </b>
                           {{ $booking->getLabAmount() }}
                        </span>
                   </div>

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
                    <br>
                    <div class="right-acc">
                       <a class="btn btn-primary ui button blue no-disable"
                       href="{{ url('admin/user/lab_detail_print/'.$page.'/'.$booking->id) }}">Print</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@elseif(isset($noc))
    <div class="booking_info_page noc-pop">
        <div class="content_Box">
            <div class="content_BoxIN">
                <div class="noc_wrap">
                    @if($error)
                        <h4> You have not filled the feedback form and this is the last step of discharge process, so
                            this will mark this patient as discharged permanently.</h4>

                    @else
                        <h4> Generating NOC will mark this patient as discharged.</h4>
                    @endif
                    <form method="POST" id="print-form" action="{{ url('/admin/booking/print-bill/'.$booking->id) }}">
                        {!! csrf_field() !!}
                        <input type="hidden" id="type" value="{{ \App\Booking::PRINT_NOC }}"/>
                        <div class="form-button_row">
                            <button id="noc" name="type" value="{{ \App\Booking::PRINT_NOC }}"
                                    class="ui button no-disable blue">
                                Print NOC
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@elseif(isset($diets))
    <div class="booking_info_page">
        <div class="content_Box">
            <div class="content_BoxIN">
                @if(!empty($diets))
                    <div class="diet-wrap">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="over-scroll">
                                    <table class="table ui">
                                        <tr>
                                            <th>Date</th>
                                            <th>Price</th>
                                        </tr>
                                        @foreach($diets as $diet)
                                            @if(count($diet->dailyDiets) > 0)
                                                @foreach($diet->dailyDiets as $daily_diet)
                                                    <tr>
                                                        <td>{{ $daily_diet->date }}</td>
                                                        <td>
                                                            @if($loop->iteration == 1)
                                                                <input type="hidden" id="daily_diet_id"
                                                                       value="{{  $daily_diet->id }}"> @endif
                                                            <span>{{ $daily_diet->getTotalAmount() }}</span>

                                                        </td>
                                                        <td>
                                                            <button id="daily_diet_details_{{ $daily_diet->id }}">View
                                                                Details
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-3" id="div_daily_diet_details">
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@elseif(isset($bed_booking))
    <div class="booking_info_page">
        <div class="content_Box">
            <div class="content_BoxIN">
                @if(!empty($info))
                    <table class="table ui">
                        <tr>
                            <th>Person Name</th>
                            <th>Is Patient</th>
                            <th>Booking Id</th>
                            <th>Patient Id</th>
                            <th>Building Name</th>
                            <th>Room No</th>
                            <th>Booking Type</th>
                            <th>Staying dates</th>
                            <th>Price</th>
                        </tr>
                        <tr>
                            <td>{{ $info->getName() }}</td>
                            <td>{{ $info->checkIfPatient() }}</td>
                            <td>{{ $info->booking->booking_id }}</td>
                            <td>{{ $info->booking->userProfile->kid }}</td>
                            <td>{{ $info->room->building->name }}</td>
                            <td>{{ $info->getRoomNumber() }}</td>
                            <td>{{ $info->getBookingType($info->type) }}</td>
                            <td>{{ date("d-m-Y", strtotime($info->check_in_date)) }}
                                to {{  date("d-m-Y", strtotime($info->check_out_date)) }} {{-- > date("Y-m-d") ? "Till date" : date("d-m-Y", strtotime($info->check_out_date)) }}--}}</td>
                            <td>{{ $info->allDaysPrice($info->room_id, false) }}</td>
                        </tr>
                    </table>
                @endif
            </div>
        </div>
    </div>
@elseif(isset($misc))
    <div class="booking_info_page">
    <div class="booking_info_page">
        <div class="content_Box">
            <div class="content_BoxIN">
                @if(!empty($booking_rooms))
                    <table class="table ui">
                        <tr>
                            <th>Person Name</th>
                            <th>Is Patient</th>
                            <th>Booking Id</th>
                            <th>Building Name</th>
                            <th>Room No</th>
                            <th>Booking Type</th>
                            <th>Staying dates</th>
                            <!-- <th>Price</th> -->
                        </tr>
                        @foreach($booking_rooms as $d)
                            <tr>
                                <td>{{ $d->getName() }}</td>
                                <td>{{ $d->checkIfPatient() }}</td>
                                <td>{{ $d->booking->booking_id }}</td>
                                <td>{{ $d->room->building->name }}</td>
                                <td>{{ $d->getRoomNumber() }}</td>
                                <td>{{ $d->getBookingType($d->type) }}</td>
                                <td>{{ date("d-m-Y", strtotime($d->check_in_date)) }}
                                    to {{ $discharge == true ?  date("Y-m-d") : date("d-m-Y", strtotime($d->check_out_date)) }} {{--> date("Y-m-d") ? "Till date" : date("d-m-Y", strtotime($d->check_out_date)) }}--}}</td>
                                <!-- <td>{{ $discharge == true ? $d->allDaysPrice($d->room_id, false) :  $d->allDaysPrice($d->room_id, false) }}</td> -->
                            </tr>
                        @endforeach
                    </table>
                @endif

            </div>
        </div>
    </div>
@endif
<script>
    function getData(id) {
        $.ajax({
            url: "{{ url("admin/user/get-daily-diet-details/") }}/" + id,
            type: "GET",
            success: function (response) {
                $("#div_daily_diet_details").html(response).show();
            }
        })
    }

    var g_id = $("#daily_diet_id").val();
    $("#daily_diet_details_" + g_id).trigger("click");
    getData(g_id);

    $("[id^=daily_diet_details_]").click(function () {
        var id = $(this).attr("id").split("daily_diet_details_")[1];
        getData(id);
    })

    $("#discount-code").change(function () {
        var val = $(this).val();
        var total_price = $("#total_amount_val").val();
        $.ajax({
            url: "{{ url("get-discount-code") }}",
            type: "POST",
            data: {
                "code": val,
                "price": "{{ $booking->id != "" ? $booking->getPendingAmount($discharge) : ""}}",
                "_token": "{{ csrf_token() }}"
            },
            success: function (data) {
                var val = "{{  $booking->getPendingAmount(true) }}";
                if(val > 0) {
                    console.log('val' + val);

                    var discounted = val - data.discount;

                    if (discounted > 0) {
                        $("#discount_amount").html(data.discount);
                        $("#discounted_amount").html(data.amount);
                    } else {
                        alert('Discount value should be less than or equal to payable amount.');
                        $("#discount-code").val('');
                    }
                }

            }
        });
    });

    $("#discount-flat").change(function () {
        //var val = "{{  $booking->id != "" ? $booking->getPendingAmount(true) : "" }}";
        var val = "{{  $booking->getPendingAmount(true) }}";
        var flat_discount = $("#discount-flat").val();

        if(val > 0) {

            var discounted = val - flat_discount;
            if (discounted > 0) {
                $("#discount_amount").html(flat_discount);
                $("#discounted_amount").html(discounted);
            } else {
                alert('Discount value should be less than or equal to payable amount.');
                $("#discount-flat").val('');
            }
        }
    })

    $("#discount-perc").change(function () {
        var val = "{{  $booking->id != "" ? $booking->getPendingAmount(true) : "" }}";
        console.log('val' + val);




        //var val = "{{  $booking->getPendingAmount(true) }}";

        if(val > 0) {
            var per_discount = $("#discount-perc").val();
            console.log('per_discount' + per_discount);
            var discount = per_discount * val / 100;
            console.log('discount' + discount);
            var discounted = val - discount;
            console.log('discounted' + discounted);


           // var discounted = val - $("#discount-flat").val();

            if (discounted > 0) {
                $("#discount_amount").html(discount);
                $("#discounted_amount").html(discounted);
            } else {
                alert('Discount value should be less than or equal to payable amount.');
                $("#discount-perc").val('');
            }
        }
    })

    $("#avail-discount").click(function (e) {
        e.preventDefault();
        $(this).attr('disabled', true);
        $(this).html('Loading...');
        $.ajax({
            url: "{{ url("/admin/booking/avail-discount/".$booking->id) }}",
            type: "POST",
            data: $("#discount-form").serialize(),
            success: function (data) {
                if (data.success == "OK") {
                    alert("Successfully availed discount");
                    location.reload();
                } else {
                    alert("Something went wrong!!! Try again later");
                }

                $(this).attr('disabled', false);
                $(this).html('Submit');
            }
        });
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

    $("#pay-due-btn").click(function (e) {
        e.preventDefault();

        var paid_amount = $("#amount_paid").val();


        if ($("#amount").val() > 0) {

            check_refund = 1;

            console.log($("#amount").val());
            console.log('paid_amount',paid_amount);
            paid_amount = parseInt(paid_amount);
            var amt = $("#amount").val();
            amt = parseInt(amt);

            if (amt > paid_amount) {
                var check_refund = 0;
            }
            var html = "REFUND Rs." + $("#amount").val();


            var val = $("#payment-type :radio:checked").val();

            if (val == 0) {
                var html = "PAY Rs." + $("#amount").val();
                check_refund = 1;
            }

            if (check_refund == 1) {
                if (confirm('Are sure want to ' + html + ' in patient account?')) {
                    $(this).attr('disabled', true);
                    $(this).html('Loading...');
                    $.ajax({
                        url: "{{ url("/admin/booking/pay/".$booking->id) }}",
                        type: "POST",
                        data: $("#pay-form").serialize(),
                        success: function (data) {
                            if (data.success == "OK") {
                                alert("Successfully paid");
                                location.reload();
                            } else {
                                alert("Something went wrong!!! Try again later");
                            }

                            $(this).attr('disabled', false);
                            $(this).html('Submit');
                        }
                    });
                }
            } else {
                alert('Refund amount should be less than or equal to paid amount.');
            }
        } else {
            alert('Amount should be greater than 0.');
        }
    })
</script>
