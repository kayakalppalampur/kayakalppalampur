@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @if(!\Auth::user()->isUser())

            @php
                if($booking->patient_type == \App\Booking::PATIENT_TYPE_IPD) {
                    if ($booking->accommodation_status == \App\Booking::ACCOMMODATION_STATUS_CONFIRMED) {
                        $route = route('Laralum::ipd.bookings.list');
                    } else {
                        $route = route('Laralum::admin.future.patients.list');
                    }
                } else {
                    $route = route('Laralum::bookings');
                }
            @endphp

            <a class="section" href="{{ $route }}">{{ trans('laralum.booking_list') }}</a>

            <i class="right angle icon divider"></i>
        @endif
        @if($booking->booking_id != null)
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

            <a class="section"
               href="{{ $route }}">{{ trans('laralum.booking_details') }}</a>
            <i class="right angle icon divider"></i>
        @endif

        <div class="active section">Booking</div>
    </div>
@endsection
@section('title', 'Booking')
@section('icon', "pencil")
@section('subtitle', 'Booking')
@section('content')
    <link rel="stylesheet" type="text/css" media="screen"
          href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">
    <div class="admin_wrapper signup">
        <div class="main_wrapper">
            @include('laralum.booking.topbar')

            <div id="edit_details">
                <div class="ui one column doubling stackable">
                    <div class="column admin_basic_detail1 personal_detail_con">
                        {!! Form::open(array('route' => ['Laralum::booking.personalDetails.store', 'booking_id' => $booking->id], 'id' => 'bookingProcessForm','files'=>true,'method'=>'post')) !!}
                        <div class="segment">
                            {{ csrf_field() }}
                            <div class="about_sec signup_bg">
                                <h3 class="title_3">Personal Details</h3>
                                <div class="form-group">
                                    <label class="label-head">Patient Type*</label>
                                    <select disabled class="form-control required" id="patient_type"
                                            name="userProfile[patient_type]" id="patient_type" autofocus required>
                                        <option value="">Patient Type</option>
                                        @foreach(\App\UserProfile::getPatientType() as $key => $patient_type)
                                            <option {{ old('userProfile.patient_type', $profile->patient_type) ==  $key ? "selected" : "" }} value="{{ $key }}">{{ $patient_type }}</option>
                                        @endforeach
                                    </select>
                                    <small><strong>IPD</strong> = Indoor Patient Department, Treatment &amp; Stay
                                    </small>
                                    <br>
                                    <small><strong>OPD </strong>= Outdoor Patient Department, Only Treatment</small>
                                </div>

                                <div class="block_divide">
                                    <label class="label-head">Personal Details</label>
                                    <div class="form-group">
                                        <input required class="user_last form-control required" type="text"
                                               value="{{ old('userProfile.first_name', $profile->first_name) }}"
                                               name="userProfile[first_name]" placeholder="First Name*"
                                               id="first_name_profile">
                                    </div>
                                    <div class="form-group">
                                        <input class="user_last form-control required" type="text"
                                               value="{{ old('userProfile.first_name', $profile->last_name) }}"
                                               name="userProfile[last_name]" placeholder="Last Name"
                                               id="first_name_profile">
                                    </div>
                                    <div class="form-group">
                                        <input class="user_password form-control" type="text"
                                               value="{{ old('userProfile.relative_name', $profile->relative_name) }}"
                                               id="relative_name" name="userProfile[relative_name]"
                                               placeholder="S/o, D/o, W/o">
                                    </div>
                                    <div class="form-group">
                                        <input required class="user_confirm datepicker form-control required"
                                               type="text"
                                               value="{{ $profile->dob != null ? old('userProfile.dob',date("d-m-Y", strtotime($profile->dob))) : ""}}"
                                               max="100" id="dob"
                                               name="userProfile[dob]" placeholder="Date of Birth*">
                                    </div>{{--
                              <div class="form-group">
                                <input required class="user_confirm form-control required" type="text" value="{{ old('userProfile.age', $profile->age) }}" max="100" maxlength="3" min="1" id="age" name="userProfile[age]" placeholder="Age*">
                              </div>--}}
                                    <div class="form-group">
                                        {!! Form::select('userProfile[gender]', ['' => 'Sex*'] + \App\UserProfile::getGenderOptions(), old('userProfile.gender', $profile->gender),['class'=>'form-control required', 'id' => 'gender', 'required' => 'required'])  !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::select('userProfile[profession_id]',  \App\Profession::getDepartmentsDropdown($profile->id)->toArray() + ['other' => 'Other'], old('userProfile.profession_id', $profile->profession_id),['class'=>'form-control required', 'id' => 'profession_id', 'placeholder' => 'Profesion*', 'required' => 'required'])  !!}

                                    </div>
                                    <div class="form-group" id="profession_name_div" style="display:none;">
                                        Please specify your profession here
                                        <input type="text" name="userProfile[profession_name]" id="profession_name"
                                               class="form-control" placeholder="Profession Name*" disabled/>
                                    </div>
                                    <div class="form-group">
                                        {!! Form::select('userProfile[marital_status]', [''=>'Marital Status*','1' => 'Unmarried', '2' => 'Married'], old('userProfile.marital_status', $profile->marital_status),['id' => 'marital_status', 'class'=>'form-control required', 'required' => 'required'])  !!}
                                    </div>
                                </div>


                                <div class="block_divide">
                                    <label class="label-head">Contact Details*</label>
                                    <div class="form-group">
                                        <input id="landline_number" class="user_confirm form-control" type="text"
                                               value="{{ old('userProfile.landline_number', $profile->landline_number) }}"
                                               name="userProfile[landline_number]" placeholder="Landline No.">
                                    </div>
                                    <div class="form-group">
                                        <input id="mobile" required class="user_confirm form-control required"
                                               type="text"
                                               value="{{ old('userProfile.mobile', $profile->mobile) }}"
                                               name="userProfile[mobile]" id="mobile" placeholder="Mobile No.*">
                                    </div>
                                    <div class="form-group">
                                        <input id="whatsapp_number" class="user_confirm form-control"
                                               type="text"
                                               name="userProfile[whatsapp_number]"
                                               value="{{ old('userProfile.whatsapp_number', $profile->whatsapp_number) }}"
                                               id="whatsapp_number" placeholder="WhatsApp No.">
                                        <div class="pd_check">
                                            <input type="checkbox" id="same_as_above" value="1"
                                                   name="userProfile[same_as_above]" {{ old('userProfile.whatsapp_number', $profile->whatsapp_number)  != "" && old('userProfile.whatsapp_number', $profile->whatsapp_number) ==  old('userProfile.mobile', $profile->mobile) ? "checked" : "" }}>Same
                                            As Mobile No
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input required id="referral_source" class="user_confirm form-control required"
                                               type="text" name="userAddress[referral_source]"
                                               value="{{ old('userAddress.referral_source', $address->referral_source) }}"
                                               placeholder="Referral Source*">
                                        <small>i.e. Walk-in, Google, Friend, Relative, Etc.</small>
                                    </div>
                                </div>

                                <div class="block_divide">
                                    <label class="label-head">Address*</label>
                                    <div class="form-group">
                                        <input required class="user_confirm form-control required" type="text"
                                               name="userAddress[address1]"
                                               value="{{ old('userAddress.address1', $address->address1) }}"
                                               id="address1"
                                               placeholder="Address Line-1*">
                                    </div>
                                    <div class="form-group">
                                        <input class="user_confirm form-control" type="text"
                                               name="userAddress[address2]"
                                               value="{{ old('userAddress.address2', $address->address2) }}"
                                               id="address2"
                                               placeholder="Address Line-2">
                                    </div>
                                    <div class="form-group">
                                        <input required class="user_confirm form-control required" type="text"
                                               name="userAddress[city]"
                                               value="{{ old('userAddress.city', $address->city) }}" id="city"
                                               placeholder="City / Town / Village*">
                                    </div>
                                    <div class="form-group">
                                        <!-- <input required class="user_confirm form-control required" type="text"
                                               name="userAddress[state]"
                                               value="{{ old('userAddress.state', $address->state) }}" id="city"
                                               placeholder="State*"> -->
                                        <select required name="userAddress[state]" class="form-control required" id="state">
                                            <option value="">Select State</option>
                                            @foreach($states as $state)
                                                <option value="{{ $state }}" {{ old('userAddress.state') == $state ||  $address->state == $state  ? 'selected' : "" }}>{{ $state }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input class="user_confirm form-control required" type="text"
                                               name="userAddress[zip]"
                                               value="{{ old('userAddress.zip', $address->zip) }}"
                                               id="zip" placeholder="Pin / Zip Code">
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="user[country_code]" id="user_country_code">
                                        <input type="hidden" name="userProfile[country_code]" id="country_code"
                                               value="{{ $profile->country_code != "" ? $profile->country_code : "IN"  }}">
                                        <select required name="userAddress[country]" class="form-control required"
                                                id="country">
                                            <option value="">Select Country</option>
                                            <?php
                                            //$countries = Laralum::countries();
                                            ?>
                                            @foreach($countries as $country)
                                                <?php
                                                if ($address->country == "")
                                                    $address->country = "IN";
                                                $cc_field_value = array_search($country, $countries); ?>
                                                <option {{ old('userAddress.country') == $cc_field_value ||  $address->country == $cc_field_value  ? 'selected' : "" }} class="item no-disable"
                                                        value="{{ $cc_field_value }}">{{ $country }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Photograph</label>Take Latest Photo<br>
                                        <div class="preview_profile">
                                            <input id="profile_picture" type="file"
                                                   name="profile_picture" size="40"></div>
                                        <div class="remove_pic_div" id="remove_pic_div_profile_picture">
                                            <i class="fa fa-remove" title="Remove Picture"
                                               style="cursor:pointer;display:   @if($profile->profile_picture == null) none; @endif"
                                               id="remove_picture-profile_picture"></i>
                                            <input type="hidden" id="remove-profile_picture"
                                                   name="userProfile[remove-profile_picture]"/>
                                        </div>
                                        <div id="view_profile_picture_div">
                                            @if($profile->profile_picture)
                                                <img width="100%"
                                                     src="{{ \App\Settings::getImageUrl($profile->profile_picture) }}">
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="block_divide">
                                    <label class="label-head">Documents*</label>
                                    <div class="browse_file_pd">
                                        <div class="form-group1">
                                            <div id="document-div"
                                                 style="display:{{ old('userAddress.country', $address->country) == "IN" ||  old('userAddress.country', $address->country) == ""  ? "" : "none" }};">
                                                @foreach(\App\DocumentType::getDocuments() as $document)
                                                    <div class="form-group ddvf">
                                                        {{ $document->title }}
                                                        <input id="document-{{ $document->id }}"
                                                               class="user_confirm form-control required" type="text"
                                                               name="document_id_{{ $document->id }}"
                                                               value="{{ old('document_id_'.$document->id, $profile->getDocument($document->id, 'id_number')) }}"
                                                               placeholder="{{ $document->title }} ID*">
                                                        <input id="document_file-{{ $document->id }}" type="file"
                                                               name="document_file-{{ $document->id }}">
                                                        <div class="progress-wrp" id="progress_bar_{{ $document->id }}">
                                                            <div class="progress-bar"></div>
                                                            <div class="status">0%</div>
                                                        </div>
                                                        <div id="output"><!-- error or success results --></div>
                                                        <label>Max document size allowed: 2M</label>
                                                        <div id="view_document_file-{{ $document->id }}_div">
                                                            <div class="remove_pic_div">
                                                                <i class="fa fa-remove" title="Remove Document"
                                                                   id="remove_picture-document_file-{{ $document->id }}"
                                                                   style="cursor:pointer;display:    @if(!$user->getDocument($document->id)) none; @endif"></i>
                                                            </div>
                                                            @if($profile->getDocument($document->id))
                                                                <a href="{{ \App\Settings::getDownloadUrl($profile->getDocument($document->id, 'file'), $profile->getDocument($document->id, 'name')) }}"
                                                                   class="no-disable">Download {{ $profile->getDocument($document->id, 'file_name') }}</a>
                                                            @endif
                                                        </div>
                                                    </div>



                                                    <input type="hidden"
                                                           id="remove_document-document_file-{{ $document->id }}"
                                                           name="remove_document-{{ $document->id }}"/>

                                                @endforeach
                                            </div>
                                            <div id="document-div-foreign"
                                                 style="display:{{ old('userAddress.country', $address->country) == "IN" ||  old('userAddress.country', $address->country) == ""  ? "none" : "" }};">
                                                @foreach(\App\DocumentType::getDocuments(\App\DocumentType::STATUS_FOREIGN_CLIENT) as $document)
                                                    <div class="form-group">
                                                        {{ $document->title }}
                                                        @if($document->is_downloadable == \App\DocumentType::IS_DOWNLOADABLE)
                                                            <a
                                                                    class="no-disable"
                                                                    href="{{ \App\Settings::getDownloadUrl($document->file, $document->file_name) }}">Download {{ $document->title }}</a>
                                                        @endif

                                                        <input id="foreign_document-{{ $document->id }}"
                                                               class="user_confirm form-control required" type="text"
                                                               name="foreign_document_id_{{ $document->id }}"
                                                               value="{{ old('foreign_document_id_'.$document->id, $user->getDocument($document->id, 'id_number')) }}"
                                                               placeholder="{{ $document->title }} ID*">


                                                        {{--<input required class="user_confirm form-control required" type="text" name="document_id_{{ $document->id }}" value="{{ old('document_id_'.$document->id) }}"  id="city" placeholder="{{ $document->title }} ID*">--}}
                                                        <input id="foreign_document_file-{{ $document->id }}"
                                                               type="file"
                                                               name="foreign_document_file-{{ $document->id }}">
                                                        <label>Max document size allowed: 2M</label>
                                                        <div class="remove_pic_div">
                                                            <i title="Remove Picture" class="fa fa-remove"
                                                               id="remove_picture-foreign_document_file-{{ $document->id }}"
                                                               style="cursor: pointer;display:    @if(!$user->getDocument($document->id)) none; @endif"></i>
                                                        </div>

                                                        <input type="hidden"
                                                               id="remove_document-foreign_document_file-{{ $document->id }}"
                                                               name="remove_document-{{ $document->id }}"/>
                                                        <div id="view_foreign_document_file-{{ $document->id }}_div">
                                                            @if($user->getDocument($document->id))
                                                                <a href="{{ \App\Settings::getDownloadUrl($user->getDocument($document->id, 'file'), $user->getDocument($document->id, 'name')) }}"
                                                                   class="no-disable">Download {{ $user->getDocument($document->id, 'file_name') }}</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <div class="form-group">
                                    <button class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.save') }}</button>


                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>

    $('#country').on('change',function(){
          getStates();
        });
    function getStates(){
          country_code = $('#country').val();  
          $.ajax({
            type:'GET',
            url:"{{url('admin/booking/registration/get_states/')}}"+'/'+country_code,
            success:function(response){
              $("#state").html('<option value="">Select State</option>');
              $.each( response, function( key, value ) {
                  $("#state").append('<option value="'+value+'">'+value+'</option>');
              });
            },error:function(e){
              console.log(e);
            } 
          })
        }

        $(".datepicker").datepicker({
            dateFormat: "dd-mm-yy", autoclose: true, maxDate: 0, changeMonth: true,
            changeYear: true, yearRange: '1900:' + "{{ date("Y") }}"
        });

        $("#edit_button").click(function () {
            $("#show_details").hide();
            $("#edit_details").show();
        });
        $("#show_button").click(function () {
            $("#show_details").show();
            $("#edit_details").hide();
        });
        $("#country").change(function () {
            var country = $("#country").val();
            var country_name = $("#country option :selected").text();
            $("#country_code").val(country);
            $("#user_country_code").val(country);
            $("#user_country_data").val(country_name);
            if (country == "IN") {
                $("#document-div").show();
                $("#document-div-foreign").hide();
                $("[id^=foreign_document-]").each(function () {
                    $(this).val("");
                })
                $("[id^=foreign_document_file-]").each(function () {
                    $(this).val("");
                })
            } else {
                $("#document-div").hide();
                $("#document-div-foreign").show();
                $("[id^=document-]").each(function () {
                    $(this).val("");
                })
                $("[id^=document_file-]").each(function () {
                    $(this).val("");
                })
            }
        });
        $("#bookingProcessForm").submit(function () {
            if ($("#profession_name").val() != "") {
                $("#profession_id").prop('disabled', 'disabled');
            }
            return true;
        })
        $("#profession_id").change(function () {
            if ($(this).val() == "other") {
                $("#profession_id").prop("required", false);
                // $("#profession_id").prop("disabled", 'disabled');
                $("#profession_name").prop("required", true);
                $("#profession_name").prop("disabled", false);
                $("#profession_name_div").show();
            } else {
                $("#profession_name").val("");
                $("#profession_id").prop("required", true);
                $("#profession_name").prop("required", false);
                $("#profession_name").prop("disabled", 'disabled');
                $("#profession_name_div").hide();
            }
        });

        $("input[type='file']").change(function () {
            var id = $(this).attr('id');
            $("#remove_picture-" + id).show();
            $("#remove_document-" + id).val("");
            $("#remove-" + id).val("");

        });

        $("[id^=remove_picture]").click(function () {
            console.log("d");
            var id = $(this).attr('id').split("remove_picture-")[1];
            $("#" + id).val("");
            $("#view_" + id + "_div").find('img').remove();
            $("#view_" + id + "_div").find('a').remove();
            $("#remove_document-" + id).val(1);
            $("#remove-" + id).val(1);
            $(this).hide();
        })
        $("#same_as_above").click(function () {
            if ($(this).is(":checked")) {
                var no = $("#mobile").val();
                $("#whatsapp_number").val(no);
                $("#whatsapp_number").attr('disabled', 'disabled');
            } else {
                $("#whatsapp_number").attr('disabled', false);
            }
        })
        $("[id^=document_file-]").change(function () {
            var form = $(this).attr('id');
            var id = form.split('document_file-')[1];
            console.log('form' + form);
            var progress_bar_id = "progress_bar_" + id;
            uploadDocument(form, progress_bar_id);
        });

        function uploadDocument(form, progress_bar_id) {
            var post_url = "{{ url('upload-document/'.$user->id) }}";
            var form_data = $("#" + form).serialize();
            var form_data = new FormData();
            form_data.append('img', $("#" + form)[0].files[0]);
            form_data.append('_token', "{{ csrf_token() }}");
            form_data.append('profile', false);
            console.log('form_data' + form_data);
            $.ajax({
                url: post_url,
                type: "POST",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                xhr: function () {
                    //upload Progress
                    var xhr = $.ajaxSettings.xhr();
                    if (xhr.upload) {
                        xhr.upload.addEventListener('progress', function (event) {
                            var percent = 0;
                            var position = event.loaded || event.position;
                            var total = event.total;
                            if (event.lengthComputable) {
                                percent = Math.ceil(position / total * 100);
                            }
                            //update progressbar
                            console.log('progress_bar_id' + progress_bar_id);
                            console.log('percent' + percent);
                            $("#" + progress_bar_id + " .progress-bar").css("width", +percent + "%");
                            $("#" + progress_bar_id + " .status").text(percent + "%");
                        }, true);
                    }
                    return xhr;
                },
                mimeType: "multipart/form-data"
            }).done(function (res) { //
                /*  $(my_form_id)[0].reset(); //reset form
                 $(result_output).html(res); //output response from server
                 submit_btn.val("Upload").prop("disabled", false); //enable submit button once ajax is done*/
            });
        }
    </script>
@endsection