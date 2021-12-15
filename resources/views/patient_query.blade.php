@extends('layouts.front.web_layout')
@section('content')
<div class="admin_wrapper signup">
    <header>
        <div class="logo_wrapper wow fadeInDown">
            <a href="{{ url('/') }}">  <h1> Kayakalp </h1></a>
        </div>
    </header>
    <div class="container">
        <div class="row">
            <div class="query-form-container">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <h4>Please check the errors below:</h4>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                    @if (session('success'))
                        <div class="alert alert-success">
                            {!! session('success') !!}
                        </div>
                    @endif
                    @if (session('status') == 'success')
                        <div class="alert alert-success">
                           {!! session('message') !!}
                        </div>
                    @endif
                {!! Form::open(array('route' => 'patient.query.store', 'id' => 'bookingProcessForm','files'=>true,'method'=>'post')) !!}
                    {{--<form id="bookingProcessForm" action="{{ route('guest.booking') }}" method=t.ost">--}}
                    {{ csrf_field() }}
                    <h2 class="text-center"></i>Patient Query form</h2>
                    <section>
                        <div class="pro_main_content">
                            <div class="row">
                                <div class="col-md-6 col-md-offset-3 ">
                                    <div class="about_sec white_bg signup_bg">
                                        <h3 class="title_3">Please fill the details</h3>
                                        <div class="form-group">
                                            <input class="user_namer form-control required" type="text" value="{{ old('name') }}" name="name" id="name" placeholder="Name" autofocus>
                                        </div>
                                        <div class="form-group">
                                            <input class="user_email form-control required" type="email" name="email_id" value="{{ old('email_id') }}" id="email" placeholder="Email Id">
                                        </div>
                                        <div class="form-group">
                                            <input class="user_email form-control required" type="text" name="title" value="{{ old('title') }}" id="email" placeholder="Subject">
                                        </div>
                                        <div class="form-group">
                                           <textarea rows="7" paceholder="Your Query" class="form-control" name="description">{{ old("description") }}</textarea>
                                        </div>
                                        <div class="form-group col-md-offset-5">
                                            <button type="submit" class="btn btn-success">Submit  </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </section>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<style>
    .query-form-container {
        margin-top: 80px;
    }
    footer {
        width:100% !important;
        left:0 !important;
    }
</style>
@include('layouts.front.booking_footer')
@endsection
@section('script')

<script>
     $("#first_name").change(function () {
         $("#first_name_profile").val($(this).val());
         $("#first_name_profile_data").text($(this).val());
     });

    $("#last_name").change(function () {
        $("#last_name_profile").val($(this).val());
        $("#first_name_profile_data").text($(this).val());
    });

    $("#country").change(function () {
        var country = $("#country").val();
        var country_name = $("#country option :selected").text();
        $("#country_code").val(country);
        $("#user_country_code").val(country);
        $("#user_country_data").val(country_name);
    });

    $("input, textarea").change(function (){
        var val = $(this).val();
        var attr = $(this).attr('id');
        $("#"+attr+"_data").text(val);
    });

    $("select").change(function () {
        var val = $(this).find(':selected').text();
        var attr = $(this).attr('id');
        $("#"+attr+"_data").text(val);
    });

    function showImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                var img = '<img src="'+e.target.result+'" alt="">';
                $('.profile_img_Sec').html(img);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#profile_picture").change(function(){
        showImage(this);
    });

</script>
@endsection