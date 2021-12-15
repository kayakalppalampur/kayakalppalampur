<div class="steps clearfix">
    <ul role="tablist">
        @if(!\Auth::user()->isPatient())
        <li role="tab" class="first @if(strpos(Request::path(), 'booking/registration/signup') !== false) current @endif"  aria-disabled="false" aria-selected="true">
            <a aria-controls="bookingProcessForm-p-0" href="{{ url(\Auth::user()->isPatient() ? 'user' : isset($reregister) ? 'admin'.'/booking/registration/signup?reregister='.$reregister : 'admin'.'/booking/registration/signup') }}" id="bookingProcessForm-t-0">
                Sign Up
            </a>
        </li>
        @endif

        <li role="tab" class="first @if(strpos(Request::path(), 'admin/booking/registration/personal_details/'.$user->id) !== false || strpos(Request::path(), 'user/booking/registration/personal_details/'.$user->id) !== false) current @endif"  aria-disabled="false" aria-selected="true">
            @if($user->checkPersonalDetailsTab())
                <a aria-controls="bookingProcessForm-p-0" href="{{ \Auth::user()->isPatient() ? url( 'user/booking/registration/personal_details/'.$user->id) :  url( isset($reregister) ? 'admin/booking/registration/personal_details/'.$user->id.'/reregister='.$reregister : 'admin/booking/registration/personal_details/'.$user->id) }}" id="bookingProcessForm-t-0">
                   Personal Details
                </a>
            @else
                Personal Details
            @endif
        </li>
        <li role="tab" class="first @if(strpos(Request::path(), 'booking/registration/health_issues/'.$booking->id) !== false || strpos(Request::path(), 'user/booking/registration/health_issues/'.$booking->id) !== false) current @endif"  aria-disabled="false" aria-selected="true">
            @if($user->checkHealthIssuesTab())
                @if($booking->id != '')
                    <a aria-controls="bookingProcessForm-p-0" href="{{ \Auth::user()->isPatient() ?  url('user/booking/registration/health_issues/'.$booking->id) :  url('admin/booking/registration/health_issues/'.$booking->id) }}" id="bookingProcessForm-t-0">
                       Health Issues
                    </a>
                @else
                     Health Issues
                @endif
            @else
                Health Issues
            @endif
        </li>
        @if($user->checkAccommodation())
        <li role="tab" class="first @if(strpos(Request::path(), 'admin/booking/registration/accommodation/'.$booking->id) !== false) current @endif"  aria-disabled="false" aria-selected="true">
            @if($user->checkAccomodationTab())
                @if($booking->id != '')
                    <a aria-controls="bookingProcessForm-p-0" href="{{  \Auth::user()->isPatient() ?  url('user/booking/registration/accommodation/'.$booking->id) :  url('admin/booking/registration/accommodation/'.$booking->id)}}" id="bookingProcessForm-t-0">
                        Accommodation
                    </a>
                @else
                     Accommodation
                @endif
            @else
                Accommodation
            @endif
        </li>
        @endif
        <li role="tab" class="first @if(strpos(Request::path(), 'admin/booking/registration/payment/'.$booking->id) !== false || strpos(Request::path(), 'user/booking/registration/payment/'.$booking->id) !== false) current @endif"  aria-disabled="false" aria-selected="true">
            @if($booking->checkPaymentTab())
                <a aria-controls="bookingProcessForm-p-0" href="{{  \Auth::user()->isPatient() ? url('user/booking/registration/payment/'.$user->id) :  url('admin/booking/registration/payment/'.$booking->id) }}" id="bookingProcessForm-t-0">
                    Payment
                </a>
            @else
                Payment
            @endif
        </li>
        <li role="tab" class="first @if(strpos(Request::path(), 'admin/booking/registration/confirm/'.$booking->id) !== false || strpos(Request::path(), 'user/booking/registration/confirm/'.$booking->id) !== false) current @endif"  aria-disabled="false" aria-selected="true">
            @if($user->checkConfirmTab())
           <a aria-controls="bookingProcessForm-p-0" href="{{  \Auth::user()->isPatient() ? url('user/booking/registration/confirm/'.$booking->id) : url('admin/booking/registration/confirm/'.$booking->id) }}" id="bookingProcessForm-t-0">
               Confirm
            </a>
            @else
                <a class="no-disable">Confirm</a>
            @endif
        </li>
            @if(!\Auth::user()->isPatient())
        <li role="tab" class="first @if(strpos(Request::path(), 'admin/booking/registration/generate-token/'.$booking->id) !== false) current @endif"  aria-disabled="false" aria-selected="true">
            @if($user->checkKidTab())
                <a aria-controls="bookingProcessForm-p-0" href="{{ url('admin/booking/registration/print-kid/'.$booking->id) }}" id="bookingProcessForm-t-0">
                    Print KID
                </a>
            @else
                Print KID
            @endif
        </li>
                @endif
    </ul>
</div>

