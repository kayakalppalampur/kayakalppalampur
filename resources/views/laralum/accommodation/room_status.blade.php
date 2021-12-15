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
@extends('layouts.admin.panel')

@section('breadcrumb')
    <div class="ui breadcrumb">
        <!-- <a class="section" href="{{ route('Laralum::buildings') }}">{{ trans('laralum.buildings') }}</a>
        <i class="right angle icon divider"></i> -->
        <div class="active section">Accommodation Status</div>
    </div>
@endsection
@section('title', 'Accommodation Status')
@section('icon', "pencil")
@section('subtitle', 'Accommodation Status')

@section('content')
    <style type="text/css">
        body {
            overflow: hidden;
        }
    </style>
    <link rel="stylesheet" type="text/css" media="screen"
          href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">
    <div class="admin_wrapper signup">
        <!-- <header style="background-color: #008fd5;">
             <div class="logo_wrapper wow fadeInDown">
                 <h1>Kayakalp</h1>
             </div>
         </header>-->
        <div class="main_wrapper">
            <div class="main_content_area">
                <div>
                    <div id="signup_wizard">
                        <h3 class="title_3">Accommodation Status</h3>

                    </div>
                    <div class="panel-group" id="tabs">
                        <div class="panel panel-default">
                            <div class="panel-heading {{--plus-minus--}}">
                                <ul>
                                    <li><a class="no-disable" href="#tabs-1"><i class="fa fa-plus"></i> <i
                                                    class="fa fa-minus"></i>ROOM WISE</a>
                                    </li>

                                </ul>
                            </div>
                            <div class="tabs-1" style="display: {{ $filter_date == true ? 'block' : 'none' }};">
                                <div id="collapseOne"
                                     class="panel-collapse collapse in @if($accordian_status_rw==1) in @endif">
                                    <div class="panel-body">
                                        <div class="chart_container">
                                            <table class="table_outer" align="center" cellpadding="0" cellspacing="0"
                                                   width="100%" height="100%">
                                                <tr>
                                                    <td class="table Datepicker month-year-filter-table">
                                                        <table cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td class="navigationDatepicker">
                                                                    <a href="#" class="Previous"><i
                                                                                class="fa fa-angle-left"
                                                                                aria-hidden="true"></i></a>
                                                                    <a href="#" class="Next"><i
                                                                                class="fa fa-angle-right"
                                                                                aria-hidden="true"></i></a>
                                                                </td>
                                                                <td class="filter-month-year">

                                                                    <span class="ASOFDATE">Select Date</span>
                                                                    {!! Form::open(['route' => ['Laralum::accommodation.room_status'],'class' => 'ui form DatePicker']) !!}

                                                                    {{ csrf_field() }}
                                                                    <div class="field ">
                                                                        {!! Form::label('text', 'See for date') !!}
                                                                        {!! Form::text('select_date',old('select_date',$default_date),['required','id'=>'datepicker']) !!}{{--
                                                                        {!! Form::select('select_year', array_combine(\App\Settings::years(), \App\Settings::years()),old('select_year', $select_year),['required','id'=>'select_year']) !!}
                                                                        {!! Form::select('select_month', array_combine(\App\Settings::months(),\App\Settings::months()), old('select_month', $select_month), ['id'=>'select_month']) !!}--}}
                                                                        <input type="hidden" name="filter_date"
                                                                               value="1">
                                                                        <button type="submit" class="button blue ui">
                                                                            Filter
                                                                        </button>
                                                                        {!! Form::close() !!}
                                                                    </div>
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
                                                        @if(!empty($overall))
                                                            <ul>
                                                                @foreach ($overall as $room_type_id=>$overall_data)
                                                                    <li>{{ $overall_data['short_name'] }}</li>
                                                                    <li>{{ $overall_data['available'] }}
                                                                        A @if($overall_data['male'] !=0 || $overall_data['female'] !=0 )
                                                                            ( @endif @if($overall_data['male'] !=0 ){{ $overall_data['male'] }}
                                                                        M @endif @if($overall_data['female'] != 0){{ $overall_data['female'] }}
                                                                        W @endif @if($overall_data['male'] !=0 || $overall_data['female'] !=0 )
                                                                            ) @endif / {{ $overall_data['total'] }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="over_status">ROOM WISE STATUS</th>
                                                </tr>
                                            </table>

                                            @foreach(\App\Building::all() as $building)
                                                <div class="col-md-6">
                                                    <table class="ui table">
                                                        <thead>
                                                        <tr>
                                                            <th class="over_status">{{ $building->name }}</th>
                                                        <tr>
                                                            <th>#Room</th>
                                                            <th>Floor</th>
                                                            <th>Type</th>
                                                            @for($i = 1; $i <= $building->getBedCount(); $i++)
                                                                <th>Bed {{ $i }}</th>
                                                            @endfor
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($building->rooms as $room)
                                                            @php $room_data = \App\Booking::getBookingsChart($room->id, old('select_date', $default_date)) @endphp
                                                            <tr room_id="{{ $room->id }}">
                                                                <td class="{{ \App\Room::getRowClass($room_data, $room->bed_count ) }}">{{ $room->room_number }}</td>
                                                                <td>{{ $room->getFloorNumber($room->floor_number) }}</td>
                                                                <td>{{ $room->getRoomType() }}</td>
                                                                @for($i = 1; $i <= $room->bed_count; $i++)
                                                                    <td bed-class="bed{{$i}}" class="{{ \App\Room::getBedClass($room_data, $i) }}">
                                                                        {{ \App\Room::getBedClass($room_data,$i, true) }}
                                                                    </td>
                                                                @endfor
                                                                {{--<td class="bed2 {{ \App\Room::getBedClass($room_data, true) }}">
                                                                    {{ \App\Room::getBedClass($room_data, true, true) }}</td>--}}
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endforeach

                                            <div class="clearfix"></div>
                                            <table>
                                                <tr>
                                                    <td class="">
                                                        <table cellpadding="0" cellspacing="0"
                                                               class="ColourCodingTable">
                                                            <tr>
                                                                <td><strong>Colour Coding</strong></td>
                                                            <tr>
                                                            <tr class="orange">
                                                                <td><strong>FB (Fully Booked)</strong></td>
                                                            <tr>
                                                            <tr class="blue">
                                                                <td><strong>PB (Partially Booked)</strong></td>
                                                            <tr>
                                                            <tr class="dark_yellow">
                                                                <td><strong>EBA (Extra Bed Available)</strong></td>
                                                            <tr>
                                                            <tr class="green">
                                                                <td><strong>FV (Fully Vacant)</strong></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td class="">
                                                        <table cellpadding="0" cellspacing="0"
                                                               class="ColourCodingTable">
                                                            <tr>
                                                                <td><strong>Character Coding</strong></td>
                                                            </tr>
                                                            <tr class="white">
                                                                <td><strong>BL = Blocked</strong></td>
                                                            </tr>
                                                            <tr class="orange">
                                                                <td><strong>B = Booked</strong></td>
                                                            </tr>
                                                            <tr class="green">
                                                                <td><strong>V = Vacant</strong></td>
                                                            </tr>
                                                            <tr class="brown">
                                                                <td><strong>SO = Single Occupancy</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>&nbsp;</td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <ul>
                                    <li><a class="no-disable" href="#tabs-2"><i class="fa fa-plus"></i> <i
                                                    class="fa fa-minus"></i>MONTH WISE</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tabs-1" style="display: {{ $filter_month == true ? 'block' : 'none' }};">
                                <div class="panel panel-default">
                                    <div id="collapseTwo"
                                         class="panel-collapse collapse @if($accordian_status_mw==1) in @endif ">
                                        <div class="panel-body">
                                            <!-- month wise start -->
                                            <div class="chart_container">
                                                <table class="table_outer" align="center" cellpadding="0"
                                                       cellspacing="0" width="100%" height="100%">
                                                    <tr>
                                                        <td class="table Datepicker month-year-filter-table">
                                                            <table cellpadding="0" cellspacing="0">
                                                                <tr>
                                                                    <td class="navigationDatepicker">
                                                                        <a href="#" class="Previous"><i
                                                                                    class="fa fa-angle-left"
                                                                                    aria-hidden="true"></i></a>
                                                                        <a href="#" class="Next"><i
                                                                                    class="fa fa-angle-right"
                                                                                    aria-hidden="true"></i></a>
                                                                    </td>
                                                                    <td class="filter-month-year">

                                                                        <span class="ASOFDATE">Select Month & Year</span>
                                                                        {!! Form::open(['route' => ['Laralum::accommodation.room_status'],'class' => 'ui form SelectMonthYear']) !!}

                                                                        {{ csrf_field() }}
                                                                        <div class="field ">
                                                                            {!! Form::select('select_year', array_combine(\App\Settings::years(), \App\Settings::years()),old('select_year', $select_year),['required','id'=>'select_year']) !!}
                                                                            {!! Form::select('select_month', array_combine(\App\Settings::months(),\App\Settings::months()), old('select_month', $select_month), ['id'=>'select_month']) !!}
                                                                            <input type="hidden" name="filter_month"
                                                                                   value="1">
                                                                            <button type="submit"
                                                                                    class="button blue ui">Filter
                                                                            </button>
                                                                            {!! Form::close() !!}
                                                                        </div>
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
                                                            <table cellpadding="0" cellspacing="0">
                                                                <tr>
                                                                    <td align="center" class="yellow"><strong>Colour
                                                                            Meaning</strong></td>
                                                                    <td align="center" class="orange">Fully Booked</td>
                                                                    <td align="center" class="green_light"><strong>Booked
                                                                            by shared place available</strong></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table table2">
                                                            <table cellpadding="0" cellspacing="0">
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
                                                                @foreach(\App\Room::orderBy('building_id')->get() as $room)
                                                                    <tr room_id="{{ $room->id }}">
                                                                        <td>{{ @$room->building->name }}</td>
                                                                        <td>{{ $room->getRoomType()}}</td>

                                                                        <td>{{ $room->room_number }}</td>
                                                                        @php $room_month_data = \App\Booking::getBookingsChartMonth($room->id, old('select_month_year', $default_month_year))@endphp
                                                                        @foreach($room_month_data as $date => $data)
                                                                            <td class="{{ \App\Room::getRowClass($data, $room->bed_count) }}">
                                                                                {{ \App\Room::getRowClass($data, $room->bed_count, true) }}
                                                                            </td>
                                                                        @endforeach
                                                                    </tr>
                                                                @endforeach
                                                            </table>


                                                            {{--@if(isset($rooms_status_arr['rooms_data']) && !empty(isset($rooms_status_arr['rooms_data'])))
                                                                @foreach($rooms_status_arr['rooms_data'] as $room_data)
                                                                    <tr>
                                                                        <td>{{ $room_data['building'] }}</td>
                                                                        <td>{{ $room_data['room_type'] }}</td>
                                                                        <td>{{ $room_data['room_number'] }}</td>
                                                                        @if(isset($room_data['days_bookings']) && !empty($room_data['days_bookings']))
                                                                            @foreach($room_data['days_bookings'] as $day_bookings)
                                                                                @if(isset($day_bookings[0]) && isset($day_bookings[1]))
                                                                                    <td class="fully_booked booked"
                                                                                        roomId={{ $room_data['room_id'] }} bookingId="{{ $day_bookings[0]['booking_id'] }}-{{ $day_bookings[1]['booking_id'] }}">
                                                                                        B
                                                                                    </td>
                                                                                @elseif(isset($day_bookings[0]))
                                                                                    @if($day_bookings[0]['booking_type'] == \App\Booking::BOOKING_TYPE_SINGLE_BED || $day_bookings[0]['booking_type'] == \App\Booking::BOOKING_TYPE_DOUBLE_BED_SHARING)
                                                                                        <td class="partial_booked"
                                                                                            roomId={{ $room_data['room_id'] }} bookingId="{{ $day_bookings[0]['booking_id'] }}">
                                                                                            PB
                                                                                            (@if($day_bookings[0]['user_gender'] == 1)
                                                                                                F @elseif($day_bookings[0]['user_gender'] == 2)
                                                                                                M @endif
                                                                                    @else
                                                                                        <td class="single_occupancy booked"
                                                                                            pageTitle="Booking Information"
                                                                                            roomId={{ $room_data['room_id'] }} bookingId="{{ $day_bookings[0]['booking_id'] }}">
                                                                                            SO
                                                                                        </td>
                                                                                    @endif
                                                                                @else
                                                                                    <td class="vacant"
                                                                                        roomId={{ $room_data['room_id'] }} bookingId=""
                                                                                        data-toggle="modal"
                                                                                        pageTitle="{{ $user_form_title }}">
                                                                                        V
                                                                                    </td>
                                                                                @endif



                                                                                --}}{{--  @if((isset($day_bookings[0]) && ($day_bookings[0]['booking_type'] ==2 || $day_bookings[0]['booking_type']==3)) || (isset($day_bookings[1]) && ($day_bookings[1]['booking_type'] ==2 || $day_bookings[1]['booking_type'] ==3)))
                                                                                      <td class="single_occupancy booked" pageTitle="Booking Information" roomId = {{ $room_data['room_id'] }} bookingId="{{ $day_bookings[0]['booking_id'] }}">SO</td>
                                                                                  @else
                                                                                      @if(count($day_bookings) == 2)
                                                                                          <td class="fully_booked booked" roomId = {{ $room_data['room_id'] }} bookingId="{{ $day_bookings[0]['booking_id'] }}-{{ $day_bookings[1]['booking_id'] }}">B</td>
                                                                                      @elseif(count($day_bookings) == 1)
                                                                                          <td class="partial_booked" roomId = {{ $room_data['room_id'] }} bookingId="{{ $day_bookings[0]['booking_id'] }}">PB (@if($day_bookings[0]['user_gender'] == 1) F @elseif($day_bookings[0]['user_gender'] == 2) M @endif </td>
                                                                                      @else
                                                                                          <td class="vacant" roomId = {{ $room_data['room_id'] }} bookingId="" data-toggle="modal" pageTitle="{{ $user_form_title }}" >V</td>
                                                                                      @endif
                                                                                  @endif--}}{{--
                                                                            <!--td>{{ count($day_bookings) }}</td-->
                                                                            @endforeach
                                                                        @endif
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </table>--}}
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
        @endsection
        @section('js')
            <script src="{{ asset('/laralum_public/js/bootstrap.datetimepicker.js') }}"></script>

            <script type="text/javascript">
                $(window).load(function () {

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
                        },
                        onRender: function () {
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
                        /*$(".panel-heading").removeClass('plus-minus');*/
                        $(this).toggleClass('plus-minus');
                        $(this).parent(".panel").find(".tabs-1").slideToggle();
                    });
                });
                $(".modal-close").click(function () {
                    $(".modal").modal("hide");
                });
                $(".booked").click(function () {
                    var roomId = $(this).parent().attr('room_id');
                    var pageTitle = "Booking Info";
                    console.log('room_id' + roomId);
                    var bed = $(this).attr('bed-class').split('bed')[1];
                    var booking_info_url = '{{ url('/admin/get_booked_room_info/') }}' + '/' + roomId + '/' + bed;
                    $(".modal .modal-title").html(pageTitle);
                    $(".modal .modal-body").html("Content loading please wait...");
                    $(".modal").modal("show");
                    $(".modal").modal({
                        backdrop: 'static',
                        keyboard: false,
                    });
                    $(".modal .modal-body").load(booking_info_url);

                });
                $(".fully_booked").click(function () {
                    var roomId = $(this).parent().attr('room_id');
                    var pageTitle = "Booking Info";
                    console.log('room_id' + roomId);
                    var booking_info_url = '{{ url('/admin/get_full_booked_room_info/') }}' + '/' + roomId;
                    $(".modal .modal-title").html(pageTitle);
                    $(".modal .modal-body").html("Content loading please wait...");
                    $(".modal").modal("show");
                    $(".modal").modal({
                        backdrop: 'static',
                        keyboard: false,
                    });
                    $(".modal .modal-body").load(booking_info_url);

                });

                var year = $("#select_year").val();
                updateMonths(year);
                $("#select_year").change(function () {
                    updateMonths($(this).val());
                })
                function updateMonths(year) {
                    if (year == "{{ date("Y") }}") {
                        var disabled_months = "{{  \App\Settings::getDisabledMonths() }}";
                        var disabled_months_ar = disabled_months.split(',');

                        $("#select_month option").each(function () {
                            console.log("sd");
                            console.log("disabled_months_ar" + disabled_months_ar);
                            var month = $(this).val();
                            if (disabled_months_ar.indexOf(month) >= 0) {
                                $(this).attr('disabled', 'disabled');
                            }
                        })
                    } else {
                        $("#select_month option").each(function () {
                            $(this).attr('disabled', false);
                        })
                    }
                }

            </script>
@endsection