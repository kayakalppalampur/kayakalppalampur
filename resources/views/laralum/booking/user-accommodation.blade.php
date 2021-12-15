@extends('layouts.admin.panel')
@section('content')
<style type="text/css">
    body{overflow: hidden;}
</style>
<link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">
<div class="admin_wrapper signup">
   <!-- <header style="background-color: #008fd5;">
        <div class="logo_wrapper wow fadeInDown">
            <h1>Kayakalp</h1>
        </div>
    </header>-->
    <div class="main_wrapper">


@include('laralum.booking.topbar')
<div class="main_content_area">
    @if($booking->id != null)
    <div id="show_details">
        <div  class="page_title">
            @if(Laralum::loggedInUser()->hasPermission('generate.token') || \Auth::user()->isPatient())

                    <div class="pull-right">
                        <button class="btn btn-primary ui button blue" id="edit_button">Edit Accommodation Details</button>
                    </div>

            @endif
        </div><div><br/>&nbsp;</div>
        <div class="ui one column doubling stackable grid container">
            <div class="column admin_basic_detail1">
                <div class="row">
                    <div class="col-md-12">
                        <div class="title">
                            <div class="space10"></div>
                            <div class="page_title"><h2>Accomodation Details</h2></div>
                            <div class="divider space10"></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table ui">
                                <tbody>
                                @if($booking)
                                <tr>
                                    <th align="center" colspan="4">Previous Booking</th>
                                </tr>
                                <tr>
                                    <th>Booking From</th>
                                    <td>{!! date('d-m-Y', strtotime($booking->check_in_date)) !!}</td>
                                    <th>Booking End</th>
                                    <td>{!! date('d-m-Y', strtotime($booking->check_out_date)) !!}</td>
                                </tr>
                                <tr>
                                    <th>Building Name</th>
                                    <td>{!! $booking->building->name !!}</td>
                                    <th>Booking Type</th>
                                    <td>{!! $booking->getBookingType($booking->booking_type)!!}</td>

                                </tr>
                                @if($booking->room != null)
                                <tr>
                                    <th>Room No</th>
                                    <td> {!! $booking->room->getFloorNumber($booking->room->floor_number) !!} - {!! $booking->room->room_number !!}</td>
                                    <th>Room Type</th>
                                    <td>{!! $booking->room->roomType->name !!}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Status</th>

                                    <td>{{ $booking->getStatusOptions($booking->status) }}</td>
                                    @if($booking->room != null)
                                    <th>Booking Price</th>
                                    <td>{{ $booking->daysPrice() }}</td>
                                    @endif
                                </tr>
                                @if($booking->services != null)
                                @foreach($booking->services as $service)
                                    <tr>
                                        <th>{{ $service->service->name }}</th>
                                        <td>{{ $service->service->price }}</td>
                                    </tr>
                                @endforeach
                                @endif
                                @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div id="edit_details" style="display:{{$booking->id != null ? "none" : "block"}};">

        <div  class="page_title">
            @if(Laralum::loggedInUser()->hasPermission('generate.token') || \Auth::user()->isPatient() )
                {{--@if($user->isEditable())--}}
                    <div class="pull-right">
                        <button class="btn btn-primary ui button blue" id="show_button">Show Accommodation Details</button>
                    </div>
                {{--@endif--}}

        </div><div><br/>&nbsp;</div>
        @endif
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

            {!! Form::open(array('route' => [ 'Laralum::user.booking.accommodation.store', 'user_id' => $user->id], 'id' =>  'bookingProcessForm','files'=>true,'method'=>'post')) !!}
            <input type="hidden" id="user_id" name="user_id" value="{{ $user->id }}"/>
            {{ csrf_field() }}

            <div class="form-group">
                <label>Select Building Name:</label>
                {!! Form::select('building_id',App\Building::getBuildingOptions(), old('building_id', $booking->building_id),['class'=>'form-control required', 'id' => 'building_id', 'required' => 'required'])  !!}
            </div>

            <div class="form-group">
                <label>Select Floor:</label>
                {!! Form::select('floor_number',App\Building::getFloorOptions(), old('floor_number', $booking->floor_number),['class'=>'form-control required select_floor_number', 'id' => 'floor_number', 'required' => 'required'])  !!}
            </div>

            <div class="form-group">
                <label>Select Booking Type:</label>
                {!! Form::select('booking_type',App\Booking::getBookingTypeOptions(), old('booking_type', $booking->booking_type),['class'=>'form-control required', 'id' => 'booking_type', 'required' => 'required'])  !!}
            </div>
            <div class="form-group ">
                {!! Form::label('check_in_date', 'Check in date') !!}
                {!! Form::text('check_in_date',old('check_in_date', $booking->check_in_date),['required', 'class' => 'form-control datepicker', 'id' => 'check_in_date']) !!}
            </div>
            <div class="form-group ">
                {!! Form::label('check_out_date', 'Check out date') !!}
                {!! Form::text('check_out_date',old('check_out_date', $booking->check_out_date),['required', 'class' => 'form-control datepicker', 'id' => 'check_out_date']) !!}
            </div>

            <div class="form-group">
                <button class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.next') }}</button>
            </div>
{!! Form::close() !!}
</div>
        </div>
    </div>
        </div>
    </div>

</div>
</div>
</div>
</div>
<!-- Modal -->
@endsection

@section('js')
        <script>
            $( ".datepicker" ).datepicker({dateFormat: "dd-mm-yy", autoclose:true/*, minDate:0,*/})/*;
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
            $("#building_id").change(function () {
                var val = $(this).val();
                updateDropdown(val);
            })

            function updateDropdown(building_id)
            {
                console.log('building_id : ' + building_id);
                $.ajax({
                    type: 'POST',
                    url: '{{ url('get_building_floor') }}',
                    data: { 'building_id' : building_id, '_token':"{{ csrf_token() }}" },
                    success: function (data) {
                        $('.select_floor_number').html(data.floors);
                    }
                });
            }
            $("#edit_button").click(function () {
                $("#show_details").hide();
                $("#edit_details").show();
            });
            $("#show_button").click(function () {
                $("#show_details").show();
                $("#edit_details").hide();
            });
        </script>
@endsection