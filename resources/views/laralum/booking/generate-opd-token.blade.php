@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::opd-tokens') }}">OPD Consultations Slips</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.generate_opd_token') }}</div>
    </div>
@endsection
@section('title', 'Bookings')
@section('icon', "pencil")
@section('subtitle', trans('laralum.generate_opd_token'))
@section('content')
    <div class="ui one column doubling stackable">

        <div class="column">
            <section class="booking_filter booking_search_patient ui padded segment">
                <div class="row">
                    <div class="search_patient_con  signup_bg">


                        <h3 class="title_3">SEARCH PATIENT TO GENERATE OPD Consultation Slip</h3>

                        <div class="form-wrap">

                            <div class="search-patient-wrap">
                                <div class="head-tag-search">
                                    <p><span>SEARCH PATIENT Or</span><span><button class="ui button no-disable blue" id="add_new" style="float:unset;">Add New</button> </span></p>
                                </div>
                                <form id="bookingFilter" action="{{ route('Laralum::bookings.generate_opd_token') }}" method="POST">
                                    {{ csrf_field() }}
                                    {{--  <div class="form-group">
                                      <label>Barcode</label>
                                          <input class="user_namer form-control required" type="text" id="filter_bar_code" value="{{ @$_REQUEST['filter_bar_code'] }}" name="filter_bar_code" autofocus>
                                      </div>--}}
                                    <div class="form-group">
                                        <label>UHID</label>
                                        <input class="user_last form-control required" type="text"
                                               id="uhid" value="{{ @$_REQUEST['uhid'] }}"
                                               name="uhid">
                                    </div>

                                    <div class="form-group">
                                        <label>Registration ID</label>
                                        <input class="user_last form-control required" type="text"
                                               id="filter_patient_id" value="{{ @$_REQUEST['filter_patient_id'] }}"
                                               name="filter_patient_id">
                                    </div>
                                    <div class="form-group">
                                        <label>Email ID</label>
                                        <input class="user_email form-control required" type="email" id="filter_email"
                                               value="{{ @$_REQUEST['filter_email'] }}" name="filter_email">
                                    </div>
                                    <div class="form-group">
                                        <label>Mobile No.</label>
                                        <input class="user_password form-control required" type="text"
                                               name="filter_mobile" value="{{ @$_REQUEST['filter_mobile'] }}"
                                               id="filter_mobile">
                                    </div>
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input class="user_namee form-control required" type="text" name="filter_name"
                                               id="filter_name" value="{{ @$_REQUEST['filter_name'] }}">
                                    </div>
                                    <div class="form-button_row">
                                        <button class="ui button no-disable blue">Search</button>
                                    </div>
                                </form>

                            </div>

                            <div class="token-form-wrap">
                                <table class="table ui padded">
                                    <thead>
                                    <tr>
                                        <th style="padding: 7px !important; background-color: #ddebf7;">Department</th>
                                        @foreach(\App\Department::all() as $department)
                                            <th style="padding: 7px !important; background-color: #ddebf7;">{{ $department->title }}</th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <th>Previous Token</th>

                                        @foreach(\App\Department::all() as $department)
                                            <th>
                                                @foreach($department->getDoctors() as $doctor)
                                                    <p>{{ $doctor->name }}- {{ $doctor->getLastTokenNo() }}</p>
                                                @endforeach
                                            </th>
                                        @endforeach
                                    </tr>
                                    </tbody>
                                </table>

                                    <form class="token patient_grn_token" method="POST"
                                          action="{{ url('/admin/booking/print-opd-token/'.$booking->id) }}" style="display:{{!empty($booking->id) ? 'block' : 'none'}};">
                                        {!! csrf_field() !!}

                                        @if(empty($booking->id))
                                            <div class="form-group">
                                                <div class="col-2"><label>First Name*</label></div>
                                                <div class="col-10">
                                                    <input type="text" name="first_name" value="" class="form-control" required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-2"><label>Last Name*</label></div>
                                                <div class="col-10">
                                                    <input type="text" name="last_name" class="form-control" value="" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-2"><label>Date of Birth*</label></div>
                                                <div class="col-10">
                                                <input required class="user_confirm datepicker form-control required"
                                                       type="text"
                                                       value="" max="100" id="dob"
                                                       name="dob" placeholder="Date of Birth*">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-2"><label>Sex*</label></div>
                                                <div class="col-10">
                                                {!! Form::select('gender', ['' => 'Sex*'] + \App\UserProfile::getGenderOptions(), old('gender'),['class'=>'form-control required', 'id' => 'gender', 'required' => 'required'])  !!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-2"><label>Profession*</label></div>
                                                <div class="col-10">
                                                {!! Form::select('profession',  \App\Profession::getDepartmentsDropdown()->toArray() + ['other' => 'Other'], old('profession'),['class'=>'form-control required', 'id' => 'profession_id', 'placeholder' => 'Profesion*', 'required' => 'required'])  !!}
                                                </div>

                                            </div>
                                            <div class="form-group" id="profession_name_div" style="display:none;">
                                                <div class="col-2"><label>  Please specify your profession here*</label></div>
                                                <div class="col-10">
                                                <input type="text" name="profession_name" id="profession_name"
                                                       class="form-control" placeholder="Profession Name*" disabled/></div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-2"><label>Mobile*</label></div>
                                                <div class="col-10">
                                                <input required class="user_confirm form-control required"
                                                       type="text"
                                                       value="{{ old('mobile') }}"
                                                       name="mobile" id="mobile" placeholder="Mobile No.*">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-2"><label>Address*</label></div>
                                                <div class="col-10">
                                                <input required class="user_confirm form-control required" type="text"
                                                       name="address"
                                                       value="{{ old('address')}}"
                                                       id="address1"
                                                       placeholder="Address*">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-2"><label>City*</label></div>
                                                <div class="col-10">
                                                <input required class="user_confirm form-control required" type="text"
                                                       name="city"
                                                       value="{{ old('city') }}" id="city"
                                                       placeholder="City / Town / Village*">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-2"><label>State*</label></div>
                                                <div class="col-10">
                                                <!-- <input required class="user_confirm form-control required" type="text"
                                                       name="state"
                                                       value="{{ old('state') }}" id="state"
                                                       placeholder="State*"> -->
                                                <select required name="state" class="form-control required" id="state">
                                                    <option value="">Select State</option>
                                                      @foreach($states as $state)
                                                          <option value="{{ $state }}">{{ $state }}</option>
                                                      @endforeach
                                                </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-2"><label>Country*</label></div>
                                                <div class="col-10">
                                                <select required name="country" class="form-control required"
                                                    id="country">
                                                <option value="">Select Country</option>
                                                @foreach($countries as $country)
                                                    <?php $cc_field_value = array_search($country, $countries); ?>
                                                    <option {{ $cc_field_value == 'IN' ? 'selected' : "" }} class="item" value="{{ $cc_field_value }}">{{ $country }}</option>
                                                @endforeach
                                            </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-2"><label> Select Department</label></div>
                                                <div class="col-10">
                                                    <select name="department_id" id="department_id" required>
                                                        <option value="">Select i.e. Ayurveda / Naturopathy</option>
                                                        @foreach(\App\Department::all() as $department)
                                                            <option value="{{ $department->id }}">{{ $department->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-2"><label> Select Doctor</label></div>
                                                <div class="col-10">
                                                    <select name="doctor_id" id="doctor_id" required>
                                                        <option value="">Select</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-2"><label> Complaints</label></div>
                                                <div class="col-10">
                                                    <textarea name="complaints" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            {{--<div class="form-group">
                                            <div class="col-2">	 <label> Token Number is</label></div>
                                                <input type="hidden" name="token_no" value="{{ $token_no }}"/>
                                                <input type="hidden" name="patient_id" value="{{ $user->id}}"/>
                                                  <div class="col-10">  {{ $token_no }}</div>
                                            </div>--}}
                                            @else
                                        <div class="form-group">
                                            <div class="col-2"><label>Allocate token to</label></div>
                                            <div class="col-10">
                                                <p>{{ $booking->getProfile('first_name') ? $booking->getProfile('first_name').' '.$booking->getProfile('last_name') : "" }}</p>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <div class="col-2"><label>Patient ID</label></div>
                                            <div class="col-10">
                                                <p>{{ $booking->getProfile('kid') ? $booking->getProfile('kid') : ""}}</p>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-2"><label> Select Department</label></div>
                                            <div class="col-10">
                                                <select name="department_id" id="department_id" required>
                                                    <option value="">Select i.e. Ayurveda / Naturopathy</option>
                                                    @foreach(\App\Department::all() as $department)
                                                        <option value="{{ $department->id }}">{{ $department->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-2"><label> Select Doctor</label></div>
                                            <div class="col-10">
                                                <select name="doctor_id" id="doctor_id" required>
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-2"><label> Complaints</label></div>
                                            <div class="col-10">
                                               <textarea name="complaints" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        {{--<div class="form-group">
                                        <div class="col-2">	 <label> Token Number is</label></div>
                                            <input type="hidden" name="token_no" value="{{ $token_no }}"/>
                                            <input type="hidden" name="patient_id" value="{{ $user->id}}"/>
                                              <div class="col-10">  {{ $token_no }}</div>
                                        </div>--}}
                                        <input type="hidden" name="patient_id" value="{{ $user->id}}"/>
                                        <input type="hidden" name="booking_id" value="{{ $booking->id}}"/>


                                            <input type="hidden" name="first_name" value="{{ $booking->getProfile('first_name')}}"/>
                                            <input type="hidden" name="last_name" value="{{ $booking->getProfile('first_name')}}"/>
                                            <input type="hidden" name="profession" value="{{ $booking->getProfile('profession_id')}}"/>
                                            <input type="hidden" name="gender" value="{{  $booking->getProfile('gender')}}"/>
                                            <input type="hidden" name="address" value="{{ $booking->getAddress('address_line_1')}}"/>
                                            <input type="hidden" name="city" value="{{ $booking->getAddress('city')}}"/>
                                            <input type="hidden" name="state" value="{{ $booking->getAddress('state')}}"/>
                                            <input type="hidden" name="country" value="{{ $booking->getAddress('country')}}"/>
                                        @endif

                                        @if($user->id != null)
                                            <div class="form-button_row btnmx">
                                                <button class="ui button no-disable blue ">PRINT TOKEN RECIEPT</button>
                                            </div>
                                        @else
                                            <div class="form-button_row">
                                                <button class="ui button no-disable blue">PRINT TOKEN RECIEPT</button>
                                            </div>
                                        @endif
                                    </form>

                                @if($search == true && empty($booking->id))
                                    <div class="ui negative icon message" id="error_message">
                                        <i class="frown icon"></i>
                                        <div class="content">
                                            <div class="header"> {{ $error }} </div>
                                            <p>There are currently no patients</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
    {{--
            <div class="column">

                <div class="ui very padded segment">

                </div>
            </div>--}}

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
            url:"{{url('booking/registration/get_states/')}}"+'/'+country_code,
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
        function updateDropdown(department_id) {
            console.log('department_id : ' + department_id);
            $.ajax({
                type: 'POST',
                url: "{{ url('get_department_doctors') }}",
                data: {'department_id': department_id, "_token": "{{ csrf_token() }}"},
                success: function (data) {
                    $('#doctor_id').html(data);
                }
            });
        }
        var val = $("#department_id").val();
        updateDropdown(val);

        $("#department_id").change(function () {
            var val = $(this).val();
            console.log('dep' + val);
            updateDropdown(val);
        })

        $("#add_new").click(function() {
            $(".patient_grn_token").show();
            $("#error_message").hide();
        });

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
    </script>
@endsection


