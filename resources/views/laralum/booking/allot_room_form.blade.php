@php $select_month_year = ''; @endphp
@if(isset($rooms_status_arr['month_data']) && !empty($rooms_status_arr['month_data']))
    @php
        $days = '';
        $days_names = '';
        $blank_tds = '';
        $select_month_year = '';
    @endphp
    @for($days_number=1;$days_number<=$rooms_status_arr['month_data']['month_date_count'];$days_number++)
        @php
            $days.= '<th>'.$days_number.'</th>';
            $loop_date = $rooms_status_arr['month_data']['year'].'-'.$rooms_status_arr['month_data']['month'].'-'.$days_number;
            $days_names.= '<td>'.date('D', strtotime($loop_date)).'</td>';
            $blank_tds.='<td></td>';
            $select_month_year = $rooms_status_arr['month_data']['month'].'-'.$rooms_status_arr['month_data']['year'];
        @endphp
    @endfor
@endif
<style type="text/css">
    body{overflow: hidden;}
</style>
<link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">
<div class="admin_wrapper signup">
   <!-- <header style="background-color: #008fd5;">
        <div class="logo_wrapper wow fadeInDown">
            <h1>Kayakalp</h1>
        </div>
    </header>-->
    <div class="main_wrapper">

<div class="main_content_area">
        <div class="panel-group" id="tabs">
        <input type="hidden" id="user_id" value="{{ $user->id }}"/>
        <div class="panel panel-default">
            <div class="panel-heading {{--plus-minus--}}">
                <ul>
                    <li><a class="no-disable" href="#tabs-1"><i class="fa fa-plus"></i> <i class="fa fa-minus"></i>ACCOMMODATION STATUS CHART - ROOM WISE</a></li>

                </ul>
            </div>
            <div class="tabs-1" style="display: {{ $filter_date == true ? 'block' : 'none' }};">
            <div id="collapseOne" class="panel-collapse collapse in @if($accordian_status_rw==1) in @endif">
                <div class="panel-body">
                    <div class="chart_container">
                    <table  class="table_outer" align="center" cellpadding="0" cellspacing="0" width="100%" height="100%">
                        <tr>
                            <td class="table Datepicker">
                                <table cellpadding="0" cellspacing="0" >
                                    <tr>
                                        <td class="navigationDatepicker">
                                            <a href="#" class="Previous"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                                            <a href="#" class="Next"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
                                        </td>
                                        <td>
                                        <span class="ASOFDATE">AS OF "%date%"</span>
                                            @if(\Auth::user()->isPatient())
                                                {!! Form::open(['route' => ['Laralum::user.booking.accommodation', 'user_id' => $user->id],'class' => 'ui form DatePicker']) !!}
                                            @else
                                            {!! Form::open(['route' => ['Laralum::booking.accommodation', 'user_id' => $user->id],'class' => 'ui form DatePicker']) !!}
                                            @endif
                                            {{ csrf_field() }}
                                            <div class="field ">
                                                {!! Form::label('text', 'See for date') !!}
                                                {!! Form::text('select_date',old('select_date',$default_date),['required','id'=>'datepicker']) !!}
                                                <input type="hidden" name="filter_date" value="1">
                                            </div>
                                            <!--input type="text" id="datepicker"-->
                                            <div class="field ">
                                                {!! Form::submit('Filter') !!}
                                            </div>
                                            {!! Form::close() !!}
                                        </td>

                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <th class="over_status">OVER ALL STATUS:</th>
                        </tr>
                        <tr>
                            <td class="overall_inner table">
                                @if(!empty($total_partial_vacant))
                                    <ul>
                                        @foreach ($total_partial_vacant as $room_type_id=>$vacant)
                                            <li>{{ $vacant['room_type'] }}</li>
                                            <li>{{ $vacant['total_vacant_rooms'] }} A @if($vacant['male'] !=0 || $vacant['female'] !=0 ) ( @endif @if($vacant['male'] !=0 ){{ $vacant['male'] }}M @endif @if($vacant['female'] != 0){{ $vacant['female'] }}W @endif @if($vacant['male'] !=0 || $vacant['female'] !=0 ) ) @endif / {{ $vacant['total'] }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="over_status">ROOM WISE STATUS</th>
                        </tr>
                        <tr>
                            @if(!empty($room_arr))
                                @foreach($room_arr as $room_bld)
                                    <table border=1 class="room-wise-table">
                                        <thead>
                                        <tr>
                                            <th colspan="5">{{ $room_bld['building_data']['building_name'] }}</th>
                                        </tr>
                                        <tr>
                                            <th colspan="5">{{ $room_bld['building_data']['room_type_name'] }}</th>
                                        </tr>
                                        <tr>
                                            <th>#Room</th>
                                            <th>Floor</th>
                                            <th>Type</th>
                                            <th>Bed 1</th>
                                            <th>Bed 2</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($room_bld['room_data'] as $room_data)
                                            @if ( isset($room_data['booking_status'][0]) || isset($room_data['booking_status'][1]) )
                                                @if ( isset($room_data['booking_status'][0]) && isset($room_data['booking_status'][1]) )
                                                    @php $room_book_status = "fully_booked"; @endphp
                                                @elseif( isset($room_data['booking_status'][0]))
                                                    @if ( $room_data['booking_status'][0]['booking_type'] == \App\Booking::BOOKING_TYPE_DOUBLE_BED_EB || $room_data['booking_status'][0]['booking_type']  == \App\Booking::BOOKING_TYPE_SINGLE_OCCUPANCY_EB)
                                                        @php
                                                            $room_book_status = "fully_booked_extrabed";
                                                        @endphp
                                                    @elseif ( $room_data['booking_status'][0]['booking_type'] == \App\Booking::BOOKING_TYPE_DOUBLE_BED || $room_data['booking_status'][0]['booking_type']  == \App\Booking::BOOKING_TYPE_SINGLE_OCCUPANCY)

                                                        @php
                                                            $room_book_status = "fully_booked";
                                                        @endphp
                                                    @else
                                                        @php
                                                            $room_book_status = "partial_booked";
                                                        @endphp
                                                    @endif
                                                @elseif( isset($room_data['booking_status'][1]))
                                                        @php
                                                            $room_book_status = "partial_booked";
                                                        @endphp
                                                @endif
                                            @else
                                                @php
                                                    $room_book_status = "fully_vacant";
                                                @endphp
                                            @endif

                                            {{--@if((isset($room_data['booking_status'][0]) && $room_data['booking_status'][0]['booking_type'] ==3) || (isset($room_data['booking_status'][1]) && $room_data['booking_status'][1]['booking_type'] ==3))
                                                @php
                                                    $room_book_status = "fully_booked_extrabed";
                                                @endphp
                                            @elseif((isset($room_data['booking_status'][0]) && $room_data['booking_status'][0]['booking_type'] ==2) || (isset($room_data['booking_status'][1]) && $room_data['booking_status'][1]['booking_type'] ==2))
                                                @php
                                                    $room_book_status = "fully_booked";
                                                @endphp
                                            @elseif(count($room_data['booking_status'])==2)
                                                @php
                                                    $room_book_status = "fully_booked";
                                                @endphp
                                            @elseif(count($room_data['booking_status'])==1)
                                                @php
                                                    $room_book_status = "partial_booked";
                                                @endphp
                                            @else
                                                @php
                                                    $room_book_status = "fully_vacant";
                                                @endphp
                                            @endif--}}
                                            <tr>
                                                <td class="{{ $room_book_status }}">{{ $room_data['room_number'] }}</td>
                                                <td>@if($room_data['floor_number']==1) GF @elseif($room_data['floor_number']==2) FF @else {{ $room_data['floor_number'] }} @endif</td>
                                                <td>{{ $room_data['room_type_short_name'] }}</td>
                                                @php
                                                    $user_form_title = 'Book a room / bed window';
                                                @endphp
                                                @if(!empty($room_data['booking_status']))
                                                     @if(isset($room_data['booking_status'][0]) && isset($room_data['booking_status'][1]))
                                                        @if( ($room_data['booking_status'][0]['booking_type'] == \App\Booking::BOOKING_TYPE_SINGLE_BED || $room_data['booking_status'][0]['booking_type'] == \App\Booking::BOOKING_TYPE_DOUBLE_BED_SHARING) && ($room_data['booking_status'][1]['booking_type'] == \App\Booking::BOOKING_TYPE_SINGLE_BED || $room_data['booking_status'][1]['booking_type'] == \App\Booking::BOOKING_TYPE_DOUBLE_BED_SHARING) )
                                                            <td class="booked"  roomId = "{{ $room_data['room_id'] }}" bookingId="{{ $room_data['booking_status'][0]['booking_id'] }}">B</td>
                                                            <td class="booked" roomId = "{{ $room_data['room_id'] }}" bookingId="{{ $room_data['booking_status'][1]['booking_id'] }}">B</td>

                                                        @endif
                                                    @endif
                                                    @if(isset($room_data['booking_status'][0]) && !isset($room_data['booking_status'][1]))
                                                             @if( ($room_data['booking_status'][0]['booking_type'] == \App\Booking::BOOKING_TYPE_SINGLE_BED || $room_data['booking_status'][0]['booking_type'] == \App\Booking::BOOKING_TYPE_DOUBLE_BED_SHARING))
                                                                <td class="booked" roomId = "{{ $room_data['room_id'] }}" bookingId="{{ $room_data['booking_status'][0]['booking_id'] }}">B</td>
                                                                <td roomId = {{ $room_data['room_id'] }} bookingId="" data-toggle="modal" pageTitle="{{ $user_form_title }}" class="vacant">V</td>
                                                             @else
                                                                    <td   class="single_occupancy booked" pageTitle="Booking Information" roomId = "{{ $room_data['room_id'] }}" bookingId="{{ $room_data['booking_status'][0]['booking_id'] }}" colspan="2">SO</td>
                                                             @endif
                                                     @endif

                                                   {{-- @if((isset($room_data['booking_status'][0]) && ($room_data['booking_status'][0]['booking_type'] != \App\Booking::BOOKING_TYPE_SINGLE_BED && $room_data['booking_status'][0]['booking_type'] !=\App\Booking::BOOKING_TYPE_DOUBLE_BED_SHARING)) && (isset($room_data['booking_status'][1]) && ($room_data['booking_status'][1]['booking_type'] !=\App\Booking::BOOKING_TYPE_SINGLE_BED && $room_data['booking_status'][1]['booking_type'] !=\App\Booking::BOOKING_TYPE_DOUBLE_BED_SHARING)))
                                                        <td colspan="2" class="single_occupancy booked" pageTitle="Booking Information" roomId = "{{ $room_data['room_id'] }}" bookingId="{{ $room_data['booking_status'][0]['booking_id'] }}">SO</td>
                                                    @else
                                                        @if(isset($room_data['booking_status'][0]))
                                                            <td class="booked" roomId = "{{ $room_data['room_id'] }}" bookingId="{{ $room_data['booking_status'][0]['booking_id'] }}">B--}}{{--{{ $room_data['booking_status'][0]['booking_type'] }}--}}{{--</td>
                                                        @else
                                                            <td class="vacant" roomId = "{{ $room_data['room_id'] }}" bookingId="" data-toggle="modal" pageTitle="{{ $user_form_title }}" >V</td>
                                                        @endif
                                                        @if(isset($room_data['booking_status'][1]))
                                                            <td class="booked" roomId = "{{ $room_data['room_id'] }}" bookingId="{{ $room_data['booking_status'][1]['booking_id'] }}">B--}}{{--{{ $room_data['booking_status'][1]['booking_type'] }}--}}{{--</td>
                                                        @else
                                                            <td class="vacant" roomId = {{ $room_data['room_id'] }} bookingId="" data-toggle="modal" pageTitle="{{ $user_form_title }}" >V</td>
                                                        @endif
                                                   --}}
                                                @else
                                                    <td class="vacant" roomId = {{ $room_data['room_id'] }} bookingId="" data-toggle="modal" pageTitle="{{ $user_form_title }}" >V</td>
                                                    <td class="vacant" roomId = {{ $room_data['room_id'] }} bookingId="" data-toggle="modal" pageTitle="{{ $user_form_title }}" >V</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endforeach
                            @endif
                        </tr>
                        <tr>
                            <td class="table table_coloum">
                                <table cellpadding="0" cellspacing="0" class="ColourCodingTable">
                                    <tr>
                                        <td><strong>Colour Coding</strong></td><tr>
                                        <tr class="orange"><td><strong>FB (Fully Booked)</strong></td><tr>
                                        <tr class="blue"><td><strong>PB (Partially Booked)</strong></td><tr>
                                        <tr class="dark_yellow"><td><strong>EBA (Extra Bed Available)</strong></td><tr>
                                        <tr class="green"><td><strong>FV (Fully Vacant)</strong></td>
                                    </tr>
                                </table>
                            </td>
                            <td class="table table_coloum">
                                <table cellpadding="0" cellspacing="0" class="ColourCodingTable">
                                    <tr>
                                        <td><strong>Character Coding</strong></td></tr>
                                        <tr class="orange"><td><strong>B = Booked</strong></td></tr>
                                        <tr class="green"><td><strong>V = Vacant</strong></td></tr>
                                        <tr class="brown"><td><strong>SO = Single Occupancy</strong></td></tr>
                                        <tr><td>&nbsp;</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table></div>
                </div>
            </div>

        </div>

            </div>

            <div class="panel panel-default">
            <div class="panel-heading">
                <ul>
                    <li><a  class="no-disable" href="#tabs-2"><i class="fa fa-plus"></i> <i class="fa fa-minus"></i>ACCOMMODATION STATUS CHART - Month WISE</a></li>
                </ul>
                </div>
             <div class="tabs-1" style="display: {{ $filter_month == true ? 'block' : 'none' }};" >
              <div class="panel panel-default">
            <div id="collapseTwo" class="panel-collapse collapse @if($accordian_status_mw==1) in @endif ">
                <div class="panel-body">
                    <!-- month wise start -->
                    <div class="chart_container">
                        <table  class="table_outer"align="center" cellpadding="0" cellspacing="0" width="100%" height="100%">
                            <tr>
                                <td class="table Datepicker">
                                    <table cellpadding="0" cellspacing="0" >
                                        <tr>
                                            <td class="navigationDatepicker">
                                                <a href="#" class="Previous"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                                                <a href="#" class="Next"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
                                            </td>
                                            <td>
                                                <span class="ASOFDATE">Select Month & Year</span>
                                                {!! Form::open(['route' => ['Laralum::booking.accommodation', 'user_id' => $user->id], 'class' => 'ui form SelectMonthYear']) !!}
                                                {{ csrf_field() }}
                                                <div class="field ">
                                                    <div class="field ">
                                                        {!! Form::text('select_month_year',old('select_month_year', $select_month_year),['required','id'=>'datepicker_month']) !!}
                                                    </div>
                                                </div>
                                                <input type="hidden" name="filter_month" value="1">
                                                <!--input type="text" id="datepicker"-->
                                                <div class="field ">
                                                    {!! Form::submit('Filter') !!}
                                                </div>
                                                {!! Form::close() !!}
                                            </td>

                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            {{--<tr>
                                <td class="table selectyear">
                                    <table cellpadding="0" cellspacing="0" >
                                        <tr>
                                            <td align="right"><strong>Select Month & Year</strong></td>
                                            <td align="left">
                                                {!! Form::open(['route' => 'guest.booking.accommodation','class' => 'ui form SelectMonthYear']) !!}
                                                {{ csrf_field() }}
                                                <div class="field ">
                                                    {!! Form::text('select_month_year',old('select_month_year', $select_month_year),['required','id'=>'datepicker_month']) !!}
                                                </div>
                                                <!--input type="text" id="datepicker"-->
                                                <div class="field ">
                                                    {!! Form::submit('Filter') !!}
                                                </div>
                                                {!! Form::close() !!}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>--}}
                            <tr>
                                <td class="table  color_meaning">
                                    <table cellpadding="0" cellspacing="0" >
                                        <tr>
                                            <td align="center" class="yellow"><strong>Colour Meaning</strong></td>
                                            <td align="center" class="orange">Fully Booked</td>
                                            <td align="center" class="green_light"><strong>Booked by shared place available</strong></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td class="table table2">
                                    <table cellpadding="0" cellspacing="0" >
                                        <tr class="parret">
                                            <th>DATE</th>
                                            <th></th>
                                            <th></th>
                                            {!! $days !!}
                                        </tr>
                                        <tr class="yellow">
                                            <td>DAY</td>
                                            <td></td>
                                            <td></td>
                                            {!! $days_names !!}
                                        </tr>
                                        <tr class="yellow">
                                            <td>Building</td>
                                            <td>Type</td>
                                            <td>Room / Bed No.</td>
                                            {!! $blank_tds !!}
                                        </tr>
                                        @php
                                            $user_form_title = 'Book a room / bed window';
                                        @endphp
                                        @if(isset($rooms_status_arr['rooms_data']) && !empty(isset($rooms_status_arr['rooms_data'])))
                                            @foreach($rooms_status_arr['rooms_data'] as $room_data)
                                                <tr>
                                                    <td>{{ $room_data['building'] }}</td>
                                                    <td>{{ $room_data['room_type'] }}</td>
                                                    <td>{{ $room_data['room_number'] }}</td>
                                                    @if(isset($room_data['days_bookings']) && !empty($room_data['days_bookings']))
                                                        @foreach($room_data['days_bookings'] as $day_bookings)
                                                            @if(isset($day_bookings[0]) && isset($day_bookings[1]))
                                                                <td class="fully_booked booked" roomId = {{ $room_data['room_id'] }} bookingId="{{ $day_bookings[0]['booking_id'] }}-{{ $day_bookings[1]['booking_id'] }}">B</td>
                                                            @elseif(isset($day_bookings[0]))
                                                                @if($day_bookings[0]['booking_type'] == \App\Booking::BOOKING_TYPE_SINGLE_BED || $day_bookings[0]['booking_type'] == \App\Booking::BOOKING_TYPE_DOUBLE_BED_SHARING)
                                                                    <td class="partial_booked" roomId = {{ $room_data['room_id'] }} bookingId="{{ $day_bookings[0]['booking_id'] }}">PB (@if($day_bookings[0]['user_gender'] == 1) F @elseif($day_bookings[0]['user_gender'] == 2) M @endif
                                                                @else
                                                                <td class="single_occupancy booked" pageTitle="Booking Information" roomId = {{ $room_data['room_id'] }} bookingId="{{ $day_bookings[0]['booking_id'] }}">SO</td>
                                                            @endif
                                                        @else
                                                                    <td class="vacant" roomId = {{ $room_data['room_id'] }} bookingId="" data-toggle="modal" pageTitle="{{ $user_form_title }}" >V</td>
                                                            @endif



                                                          {{--  @if((isset($day_bookings[0]) && ($day_bookings[0]['booking_type'] ==2 || $day_bookings[0]['booking_type']==3)) || (isset($day_bookings[1]) && ($day_bookings[1]['booking_type'] ==2 || $day_bookings[1]['booking_type'] ==3)))
                                                                <td class="single_occupancy booked" pageTitle="Booking Information" roomId = {{ $room_data['room_id'] }} bookingId="{{ $day_bookings[0]['booking_id'] }}">SO</td>
                                                            @else
                                                                @if(count($day_bookings) == 2)
                                                                    <td class="fully_booked booked" roomId = {{ $room_data['room_id'] }} bookingId="{{ $day_bookings[0]['booking_id'] }}-{{ $day_bookings[1]['booking_id'] }}">B</td>
                                                                @elseif(count($day_bookings) == 1)
                                                                    <td class="partial_booked" roomId = {{ $room_data['room_id'] }} bookingId="{{ $day_bookings[0]['booking_id'] }}">PB (@if($day_bookings[0]['user_gender'] == 1) F @elseif($day_bookings[0]['user_gender'] == 2) M @endif </td>
                                                                @else
                                                                    <td class="vacant" roomId = {{ $room_data['room_id'] }} bookingId="" data-toggle="modal" pageTitle="{{ $user_form_title }}" >V</td>
                                                                @endif
                                                            @endif--}}
                                                        <!--td>{{ count($day_bookings) }}</td-->
                                                        @endforeach
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endif
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!-- month wise end -->
                </div>
            </div>
                </div>
             </div>

             </div>


</div>
    </div>
</div>
</div>
<input type="hidden" id="memberId" value="{{ $m_id }}">
        <script  src="{{ asset('/laralum_public/js/bootstrap.datetimepicker.js') }}"></script>

    <script type="text/javascript">
        $(window).load(function() {
            $(".modal-close").click(function () {
                $(".modal").modal("hide");
            });

            $(".modal").modal({
                backdrop: 'static',
                keyboard: false
            })
            $('[data-toggle="modal"]').click(function () {
                console.log("asdsa");
                var user_id = '/' + $("#user_id").val();
                var pageTitle = $(this).attr('pageTitle');
                var roomId = '/' + $(this).attr('roomId');
                var bookingId = $(this).attr('bookingId');
                console.log('bid' + bookingId);
                if (bookingId != "") {
                    var bookingId = '/' + bookingId;
                }
                // var pageName = $(this).attr('pageName');
                var booking_form_url = '{{ \Auth::user()->isPatient() ? url('/user/booking/accomm_booking_form/')  : url('/admin/booking/accomm_booking_form/') }}' + user_id + roomId + bookingId;

                $(".modal .modal-title").html(pageTitle);
                $(".modal .modal-body").html("Content loading please wait...");
                $(".modal").modal("show");
                $(".modal").modal({
                    backdrop: 'static',
                    keyboard: false,
                });
                $(".modal .modal-body").load(booking_form_url);
            });
            $(".booked").click(function () {
                var pageTitle = $(this).attr('pageTitle');
                var bookingId = '/' + $(this).attr('bookingId');
                var roomId = '/' + $(this).attr('roomId');

                /*var booking_info_url = '../booking/get_booking_info/'+bookingId+roomId;*/
                var booking_info_url = '{{ \Auth::user()->isPatient() ? url('/user/booking/get_booking_info/')  : url('/admin/booking/get_booking_info/') }}' + bookingId + roomId + '/{{ $user->id }}';
                $(".modal .modal-title").html(pageTitle);
                $(".modal .modal-body").html("Content loading please wait...");
                $(".modal").modal("show");
                $(".modal").modal({
                    backdrop: 'static',
                    keyboard: false,
                });
                $(".modal .modal-body").load(booking_info_url);
            });
            $(".partial_booked").click(function () {
                var user_id = '/' + $("#user_id").val();
                var pageTitle = $(this).attr('pageTitle');
                var bookingId = '/' + $(this).attr('bookingId');
                var roomId = '/' + $(this).attr('roomId');
                console.log("{{ Request::root() }}");
                /* var booking_info_url = '{{ Request::root() }}/guest/booking/accomm_booking_form/'+roomId+bookingId;*/
                var booking_info_url = '{{ \Auth::user()->isPatient() ? url('/user/booking/accomm_booking_form/')  : url('/admin/booking/accomm_booking_form/') }}' + user_id + roomId + bookingId;
                $(".modal .modal-title").html(pageTitle);
                $(".modal .modal-body").html("Content loading please wait...");
                $(".modal").modal("show");
                $(".modal").modal({
                    backdrop: 'static',
                    keyboard: false,
                });
                $(".modal .modal-body").load(booking_info_url);
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
        $(document).ready(function(){
            $(".panel-heading").on('click',function(){
                $(".panel-heading").removeClass('plus-minus');
                $(this).toggleClass('plus-minus');
                $(this).parent(".panel").find(".tabs-1").slideToggle();
            });
        });
    </script>