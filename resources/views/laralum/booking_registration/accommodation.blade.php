@php $select_month_year = ''; @endphp
@if(isset($rooms_status_arr['month_data']) && !empty($rooms_status_arr['month_data']))
    @php
        $days = '';
        $days_names = '';
        $blank_tds = '';
        $select_month_year = '';
    @endphp
    @for($days_number=1;$days_number<=$rooms_status_arr['month_data']['month_date_count'];$days_number++)
        @php
            $days.= '<th>'.$days_number.'</th>';
            $loop_date = $rooms_status_arr['month_data']['year'].'-'.$rooms_status_arr['month_data']['month'].'-'.$days_number;
            $days_names.= '<td>'.date('D', strtotime($loop_date)).'</td>';
            $blank_tds.='<td></td>';
            $select_month_year = $rooms_status_arr['month_data']['month'].'-'.$rooms_status_arr['month_data']['year'];
        @endphp
    @endfor
@endif
@extends('layouts.admin.panel')

@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::bookings') }}">{{ trans('laralum.booking_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Booking</div>
    </div>
@endsection
@section('title', 'Booking')
@section('icon', "pencil")
@section('subtitle', 'Booking')
@section('content')
    <style type="text/css">
        body {
            overflow: hidden;
        }
    </style>
    <link rel="stylesheet" type="text/css" media="screen"
          href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">
    <div class="admin_wrapper signup">
        <!-- <header style="background-color: #008fd5;">
             <div class="logo_wrapper wow fadeInDown">
                 <h1>Kayakalp</h1>
             </div>
         </header>-->
        <div class="main_wrapper">
            @include('laralum.booking_registration.topbar')
            <div class="column admin_basic_detail1">
                <div class="segment form_spacing_inn">
                    <div class="main_content_area">
                        <div id="edit_details">
                            <div id="signup_wizard">
                                <h3 class="title_3">Accommodation</h3>
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
                                @if (session('status') == 'success')
                                    <div class="alert alert-success">
                                        {!! session('message') !!}
                                    </div>
                                @endif
                            </div>
                            <div class="panel-group" id="tabs">
                                <div class="panel1 panel-default">
                                    <input type="hidden" id="user_id" value="{{ $user->id }}"/>
                                    {!! Form::open(array('route' => ['Laralum::booking.registration.accommodation_request', 'user_id' => $booking->id] ,'class'=>'','id' => 'bookingProcessForm','files'=>true,'method'=>'post')) !!}
                                    {{--<form id="bookingProcessForm" action="{{ route('guest.booking') }}" method="post">--}}
                                    <input type="hidden" name="user_id" value="{{ $user_id }}">
                                    {{ csrf_field() }}
                                    <section class="booking_process_form_main">
                                        <div class="pro_main_content">
                                            <div class="row11">
                                                <div class="bookling_coll about_sec">
                                                    <div class="white_bg signup_bg">
                                                        <div class="form-group pull-left field_1_sec">
                                                            <label>Building Name:</label>
                                                            {!! Form::select('building_id',["" => "Select Building"] + App\Building::getBuildingOptions(), old('building_id', $booking->building_id),['class'=>'form-control required', 'id' => 'building_id', 'required' => 'required'])  !!}
                                                        </div>

                                                        <div class="form-group pull-left field_1_sec">
                                                            <label>Floor:</label>
                                                            {!! Form::select('floor_number',["" => "Select Floor"] + App\Building::getFloorOptions(), old('floor_number', $booking->floor_number),['class'=>'form-control required select_floor_number', 'id' => 'floor_number', 'required' => 'required'])  !!}
                                                        </div>

                                                        <div class="form-group pull-left field_1_sec">
                                                            <label>Booking Type:</label>
                                                            {!! Form::select('booking_type',["" => "Select Booking Type"] + App\Booking::getBookingTypeOptions(), old('booking_type', $booking->booking_type),['class'=>'form-control required', 'id' => 'booking_type', 'required' => 'required'])  !!}
                                                        </div>
                                                        <div class="form-group pull-left field_1_sec">
                                                            {!! Form::label('check_in_date', 'Check in date') !!}
                                                            {!! Form::text('check_in_date', $booking->check_in_date != "" ? old('check_in_date', date('d-m-Y',strtotime($booking->check_in_date))) : "",['required', 'class' => 'form-control datepicker', 'id' => 'check_in_date']) !!}
                                                        </div>
                                                        <div class="form-group pull-left field_1_sec">
                                                            {!! Form::label('check_out_date', 'Check out date') !!}
                                                            {!! Form::text('check_out_date', $booking->check_out_date != "" ? old('check_out_date', date('d-m-Y',strtotime($booking->check_out_date))) : "",['required', 'class' => 'form-control datepicker', 'id' => 'check_out_date']) !!}
                                                        </div>
                                                        <div class="form-group pull-left field_1_sec custom_check">
                                                           <input type="checkbox" value="1" id="cd_service_check_1" name="is_child" @if( $booking->is_child == 1) checked @endif> Child <input id="cd_service_number_1" type="number"  class="input_field" name="child_count" min="1" 
                                                           @if(old('child_count', $booking->child_count) > 0) value="{{ old('child_count', $booking->child_count) }}" @endif>
</div>
                                                        <div class="form-group pull-left field_1_sec custom_check">
                                                            <input id="cd_service_check_2" type="checkbox" value="1" name="is_driver" @if($booking->is_driver == 1) checked @endif> Driver <input id="cd_service_number_2" class="input_field" type="number" name="driver_count" min="1" @if(old('driver_count', $booking->driver_count) > 0) value="{{ old('driver_count', $booking->driver_count) }}" @endif >
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <br>

                                                        <div id="old-members-div" class="add_member_Section"> @php $x = 1 @endphp
                                                            @if($members != null)
                                                                @foreach($members as $member)
                                                                    <div id="old_members_{{ $member->id }}">
                                                                        <div class="form-group pull-left col-md-2">
                                                                            <input type="hidden" name="member[id][]"
                                                                                   value="{{ $member->id }}"/>
                                                                            <label>Name:</label>
                                                                            {!! Form::text('member[name][]',$member->name,['class' => 'form-control', 'id' => 'name']) !!}
                                                                        </div>

                                                                        <div class="form-group pull-left col-md-2">
                                                                            <label>Gender:</label>
                                                                            {!! Form::select('member[gender][]',App\UserProfile::getGenderOptions(), $member->gender,['class'=>'form-control', 'id' => 'gender'])  !!}
                                                                        </div>
                                                                        <div class="form-group pull-left col-md-2">
                                                                            {!! Form::label('member[age][]', 'Age') !!}
                                                                            {!! Form::text('member[age][]',$member->age,['class' => 'form-control', 'id' => 'age']) !!}
                                                                        </div>
                                                                        <div class="form-group pull-left col-md-2">
                                                                            {!! Form::label('member[id_proof][]', 'Id Proof') !!}
                                                                            {!! Form::file('member[id_proof][]',['class' => 'form-control', 'id' => 'id_proof']) !!}
                                                                        </div>
                                                                        <div class="form-group pull-left field_1_sec">
                                                                            <label>Building Name:</label>
                                                                            {!! Form::select('member[member_building_id][]',["" => "Select Building"] + App\Building::getBuildingOptions(),
                                                                            old('building_id', $member->building_id),
                                                                            ['class'=>'form-control ', 'id' => 'member_building_id'])  !!}
                                                                        </div>

                                                                        <div class="form-group pull-left field_1_sec">
                                                                            <label>Floor:</label><br>
                                                                            {!! Form::select('member[member_floor_number][]',["" => "Select Floor"] + App\Building::getFloorOptions(),
                                                                            old('floor_number', $member->floor_number),
                                                                            ['class'=>'form-control select_floor_number', 'id' => 'member_floor_number'])  !!}
                                                                        </div>

                                                                        <div class="form-group pull-left field_1_sec">
                                                                            <label>Booking Type:</label>
                                                                            {!! Form::select('member[member_booking_type][]',["" => "Select Booking Type"] + App\Booking::getBookingTypeOptions(), 
                                                                            old('booking_type', $member->booking_type),
                                                                            ['class'=>'form-control', 'id' => 'member_booking_type'])  !!}
                                                                        </div>
                                                                        <div class="form-group pull-left field_1_sec">
                                                                            {!! Form::label('member_check_in_date', 'Check in date') !!}
                                                                            {!! Form::text('member[member_check_in_date][]', $member->check_in_date != "" ? old('member_check_in_date', date('d-m-Y',strtotime($member->check_in_date))) : "",['class' => 'form-control datepicker', 'id' => 'member_check_in_date']) !!}
                                                                        </div>
                                                                        <div class="form-group pull-left field_1_sec">
                                                                            {!! Form::label('member_check_out_date', 'Check out date') !!}
                                                                            {!! Form::text('member[member_check_out_date][]', $member->check_out_date != "" ? old('member_check_out_date', date('d-m-Y',strtotime($member->check_out_date))) : "",['class' => 'form-control datepicker', 'id' => 'member_check_out_date']) !!}
                                                                        </div>
                                                                        <div class="form-group pull-left field_1_sec custom_check">
                                                                            <input id="c_service_check_{{ $x }}" type="checkbox" value="1" name="member[member_is_child][]" @if( $member->is_child == 1) checked @endif> Child <input id="c_service_number_{{ $x }}" type="number" class="input_field" id="c_service_value_{{ $x }}" name="member[member_child_count][]" min="1" @if(old('child_count', $member->child_count) > 0) value="{{ old('child_count', $member->child_count) }}" @endif>
 </div>
                                                                        <div class="form-group pull-left field_1_sec custom_check">                                                                           
 <input id="d_service_check_{{ $x }}" type="checkbox" value="1" name="member[member_is_driver][]" @if( $member->is_driver == 1) checked  @endif> Driver <input id="d_service_number_{{ $x }}" id="d_service_value_{{ $x }}"  class="input_field" type="number" name="member[member_driver_count][]" min="1" 
 @if(old('driver_count', $member->driver_count)> 0) value="{{ old('driver_count', $member->driver_count) }}" @endif >
                                                                        </div>
                                                                        <a class="remove btn col-md-2 clearfix no-disable" id="old_remove_{{ $member->id }}"> <i class="fa fa-times-circle fa-2x "></i>
                                                                        </a>
                                                                        <div class="clearfix"></div>
                                                                    </div>
                                                                @php $x++ @endphp
                                                                @endforeach

                                                            @endif
                                                                <input type="hidden" id="cron_value" value="{{ $x }}" />
                                                        </div>


                                                        <div class="add_member_sec">
                                                            <button class="save_btn_signup form-control"
                                                                    id="add-members"> Add Members <i
                                                                        class="fa fa-plus"></i></button>

                                                            <button id="hide-members"
                                                                    class="save_btn_signup form-control"
                                                                    style="display:none;">Remove Members
                                                            </button>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <div id="members-div"
                                                             style="display:none;">
                                                            <div id="clone" class="clearfix">
                                                                <div class="form-group pull-left col-md-2">
                                                                    <input type="hidden" name="member[id][]" value=""/>
                                                                    <label>Name:</label>
                                                                    {!! Form::text('member[name][]','',['class' => 'form-control', 'id' => 'name']) !!}
                                                                </div>

                                                                <div class="form-group pull-left col-md-2">
                                                                    <label>Gender:</label>
                                                                    {!! Form::select('member[gender][]',App\UserProfile::getGenderOptions(), '',['class'=>'form-control', 'id' => 'gender'])  !!}
                                                                </div>
                                                                <div class="form-group pull-left col-md-2">
                                                                    {!! Form::label('member[age][]', 'Age') !!}
                                                                    {!! Form::text('member[age][]','',['class' => 'form-control', 'id' => 'age']) !!}
                                                                </div>
                                                                <div class="form-group pull-left col-md-2">
                                                                    {!! Form::label('member[id_proof][]', 'Id Proof') !!}
                                                                    <br>
                                                                    {!! Form::file('member[id_proof][]',['class' => 'form-control', 'id' => 'id_proof']) !!}
                                                                </div>
                                                                <div class="form-group pull-left field_1_sec">
                                                                    <label>Building Name:</label>
                                                                    {!! Form::select('member[member_building_id][]',["" => "Select Building"] + App\Building::getBuildingOptions(),
                                                                    old('building_id', $booking->building_id),
                                                                    ['class'=>'form-control ', 'id' => 'member_building_id'])  !!}
                                                                </div>

                                                                <div class="form-group pull-left field_1_sec">
                                                                    <label>Floor:</label><br>
                                                                    {!! Form::select('member[member_floor_number][]',["" => "Select Floor"] + App\Building::getFloorOptions(),
                                                                    old('floor_number', $booking->floor_number),
                                                                    ['class'=>'form-control select_floor_number', 'id' => 'member_floor_number'])  !!}
                                                                </div>

                                                                <div class="form-group pull-left field_1_sec">
                                                                    <label>Booking Type:</label>
                                                                    {!! Form::select('member[member_booking_type][]',["" => "Select Booking Type"] + App\Booking::getBookingTypeOptions(), 
                                                                    old('booking_type', $booking->booking_type),
                                                                    ['class'=>'form-control', 'id' => 'member_booking_type'])  !!}
                                                                </div>
                                                                <div class="form-group pull-left field_1_sec">
                                                                    {!! Form::label('member_check_in_date', 'Check in date') !!}
                                                                    {!! Form::text('member[member_check_in_date][]', $booking->member_check_in_date != "" ? old('member_check_in_date', date('d-m-Y',strtotime($booking->member_check_in_date))) : "",['class' => 'form-control datepicker', 'id' => 'member_check_in_date']) !!}
                                                                </div>
                                                                <div class="form-group pull-left field_1_sec">
                                                                    {!! Form::label('member_check_out_date', 'Check out date') !!}
                                                                    {!! Form::text('member[member_check_out_date][]', $booking->member_check_out_date != "" ? old('member_check_out_date', date('d-m-Y',strtotime($booking->member_check_out_date))) : "",['class' => 'form-control datepicker', 'id' => 'member_check_out_date']) !!}
                                                                </div>
<div class="form-group pull-left field_1_sec custom_check">
                                                                    <input type="checkbox"  id="c_service_check_{{ $x }}" value="1" name="member[member_is_child][]"> Child
                                                                    <input class="input_field" type="number" id="c_service_value_{{ $x }}" name="member[member_child_count][]">
                                                                </div>
                                                                <div class="form-group pull-left field_1_sec custom_check">
                                                                    <input id="d_service_check_{{ $x }}"  type="checkbox" value="1" name="member[member_is_driver][]"> Driver
                                                                    <input class="input_field" type="number" id="d_service_value_{{ $x }}"  name="member[member_driver_count][]">
                                                                </div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        </div>

                                                        <div class="add_member_sec">
                                                            <button id="add_more" style="display:none;" class="btn"><i
                                                                        class="fa fa-plus-circle fa-2x"></i></button>
                                                        </div>

                                                        <div class="form-group btn_signup_con span10">
                                                            <button type="submit" id="submit"
                                                                    class="btn ui blue save_btn_signup form-control">
                                                                Next »
                                                            </button>
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

                    @endsection
                    @section('js')
                        <script>
                             $(".datepicker").datepicker({dateFormat: "dd-mm-yy", autoclose: true/*, minDate: 0*/})
                            /*;
                             $( ".datepicker" ).datepicker({format: "yyyy-mm-dd", autoclose:true, startDate:"+0d"});*/
                            $(".datepicker").change(function () {
                                var date_in = $("#check_in_date").val();
                                var date_out = $("#check_out_date").val();
                                //var date_in = $("#check_in_date").datepicker("getDate");
                                //var date_out = $("#check_out_date").datepicker("getDate");
                               /* if (date_in != "" && date_out != "") {
                                    var nights = (date_out -
                                        date_in) /
                                        1000 / 60 / 60 / 24;
                                    $('#nights').html(nights + " nights");
                                    getPrice();
                                    getTypes();
                                }*/
                            });

                            $(document).delegate("#check_in_date", "change", function () {
                                var checkin = $('#check_in_date').datepicker('getDate');
                                $('#check_out_date').datepicker('option', 'minDate', checkin);
                            });
                            $("#building_id").change(function () {
                                var val = $(this).val();
                                updateDropdown(val);
                            })

                            function updateDropdown(building_id) {
                                console.log('building_id : ' + building_id);
                                $.ajax({
                                    type: 'POST',
                                    url: '{{ url('get_building_floor') }}',
                                    data: {'building_id': building_id, '_token': "{{ csrf_token() }}"},
                                    success: function (data) {
                                        $('.select_floor_number').html(data.floors);
                                    }
                                });
                            }
                            $("#add-members").click(function (e) {

                                e.preventDefault();
                                var checkin = $('#check_in_date').val();
                                var checkout = $('#check_out_date').val();
                                $('#member_check_in_date').val(checkin);
                                $('#member_check_out_date').val(checkout);
                                $("#hide-members").show();
                                $("#add_more").show();
                                $(this).hide();
                                $("#members-div").find('input').not('input[type="file"]').each(function () {
                                    $(this).attr('disabled', false);
                                    $(this).attr('required', 'required');
                                });
                                $("#members-div").find('input[type="checkbox"]').each(function () {
                                    $(this).attr('disabled', false);
                                    $(this).attr('required', false);
                                });

                                $("#members-div").find('select').each(function () {
                                    $(this).attr('disabled', false);
                                    $(this).attr('required', 'required');
                                });
                                $("#members-div").find('[id^=c_service_value_]').prop('required', false);
                                $("#members-div").find('[id^=d_service_value_]').prop('required',false);

                                $("#members-div").show();

                            })

                            $("#hide-members").click(function (e) {
                                var member_count = member_count-1;
                                 //alert(member_count);
                                e.preventDefault();
                                if (confirm("Are you sure you want to delete this record?")) {
                                    $("#add-members").show();
                                    $("#add_more").hide();
                                    $(this).hide();
                                    $("#members-div").find('input').not('input[type="file"]').each(function () {
                                        $(this).attr('disabled', true);
                                        $(this).attr('required', false);
                                    });
                                    $("#members-div").find('select').each(function () {
                                        $(this).attr('disabled', true);
                                        $(this).attr('required', false);
                                    });


                                    $("#members-div").hide();
                                }
                            })
                            $("#add_more").click(function (e) {
                                e.preventDefault();
                                var $div = $('[id^="clone"]:last');
                                console.log($div);

// Read the Number from that DIV's ID (i.e: 3 from "klon3")
// And increment that number by 1
                                var id = parseInt($div.prop("id").match(/\d+/g), 10);
                                if (isNaN(id))
                                    id = 0;
                                var num = id + 1;
                                $("#clone").clone().prop('id', 'clone' + num).appendTo("#members-div").show();
                                $("<a id='remove" + num + "' class='remove btn col-md-2'> <i class='fa fa-times-circle fa-2x'></i> </a>").appendTo('#clone' + num);

                                $("#clone" + num).find('input').each(function () {
                                    $(this).val("");
                                })
                                $("#clone" + num).find('select').each(function () {
                                    $(this).val("");
                                })

                                var x = $("#cron_value").val();
                                x++;
                                $("#clone"+num).find('[id^=c_service_check_]').prop('id', 'c_service_check_'+x);

                                $("#clone"+num).find('[id^=d_service_check_]').prop('id', 'd_service_check_'+x);
                                $("#clone"+num).find('[id^=c_service_value_]').prop('id', 'c_service_value_'+x);

                                $("#clone"+num).find('[id^=d_service_value_]').prop('id', 'd_service_value_'+x);
                                debugger;
                                $("#clone"+num).find('[id^=c_service_value_]').prop('required', false);
                                $("#clone"+num).find('[id^=d_service_value_]').prop('required',false);

                                $("#clone"+num).find('#member_check_in_date').removeClass('hasDatepicker');
                                $("#clone"+num).find('#member_check_out_date').removeClass('hasDatepicker');

                                $("#clone"+num).find('#member_check_in_date').prop('id', 'member_check_in_date_'+num);
                                $("#clone"+num).find('#member_check_out_date').prop('id', 'member_check_out_date_'+num);

                                $("#clone"+num).find('#member_check_in_date_'+num).datepicker({dateFormat: "dd-mm-yy", autoclose: true, minDate: 0})
                                $("#clone"+num).find('#member_check_out_date_'+num).datepicker({dateFormat: "dd-mm-yy", autoclose: true, minDate: 0})


                                $("#cron_value").val(x);

                            })
                            $(document).delegate("[id^=remove]", "click", function (e) {
                                e.preventDefault();
                                if (confirm("Are you sure you want to delete this record?")) {
                                    var id = $(this).attr("id").split("remove")[1];
                                    $("#clone" + id).remove();
                                    $("#remove" + id).remove();
                                }
                            })
                            $("[id^=old_remove_]").click(function () {
                                if (confirm("Are you sure you want to delete this record?")) {
                                    var id = $(this).attr('id').split('old_remove_')[1];
                                    $.ajax({
                                        url: "{{ url('guest/booking/delete-members') }}",
                                        type: "POST",
                                        data: {'id': id, 'user_id': "{{ $user->id }}", "_token": "{{ csrf_token() }}"},
                                        success: function (response) {
                                            $("#old_members_" + response.id).remove();
                                        }
                                    })
                                }
                            });


                            $(document).delegate("[id^=cd_service_check_]", 'change', function () {
                                if ($(this).is(":checked")) {
                                    var id = $(this).attr('id').split('cd_service_check_')[1];
                                    $('#cd_service_number_'+id).prop('required',true);

                                    var value =$('#cd_service_number_'+id).val();
                                    if(value == "" || value == "0") {
                                        $('#cd_service_number_'+id).val(1);
                                    }
                                }
                                else{
                                    var id = $(this).attr('id').split('cd_service_check_')[1];
                                    $('#cd_service_number_'+id).prop('required',false);
                                    $('#cd_service_number_'+id).val('');
                                }
                            })


                            $(document).delegate("[id^=c_service_check_]", 'change', function () {
                                if ($(this).is(":checked")) {
                                    var id = $(this).attr('id').split('c_service_check_')[1];
                                    $('#c_service_value_'+id).prop('required',true);

                                    var value =$('#c_service_value_'+id).val();
                                    if(value == "" || value == "0") {
                                        $('#c_service_value_'+id).val(1);
                                    }
                                }
                                else{
                                    var id = $(this).attr('id').split('c_service_check_')[1];
                                    $('#c_service_value_'+id).prop('required',false);
                                    $('#c_service_value_'+id).val('');
                                }
                            })

                            $(document).delegate("[id^=d_service_check_]", 'change', function () {
                                if ($(this).is(":checked")) {
                                    var id = $(this).attr('id').split('d_service_check_')[1];
                                    $('#d_service_value_'+id).prop('required',true);

                                    var value =$('#d_service_value_'+id).val();
                                    if(value == "" || value == "0") {
                                        $('#d_service_value_'+id).val(1);
                                    }
                                }
                                else{
                                    var id = $(this).attr('id').split('d_service_check_')[1];
                                    $('#d_service_value_'+id).prop('required',false);
                                    $('#d_service_value_'+id).val('');
                                }
                            })

                            /*$(document).delegate("[id^=cd_service_check_]", 'change', function () {
                                if (!$(this).is(":checked")) {
                                    var id = $(this).attr('id').split('cd_service_check_')[1];
                                    $('#cd_service_number_'+id).prop('required',true);
                                }
                                getPrice();
                            })*/

                        </script>
                </div>
            </div>
        </div>
    </div>

@endsection

