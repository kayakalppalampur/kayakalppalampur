<div class="steps clearfix">
    <ul role="tablist">
        <li role="tab" class="first @if(strpos(Request::url(), 'bookings/'.$booking->id.'/personal-details') !== false) current @endif"  aria-disabled="false" aria-selected="true">
            @if($user->checkPersonalDetailsTab())
                <a aria-controls="bookingProcessForm-p-0" href="{{ url( 'bookings/'.$booking->id.'/personal-details/') }}" id="bookingProcessForm-t-0">
                   Personal Details
                </a>
            @else
                Personal Details
            @endif
        </li>
        <li role="tab" class="first @if(strpos(Request::url(), 'booking/'.$user->id.'/health_issues') !== false) current @endif"  aria-disabled="false" aria-selected="true">
            @if($user->checkHealthIssuesTab())
                <a aria-controls="bookingProcessForm-p-0" href="{{ url('booking/'.$booking->id.'/health_issues') }}" id="bookingProcessForm-t-0">
                   Health Issues
                </a>
            @else
                Health Issues
            @endif
        </li>
        @if($user->checkAccommodation())
        <li role="tab" class="first @if(strpos(Request::path(), 'booking/'.$booking->id.'/accommodation') !== false) current @endif"  aria-disabled="false" aria-selected="true">
            @if($user->checkAccomodationTab())
                <a aria-controls="bookingProcessForm-p-0" href="{{ url('booking/'.$booking->id.'/accommodation') }}" id="bookingProcessForm-t-0">
                    Accommodation
                </a>
            @else
                Accommodation
            @endif
        </li>
        @endif
        <li role="tab" class="first @if(strpos(Request::path(), 'booking/'.$booking->id.'/payment') !== false) current @endif"  aria-disabled="false" aria-selected="true">
            @if($user->checkPaymentTab())
                <a aria-controls="bookingProcessForm-p-0" href="{{ url('booking/'.$booking->id.'/payment') }}" id="bookingProcessForm-t-0">
                    Payment
                </a>
            @else
                Payment
            @endif
        </li>
        <li role="tab" class="first @if(strpos(Request::path(), 'booking/'.$booking->id.'/confirm') !== false) current @endif"  aria-disabled="false" aria-selected="true">
           <a aria-controls="bookingProcessForm-p-0" href="{{ url('booking/'.$booking->id.'/confirm') }}" id="bookingProcessForm-t-0">
               Confirm
            </a>
        </li>
    </ul>
</div>

