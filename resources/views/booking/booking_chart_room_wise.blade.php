@extends('layouts.front.web_layout')
@section('content')
    <div class="admin_wrapper signup">
        <header>
            <div class="logo_wrapper wow fadeInDown">
                <h1>Kayakalp</h1>
            </div>
        </header>
        <div class="wrapper">
            <div class="chart_container">
                <table  class="table_outer" align="center" cellpadding="0" cellspacing="0" width="100%" height="100%">
                    <tr>
                        <th class="heading">ACCOMMODATION STATUS CHART - ROOM WISE</th>
                    </tr>
                    <tr>
                        <td>AS OF <span>"%date%"</span></td>
                    </tr>
                    <tr>
                        <td class="table">
                            <table cellpadding="0" cellspacing="0" >
                                <tr>
                                    <td><a href="#">« Previous Day</a></td>
                                    <td>
                                        {!! Form::open(['route' => 'guest.bookingRm','class' => 'ui form']) !!}
                                        {{ csrf_field() }}
                                        <div class="field ">
                                            {!! Form::label('text', 'See for date') !!}
                                            {!! Form::text('select_date',old('select_date',$default_date),['required','id'=>'datepicker']) !!}
                                        </div>
                                        <!--input type="text" id="datepicker"-->
                                        <div class="field ">
                                            {!! Form::submit('Filter') !!}
                                        </div>
                                        {!! Form::close() !!}
                                    </td>
                                    <td><a href="#">Next Day »</a></td>
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
                                            @if((isset($room_data['booking_status'][0]) && $room_data['booking_status'][0]['booking_type'] ==3) || (isset($room_data['booking_status'][1]) && $room_data['booking_status'][1]['booking_type'] ==3))
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
                                            @endif
                                            <tr>
                                                <td class="{{ $room_book_status }}">{{ $room_data['room_number'] }}</td>
                                                <td>@if($room_data['floor_number']==1) GF @elseif($room_data['floor_number']==2) FF @else {{ $room_data['floor_number'] }} @endif</td>
                                                <td>{{ $room_data['room_type_short_name'] }}</td>
                                                @php
                                                    $booking_form_title = 'Book a room / bed window';
                                                @endphp
                                                @if(!empty($room_data['booking_status']))
                                                    @if((isset($room_data['booking_status'][0]) && ($room_data['booking_status'][0]['booking_type'] ==2 || $room_data['booking_status'][0]['booking_type']==3)) || (isset($room_data['booking_status'][1]) && ($room_data['booking_status'][1]['booking_type'] ==2 || $room_data['booking_status'][1]['booking_type'] ==3)))
                                                        <td colspan="2" class="single_occupancy booked" pageTitle="Booking Information" bookingId="{{ $room_data['booking_status'][0]['booking_id'] }}">SO</td>
                                                    @else
                                                        @if(isset($room_data['booking_status'][0]))
                                                            <td class="booked" bookingId="{{ $room_data['booking_status'][0]['booking_id'] }}">{{ $room_data['booking_status'][0]['booking_type'] }}</td>
                                                        @else
                                                            <td class="vacant" data-toggle="modal" pageTitle="{{ $booking_form_title }}" >V</td>
                                                        @endif
                                                        @if(isset($room_data['booking_status'][1]))
                                                            <td class="booked" bookingId="{{ $room_data['booking_status'][1]['booking_id'] }}">{{ $room_data['booking_status'][1]['booking_type'] }}</td>
                                                        @else
                                                            <td class="vacant" data-toggle="modal" pageTitle="{{ $booking_form_title }}" >V</td>
                                                        @endif
                                                    @endif
                                                @else
                                                    <td class="vacant" data-toggle="modal" pageTitle="{{ $booking_form_title }}" >V</td>
                                                    <td class="vacant" data-toggle="modal" pageTitle="{{ $booking_form_title }}" >V</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endforeach
                            @endif

                            <table cellpadding="0" cellspacing="0" >
                                <tr>
                                    <td align="right">Cottage Deluxe</td>
                                    <td align="left">1M 1W / 2</td>
                                    <td align="right">Cottage Deluxe</td>
                                    <td align="left">1M 1W / 2</td>
                                    <td align="right">Cottage Deluxe</td>
                                    <td align="left">1M 1W / 2</td>
                                </tr>
                                <tr class="bottom_border">
                                    <td align="right">Cottage Deluxe</td>
                                    <td align="left">1M 1W / 2</td>
                                    <td align="right">Cottage Deluxe</td>
                                    <td align="left">1M 1W / 2</td>
                                    <td align="right">Cottage Deluxe</td>
                                    <td align="left">1M 1W / 2</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th class="over_status">ROOM WISE STATUS</th>
                    </tr>
                    <tr>
                        <td class="table">
                            <table cellpadding="0" cellspacing="0" >
                                <tr>
                                    <th align="right" class="text-right">Building Name</th>
                                    <th>KETAN</th>
                                    <th>NIKET</th>
                                    <th>NILAY</th>
                                    <th>BASERA</th>
                                </tr>
                                <tr class="bottom_border">
                                    <td align="right">Type</td>
                                    <td><strong>Cottage & Delux</strong></td>
                                    <td><strong>Deluxe Double Bed</strong></td>
                                    <td><strong>Doubel Bed Room</strong></td>
                                    <td><strong>Dormitory</strong></td>
                                </tr>
                                <tr class="bottom_border">
                                    <td class="table">
                                        <table cellpadding="0" cellspacing="0"  class="room_type">
                                            <tr>
                                                <th align="right" class="text-right">Room No.</th>
                                            </tr>
                                            <tr>
                                                <td  align="right" class="text-right">Type</td>
                                            </tr>
                                            <tr>
                                                <td  align="right">Bed-1</td>
                                            </tr>
                                            <tr>
                                                <td  align="right">Bed-2</td>
                                            </tr>
                                            <tr>
                                                <td  align="right">Bed-3</td>
                                            </tr>
                                            <tr>
                                                <td  align="right">Bed-4</td>
                                            </tr>
                                            <tr>
                                                <td  align="right">Bed-5</td>
                                            </tr>
                                            <tr>
                                                <td  align="right">Bed-6</td>
                                            </tr>
                                            <tr><td>&nbsp;</td></tr>
                                            <tr>
                                                <td  align="right" class="text-right">Extra Bed-1</td>
                                            </tr>
                                            <tr>
                                                <td  align="right" class="text-right">Extra Bed-2</td>
                                            </tr>
                                            <tr><td>&nbsp;</td></tr>
                                            <tr>
                                                <th align="right" class="text-right">Extra Services:</th>
                                            </tr>
                                            <tr>
                                                <td  align="right" class="text-right">Extra Service-1 (i.e. Heater)</td>
                                            </tr>
                                            <tr>
                                                <td  align="right" class="text-right">Extra Service-2 (I.e. Blower)</td>
                                            </tr>
                                            <tr>
                                                <td  align="right" class="text-right">Extra Service-3 (i.e. Heat Pillar)</td>
                                            </tr>
                                            <tr>
                                                <td  align="right" class="text-right">Extra Service-4 (i.e. Reverse Cycle A/C)</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="table table_coloum">
                                        <table cellpadding="0" cellspacing="0" >
                                            <tr>
                                                <th class="orange">101</th>
                                                <th class="blue">102</th>
                                                <th class="orange">103</th>
                                                <th class="orange">104</th>
                                                <th class="orange">105</th>
                                                <th class="orange">106</th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <td>CD</td>
                                                <td>CD</td>
                                                <td>C</td>
                                                <td>C</td>
                                                <td>C</td>
                                                <td>C</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td rowspan="2" class="brown">SO</td>
                                                <td class="orange">B</td>
                                                <td class="orange">B</td>
                                                <td class="orange">B</td>
                                                <td class="orange">B</td>
                                                <td class="orange">B</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td class="green left_white">V</td>
                                                <td class="orange">B</td>
                                                <td class="orange">B</td>
                                                <td class="orange">B</td>
                                                <td class="orange">B</td>
                                                <th>&nbsp;</th>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>

                                        </table>
                                    </td>
                                    <td class="table table_coloum">
                                        <table cellpadding="0" cellspacing="0" >
                                            <tr>
                                                <th class="green">101</th>
                                                <th class="orange">102</th>
                                                <th class="orange">103</th>
                                                <th class="blue">104</th>
                                                <th class="orange">105</th>
                                                <th class="orange">106</th>
                                                <th>&nbsp;</th>
                                            </tr>
                                            <tr>
                                                <td>DDB</td>
                                                <td>DDB</td>
                                                <td>DDB</td>
                                                <td>DDB</td>
                                                <td>DDB</td>
                                                <td>DDB</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td class="green">V</td>
                                                <td class="orange">B</td>
                                                <td class="orange">B</td>
                                                <td class="orange">B</td>
                                                <td class="orange">B</td>
                                                <td class="orange">B</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td class="green">V</td>
                                                <td class="orange">B</td>
                                                <td class="orange">B</td>
                                                <td class="green">V</td>
                                                <td class="orange">B</td>
                                                <td class="orange">B</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>

                                        </table>
                                    </td>
                                    <td class="table table_coloum">
                                        <table cellpadding="0" cellspacing="0" >
                                            <tr>
                                                <th>101</th>
                                                <th>102</th>
                                                <th>103</th>
                                                <th>104</th>
                                                <th>105</th>
                                                <th>106</th>
                                                <th>&nbsp;</th>
                                            </tr>
                                            <tr>
                                                <td>DB</td>
                                                <td>DB</td>
                                                <td>DB</td>
                                                <td>DB</td>
                                                <td>DB</td>
                                                <td>DB</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>

                                        </table>
                                    </td>
                                    <td class="table table_coloum">
                                        <table cellpadding="0" cellspacing="0" >
                                            <tr>
                                                <th>MAN</th>
                                                <th>WOMAN</th>
                                            </tr>
                                            <tr>
                                                <td>DR</td>
                                                <td>DR</td>
                                            </tr>
                                            <tr>
                                                <td class="orange">B</td>
                                                <td class="green">V</td>
                                            </tr>
                                            <tr>
                                                <td class="orange">B</td>
                                                <td class="orange">B</td>
                                            </tr>
                                            <tr>
                                                <td class="green">V</td>
                                                <td class="orange">B</td>
                                            </tr>
                                            <tr>
                                                <td class="green">V</td>
                                                <td class="orange">B</td>
                                            </tr>
                                            <tr>
                                                <td class="orange">B</td>
                                                <td class="green">V</td>
                                            </tr>
                                            <tr>
                                                <td class="orange">B</td>
                                                <td class="green">V</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table table_coloum">
                                        <table cellpadding="0" cellspacing="0" >
                                            <tr>
                                                <td>&nbsp;</td></tr>
                                            <tr><td>&nbsp;</td></tr>
                                            <tr><td>&nbsp;</td></tr>
                                            <tr><td>&nbsp;</td></tr>
                                            <tr><td>&nbsp;</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="table table_coloum">
                                        <table cellpadding="0" cellspacing="0" >
                                            <tr>
                                                <td>&nbsp;</td></tr>
                                            <tr><td>&nbsp;</td></tr>
                                            <tr><td>&nbsp;</td></tr>
                                            <tr><td>&nbsp;</td></tr>
                                            <tr><td>&nbsp;</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="table table_coloum">
                                        <table cellpadding="0" cellspacing="0" >
                                            <tr>
                                                <td><strong>Colour Coding</strong></td><tr>
                                            <tr  class="orange"><td><strong>FB (Fully Booked)</strong></td><tr>
                                            <tr  class="blue"><td><strong>PB (Partially Booked)</strong></td><tr>
                                            <tr  class="dark_yellow"><td><strong>EBA (Extra Bed Available)</strong></td><tr>
                                            <tr  class="green"><td><strong>FV (Fully Vacant)</strong></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="table table_coloum">
                                        <table cellpadding="0" cellspacing="0" >
                                            <tr>
                                                <td><strong>Character Coding</strong></td></tr>
                                            <tr class="orange"><td><strong>B = Booked</strong></td></tr>
                                            <tr class="green"><td><strong>V = Vacant</strong></td></tr>
                                            <tr class="brown"><td><strong>SO = Single Occupancy</strong></td></tr>
                                            <tr><td>&nbsp;</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="table table_coloum">
                                        <table cellpadding="0" cellspacing="0" >
                                            <tr>
                                                <td>&nbsp;</td></tr>
                                            <tr><td>&nbsp;</td></tr>
                                            <tr><td>&nbsp;</td></tr>
                                            <tr><td>&nbsp;</td></tr>
                                            <tr><td>&nbsp;</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
            </div>
        </div><!--wrapper ends here-->
    </div>
    <!-- Modal -->
    <div class="modal fade" id="bookingModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modal Header</h4>
                </div>
                <div class="modal-body">
                    <p>Some text in the modal.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
@endsection

