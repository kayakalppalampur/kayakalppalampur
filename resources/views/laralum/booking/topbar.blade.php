<div class="steps clearfix">
    <ul role="tablist">
        <li role="tab"
            class="first @if(strpos(Request::path(), 'admin/booking/'.$booking->id.'/show') !== false) || strpos(Request::path(), 'admin/opd-booking/'.$booking->id.'/show') !== false) current @endif"
            aria-disabled="false" aria-selected="true">
            @php
                if($booking->patient_type == \App\Booking::PATIENT_TYPE_IPD) {
                    if ($booking->accommodation_status == \App\Booking::ACCOMMODATION_STATUS_CONFIRMED) {
                        $route = route('Laralum::ipd.booking.show', ['booking_id' => $booking->id]);
                    } else {
                        $route = route('Laralum::future.booking.show', ['booking_id' => $booking->id]);
                    }
                   }
                else
                   $route = route('Laralum::opd.booking.show', ['booking_id' => $booking->id]);
            @endphp

            <a aria-controls="bookingProcessForm-p-0" href="{{ $route }}"
               id="bookingProcessForm-t-0" class="no-disable">
                Booking Details
            </a>

        </li>

        @php
            if (\Auth::user()->isPatient()){
                $route = url( 'user/booking/personal_details/'.$booking->id);
            }else{
                if($booking->patient_type == \App\Booking::PATIENT_TYPE_IPD){
                    if ($booking->accommodation_status == \App\Booking::ACCOMMODATION_STATUS_CONFIRMED) {
                        $route = route('Laralum::ipd.booking.personalDetails', ['booking_id' => $booking->id]);
                    } else {
                        $route = route('Laralum::future.booking.personal_details', ['booking_id' => $booking->id]);
                    }
                   }
                else
                   $route = url( 'admin/opd-bookings/personal_details/'.$booking->id);
            }
        @endphp

        <li role="tab"
            class="first @if(strpos(Request::url(), $route) !== false || strpos(Request::path(), 'admin/opd-bookings/personal_details/'.$booking->id) !== false || strpos(Request::path(), 'user/booking/personal_details/'.$booking->id) !== false) current @endif"
            aria-disabled="false" aria-selected="true">


            <a aria-controls="bookingProcessForm-p-0"
               href="{{ $route }}"
               id="bookingProcessForm-t-0" class="no-disable">
                Personal Details
            </a>
        </li>

        @php
            if (\Auth::user()->isPatient()){
                $route = url( 'user/booking/health_issues/'.$booking->id);
            }else{
                if($booking->patient_type == \App\Booking::PATIENT_TYPE_IPD){
                    if ($booking->accommodation_status == \App\Booking::ACCOMMODATION_STATUS_CONFIRMED) {
                        $route = route('Laralum::ipd.booking.health_issues', ['booking_id' => $booking->id]);
                    } else {
                        $route = route('Laralum::future.booking.health_issues', ['booking_id' => $booking->id]);
                    }
                   }
                else
                   $route = url( 'admin/opd-bookings/health_issues/'.$booking->id);
            }
        @endphp
        <li role="tab"
            class="first @if(strpos(Request::url(), $route) !== false || strpos(Request::path(), 'admin/opd-bookings/health_issues/'.$booking->id) !== false || strpos(Request::path(), 'user/booking/health_issues/'.$booking->id) !== false) current @endif"
            aria-disabled="false" aria-selected="true">

            <a aria-controls="bookingProcessForm-p-0"
               href="{{ $route }}"
               id="bookingProcessForm-t-0" class="no-disable">
                Health Issues
            </a>
        </li>

        @php
            if (\Auth::user()->isPatient()){
                $route = url( 'user/booking/health_issues/'.$booking->id);
            }else{
                if($booking->patient_type == \App\Booking::PATIENT_TYPE_IPD){
                    if ($booking->accommodation_status == \App\Booking::ACCOMMODATION_STATUS_CONFIRMED) {
                        $route = route('Laralum::ipd.booking.accommodation', ['booking_id' => $booking->id]);
                    } else {
                        $route = route('Laralum::future.booking.accommodation', ['booking_id' => $booking->id]);
                    }
                   }
                else
                   $route = url( 'admin/opd-bookings/accommodation/'.$booking->id);
            }
        @endphp

        @if($user->checkAccommodation($booking->id))
            <li role="tab"
                class="first @if(strpos(Request::url(), $route) !== false) current @endif"
                aria-disabled="false" aria-selected="true">
                <a aria-controls="bookingProcessForm-p-0" href="{{ $route }}"
                   id="bookingProcessForm-t-0" class="no-disable">
                    Accommodation
                </a>
            </li>
        @endif

        @php
            if (\Auth::user()->isPatient()){
                $route = url( 'user/booking/health_issues/'.$booking->id);
            }else{
                if($booking->patient_type == \App\Booking::PATIENT_TYPE_IPD){
                    if ($booking->accommodation_status == \App\Booking::ACCOMMODATION_STATUS_CONFIRMED) {
                        $route = route('Laralum::ipd.booking.payment', ['booking_id' => $booking->id]);
                    } else {
                        $route = route('Laralum::future.booking.payment', ['booking_id' => $booking->id]);
                    }
                   }
                else
                   $route = url( 'admin/opd-bookings/payment/'.$booking->id);
            }
        @endphp

        <li role="tab"
            class="first @if(strpos(Request::url(), $route) !== false || strpos(Request::path(), 'admin/opd-bookings/payment/'.$booking->id) !== false || strpos(Request::path(), 'user/booking/payment/'.$booking->id) !== false) current @endif"
            aria-disabled="false" aria-selected="true">

            <a aria-controls="bookingProcessForm-p-0"
               href="{{  $route }}"
               id="bookingProcessForm-t-0" class="no-disable">
                Payment
            </a>
        </li>


        @php
            if (\Auth::user()->isPatient()){
                $route = url( 'user/booking/confirm/'.$booking->id);
            }else{
                if($booking->patient_type == \App\Booking::PATIENT_TYPE_IPD){
                    if ($booking->accommodation_status == \App\Booking::ACCOMMODATION_STATUS_CONFIRMED) {
                        $route = route('Laralum::ipd.booking.confirm', ['booking_id' => $booking->id]);
                    } else {
                        $route = route('Laralum::future.booking.confirm', ['booking_id' => $booking->id]);
                    }
                   }
                else
                   $route = url( 'admin/opd-bookings/confirm/'.$booking->id);
            }
        @endphp

        <li role="tab"
            class="first @if(strpos(Request::url(), $route) !== false || strpos(Request::path(), 'admin/opd-bookings/confirm/'.$booking->id) !== false  || strpos(Request::path(), 'user/booking/confirm/'.$booking->id) !== false) current @endif"
            aria-disabled="false" aria-selected="true">

            <a aria-controls="bookingProcessForm-p-0"
               href="{{  $route }}"
               id="bookingProcessForm-t-0" class="no-disable">
                Confirm
            </a>
        </li>

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
                   $route = url( 'admin/opd-bookings/print-kid/'.$booking->id);
            }
        @endphp

        @if(!\Auth::user()->isPatient())
            <li role="tab"
                class="first @if(strpos(Request::url(), $route) !== false || strpos(Request::path(), 'admin/opd-bookings/print-kid/'.$booking->id) !== false) current @endif"
                aria-disabled="false" aria-selected="true">

                @if($booking->status == \App\Booking::STATUS_COMPLETED)
                    <a aria-controls="bookingProcessForm-p-0" href="{{ $route }}"
                       id="bookingProcessForm-t-0" class="no-disable">
                        Print KID
                    </a>
                @else
                    <a class="no-disable">
                        Print KID
                    </a>
                @endif
            </li>
        @endif
    </ul>
</div>

