<ul class="sideNav_links">
    <li class="{{\Route::getCurrentRoute()->getPath() == "home" ? "active" : ""}}"
        style="background-color: {{ \Route::getCurrentRoute()->getPath() == "home"  ? \App\Http\Controllers\Laralum\Laralum::settings()->header_color .' !important; ' : ''}}">
        <a href="{{ url('home') }}">
            <i class="fa fa-user"></i>
            <span>My Profile</span>
        </a>
    </li>
  
    @if(\Auth::user()->isPatient())
        <li class="{{\Route::getCurrentRoute()->getPath() == "user/bookings" ? "active" : ""}}"
            style="background-color: {{ \Route::getCurrentRoute()->getPath() == "user/booking-detail"  ? \App\Http\Controllers\Laralum\Laralum::settings()->header_color .' !important; ' : ''}}">
            <a href="{{ url('user/bookings') }}">
                <i class="fa fa-user"></i>
                <span>My Bookings</span>
            </a>
        </li>
    @endif
    <li class="{{\Route::getCurrentRoute()->getPath() == "user/change-password" ? "active" : ""}}"
        style="background-color: {{ \Route::getCurrentRoute()->getPath() == "user/change-password"  ? \App\Http\Controllers\Laralum\Laralum::settings()->header_color .' !important; ' : ''}}">
        <a href="{{ url('user/change-password') }}">
            <i class="fa fa-key"></i>
            <span>Change Password</span>
        </a>
    </li>
    {{--<li>
        <a href="#">
            <i class="fa fa-book"></i>
            <span>Appointments</span>
        </a>
    </li>
    <li>
        <a href="#">
            <i class="fa fa-calendar"></i>
            <span>Shedule</span>
        </a>khana
    </li>--}}
</ul>