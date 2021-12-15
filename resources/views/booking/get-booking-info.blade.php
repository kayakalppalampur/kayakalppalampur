<div class="booking_info_page">
    <div class="content_Box">
        <div class="content_BoxIN">
            @if(!empty($booked_info))
                @foreach($booked_info as $booking)
                    <p>Patient Name: {{ $booking->first_name }} {{ $booking->last_name }}</p>
                    <p>Floor number: {{ $booking->room->getFloorNumber($booking->floor_number) }}</p>
                    <p>Room number: {{ $booking->room_number }}</p>
                    <!--1 => SingleBed, 2 => SingleOccupancy, 3=>SingleOccupancyWithExtraBed, 4- DoubleBedWithSharing-->
                    @php $booking_type = $booking->getBookingType($booking->booking_type); @endphp
                    <p>Booking type: {{ $booking_type }}</p>
                    <p>Staying: From: {{ date('d M, Y',strtotime($booking->check_in_date)) }} to: {{ date('d M, Y',strtotime($booking->check_out_date)) }}</p>
                @endforeach
            @endif
        </div>
    </div>
</div>
