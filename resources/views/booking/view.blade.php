@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @if(!\Auth::user()->isUser())
            @php
                if($booking->patient_type == \App\Booking::PATIENT_TYPE_IPD) {
                    if ($booking->accommodation_status == \App\Booking::ACCOMMODATION_STATUS_CONFIRMED) {
                        $route = route('Laralum::ipd.bookings.list');
                    } else {
                        $route = route('Laralum::admin.future.patients.list');
                    }
                } else {
                    $route = route('Laralum::bookings');
                }
            @endphp

            <a class="section" href="{{ $route }}">{{ trans('laralum.booking_list') }}</a>
            <i class="right angle icon divider"></i>
        @endif

        <div class="active section">{{  trans('laralum.booking_details') }}</div>
    </div>
@endsection
@section('title', 'Booking Details')
@section('icon', "pencil")
@section('subtitle', 'Booking Details')

@section('content')
    <div class="ui one column doubling stackable grid">
        <div class="column ">
            <div class="ui very padded segment table_sec2">
                <div class="page_title table_top_btn">
                    <h2 class="pull-left">Basic Details</h2>

                    <div class="pull-right btn-group">
                        @if(Laralum::loggedInUser()->hasPermission('admin.bookings.tokens.list'))
                            {{--@if (empty($booking->getCurrentBooking()) && $booking->checkAccommodation() && !$booking->isCancelled() && !$booking->isDischarged())
                                @php
                                    if ($booking->accommodation_status != \App\Booking::ACCOMMODATION_STATUS_CONFIRMED) {
                                        $route = url('admin/future-bookings/allot-accommodation/'.$booking->id);
                                    }else{
                                        $route = url('admin/booking/allot-accommodation/'.$booking->id);
                                    }@endphp

                                <a class="btn btn-primary ui button no-disable"
                                   href="{{ $route }}">Allot
                                    Accommodation</a>
                            @endif--}}


                            @if($booking->isEditable() && !$booking->isCancelled())
                                @if ($booking->patient_type == \App\Booking::PATIENT_TYPE_OPD)
                                    <a class="btn btn-primary ui button no-disable"
                                       href="{{ url('admin/booking/opd-generate-token/'.$booking->id) }}">Generate
                                        Token</a>
                                @elseif ($booking->accommodation_status == \App\Booking::ACCOMMODATION_STATUS_CONFIRMED)
                                    <a class="btn btn-primary ui button no-disable"
                                       href="{{ url('admin/booking/generate-token/'.$booking->id) }}">Generate Token</a>
                                @endif
                            @endif
                        @endif

                        @if (!$booking->isCancelled() && $booking->isEditable())
                            <a class="btn btn-primary ui button no-disable"
                               href="{{ route('Laralum::user.booking.print', ['booking_id' => $booking->id]) }}">Print</a>
                        @endif

                        @if ($booking->bookingValidity() && $booking->isEditable() && $booking->accommodation_status == \App\Booking::ACCOMMODATION_STATUS_CONFIRMED)
                            <a class="btn btn-primary ui button no-disable"
                               href="{{ route('Laralum::user.booking.account', ['booking_id' => $booking->id]) }}">Account</a>
                        @endif

                                            <a class="btn btn-primary ui button no-disable"
                                               href="{{ url('bookings/'.$booking->id.'/personal-details') }}">Edit Booking

                                                @php
                                                    if (\Auth::user()->isPatient()){
                                                        $route = url( 'user/booking/print-kid/'.$booking->id);
                                                    }else{
                                                        if($booking->patient_type == \App\Booking::PATIENT_TYPE_IPD){
                                                            if ($booking->accommodation_status == \App\Booking::ACCOMMODATION_STATUS_CONFIRMED) {
                                                                $route = route('Laralum::ipd.booking.print_kid', ['booking_id' => $booking->id]);
                                                            } else {
                                                                $route = route('Laralum::future.booking.print_kid', ['booking_id' => $booking->id]);
                                                            }
                                                           }
                                                        else
                                                           $route = url( 'admin/opd-bookings/print_kid/'.$booking->id);
                                                    }
                                                @endphp

                        <a class="btn btn-primary ui button no-disable"
                           href="{{ $route }}">Print Patient Card</a>
                    </div>


                </div>

                <div class="table_head_lft">
                    <table class="ui table table_cus_v bs">
                        <thead>
                        <tbody>
                        @if($booking->status == \App\Booking::STATUS_CANCELLED)
                            <tr>
                                <th>Cancel Reason</th>
                                <td colspan="3">{{ $booking->cancel_reason}}</td>
                            </tr>
                        @endif
                        <tr>
                            <th>UHID</th>
                            <td>{{ $booking->getProfile('uhid')}}</td>
                            <th>Booking Id</th>
                            <td>{{ $booking->booking_id}}</td>
                           {{-- <th>Patient Id</th>
                            <td>{{ $booking->getProfile('kid') }}</td>--}}
                            <th>Registration Id</th>
                            <td>{{ $booking->getProfile('kid') }}</td>
                        </tr>

                        <tr>
                            <th>Name</th>
                            <td>{{ $booking->user->name }} - {{ $booking->patient_type != null ? $booking->getPatientType($booking->patient_type) : "" }}</td>
                            <th>Patient's Name</th>
                            <td>{{ $booking->getProfile('first_name').' '.$booking->getProfile('last_name')  }}</td>
                            <th>Email</th>
                            <td>{{ $booking->user->email }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{{ $booking->getStatusOptions($booking->status) }}</td>
                            <th>S/o, D/o, W/o</th>
                            <td>{{ $booking->getProfile('relative_name') }}</td>
                            <th>Gender</th>
                            <td>{{ $booking->getProfile('gender') != null ? \App\UserProfile::getGenderOptions($booking->getProfile('gender')) : "" }}</td>
                        </tr>
                        <tr>
                            <th>Age</th>
                            <td>{{ $booking->getProfile('age') }}</td>
                            <th>Contact Number</th>
                            <td>{{ $booking->getProfile('mobile') }}</td>
                            <th>Landline Number</th>
                            <td>{{ $booking->getProfile('landline_number') }}</td>

                        </tr>
                        <tr>
                            <th>Whatsapp Number</th>
                            <td>{{ $booking->getProfile('whatsapp_number') }}</td>
                            <th>Marital Status</th>
                            <td>{{ $booking->getProfile('marital_status') != null ? \App\UserProfile::getMaritalStatus($booking->getProfile('marital_status')) : ""}}</td>
                            <th>Profession</th>
                            <td>{{ @\App\UserProfile::getProfessionType($booking->getProfile('profession_id')) }}</td>
                        </tr>
                        <tr>
                            <th>Created at</th>
                            <td>{{ date("d-m-Y H:i:s", strtotime($booking->getDate('check_in_date'))) }}</td>
                            <th>Status</th>
                            <td>{{ $booking->getStatusOptions($booking->status) }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>


                 @if($booking->members->count() > 0)
                    <div class="table_head_lft">
                        <div class="table_listing_row">
                            <div class="title">
                                <div class="space10"></div>
                                <div class="page_title"><h2>Members Details</h2></div>
                                <div class="divider space10"></div>
                            </div>
                            <div class="table-responsive">
                                <table class="table ui table_cus_v bs">
                                    <tbody>
                                    <tr>
                                        <th>Name</th>
                                        <th>Age</th>
                                        <th>Gender</th>
                                        <th>Id Proof</th>
                                    </tr>
                                    @foreach($booking->members as $member)
                                        <tr>
                                            <td>{{ $member->name }}</td>
                                            <td>{{ $member->age }}</td>
                                            <td>{{ $member->getGenderOptions($member->gender) }}</td>
                                            <td> @if($member->id_proof != null) <a
                                                        href="{{ \App\Settings::getDownloadUrl($member->id_proof) }}">Download</a> @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                    </div>
                @endif


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
                                        <th>Name</th>
                                        <th>Room Details</th>
                                        <th>Dates</th>
                                        <th>Services Details</th>
                                        <th>Price</th>
                                    </tr>
                                    @if(empty($booking->getCurrentBooking()) && $booking->checkAccommodation())
                                    <tr>
                                        <td>{{ $booking->getProfile('first_name').' '.$booking->getProfile('last_name')  }}</td>
                                        
                                            <td> {{ \App\Room::getBuildingName($booking->building_id)  }} - {{ \App\Room::getFloorNumber($booking->floor_number) }} ({{ $booking->getBookingType($booking->booking_type) }}) </td>
                                            <td>{{ date("d-m-Y",strtotime($booking->check_in_date)) }} to {{ date("d-m-Y",strtotime($booking->check_out_date)) }} </td>
                                            <td> 
                                                @if($booking->is_child == '1') Child @endif
                                                @if($booking->is_driver == '1') Driver @endif 
                                            </td>
                                            <td> -- </td>
                                    </tr>
                                    @endif
                                    @if(!empty($booking->getCurrentBooking()))

                                         @foreach($booking->bookingRooms as $booked_room)
                                         <tr>
                                         <td>{{ $booking->getProfile('first_name').' '.$booking->getProfile('last_name')  }}</td>
                                            <td>{{ $booked_room->roomDetails() }}</td>

                                            <td>{{ date('d-m-Y', strtotime($booked_room->check_in_date)).' to '.date('d-m-Y', strtotime($booked_room->check_out_date)) }}</td>
                                            <td>
                                                <div style="max-height: 70px!important;overflow: auto;">{!! $booked_room->serviceDetails()  !!}</div>
                                            </td>

                                            <td>{{ $booked_room->allDaysPrice(null, true, false) }}</td>
                                        </tr>
                                        @endforeach
                                    @endif

                                        <!--                if have member                   -->
                                    @if($booking->members->count() > 0)
                                        @foreach($booking->members as $member)
                                            @if($member->bookingRooms->count() > 0)
                                                @foreach($member->bookingRooms as $booked_room)
                                                <tr>
                                                    <td>{{ $member->name }}  (Member)</td>
                                                    <td>
                                                    @if( $member->getRoomDetails())
                                                        {!! $member->getRoomDetails()  !!}
                                                    @endif
                                                    </td>
                                                    <td>{{ date("d-m-Y",strtotime($booked_room->check_in_date)) }} to {{ date("d-m-Y",strtotime($booked_room->check_out_date)) }}</td>
                                                    <td> 
                                                    <div style="max-height: 70px!important;overflow: auto;">{!! $booked_room->serviceDetails()  !!}</div>
                                                    </td>
                                                    <td>{{ $member->daysPrice(null, true, false) }}</td>

                                                </tr>
                                                @endforeach
                                            @else
                                                <tr> 
                                                    <td>{{ $member->name }}  (Member)</td>
                                                    <td>{{ \App\Room::getBuildingName($member->building_id)  }} - {{ \App\Room::getFloorNumber($member->floor_number) }} ({{ $booking->getBookingType($member->booking_type) }})
                                                    </td>
                                                    <td>{{ date("d-m-Y",strtotime($member->check_in_date)) }} to {{ date("d-m-Y",strtotime($member->check_out_date)) }}</td>
                                                    <td> 
                                                        @if($member->is_child == '1') Child @endif
                                                        @if($member->is_driver == '1') Driver @endif
                                                    </td>
                                                    <td> -- </td>

                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif

                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

               

                <div class="table_head_lft">
                    <div class="table_listing_row ">

                        <div class="title">
                            <div class="space10"></div>
                            <div class="page_title"><h2>Documents</h2></div>
                            <div class="divider space10"></div>
                        </div>

                        <div class="table-responsive">
                            <table class="table ui table_cus_v">
                                <tr>
                                    <th>Document</th>
                                    <th>Id number</th>
                                    <th>Download</th>
                                </tr>
                                <tbody>
                                @foreach(\App\DocumentType::getDocuments() as $document)
                                    @if($booking->userProfile->getDocument($document->id, 'file_name') != "")
                                        <tr>
                                            <td>{{ $document->title }}</td>
                                            <td>{{ $booking->userProfile->getDocument($document->id, 'id_number') }}</td>
                                            <td>
                                                <a title="Download"
                                                   href="{{ \App\Settings::getDownloadUrl($booking->userProfile->getDocument($document->id, 'file'), $booking->userProfile->getDocument($document->id, 'file_name')) }}"
                                                   class="no-disable"><i
                                                            class="fa fa-cloud-download"></i> {{ $booking->userProfile->getDocument($document->id, 'file_name') }}
                                                </a></td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="table_head_lft">
                    <div class="table_listing_row">
                        <div class="title">
                            <div class="space10"></div>
                            <div class="page_title"><h2>Health Issues</h2></div>
                            <div class="divider space10"></div>
                        </div>

                        <div class="table-responsive">
                            <table class="table ui table_cus_v bs">
                                <tbody>
                                <tr>
                                    <th width="20%">Health Issues</th>
                                    <td width="80%">{!!  $booking->getProfile('health_issues')  !!}</td>

                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="table_head_lft">
                    <div class="table_listing_row">
                        <div class="title">
                            <div class="space10"></div>
                            <div class="page_title"><h2>Address Details</h2></div>
                            <div class="divider space10"></div>
                        </div>

                        <div class="table-responsive">
                            <table class="table ui table_cus_v bs">
                                <tbody>
                                <tr>
                                    <th width="30%">Address</th>
                                    <td width="70%">{!! $booking->getAddress('address1') ? $booking->getAddress('address1').', '.$booking->getAddress('address2').'<br>'.$booking->getAddress('city').', '.$booking->getAddress('zip').'<br>'.$booking->getAddress('state').'<br>'.$booking->getAddress('country') : ""!!}</td>
                                </tr>
                                <tr>
                                    <th width="30%">Referral Source</th>
                                    <td width="70%">{{ $booking->getAddress('address1') ?  $booking->getAddress('referral_source') : ""}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>



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
            $("#datepicker").datepicker({dateFormat: "dd-mm-yy", minDate: 0});

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