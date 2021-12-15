@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Profile</div>
    </div>
@endsection
@section('title', 'Profile')
@section('icon', "pencil")
@section('subtitle', 'Profile')

@section('content')
<div class="main_content_area">
 <!-- <div class="main_title white_bg">
        <h2>My Profile</h2>
    </div>-->
<form id="profile-form" action="{{ url('user/profile/update') }}" method="POST">
{{ csrf_field() }}

    @if(isset($user->userProfile->first_name))
        <input type="hidden" name="patient_type" value="{{ $user->userProfile->patient_type}}">
        <input type="hidden" name="age" value="{{ $user->userProfile->age}}">
        <input type="hidden" name="profession_id" value="{{ $user->userProfile->profession_id}}">
        <input type="hidden" name="relative_name" value="{{ $user->userProfile->relative_name}}">
        <input type="hidden" name="marital_status" value="{{ $user->userProfile->marital_status}}">
        <input type="hidden" name="country_code" value="{{ $user->userProfile->country_code}}">
        <input type="hidden" name="health_issues" value="{{ $user->userProfile->health_issues}}">
        <input type="hidden" name="kid" value="{{ $user->userProfile->kid}}">
    @endif
    <div class="profoile_top_sec">
        <div class="profile_img_Sec" id="profile-picture">
            @if(isset($user->userProfile->profile_picture))
                <img src="{{ \App\Settings::getImageUrl($user->userProfile->profile_picture) }}">
            @else
                <img src="{{ asset('images/pic.png')}}" onError="this.onerror=null;this.src='{{ asset('images/pic.png')}}';">
            @endif
			<div class="browse_image"> 
       			<i class="fa fa-image"></i> 
       			<input type="file" name="profile_picture" id="profile_picture_input" onchange="update()"/>
       		</div>
        </div>
        <div class="profile_top_content">
            <h3><span id="name_data">{{ isset($user->userProfile->first_name) ? $user->userProfile->first_name.' '.$user->userProfile->last_name : $user->name }}</span>
                <div class="pro_name_wrap">
                    <span class="dropdown-toggle edit_span" data-toggle="dropdown" style="color: {{ \App\Http\Controllers\Laralum\Laralum::settings()->header_color .' !important; ' }};"><i class="fa fa-edit"></i></span>
                    <div class="dropdown-menu profilename_edit">
                            <div class="form-group">
                                <label>name</label>
                                <input class="form-control" value="{{ isset($user->userProfile->first_name) ? $user->userProfile->first_name : "" }}" name="first_name" class="form-control" placeholder="Peter" type="text" id="first_name">
                            </div>
                            <div class="form-group">
                                <label>Last name</label>
                                <input class="form-control" value="{{ isset($user->userProfile->last_name) ? $user->userProfile->last_name : "" }}" name="last_name" class="form-control" placeholder="Johnson" type="text">
                            </div>
                        <div class="form-group">
                            <label>Designation</label>
                            <input class="form-control" value="{{ isset($user->userProfile->designation) ? $user->userProfile->designation : " " }}" name="designation" class="form-control" placeholder="Johnson" type="text">
                        </div>
                            <div class="editt_row rel">
                                <button class="btn btn-save-edit" style="background-color: {{ \App\Http\Controllers\Laralum\Laralum::settings()->header_color .' !important; ' }};border:1px solid {{  \App\Http\Controllers\Laralum\Laralum::settings()->header_color .' !important; '}}" onclick="update()">SAVE</button>
                                <span>
                                <button class="btn btn-cancel-edit " id="cancel" style="color: {{ \App\Http\Controllers\Laralum\Laralum::settings()->header_color .' !important; ' }};border:1px solid {{  \App\Http\Controllers\Laralum\Laralum::settings()->header_color .' !important; '}}">CANCEL</button></span>
                            </div>
                    </div>
                </div>
            </h3>
            <h4 id="designation_data">{{ isset($user->userProfile->designation) ? $user->userProfile->designation : "" }}</h4>
        </div>
        <div class="follow_us_profile">
            <span>Follow me:</span>
            <div class="social_con">
                <a target="_blank" title="facebook" href="{{ isset($user->userProfile->facebook_url) ? '//'.$user->userProfile->facebook_url : "#" }}" id="facebook_url_data"><i class="fa fa-facebook-square"></i></a>
                <a target="_blank" title="twitter" href="{{ isset($user->userProfile->twitter_url) ? '//'.$user->userProfile->twitter_url : "#" }}" id="twitter_url_data"><i class="fa fa-twitter-square"></i></a>
                <a target="_blank" title="linkedin" href="{{ isset($user->userProfile->linkedin_url) ? '//'.$user->userProfile->linkedin_url : "#" }}" id="linkedin_url_data"><i class="fa fa-linkedin-square"></i></a>
                <a target="_blank" title="google+" href="{{ isset($user->userProfile->google_plus_url) ? '//'.$user->userProfile->google_plus_url : "#" }}" id="google_plus_url_data"><i class="fa fa-google-plus-square"></i></a>
            </div>
        </div>
    </div>
    <div class="pro_main_content">
        <div class="row">
            <div class="col-md-8">
                <div class="about_sec white_bg">
                    <h3 class="title_3">About us
                    <span class="edit pull-right" style="color: {{ \App\Http\Controllers\Laralum\Laralum::settings()->header_color .' !important; ' }};"><i class="fa fa-edit"></i></span>
                    </h3>
                    <p id="about_data">
                    @if(isset($user->userProfile->about))
                        {!! $user->userProfile->about  !!}
                    @endif
                    </p>
                    <div id="about-form" class="form" style="display: none;">
						<div class="form-group full_width">
                        	<textarea cols="70" rows="10" id="about" name="about">{!! isset($user->userProfile->about) ? $user->userProfile->about  : ""  !!}</textarea>
                        </div>
                        <div class="editt_row rel">
                            <button class="btn btn-save-edit" style="background-color: {{ \App\Http\Controllers\Laralum\Laralum::settings()->header_color .' !important; ' }};border:1px solid {{  \App\Http\Controllers\Laralum\Laralum::settings()->header_color .' !important; '}}" onclick="update('about-form')">SAVE</button>
                            <button class="btn btn-cancel-edit cancel" style="color: {{ \App\Http\Controllers\Laralum\Laralum::settings()->header_color .' !important; ' }};border:1px solid {{  \App\Http\Controllers\Laralum\Laralum::settings()->header_color .' !important; '}}">CANCEL</button>
                        </div>
                    </div>


                </div>
            </div>
            <div class="col-md-4">
                <div class="basic_profile_info white_bg">
                    <h3 class="title_3">Basic Information
                        <span class="edit pull-right" style="color: {{ \App\Http\Controllers\Laralum\Laralum::settings()->header_color .' !important; ' }};"><i class="fa fa-edit"></i></span>
                    </h3>
                    <div class="data">
                    <div class="add_list">
                        <strong>User Name:</strong> <span id="username_data">{{ $user->name }}</span>
                    </div>
                    <div class="add_list">
                        <strong>Email:</strong> <span id="email_data">{{ $user->email }}</span>
                    </div>
                    <div class="add_list">
                        <strong>Mobile:</strong> <span id="mobile_data">{{ isset($user->userProfile->mobile) ? $user->userProfile->mobile : ""}}</span>
                    </div>
                    <div class="add_list">
                        <strong>Landline Number:</strong> <span id="landline_number_data">{{ isset($user->userProfile->landline_number) ? $user->userProfile->landline_number : ""}}</span>
                    </div>
                    <div class="add_list">
                        <strong>WhatsApp Number:</strong> <span id="whatsapp_number_data">{{ isset($user->userProfile->whatsapp_number) ? $user->userProfile->whatsapp_number : ""}}</span>
                    </div>
                </div>
                    <div id="basic-form" style="display: none;" class="form profile_form">
						<div class="form-group">
                        	<input class="form-control" type="text" required name="username" id="username" value="{{ $user->name }}" placeholder="User Name">
                        </div>
                        <div class="form-group">
                        	<input class="form-control" type="email" required name="email" id="email" value="{{ $user->email }}" placeholder="Email">
						</div>
                        <div class="form-group">
                        	<input class="form-control" type="text" name="mobile" id="mobile" value="{{ isset($user->userProfile->mobile) ? $user->userProfile->mobile : ""}}" placeholder="Mobile">
						</div>
                        <div class="form-group">
                        	<input class="form-control" type="text" name="landline_number" id="landline_number" value="{{ isset($user->userProfile->landline_number) ? $user->userProfile->landline_number : ""}}" placeholder="Landline Number">
						</div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="whatsapp_number" id="whatsapp_number" value="{{ isset($user->userProfile->whatsapp_number) ? $user->userProfile->whatsapp_number : ""}}" placeholder="Whatsapp Number">
                        </div>
                        <div class="editt_row rel">
                            <button class="btn btn-save-edit" style="background-color: {{ \App\Http\Controllers\Laralum\Laralum::settings()->header_color .' !important; ' }};border:1px solid {{  \App\Http\Controllers\Laralum\Laralum::settings()->header_color .' !important; '}}" onclick="update('basic-form')">SAVE</button>
                            <button class="btn btn-cancel-edit cancel" style="color: {{ \App\Http\Controllers\Laralum\Laralum::settings()->header_color .' !important; ' }};border:1px solid {{  \App\Http\Controllers\Laralum\Laralum::settings()->header_color .' !important; '}}">CANCEL</a>
                        </div>
                    </div>

                </div>
                <div class="basic_profile_info white_bg">
                    <h3 class="title_3">Additional Information<span class="edit pull-right" style="color: {{ \App\Http\Controllers\Laralum\Laralum::settings()->header_color .' !important; ' }};"><i class="fa fa-edit"></i></span></h3>
                    <div class="data">
                    <div class="add_list">
                        <strong>Gender:</strong> <span id="gender_data">{{ isset($user->userProfile->gender) ? \App\UserProfile::getGenderOptions($user->userProfile->gender ) : ""}}</span>
                    </div>
                    <div class="add_list">
                        <strong>Date of Birth:</strong> <span id="dob_data">{{ isset($user->userProfile->dob) ? $user->userProfile->dob : ""}}</span>
                    </div>
                    <div class="add_list">
                        <strong>Location:</strong> <span id="location_data">{{ isset($user->address->address1) ? $user->address->address1 : ""}}</span>
                    </div>
                    </div>
                    <div id="additional-form" style="display: none;" class="form profile_form">
                        <div class="form-group">
                            <select name="gender" class="form-control">
                                <option>Select Gender</option>
                                @foreach(\App\UserProfile::getGenderOptions() as $key => $val)
                                    <option {{  isset($user->userProfile->gender) ? $user->userProfile->gender == $key ? "selected": "":""}} value="{{ $key }}"> {{  $val }}</option>
                                @endforeach
                            </select>
						</div>
                        <div class="form-group">                        	
                        	<input class="form-control datepicker" type="text" name="dob" data-date-format="dd-mm-yy" value="{{ isset($user->userProfile->dob) ? $user->userProfile->dob : ""}}" data-provide="datepicker" placeholder="Date of birth" data-date-end-date="0d">
						</div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="location" id="location" value="{{ isset($user->address->address1) ? $user->address->address1 : ""}}" placeholder="Location">
                        </div>
                       <div class="form-group">
                        	<input class="form-control" type="text" name="facebook_url" id="facebook_url" value="{{ isset($user->userProfile->facebook_url) ? $user->userProfile->facebook_url : ""}}" placeholder="Facebook Url">
						</div>
                       <div class="form-group">
                        <input class="form-control" type="text" name="twitter_url" id="twitter_url" value="{{ isset($user->userProfile->twitter_url) ? $user->userProfile->twitter_url : ""}}" placeholder="Twitter Url">
						</div>
                       <div class="form-group">
                        	<input class="form-control" type="text" name="linkedin_url" id="location" value="{{ isset($user->userProfile->linkedin_url) ? $user->userProfile->linkedin_url : ""}}" placeholder="Linkedin url">
						</div>
                       <div class="form-group">
                        <input class="form-control" type="text" name="google_plus_url" id="google_plus_url" value="{{ isset($user->userProfile->google_plus_url) ? $user->userProfile->google_plus_url : ""}}" placeholder="Google plus url">
						</div>
                        <div class="editt_row rel">
                            <button class="btn btn-save-edit" style="background-color: {{ \App\Http\Controllers\Laralum\Laralum::settings()->header_color .' !important; ' }};border:1px solid {{  \App\Http\Controllers\Laralum\Laralum::settings()->header_color .' !important; '}}" onclick="update('additional-form')">SAVE</button>

                            <button class="btn btn-cancel-edit cancel" style="color: {{ \App\Http\Controllers\Laralum\Laralum::settings()->header_color .' !important; ' }};border:1px solid {{  \App\Http\Controllers\Laralum\Laralum::settings()->header_color .' !important; '}}">CANCEL</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
</div>

@endsection
@section('js')
    <script>
        $(".datepicker").datepicker({"dateFormat":"dd-mm-yy", "maxDate":"0d"});
        $("#profile-form").submit(function(){
            return false;
        });
     function update(type) {
            if (!$("#profile-form")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#profile-form").find(':submit').click()
            }else {
                var formData = new FormData($("#profile-form")[0]);

                $.ajax({
                    url: $("#profile-form").attr('action'),
                    type: 'POST',
                    data: formData,
                    async: false,
                    success: function (response) {
                        $(".pro_name_wrap").removeClass("open");
                        $("#" + type).hide();
                        $("#" + type).parent().find(".data").show();
                        updateFields(response.data);
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });

                return false;
            }
         return false;
        }

       function showImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#profile_picture img').attr('src', e.target.result);
                    $('.user_img_sec img').attr('src', e.target.result);

                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#profile_picture_input").change(function(){
            showImage(this);
        });

        $("#cancel").click(function () {
            $(".pro_name_wrap").removeClass("open");
        })
        $('.cancel').click(function () {
            $(this).parent().parent().hide();
            $(this).parent().parent().parent().find('.data').show();
        })


        $(".edit").click(function () {
            $data = $(this).parent().parent().find(".data");
            $form = $(this).parent().parent().find(".form");
            $all_form = $(".form").not($form);
            $all_data = $(".data").not($data);

            $data.toggle();
            $form.toggle();
            $all_form.hide();
            $all_data.show();

        })

        function updateFields(data) {
            console.log(data);
            //Set data
            if( data.user_profile.profile_picture != '') {
                $("#profile-picture img").attr('src', data.user_profile.profile_picture);
            }

            $("#username_data").html(data.name);
            $("#name_data").html(data.user_profile.first_name + ' '+data.user_profile.last_name);
            $("#email_data").html(data.email);
            $("#mobile_data").html(data.user_profile.mobile);
            $("#office_data").html(data.user_profile.office);
            var gender = data.user_profile.gender;
            if(gender == "{{ \App\UserProfile::GENDER_FEMALE }}")
                $("#gender_data").html("Female");
            else
                $("#gender_data").html("Male");

            $("#dob_data").html(data.user_profile.dob);
            $("#location_data").html(data.user_profile.location);
            $("#about_data").html(data.user_profile.about);
            $("#designation_data").html(data.user_profile.designation);
            $("#facebook_url_data").attr('href',data.user_profile.facebook_url);
            $("#twitter_url_data").attr('href',data.user_profile.twitter_url);
            $("#linkedin_url_data").attr('href',data.user_profile.linkedin_url);
            $("#google_plus_url_data").attr('href',data.user_profile.google_plus_url);
            $("#landline_number_data").html(data.user_profile.landline_number);
            $("#whatsapp_number_data").html(data.user_profile.whatsapp_number);

            //Set values
            $("#username").val(data.name);
            $("#first_name").val(data.user_profile.first_name);
            $("#designation").val(data.user_profile.designation);
            $("#last_name").val(data.user_profile.last_name);
            $("#email").val(data.email);
            $("#mobile").val(data.user_profile.mobile);
            $("#office").val(data.user_profile.office);
            $("#gender").val(data.user_profile.gender);
            $("#dob").val(data.user_profile.dob);
            $("#location").val(data.user_profile.location);
            $("#about").val(data.user_profile.about);
            $("#facebook_url").val(data.user_profile.facebook_url);
            $("#twitter_url").val(data.user_profile.twitter_url);
            $("#linkedin_url").val(data.user_profile.linkedin_url);
            $("#google_plus_url").val(data.user_profile.google_plus_url);
            $("#landline_number").val(data.user_profile.landline_number);
            $("#whatsapp_number").val(data.user_profile.whatsapp_number);
            return false;
        }

  $(document).ready(function(){
    $('.pro_name_wrap .fa-edit').click(function(){
        $('.pro_name_wrap').toggleClass('open');
    });
  });



    </script>


@endsection

