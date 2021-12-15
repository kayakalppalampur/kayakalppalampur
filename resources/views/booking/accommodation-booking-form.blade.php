@include('booking.get-booking-info',$booked_info)
<link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">
{!! Form::open(['route' => 'guest.accombookingstore.form','class' => 'ui form']) !!}
{{ csrf_field() }}
<div class="col-md-4">
    Patient:
</div>
<div class="col-md-8">
    {{ $user_obj->userProfile->first_name }} {{ $user_obj->userProfile->last_name }} (Gender-{{ $user_obj->userProfile->getGenderOptions($user_obj->userProfile->gender) }}, Age-{{ $user_obj->userProfile->getAge() }})
</div>
@php
if(!empty($room_data)){
    $building = $room_data['building'];
    $room_price = $room_data['room_price'];
    $room_type = $room_data['room_type'];
    $room_number = $room_data['room_number'];
    $room_id = $room_data['room_id'];;
}
else{
    $building = '';
    $room_type = '';
    $room_number = '';
    $room_price = '';
    $room_id = '';
}
@endphp
<div class="field ">
    <input type="hidden" id="room_price" value="{{ $room_price }}">
    <div class="col-md-4">
        Room:
    </div>
    <div class="col-md-8">
        {{ $building }} - {{ $room_type }} - {{ $room_number }}
    </div>
</div>
<div class="clearfix"></div><br/>
<div class="field ">
    <div class="col-md-4">
        {!! Form::label('check_in_date', 'Check in date') !!}
    </div>
    <div class="col-md-8">
        {!! Form::text('check_in_date',old('check_in_date'),['required', 'class' => 'form-control datepicker', 'id' => 'check_in_date']) !!}
    </div>
</div>
<div class="clearfix"></div><br/>
<div class="field">
    <div class="col-md-4">
        {!! Form::label('check_out_date', 'Check out date') !!}
    </div>
    <div class="col-md-8">
        {!! Form::text('check_out_date',old('check_out_date'),['required', 'class' => 'form-control datepicker', 'id' => 'check_out_date']) !!}
    </div>
</div>
<div class="clearfix"></div><br/>
<div class="field">
    <div class="col-md-4">
        {!! Form::label('booking_type', 'Select Booking Type') !!}
    </div>
    <input type="hidden" id="user_id" name="user_id" value="{{ \Session::get('user_id') }}">
    <input type="hidden" id="room_id" name="room_id" value="{{ $room_id }}">
    <div class="col-md-8 booking_types_data">
        @foreach($booking_types as $booking_type_id => $booking_type_value)
        {{ Form::radio('booking_type', $booking_type_id,'', ['class' => 'booking_type_id', 'required' => 'required']) }} {{ $booking_type_value }}<br>
        @endforeach
    </div>
</div>
<div class="clearfix"></div><br/>

<div class="field">
    <div class="col-md-4">
    Shows nights:
    </div>
    <div class="col-md-8">
        <span id="nights"></span>
    </div>
</div>
<div class="clearfix"></div><br/>
<div class="field">
    <div class="col-md-4">
        {!! Form::label('external_services', 'Extra Services') !!}
    </div>
    <div class="col-md-8">
    {!! Form::select('external_services[]',$external_services,1,['required' => 'required','placeholder' => 'Select External Services','multiple'=>'multiple']) !!}
    </div>
    @foreach(\App\ExternalService::all() as $service)
        <input type="hidden" id="service_{{ $service->id }}" value="{{ $service->price }}">
    @endforeach

</div>
<div class="clearfix"></div><br/>
Total Amount: <span id="total_price"></span>
<input type="hidden" id="total_price_val" name="total_price">
<div class="field ">
    {!! Form::submit('Book') !!}
</div>
{!! Form::close() !!}

<script>
    $( ".datepicker" ).datepicker({dateFormat: "dd-mm-yy", autoclose:true, minDate:0,})/*;
    $( ".datepicker" ).datepicker({format: "yyyy-mm-dd", autoclose:true, startDate:"+0d"});*/
    $(".datepicker").change(function () {
        var date_in = $("#check_in_date").val();
        var date_out = $("#check_out_date").val();
        if (date_in != "" && date_out != "") {
            var nights = getNights(date_in, date_out);
            $('#nights').html(nights + " nights");
            getPrice();
            getTypes();
        }
    });

    function getNights() {
        var diff = ($("#check_out_date").datepicker("getDate") -
                $("#check_in_date").datepicker("getDate")) /
                1000 / 60 / 60 / 24; // days
        var rate = $("#room_price").val();
        return diff;
    }

    var type = $(".booking_type_id").val();
    $(document).delegate(".booking_type_id", "change", function () {
        console.log("sfd");
         type = $(this).val();
        getPrice();
    });

    $("[name='external_services[]'").change(function () {
        getPrice();
    });

    function getPrice() {
        var price = $("#room_price").val();
        var service_val = $("[name='external_services[]'").val();
        service_val = service_val.toString();
        var service_val_ar = service_val.split(',');
        var service_price = 0;
        for (key in service_val_ar) {
            service_price = eval(service_price) + eval( $("#service_"+service_val_ar[key]).val());
        }

console.log('service_val'+service_val);
        var nights = getNights();

        if (type == '{{ \App\Booking::BOOKING_TYPE_SINGLE_BED }}' || type == '{{ \App\Booking::BOOKING_TYPE_DOUBLE_BED_SHARING }}') {console.log('da');
            price = price / 2;
        } else if (type == '{{ \App\Booking::BOOKING_TYPE_DOUBLE_BED_EB }}' || type == '{{ \App\Booking::BOOKING_TYPE_SINGLE_OCCUPANCY_EB }}') {console.log('d');
            price = eval(price) + eval(price / 2);
        }

console.log('price'+price);
        var final_price = price * nights;
        console.log('final_price'+final_price);
        /*var basic_price = '{{ \App\Settings::BASIC_PRICE }}';*/
        if(isNaN(final_price))
            final_price = 0;

        if(isNaN(service_price))
            service_price = 0;

        var total_price = eval(final_price) + /*eval(basic_price) + */eval(service_price);
        $("#total_price").html(total_price);
        console.log('total_price'+total_price);
        console.log('service_price'+service_price);
        $("#total_price_val").val(total_price);
    }
    
    function getTypes() {
        var date_in = $("#check_in_date").val();
        var date_out = $("#check_out_date").val();
        if (date_in != "" && date_out != "") {
            var room_id = $("#room_id").val();
            $.ajax({
                url:"{{url('room/get-status')}}",
                type:"POST",
                data:{'_token':'{{ csrf_token() }}', 'date_in':date_in, 'date_out':date_out,'room_id':room_id, 'user_id' : '{{  \Session::get('user_id') }}'},
                success:function (response) {
                    if(response.status == 'NOK') {
                        alert('Room is already booked for these dates, Check the chart and try another dates.');
                        $("#check_in_date").val("");
                        $("#check_out_date").val("");
                    }else{
                        $(".booking_types_data").html(response.data);
                        $(".booking_type_id").trigger("change");
                    }
                    getPrice();
                }
            })
        }
    }
    getPrice();


</script>