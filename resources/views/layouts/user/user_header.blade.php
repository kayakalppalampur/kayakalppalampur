<header style="background-color:{{ \App\Http\Controllers\Laralum\Laralum::settings()->header_color }}">
    <div class="logo_wrapper">
        <h1>Kayakalp</h1>
    </div>

    <div class="header_right">
        <div class="header_icons_right">
            <ul>
                <li><a href="#"><i class="fa fa-bell-o"></i></a></li>
                <li><a href="#"><i class="fa fa-envelope"></i></a></li>
                <li class="dropdown">
                    <a class="btn dropdown-toggle" type="button" data-toggle="dropdown" href="#"><i class="fa fa-gear"></i> <span class="caret"></span> </a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms</a></li>
                        <li><a href="#">Change Password</a></li>
                    </ul>
                </li>

                <li>

                    <a href="{{ url('/logout') }}"
                       onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();"
                    > <i class="fa fa-sign-out"></i></a></a>
                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form></li>
            </ul>
        </div>

        <nav>
            <ul>
                @if(Laralum::loggedInUser()->isAdmin() || Laralum::loggedInUser()->hasPermission('dashboard'))
                    <li>
                        <a href="{{ route('Laralum::dashboard') }}">Administration</a>
                    </li>
                @endif
                <li><a href="{{ url('/home') }}">Home</a></li>
                {{--<li><a href="#">Registration</a></li>
                <li><a href="#">Token</a></li>
                <li><a href="#">Discharge</a></li>
                <li><a href="#">Tips</a></li>--}}
            </ul>
        </nav>

    </div>

</header>
