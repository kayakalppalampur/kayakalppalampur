<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu"
            data-keep-expanded="false"
            data-auto-scroll="true"
            data-slide-speed="200">
            <li @if(strpos(Request::path(), 'guest/booking/signup') !== false) class="active" @endif>
                <a href="{{ url('guest/booking/signup') }}">
                    <i class="fa fa-sign-in"></i>
                    <span class="title">Sign Up</span>
                </a>
            </li>
            <li @if(strpos(Request::path(), 'guest/booking/personal_details') !== false) class="active" @endif>
                <a href="{{ url('guest/booking/personal_details') }}">
                    <i class="fa fa-user-circle-o"></i>
                    <span class="title">Personal Details</span>
                </a>
            </li>
            <li @if(strpos(Request::path(), 'guest/booking/health_issues') !== false) class="active" @endif>
                <a href="{{ url('guest/booking/health_issues') }}">
                    <i class="fa fa-medkit"></i>
                    <span class="title">Health Issues</span>
                </a>
            </li>
            <li style="display: {{ $user->checkAccommodation() ? 'block' : 'none' }};"@if(strpos(Request::path(), 'guest/booking/accommodation') !== false) class="active" @endif>
                <a href="{{ url('guest/booking/accommodation') }}">
                    <i class="fa fa-medkit"></i>
                    <span class="title">Accommodation</span>
                </a>
            </li>
            <li @if(strpos(Request::path(), 'guest/booking/aggreement') !== false) class="active" @endif>
                <a href="{{ url('guest/booking/aggreement') }}">
                    <i class="fa fa-medkit"></i>
                    <span class="title">Agreement</span>
                </a>
            </li>

            <li @if(strpos(Request::path(), 'guest/booking/payment') !== false) class="active" @endif>
                <a href="{{ url('guest/booking/payment') }}">
                    <i class="fa fa-medkit"></i>
                    <span class="title">Payment</span>
                </a>
            </li>

            <li @if(strpos(Request::path(), 'guest/booking/confirm') !== false) class="active" @endif>
                <a href="{{ url('guest/booking/confirm') }}">
                    <i class="fa fa-check"></i>
                    <span class="title">Confirm</span>
                </a>
            </li>
        </ul>
    </div>
</div>