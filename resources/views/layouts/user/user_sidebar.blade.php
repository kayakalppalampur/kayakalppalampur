<div class="sideNavBar">
    {{--<div class="sidenar-toggle">
        <img src="images/toggle-left.png" alt="" />
    </div>--}}
    <div class="sideUser_info">
        <div class="user_img_sec">
            @if(isset($user->userProfile->profile_picture))
                <img src="{{ \App\Settings::getImageUrl($user->userProfile->profile_picture) }}">
            @else
                <img src="{{ asset('images/pic.png')}}">
            @endif
        </div>
        <h3>{{ $user->name }}</h3>
    </div>

    <ul class="sideNav_links">
        <li>
            <a href="#">
                <i class="fa fa-dashboard"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="{{\Route::getCurrentRoute()->getPath() == "home" ? "active" : ""}}" style="background-color: {{ \Route::getCurrentRoute()->getPath() == "home"  ? \App\Http\Controllers\Laralum\Laralum::settings()->header_color .' !important; ' : ''}}">
            <a href="{{ url('home') }}">
                <i class="fa fa-user"></i>
                <span>My Profile</span>
            </a>
        </li>
        @if(\Auth::user()->isPatient())
            <li class="{{\Route::getCurrentRoute()->getPath() == "user/booking-detail" ? "active" : ""}}" style="background-color: {{ \Route::getCurrentRoute()->getPath() == "user/booking-detail"  ? \App\Http\Controllers\Laralum\Laralum::settings()->header_color .' !important; ' : ''}}">
                <a href="{{ url('user/booking-detail') }}">
                    <i class="fa fa-user"></i>
                    <span>Booking Details</span>
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
            </a>
        </li>--}}
    </ul>
    <div class="footer_logo">Kayakalp</div>
</div>