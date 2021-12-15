<div class="back_button" style="display:none;text-align: right">
    <a href="javascript:void(0)" id="back_btn" bookingId="{{ $booking->id }}" class="button ui no-disable">Back</a>
</div>

<link rel="stylesheet" type="text/css" media="screen"
      href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">
@if(\Auth::user()->isPatient())
    {!! Form::open(['route' => ['Laralum::user.accombookingstore.form', 'user_id' => $booking->id],'class' => 'ui form']) !!}
@else
    @php $booking_form_class = $booking_room->id != null ? 'edit_booking_form' : 'new_form'; @endphp

    {!! Form::open(['route' => ['Laralum::accombookingstore.form', 'user_id' => $booking->id, 'room_id' => $booking_room->id],'class' => 'ui form '.$booking_form_class]) !!}
@endif
{{ csrf_field() }}

@php $is_member = false; @endphp
@if($member != null)
    @php $is_member = true; @endphp
    <div class="clearfix"></div>
    <p>
        Member Details
        {{ $member->name }} (Gender-{{ $member->getGenderOptions($member->gender) }}, Age-{{ $member->age }})</p>
    <input type="hidden" id="gender" value="{{ $member->gender }}">
    <input type="hidden" id="user_type" value="member">
    <input type="hidden" id="user_id" value="{{ $member->id }}">
@else
    <input type="hidden" id="user_type" value="patient">
    <input type="hidden" id="user_id" value="{{ $user->id }}">
    <input type="hidden" id="gender" value="{{ $booking->getProfile('gender') }}">
@endif


<input type="hidden" id="user_id" name="user_id" value="{{ $user->id }}">
<input type="hidden" id="member_id" name="member_id" value="{{ $member != null ? $member->id : "" }}">

<div class="form_table_vp">
    <div class="">
        @if($booking_room->id != null)
            <b>Edit Booking Alloted to {{ $booking_room->alloted_to }} - {{ $booking_room->check_in_date }}
                - {{ $booking_room->check_out_date }}</b>
        @else
            <b>Allot Accomodation</b>
        @endif
    </div>

    
    @if($member != null)

    <div class="field ">
        <label> {!! Form::label('check_in_date', 'Check in date') !!} </label>
        <div class="form_sec_rht">
            {!! Form::text('check_in_date',date('d-m-Y', strtotime(old('check_in_date', $booking_room->check_in_date))),['required', 'class' => 'form-control datepicker', 'id' => 'check_in_date', 'data-parent-class' => $booking_room->id != null ? 'edit_booking_form' : 'new_form' ]) !!}
        </div>
    </div>
    <div class="field">
        <label> {!! Form::label('check_out_date', 'Check out date') !!} </label>
        <div class="form_sec_rht">
            {!! Form::text('check_out_date',date('d-m-Y', strtotime(old('check_out_date',  $booking_room->check_out_date))),['required', 'class' => 'form-control datepicker', 'id' => 'check_out_date', 'data-parent-class' => $booking_room->id != null ? 'edit_booking_form' : 'new_form' ]) !!}
        </div>
    </div>


    <div class="field">
        <label> {!! Form::label('building_id', 'Building') !!} </label>
        <div class="form_sec_rht">
            {!! Form::select('building_id',["" => "Select Building"] + App\Building::getBuildingOptions() , $member->building_id  ,['class'=>'form-control required', 'id' => 'building_id', 'required' => 'required'])  !!}
        </div>
    </div>

    <div class="field" id="floor_div" style="display:none;">
        <label> {!! Form::label('floor_number', 'Floor') !!} </label>
        <div class="form_sec_rht">
            {!! Form::select('floor_number', App\Building::getFloorOptions($building), $floor,['class'=>'form-control required select_floor_number', 'id' => 'floor_number', 'required' => 'required'])  !!}
        </div>
    </div>

    <div class="field" id="booking_type_div">
        <label> {!! Form::label('booking_type', 'Select Booking Type') !!} </label>
        <div class="form_sec_rht booking_types_data">
            {!! Form::select('type',App\Booking::getBookingTypeOptions(), old('type', $booking_room->type),['class'=>'form-control required', 'id' => 'type', 'required' => 'required'])  !!}
        </div>
    </div>

    <div class="field" id="room_id_div" style="display:none;">
        <label> {!! Form::label('room_id', 'Room number') !!} </label>
        <div class="form_sec_rht">
            <select required class="form-control select_room_number" id="room_id" name="room_id">
                @foreach(App\Building::getRoomOptionsArray($building, $floor, $booking_room->check_in_date, $booking_room->check_out_date, $booking_room->type, $gender, $booking->id, $is_member) as $room)
                    <option {{ $booking_room->room_id == $room['id'] ? "selected" : "" }} data-price="{{ $room['price'] }}"
                            value="{{ $room['id'] }}">{{ $room['number'] }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="field" id="bed_no_div" style="display:none;">
        <label>  {!! Form::label('bed_no', 'Select Bed') !!} </label>
        <div class="form_sec_rht booking_types_data bed_checklist select_box">

            @php $bed_status_ar = App\Building::getBedOptionsArray($building, $floor, $booking_room->check_in_date, $booking_room->check_out_date, $booking_room->type, $gender);
            @endphp
            @if($bed_status_ar['all_booked'] == \App\Room::IS_AVAILABLE)
                @foreach($bed_status_ar['beds'] as $bed_ar)
                    <input type="radio" name="bed_no"
                           value="{{ $bed_ar['bed_no'] }}" {{ $bed_ar['bed_status'] == \App\Room::IS_BLOCKED  ? "disabled" : "" }}>
                    &nbsp;&nbsp;&nbsp; Bed {{ $bed_ar['bed_no'] }}
                @endforeach
            @endif
        </div>
    </div>


    @else

    <div class="field ">
        <label> {!! Form::label('check_in_date', 'Check in date') !!} </label>
        <div class="form_sec_rht">
            {!! Form::text('check_in_date',date('d-m-Y', strtotime(old('check_in_date', $booking_room->check_in_date))),['required', 'class' => 'form-control datepicker', 'id' => 'check_in_date', 'data-parent-class' => $booking_room->id != null ? 'edit_booking_form' : 'new_form' ]) !!}
        </div>
    </div>
    <div class="field">
        <label> {!! Form::label('check_out_date', 'Check out date') !!} </label>
        <div class="form_sec_rht">
            {!! Form::text('check_out_date',date('d-m-Y', strtotime(old('check_out_date',  $booking_room->check_out_date))),['required', 'class' => 'form-control datepicker', 'id' => 'check_out_date', 'data-parent-class' => $booking_room->id != null ? 'edit_booking_form' : 'new_form' ]) !!}
        </div>
    </div>


    <div class="field">
        <label> {!! Form::label('building_id', 'Building') !!} </label>
        <div class="form_sec_rht">
            {!! Form::select('building_id',["" => "Select Building"] + App\Building::getBuildingOptions() , $building ,['class'=>'form-control required', 'id' => 'building_id', 'required' => 'required'])  !!}
        </div>
    </div>

    <div class="field" id="floor_div" style="display:none;">
        <label> {!! Form::label('floor_number', 'Floor') !!} </label>
        <div class="form_sec_rht">
            {!! Form::select('floor_number', App\Building::getFloorOptions($building), $floor,['class'=>'form-control required select_floor_number', 'id' => 'floor_number', 'required' => 'required'])  !!}
        </div>
    </div>

    <div class="field" id="booking_type_div">
        <label> {!! Form::label('booking_type', 'Select Booking Type') !!} </label>
        <div class="form_sec_rht booking_types_data">
            {!! Form::select('type',App\Booking::getBookingTypeOptions(), old('type', $booking_room->type),['class'=>'form-control required', 'id' => 'type', 'required' => 'required'])  !!}
        </div>
    </div>

    <div class="field" id="room_id_div" style="display:none;">
        <label> {!! Form::label('room_id', 'Room number') !!} </label>
        <div class="form_sec_rht">
            <select required class="form-control select_room_number" id="room_id" name="room_id">
                @foreach(App\Building::getRoomOptionsArray($building, $floor, $booking_room->check_in_date, $booking_room->check_out_date, $booking_room->type, $gender, $booking->id, $is_member) as $room)
                    <option {{ $booking_room->room_id == $room['id'] ? "selected" : "" }} data-price="{{ $room['price'] }}"
                            value="{{ $room['id'] }}">{{ $room['number'] }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="field" id="bed_no_div" style="display:none;">
        <label>  {!! Form::label('bed_no', 'Select Bed') !!} </label>
        <div class="form_sec_rht booking_types_data bed_checklist select_box">

            @php $bed_status_ar = App\Building::getBedOptionsArray($building, $floor, $booking_room->check_in_date, $booking_room->check_out_date, $booking_room->type, $gender);
            @endphp
            @if($bed_status_ar['all_booked'] == \App\Room::IS_AVAILABLE)
                @foreach($bed_status_ar['beds'] as $bed_ar)
                    <input type="radio" name="bed_no"
                           value="{{ $bed_ar['bed_no'] }}" {{ $bed_ar['bed_status'] == \App\Room::IS_BLOCKED  ? "disabled" : "" }}>
                    &nbsp;&nbsp;&nbsp; Bed {{ $bed_ar['bed_no'] }}
                @endforeach
            @endif
        </div>
    </div>


    @endif


    {{--<div class="field">
        <label>  {!! Form::label('requested_external_services', 'Requested External Services') !!} </label>
        <div class="form_sec_rht">
            @php echo implode(', ', \App\ExternalService::whereIn('id', explode(',', $booking->external_services))->pluck('name', 'id')->toArray()) @endphp
        </div>
    </div>--}}

    <?php
    $services_array = isset($booking->room->id) ? explode(',', $booking->room->services) : array();
    $ext_services = \App\ExternalService::whereIn('id', $services_array)->get(); ?>

    <div class="field external_services_div" style="display:block;">
            @include('laralum.booking._external_services_div')
    </div>

    <div class="field">
        <label> Shows nights: </label>
        <div class="form_sec_rht"><span id="nights"></span></div>
    </div>

    <div class="field">
        <label>Total Amount:</label>
        <span class="form_sec_rht" id="total_price"></span>
    </div>
    <input type="hidden" id="total_price_val" name="total_price" value="">
    <input type="hidden" id="booking_id" name="booking_id" value="{{ $booking->id }}">
    <input type="hidden" id="booking_room_id" name="booking_room_id" value="{{ $booking_room->id }}">

    <div class="field room_book">
        <label> &nbsp; </label>
        {!! Form::submit('Book') !!}
    </div>

</div>
{!! Form::close() !!}


<script>
    $(".datepicker").datepicker({dateFormat: "dd-mm-yy", autoclose: true/*, minDate: "+0d"*/});

    $(document).delegate("#check_in_date", "change", function () {

        var date_in = $("#check_in_date").val();
        var date_out = $("#check_out_date").val();

        if (date_in != "" && date_out != "") {
            var nights = getNights(date_in, date_out);
            $('#nights').html(nights + " nights");
            var val = $("#floor_number").val();
            var building = $("#building_id").val();
            updateRoomDropdown(building, val);
            getPrice();
            // getTypes();
        }
    })
    $(document).delegate("#check_out_date", "change", function () {
        var date_in = $("#check_in_date").val();
        var date_out = $("#check_out_date").val();

        if (date_in != "" && date_out != "") {
            var nights = getNights(date_in, date_out);
            $('#nights').html(nights + " nights");
            var val = $("#floor_number").val();
            var building = $("#building_id").val();
            updateRoomDropdown(building, val);
            getPrice();
            // getTypes();
        }
    })
    $(document).delegate("#building_id", 'change', function () {
        var val = $(this).val();
        updateDropdown(val);
    })
    $(document).delegate("#floor_number", 'change', function () {
        var val = $(this).val();
        var building = $("#building_id").val();
        updateRoomDropdown(building, val);
    });

    $(document).delegate("#type", 'change', function () {
        var val = $("#floor_number").val();
        var building = $("#building_id").val();
        updateRoomDropdown(building, val);
    })

    $(document).delegate("#room_id", 'change', function () {
        var val = $(this).val();
        //  updateServicesDropdown(val);

        var type = $("#type").val();

        if (type == "{{ \App\BookingRoom::BOOKING_TYPE_SINGLE_BED}}") {
            updateBedDropdown(val);
        } else {
            $(".bed_checklist").html("");
            $("#bed_no_div").hide();
        }

    });

    var val = $("#building_id").val();
    updateDropdown(val);


    function updateBedDropdown(val) {
        var gender = $("#gender").val();
        var user_type = $("#user_type").val();
        var booking_id = $("#booking_id").val();

        if (user_type == "member") {
            var booking_id = $("#user_id").val();
        }

        $.ajax({
            type: 'POST',
            url: '{{ url('get_room_beds') }}/' + val,
            data: {
                'room_id': val,
                '_token': "{{ csrf_token() }}",
                "check_in_date": $("#check_in_date").val(),
                "check_out_date": $("#check_out_date").val(),
                "gender": gender,
                "booking_id": booking_id,
                'user_type': user_type
            },
            success: function (data) {

                var html = "";
                for (key in data.beds) {
                    var checked = "";

                    if (data.beds[key].booked_by_me == true || data.beds[key].status == "{{ \App\Room::IS_BLOCKED }}")
                        checked = "checked";
                    var disabled = "";

                    if (data.beds[key].bed_status == "{{ \App\Room::IS_BLOCKED }}")
                        disabled = "disabled";

                    html += "<input type='radio' required name='bed_no' value='" + data.beds[key].bed_no + "'" + checked + " " + disabled + ">Bed " + data.beds[key].bed_no + " ";
                }
                $('.bed_checklist').html(html).show();
                $('#bed_no_div').show();
                getPrice();
                getNights();
            }
        });
    }

    function updateRoomDropdown(building, val) {
        var gender = $("#gender").val();
        var booking_id = $("#booking_id").val();
        var member_id = null;

        var booking_room_id = $("#booking_room_id").val();
        var user_type = $("#user_type").val();

        if (user_type == "member") {
            var member_id = $("#user_id").val();
        }
        $.ajax({
            type: 'POST',
            url: '{{ url('get_building_rooms/'.$booking_room->id) }}',
            data: {
                'booking_id': booking_id,
                'member_id' : member_id.
                'gender': gender,
                'building_id': building,
                "floor": val,
                '_token': "{{ csrf_token() }}",
                "check_in_date": $("#check_in_date").val(),
                "check_out_date": $("#check_out_date").val(),
                'type': $("#type").val(),
                'user_type': user_type,
                'booking_room_id': booking_room_id
            },
            success: function (data) {
                $('.select_room_number').html(data.rooms);
                var val = $("#room_id").val();
                $("#room_id_div").show();
                updateServicesDropdown(val);
                if ($("#type").val() == "{{ \App\BookingRoom::BOOKING_TYPE_SINGLE_BED}}") {
                    updateBedDropdown(val);
                } else {
                    $(".bed_checklist").html("");
                    $("#bed_no_div").hide();
                    getPrice();
                    getNights();
                }

            }
        });
    }

    function updateServicesDropdown(room_id) {
        var booking_id = "{{ $booking->id }}";
        $.ajax({
            type: 'POST',
            url: '{{ url('get_room_services') }}',
            data: {
                'room_id': room_id,
                '_token': "{{ csrf_token() }}",
                'booking_id': booking_id,
                'booking_room_id': "{{ $booking_room->id }}"
            },
            success: function (data) {
                $("#ext_services").html(data.html);
            }
        });

    }

    function updateDropdown(building_id) {
        console.log('building_id : ' + building_id);
        $.ajax({
            type: 'POST',
            url: '{{ url('get_building_floor') }}',
            data: {'building_id': building_id, '_token': "{{ csrf_token() }}", 'floor': '{{ $floor }}'},
            success: function (data) {
                var val = $("#floor_number").val();
                $('.select_floor_number').html(data.floors);
                $("#floor_div").show();
                var building = $("#building_id").val();
                updateRoomDropdown(building, val);
            }
        });
    }

    function getNights() {
        var diff = ($("#check_out_date").datepicker("getDate") -
            $("#check_in_date").datepicker("getDate")) /
            1000 / 60 / 60 / 24; // days
        var rate = $("#room_price").val();
        $('#nights').html(diff + " nights");
        return diff;
    }

    var type = $(".booking_type_id").val();

    $(document).delegate(".booking_type_id", "change", function () {
        type = $(this).val();
        getPrice();
    });

    $("[name='external_services[]'").change(function () {
        getPrice();
    });

    function getPrice() {
        var r_price = $("#room_id").find('option:selected').attr('data-price');
        console.log('r_price' + r_price);
        var service_price = 0;

        $("[id^=user_external_service_]").each(function () {
            var id = $(this).attr('id').split('user_external_service_')[1];
            var price = $(this).attr('data-price');
            console.log('pri' + price);

            price = parseInt(price);

            if ($(this).is(":checked")) {
                var diff = ($("#end_date_" + id).datepicker("getDate") -
                    $("#start_date_" + id).datepicker("getDate")) /
                    1000 / 60 / 60 / 24; // days
                // Round down.
                var diff = Math.floor(diff);
                if (diff > 0) {
                    service_price += (price * diff);
                }
            }
        });


        var nights = getNights();

        var final_price = r_price * nights;

        console.log('final_price' + final_price);
        console.log('service_price' + service_price);

        if (isNaN(final_price))
            final_price = 0;

        var total_price = final_price;

        if (!isNaN(service_price)) {
            total_price = eval(final_price) + eval(service_price);
        }

        $("#total_price").html(total_price);
        $("#total_price_val").val(total_price);
    }

</script>
