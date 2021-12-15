@if(isset($rooms_status_arr['month_data']) && !empty($rooms_status_arr['month_data']))
    @php
        $days = '';
        $days_names = '';
        $blank_tds = '';
    @endphp
    @for($days_number=1;$days_number<=$rooms_status_arr['month_data']['month_date_count'];$days_number++)
        @php
            $days.= '<th>'.$days_number.'</th>';
            $loop_date = $rooms_status_arr['month_data']['year'].'-'.$rooms_status_arr['month_data']['month'].'-'.$days_number;
            $days_names.= '<td>'.date('D', strtotime($loop_date)).'</td>';
            $blank_tds.='<td></td>';
        @endphp
    @endfor
@endif
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
                <table  class="table_outer"align="center" cellpadding="0" cellspacing="0" width="100%" height="100%">
            <tr>
                <th class="heading">ACCOMMODATION STATUS CHART - PERIOD WISE</th>
            </tr>
            <tr>
                <td class="table selectyear">
                    <table cellpadding="0" cellspacing="0" >
                        <tr>
                            <td align="right"><strong>Select Month & Year</strong></td>
                            <td align="left">
                                {!! Form::open(['route' => 'guest.bookingMw','class' => 'ui form']) !!}
                                {{ csrf_field() }}
                                <div class="field ">
                                    {!! Form::label('text', 'Select Month & Year') !!}
                                    {!! Form::text('select_month_year',old('select_month_year'),['required','id'=>'datepicker_month']) !!}
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
                <td class="table  color_meaning">
                    <table cellpadding="0" cellspacing="0" >
                        <tr>
                            <td align="right"><strong>Colour Meaning</strong></td>
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
            $booking_form_title = 'Book a room / bed window';
        @endphp
        @if(isset($rooms_status_arr['rooms_data']) && !empty(isset($rooms_status_arr['rooms_data'])))
            @foreach($rooms_status_arr['rooms_data'] as $room_data)
                <tr>
                    <td>{{ $room_data['building'] }}</td>
                    <td>{{ $room_data['room_type'] }}</td>
                    <td>{{ $room_data['room_number'] }}</td>
                    @if(isset($room_data['days_bookings']) && !empty($room_data['days_bookings']))
                        @foreach($room_data['days_bookings'] as $day_bookings)
                            @if((isset($day_bookings[0]) && ($day_bookings[0]['booking_type'] ==2 || $day_bookings[0]['booking_type']==3)) || (isset($day_bookings[1]) && ($day_bookings[1]['booking_type'] ==2 || $day_bookings[1]['booking_type'] ==3)))
                                <td class="single_occupancy booked" pageTitle="Booking Information" bookingId="{{ $day_bookings[0]['booking_id'] }}">SO</td>
                            @else
                                @if(count($day_bookings) == 2)
                                    <td class="fully_booked booked" bookingId="{{ $day_bookings[0]['booking_id'] }}-{{ $day_bookings[1]['booking_id'] }}">B</td>
                                @elseif(count($day_bookings) == 1)
                                    <td class="partial_booked" bookingId="{{ $day_bookings[0]['booking_id'] }}">PB (@if($day_bookings[0]['user_gender'] == 1) F @elseif($day_bookings[0]['user_gender'] == 2) M @endif )</td>
                                @else
                                    <td class="vacant" data-toggle="modal" pageTitle="{{ $booking_form_title }}" >V</td>
                                @endif
                            @endif
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