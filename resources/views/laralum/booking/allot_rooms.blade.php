@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @php
            if($booking->patient_type == \App\Booking::PATIENT_TYPE_IPD) {
               if ($booking->accommodation_status == \App\Booking::ACCOMMODATION_STATUS_CONFIRMED) {
                        $route = route('Laralum::ipd.bookings.list');
                    } else {
                        $route = route('Laralum::admin.future.patients.list');
                    }
            }else{
               $route = route('Laralum::bookings');
            }
        @endphp

        <a class="section" href="{{ $route }}">{{ trans('laralum.booking_list') }}</a>
        <i class="right angle icon divider"></i>

        @php
            if($booking->accommodation_status == \App\Booking::ACCOMMODATION_STATUS_CONFIRMED)
               $route = route('Laralum::ipd.booking.show', $booking->id);
            else
               $route = route('Laralum::future.booking.show', $booking->id);
        @endphp

        <a class="section" href="{{ $route }}">Booking
            Details</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Allot Rooms</div>
    </div>
@endsection
@section('title', 'Bookings')
@section('icon', "pencil")
@section('subtitle', 'List of all bookings')
@section('content')
    <div class="ui one column doubling stackable grid">

        <div class="column">
            <div class="ui very padded segment table_sec2 ">
                <div class="page_title">
                    <h2 class="pull-left">Basic Details</h2>
                    {{--@if(Laralum::loggedInUser()->hasPermission('generate.token'))--}}
                    <div class="pull-right">
                        {{-- <a class="btn btn-primary ui button blue"
                            href="{{ url('admin/booking/'.$booking->id.'/show') }}">View Booking</a>
                         <a class="btn btn-primary ui button blue"
                            href="{{ url('admin/booking/generate-token/'.$booking->id) }}">Generate Token</a>
                         <a class="btn btn-primary ui button blue"
                            href="{{ url('admin/booking/personal_details/'.$booking->id) }}">Edit Booking--}}{{--
                         @if($booking->isEditable()) Edit Booking @else Revisit @endif --}}{{--</a>
                         @if ($booking->bookingValidity())
                             <a class="btn btn-primary ui button blue"
                                href="{{ url('admin/booking/account/'.$booking->id) }}">Account</a>
                         @endif
                         @if($booking->isEditable())
                             <a class="btn btn-primary ui button blue"
                                href="{{ url('admin/booking/discharge-patient-billing/'.$booking->id) }}">Discharge</a>
                         @endif--}}
                        <a class="btn btn-primary ui button blue"
                           href="{{ url('admin/booking/accommodation-print/'.$booking->id) }}">Print</a>
                    </div>
                    {{--@endif--}}


                </div>

                <div class="segment">
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

                                        <tr>
                                            <td colspan="6">
                                                @if($booking->getCurrentBooking())
                                                    <a id="allot-room-patient"
                                                       class="button ui no-disable allot-room-patient_new">Shift
                                                        Room/Bed</a>
                                                @endif
                                            </td>
                                        </tr>
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
                                            <th><a id="allot-room-patient"
                                                   class="button ui no-disable allot-room-patient_new">Allot
                                                    Accommodation</a></th>
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

                                                <td>@if( $member->getRoomDetails())
                                                        {!! $member->getRoomDetails()  !!}
                                                        <br/>Services:<br/> {!! $member->getServiceDetails()  !!}
                                                    @endif
                                                </td>
                                                <td>Rs.{{ $member->daysPrice(null, true, false) }}</td>
                                                <td>

                                                    <a id="allot-room-member_{{ $member->id }}"
                                                       class="button ui no-disable">{{ $member->bookingRooms->count() > 0 ? "Shift Room/Bed" : "Allot Accommodation" }}</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Modal -->
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
                        <button type="button" class="ui blue btn btn-default modal-close" data-dismiss="modal">Close
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="AllotRoomModal" role="dialog" data-backdrop="static">
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
                        <button type="button" class="ui blue btn btn-default modal-close" data-dismiss="modal">Close
                        </button>
                    </div>
                </div>

            </div>
        </div>
        @endsection
        @section('js')
            <script>
                $(".allot-room-patient_new").click(function () {
                    var allot_room_url = "{{ url("admin/booking/allot-room-form/".$booking->id) }}";
                    $("#bookingModal .modal-title").html("Allot Room");
                    $("#bookingModal .modal-body").html("Content loading please wait...");
                    $("#bookingModal").modal("show");
                    $("#bookingModal").modal({
                        backdrop: 'static',
                        keyboard: false,
                    });
                    $("#bookingModal .modal-body").load(allot_room_url);
                })

                $("[id^=allot-room-member_]").click(function () {
                    var id = $(this).attr('id').split('allot-room-member_')[1];
                    var allot_room_url = "{{ url("admin/booking/allot-room-form/".$booking->id) }}" + "/" + id;
                    $("#bookingModal .modal-title").html("Allot Room");
                    $("#bookingModal .modal-body").html("Content loading please wait...");
                    $("#bookingModal").modal("show");
                    $("#bookingModal").modal({
                        backdrop: 'static',
                        keyboard: false,
                    });
                    $("#bookingModal .modal-body").load(allot_room_url);
                })
            </script>
            <script src="{{ asset('/laralum_public/js/bootstrap.datetimepicker.js') }}"></script>

            <script type="text/javascript">
                $(window).load(function () {
                    $(".modal-close").click(function () {
                        $(".modal").modal("hide");
                    });

                    $(document).delegate('[data-toggle="modal"]', "click", function () {
                        console.log("asdsa");
                        var user_id = '/' + $("#user_id").val();
                        var pageTitle = $(this).attr('pageTitle');
                        var roomId = '/' + $(this).attr('roomId');
                        var bookingId = $(this).attr('bookingId');
                        console.log('bid' + bookingId);
                        if (bookingId != "") {
                            var bookingId = '/' + bookingId;
                        } else {
                            var bookingId = '/null';
                        }

                        var memberId = $("#memberId").val();
                        if (memberId != "") {
                            var memberId = '/' + memberId;
                        }
                        // var pageName = $(this).attr('pageName');
                        var booking_form_url = '{{ \Auth::user()->isPatient() ? url('/user/booking/accomm_booking_form/')  : url('/admin/booking/accomm_booking_form/') }}' + user_id + roomId + bookingId + memberId;

                        $("#AllotRoomModal .modal-title").html(pageTitle);
                        $("#AllotRoomModal .modal-body").html("Content loading please wait...");
                        $("#AllotRoomModal").modal("show");
                        $("#AllotRoomModal").modal({
                            backdrop: 'static',
                            keyboard: false,
                        });
                        $("#AllotRoomModal .modal-body").load(booking_form_url);
                    });

                    $(document).delegate('[id^=edit_booked_room_]', "click", function () {
                        var id = '/' + $(this).attr('id').split('edit_booked_room_')[1];
                        var bookingId = $(this).attr('bookingId');

                        var booking_form_url = '{{ url('/admin/booking/get_edit_accom_form/') }}/' + bookingId + id;
                        $.ajax({
                            url: booking_form_url,
                            success: function (res) {
                                $(".orginal_edit_form").hide();
                                $(".allot_acc_form_div").html(res.html);
                                $(".back_button").show();
                            }
                        })
                    })

                    $(document).delegate('[id^=delete_booked_room_]', "click", function () {
                        if (confirm('Are you sure you want to delete this alloted accommodation?')) {
                            var id = '/' + $(this).attr('id').split('delete_booked_room_')[1];
                            var booking_form_url = '{{ url('/admin/booking/delete_booked_room/') }}' + id;
                            $.ajax({
                                url: booking_form_url,
                                type: "POST",
                                data: {"_token": "{{csrf_token()}}"},
                                success: function (res) {
                                    $(".row_booked_room-" + res.id).hide();
                                }
                            })
                        }
                    })

                    $(document).delegate('#back_btn', "click", function () {
                        $(".orginal_edit_form").show();
                        $(".allot_acc_form_div").html("");
                        $(".back_button").hide();
                        ;
                        var bookingId = $(this).attr('bookingId');

                        var booking_form_url = '{{ url('/admin/booking/get_edit_accom_form/') }}/' + bookingId;
                        $.ajax({
                            url: booking_form_url,
                            success: function (res) {
                                $(".orginal_edit_form").show();
                                $(".allot_acc_form_div").html(res.html);
                                $(".back_button").hide();
                            }
                        })
                    })


                    $(document).delegate(".booked", "click", function () {
                        var pageTitle = $(this).attr('pageTitle');
                        var bookingId = '/' + $(this).attr('bookingId');
                        var roomId = '/' + $(this).attr('roomId');
                        var memberId = $("#memberId").val();
                        if (memberId != "") {
                            var memberId = '/' + memberId;
                        }
                        /*var booking_info_url = '../booking/get_booking_info/'+bookingId+roomId;*/
                        var booking_info_url = '{{ \Auth::user()->isPatient() ? url('/user/booking/get_booking_info/')  : url('/admin/booking/get_booking_info/') }}' + bookingId + roomId + '/{{ $user->id }}';
                        $("#AllotRoomModal .modal-title").html(pageTitle);
                        $("#AllotRoomModal .modal-body").html("Content loading please wait...");
                        $("#AllotRoomModal").modal("show");
                        $("#AllotRoomModal").modal({
                            backdrop: 'static',
                            keyboard: false,
                        });
                        $("#AllotRoomModal .modal-body").load(booking_info_url);
                    });
                    $(document).delegate(".partial_booked", "click", function () {
                        var user_id = '/' + $("#user_id").val();
                        var pageTitle = $(this).attr('pageTitle');
                        var bookingId = '/' + $(this).attr('bookingId');
                        var roomId = '/' + $(this).attr('roomId');
                        var memberId = $("#memberId").val();
                        if (memberId != "") {
                            var memberId = '/' + memberId;
                        }

                        console.log("{{ Request::root() }}");
                        /* var booking_info_url = '{{ Request::root() }}/guest/booking/accomm_booking_form/'+roomId+bookingId;*/
                        var booking_info_url = '{{ \Auth::user()->isPatient() ? url('/user/booking/accomm_booking_form/')  : url('/admin/booking/accomm_booking_form/') }}' + user_id + roomId + bookingId + memberId;
                        $("#AllotRoomModal .modal-title").html(pageTitle);
                        $("#AllotRoomModal .modal-body").html("Content loading please wait...");
                        $("#AllotRoomModal").modal("show");
                        $("#AllotRoomModal").modal({
                            backdrop: 'static',
                            keyboard: false,
                        });
                        $(".modal1 .modal-body").load(booking_info_url);
                    });
                    $("#datepicker").datepicker({dateFormat: "dd-mm-yy"/*, minDate: 0*/});

                    $("#datepicker_month").datepicker({
                        changeMonth: true,
                        changeYear: true,
                        showButtonPanel: true,
                        dateFormat: "mm-yy",
                        onClose: function (dateText, inst) {
                            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
                        },
                        beforeShow: function () {
                            $(".ui-datepicker-calendar").hide();
                        }
                    });
                    /*

                     $( "#datepicker_month" ).datepicker({
                     dateFormat: "mm-yy",
                     minDate:0,
                     showButtonPanel: true,
                     viewModw: "months",
                     minViewMode: "months",

                     });*/
                });

                $("#edit_button").click(function () {
                    $("#show_details").hide();
                    $("#edit_details").show();
                });
                $("#show_button").click(function () {
                    $("#show_details").show();
                    $("#edit_details").hide();
                });
                $(document).ready(function () {
                    $(".panel-heading").on('click', function () {
                        $(".panel-heading").removeClass('plus-minus');
                        $(this).toggleClass('plus-minus');
                        $(this).parent(".panel").find(".tabs-1").slideToggle();
                    });
                });
            </script>
@endsection