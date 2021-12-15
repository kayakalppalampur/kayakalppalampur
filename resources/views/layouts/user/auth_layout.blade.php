<!DOCTYPE html>
<html lang="en">
@include('layouts.front.web_head')
@include('layouts.user.user_header')
<body>
<div class="main_wrapper">
@include('layouts.user.user_sidebar')
@yield('content')
</body>
<!-- Scripts -->
@include('layouts.front.web_foot')
</body>
</html>









