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
            <b>Edit/Shift Booking Alloted to {{ $booking_room->alloted_to }} : {{ date("d-m-Y", strtotime($booking_room->check_in_date)) }}
                - {{ date("d-m-Y", strtotime($booking_room->check_out_date)) }}</b>
        @else
            <b>Allot Accomodation</b>
        @endif
    </div>

    

    @if($member != null)
        <div class="field ">
            <label> {!! Form::label('check_in_date', 'Check in date') !!} </label>
            <div class="form_sec_rht">
                @php
                    $checkin = $member->check_in_date;
                        if($booking_room->id != null) {
                            $checkin = $booking_room->check_in_date;
                        }
                        $m_checkin = date('Y-m-d', strtotime(old('check_in_date', $checkin))) > date("Y-m-d") ? date('d-m-Y', strtotime(old('check_in_date', $checkin))) : date("d-m-Y")@endphp


                {!! Form::text('check_in_date', $m_checkin,['required', 'class' => 'form-control datepicker', 'id' => 'check_in_date', 'data-parent-class' => $booking_room->id != null ? 'edit_booking_form' : 'new_form' ]) !!}
            </div>
        </div>

        <div class="field">
            <label> {!! Form::label('check_out_date', 'Check out date') !!} </label>
            <div class="form_sec_rht">
             @php
                    $checkout = $member->check_out_date;
                        if($booking_room->id != null) {
                            $checkout = $booking_room->check_out_date;
                        }
                        $m_checkout = date('Y-m-d', strtotime(old('check_out_date', $checkout))) > date("Y-m-d") ? date('d-m-Y', strtotime(old('check_out_date', $checkout))) : date("d-m-Y", strtotime(' + 1 days'))@endphp

                {!! Form::text('check_out_date',$m_checkout,['required', 'class' => 'form-control datepicker', 'id' => 'check_out_date', 'data-parent-class' => $booking_room->id != null ? 'edit_booking_form' : 'new_form' ]) !!}
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
                {!! Form::select('floor_number', App\Building::getFloorOptions( $member->building_id ), $member->floor_number ,['class'=>'form-control required select_floor_number', 'id' => 'floor_number', 'required' => 'required'])  !!}
            </div>
        </div>

        <div class="field" id="booking_type_div">
            <label> {!! Form::label('booking_type', 'Select Booking Type') !!} </label>
            <div class="form_sec_rht booking_types_data">
                {!! Form::select('type',App\Booking::getBookingTypeOptions(), old('type', $member->booking_type),['class'=>'form-control required', 'id' => 'type', 'required' => 'required'])  !!}
            </div>
        </div>

        <div class="field" id="room_id_div" style="display:none;">
            <label> {!! Form::label('room_id', 'Room number') !!} </label>
            <div class="form_sec_rht">
                <select required class="form-control select_room_number" id="room_id" name="room_id">
                    @foreach(App\Building::getRoomOptionsArray($building, $floor, $member->check_in_date, $member->check_out_date, $member->type, $gender, $booking->id, $is_member) as $room)
                        <option {{ $member->room_id == $room['id'] ? "selected" : "" }} data-price="{{ $room['price'] }}"
                                value="{{ $room['id'] }}">{{ $room['number'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="field" id="bed_no_div" style="display:none;">
            <label>  {!! Form::label('bed_no', 'Select Bed') !!} </label>
            <div class="form_sec_rht booking_types_data bed_checklist select_box">

                @php $bed_status_ar = App\Building::getBedOptionsArray($building, $floor, $member->check_in_date, $member->check_out_date, $member->type, $gender);
                @endphp
                @if($bed_status_ar['all_booked'] == \App\Room::IS_AVAILABLE && isset($bed_status_ar['beds']))
                    @foreach($bed_status_ar['beds'] as $bed_ar)
                        <input type="radio" name="bed_no"
                               value="{{ $bed_ar['bed_no'] }}" {{ $bed_ar['bed_status'] == \App\Room::IS_BLOCKED  ? "disabled" : "" }}>
                        &nbsp;&nbsp;&nbsp; Bed {{ $bed_ar['bed_no'] }}
                    @endforeach
                @endif
            </div>
        </div>

        @if($member->status == 2)
        <div class="field external_services_div">
            {!! Form::label('external_services', 'Extra Services') !!}
            <div class="form_sec_rht multi_select">
                <!----------------------------------- Member Child -------------------------------------->

                @if(!empty($services) && $service_child_count != 0)
                    <div id="child_div">
                        @for($x = 1; $x <= $service_child_count; $x++)
                            <div id="row_child_div_{{ $x }}">
                                <div class="row">
                                    <div class="col-md-2" style="min-width: 24%;">
                                        <input type="checkbox" name="is_child[]" id="service_child_{{ $x }}" data-price="{{ $child_price }}" @if(in_array('1',$services))  checked @endif> Child - {{ $child_price }} /night
                                    </div>
                                    <div class="col-md-4">

                                        @php
                                            if(in_array('1',$services)) {
                                              $date = date('Y-m-d') > $child_check_in[$x-1] ? date("Y-m-d") : $child_check_in[$x-1];
                                          }
                                        @endphp

                                        <input type="text" class="datepicker form-control service_check_in_date"
                                               id="start_date_child_{{ $x }}"
                                               name="child_start_date[]" placeholder="Start Date"
                                               @if(in_array('1',$services)) value="{{ date('d-m-Y', strtotime($date)) }}" @endif>
                                    </div>
                                    <div class="col-md-4">
                                        @php 
                                            $checkout = $child_check_out[$x-1];
                                            $s_checkout = date('Y-m-d', strtotime($checkout)) > date("Y-m-d") ? date('d-m-Y', strtotime($child_check_out[$x-1])) : date("d-m-Y", strtotime(' + 1 days'))
                                        @endphp
                                        <input type="text" class="datepicker form-control service_check_out_date"
                                               id="end_date_child_{{ $x }}"
                                               name="child_end_date[]"
                                               placeholder="End Date"  @if(in_array('1',$services))
                                               value="{{ $s_checkout  }}" @endif>
                                    </div>
                                    @if($x == 1)
                                        <div class="col-md-2">
                                            <span class="add_more_cd" id="add_child_{{ $x }}"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                        </div>
                                    @else
                                        <div class="col-md-2">
                                            <span class="remove_cd" id="remove_child_{{ $x }}"><i class="fa fa-times" aria-hidden="true"></i></span>
                                        </div>
                                    @endif
                                </div><div class="clearfix"></div><br>
                            </div>
                        @endfor
                    </div>
                    <input type="hidden" value="{{ $x }}" id="viewed_child_count">
                @else
                    <div id="child_div"> 
                        <div id="row_child_div_1">
                            <div class="row">
                                <div class="col-md-2" style="min-width: 24%;">
                                    <input type="checkbox" name="is_child[]" id="service_child_1" data-price="{{ $child_price }}"> Child - {{ $child_price }} /night
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="datepicker form-control service_check_in_date"
                                           id="start_date_child_1"
                                           name="child_start_date[]" placeholder="Start Date">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="datepicker form-control service_check_out_date"
                                           id="end_date_child_1"
                                           name="child_end_date[]"
                                           placeholder="End Date">
                                </div>

                                <div class="col-md-2">
                                    <span class="add_more_cd" id="add_child_1"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                </div>
                            
                            </div><div class="clearfix"></div><br>
                        </div>
                    </div>
                    <input type="hidden" value="2" id="viewed_child_count">
                @endif

                <!----------------------------------- Member Driver -------------------------------------->

                @if(!empty($services) && $service_driver_count != 0)
                    <div id="driver_div">
                        @for($x = 1; $x <= $service_driver_count; $x++)
                            <div id="row_driver_div_{{ $x }}">
                                <div class="row">
                                    <div class="col-md-2" style="min-width: 24%">
                                        <input type="checkbox" name="is_driver[]" id="service_driver_{{ $x }}" data-price="{{ $driver_price }}"  @if(in_array('2',$services))  checked @endif> Driver <span id="driver_price_{{ $x }}" @if(in_array('2',$services) && $driver_stay[$x-1] == '1') style="display: none;" @endif >-{{ $driver_price }}/night</span>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="radio" name="driver_stay[{{ $x }}][]" id="driver_date_inside_{{ $x }}" value="inside" @if(in_array('2',$services) && $driver_stay[$x-1] == '1')  checked @endif> Inside
                                    </div>
                                    <div class="col-md-4">
                                        <input type="radio" name="driver_stay[{{ $x }}][]" id="driver_date_outside_{{ $x }}" value="outside"  @if(in_array('2',$services) && $driver_stay[$x-1] == '2')  checked @endif> Outside
                                    </div>
                                    @if($x == 1)
                                        <div class="col-md-2">
                                            <span class="add_more_cd" id="add_driver_{{ $x }}"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                        </div>
                                    @else
                                        <div class="col-md-2">
                                            <span class="remove_cd" id="remove_driver_{{ $x }}"><i class="fa fa-times" aria-hidden="true"></i></span>
                                        </div>
                                    @endif
                                </div>
                                <div class="clearfix"></div><br>
                                <div class="row" id="driver_dates_{{ $x }}" @if(in_array('2',$services) && $driver_stay[$x-1] == '1') style="display: none;" @endif>
                                    <div class="col-md-2" style="min-width: 24%"></div>
                                    <div class="col-md-4">
                                        @php
                                            if(in_array('2',$services)) {
                                              $date = date('Y-m-d') > $driver_check_in[$x-1] ? date("Y-m-d") : $driver_check_in[$x-1];
                                          }
                                        @endphp
                                        <input type="text" class="datepicker form-control service_check_in_date"
                                               id="start_date_driver_{{ $x }}"
                                               name="driver_start_date[]" placeholder="Start Date" @if(in_array('2',$services) && $driver_stay[$x-1] == '2' )
                                               value="{{ date('d-m-Y', strtotime($date))  }}" @endif>
                                    </div>
                                    <div class="col-md-4">
                                        @php 
                                            $checkout = $driver_check_out[$x-1];
                                            $s_checkout = date('Y-m-d', strtotime($checkout)) > date("Y-m-d") ? date('d-m-Y', strtotime($driver_check_out[$x-1])) : date("d-m-Y", strtotime(' + 1 days'))
                                        @endphp
                                        <input type="text" class="datepicker form-control service_check_out_date"
                                               id="end_date_driver_{{ $x }}"
                                               name="driver_end_date[]"
                                               placeholder="End Date" @if(in_array('2',$services) && $driver_stay[$x-1] == '2')
                                                value="{{ $s_checkout }}" @endif>
                                    </div>
                                </div><div class="clearfix"></div><br>
                            </div>
                        @endfor
                    </div>
                    <input type="hidden" id="viewed_driver_count" value="{{ $x }}">
                @else
                    <div id="driver_div"> 
                        <div id="row_driver_div_1">
                            <div class="row">
                                <div class="col-md-2" style="min-width: 24%">
                                    <input type="checkbox" name="is_driver[]" id="service_driver_1" data-price="{{ $driver_price }}"> Driver <span id="driver_price_1" style="display: none;">-{{ $driver_price }}/night</span>
                                </div>
                                <div class="col-md-4">
                                    <input type="radio" name="driver_stay[1][]" id="driver_date_inside_1"  value="inside"> Inside
                                </div>
                                <div class="col-md-4">
                                    <input type="radio" name="driver_stay[1][]" id="driver_date_outside_1" value="outside"> Outside
                                </div>
                                <div class="col-md-2">
                                    <span class="add_more_cd" id="add_driver_1"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                </div>     
                            </div>
                            <div class="clearfix"></div><br>
                            <div class="row" id="driver_dates_1" style="display: none;">
                                <div class="col-md-2" style="min-width: 24%"></div>
                                <div class="col-md-4">
                                    <input type="text" class="datepicker form-control service_check_in_date"
                                           id="start_date_driver_1"
                                           name="driver_start_date[]" placeholder="Start Date">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="datepicker form-control service_check_out_date"
                                           id="end_date_driver_1"
                                           name="driver_end_date[]"
                                           placeholder="End Date">
                                </div>
                            </div>

                            <div class="clearfix"></div><br>
                        </div>
                    </div>
                    <input type="hidden" value="2" id="viewed_driver_count">
                @endif
            </div>
        </div>
        @else
        <div class="field external_services_div">
            {!! Form::label('external_services', 'Extra Services') !!}
            <div class="form_sec_rht multi_select">
            @if($member->is_child == 1)
                <div id="child_div"> 
                    @for($x = 1; $x <= $member->child_count; $x++)
                        <div id="row_child_div_{{ $x }}">
                            <div class="row">
                                <div class="col-md-2" style="min-width: 24%;">
                                    <input type="checkbox" name="is_child[]" id="service_child_{{ $x }}" data-price="{{ $child_price }}"  @if($member->is_child == 1) checked @endif> Child - {{ $child_price }} /night
                                </div>
                                <div class="col-md-4">


                                    @php
                                        if($member->is_child == '1') {
                                          $date = date('Y-m-d') > $member->check_in_date ? date("Y-m-d") :  $member->check_in_date;
                                      }
                                    @endphp


                                    <input type="text" class="datepicker form-control service_check_in_date"
                                           id="start_date_child_{{ $x }}"
                                           name="child_start_date[]" placeholder="Start Date"  @if($member->is_child == '1')
                                           value="{{ date('d-m-Y', strtotime($date)) }}" @endif>
                                </div>
                                <div class="col-md-4">
                                    @if($member->is_child == '1')
                                           @php 
                                                $checkout = $member->check_out_date;
                                                $s_checkout = date('Y-m-d', strtotime($checkout)) > date("Y-m-d") ? date('d-m-Y', strtotime($member->check_out_date)) : date("d-m-Y", strtotime(' + 1 days'))
                                            @endphp
                                    @endif
                                    <input type="text" class="datepicker form-control service_check_out_date"
                                           id="end_date_child_{{ $x }}"
                                           name="child_end_date[]"
                                           placeholder="End Date" @if($member->is_child == '1')
                                           value="{{ $s_checkout  }}" @endif>
                                </div>
                                @if($x == 1)
                                    <div class="col-md-2">
                                        <span class="add_more_cd" id="add_child_{{ $x }}"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                    </div>
                                @else
                                    <div class="col-md-2">
                                        <span class="remove_cd" id="remove_child_{{ $x }}"><i class="fa fa-times" aria-hidden="true"></i></span>
                                    </div>
                                @endif
                            </div><div class="clearfix"></div><br>
                        </div>
                    @endfor
                </div>
                <input type="hidden" value="{{ $x }}" id="viewed_child_count">
            @else
                <div id="child_div"> <div id="row_child_div_1">
                    <div class="row"> 
                        <div class="col-md-2" style="min-width: 24%;">
                            <input type="checkbox" name="is_child[]" id="service_child_1" data-price="{{ $child_price }}"> Child - {{ $child_price }} /night
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="datepicker form-control service_check_in_date"
                                   id="start_date_child_1"
                                   name="child_start_date[]" placeholder="Start Date">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="datepicker form-control service_check_out_date"
                                   id="end_date_child_1"
                                   name="child_end_date[]"
                                   placeholder="End Date">
                        </div>

                        <div class="col-md-2">
                            <span class="add_more_cd" id="add_child_1"><i class="fa fa-plus" aria-hidden="true"></i></span>
                        </div>
                                    
                    </div><div class="clearfix"></div><br>
                </div></div>
                <input type="hidden" value="2" id="viewed_child_count"> 

            @endif
            @if($member->is_driver == '1')
                <div id="driver_div" >
                    @for($x = 1; $x <= $member->driver_count; $x++)
                        <div id="row_driver_div_{{ $x }}">
                            <div class="row">  
                                <div class="col-md-2" style="min-width: 24%">
                                    <input type="checkbox" name="is_driver[]" id="service_driver_{{ $x }}" data-price="{{ $driver_price }}"  @if($member->is_driver == '1') checked @endif> Driver <span id="driver_price_{{ $x }}" style="display: none;">-{{ $driver_price }}/night</span>
                                </div>
                                <div class="col-md-4">
                                    <input type="radio" name="driver_stay[{{ $x }}][]" id="driver_date_inside_{{ $x }}"  value="inside" checked> Inside
                                </div>
                                <div class="col-md-4">
                                    <input type="radio" name="driver_stay[{{ $x }}][]" id="driver_date_outside_{{ $x }}" value="outside"> Outside
                                </div>
                                @if($x == 1)
                                    <div class="col-md-2">
                                        <span class="add_more_cd" id="add_driver_{{ $x }}"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                    </div>
                                @else
                                    <div class="col-md-2">
                                        <span class="remove_cd" id="remove_driver_{{ $x }}"><i class="fa fa-times" aria-hidden="true"></i></span>
                                    </div>
                                @endif
                            </div>
                            <div class="clearfix"></div><br>
                            <div class="row" id="driver_dates_{{ $x }}" style="display: none;">
                                <div class="col-md-2" style="min-width: 24%"></div>
                                <div class="col-md-4">
                                    <input type="text" class="datepicker form-control service_check_in_date"
                                           id="start_date_driver_{{ $x }}"
                                           name="driver_start_date[]" placeholder="Start Date">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="datepicker form-control service_check_out_date"
                                           id="end_date_driver_{{ $x }}"
                                           name="driver_end_date[]"
                                           placeholder="End Date">
                                </div>
                            </div>
                            <div class="clearfix"></div><br>
                        </div>
                    @endfor
                </div>
                <input type="hidden" value="{{ $x }}" id="viewed_driver_count">
            @else
                <div id="driver_div" ><div id="row_driver_div_1">
                    <div class="row">    
                        <div class="col-md-2" style="min-width: 24%">
                            <input type="checkbox" name="is_driver[]" id="service_driver_1" data-price="{{ $driver_price }}">Driver<span id="driver_price_1" style="display: none;">-{{ $driver_price }}/night</span>
                        </div>
                        <div class="col-md-4">
                            <input type="radio" name="driver_stay[1][]" id="driver_date_inside_1"  value="inside" checked> Inside
                        </div>
                        <div class="col-md-4">
                            <input type="radio" name="driver_stay[1][]" id="driver_date_outside_1" value="outside"> Outside
                        </div>
                        <div class="col-md-2">
                            <span class="add_more_cd" id="add_driver_1"><i class="fa fa-plus" aria-hidden="true"></i></span>
                        </div>
                                   
                    </div>
                    <div class="clearfix"></div><br>
                    <div class="row" id="driver_dates_1" style="display: none;">
                        <div class="col-md-2" style="min-width: 24%"></div>
                        <div class="col-md-4">
                            <input type="text" class="datepicker form-control service_check_in_date"
                                   id="start_date_driver_1"
                                   name="driver_start_date[]" placeholder="Start Date">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="datepicker form-control service_check_out_date"
                                   id="end_date_driver_1"
                                   name="driver_end_date[]"
                                   placeholder="End Date">
                        </div>
                    </div>
                    <div class="clearfix"></div><br>
                </div> </div>
                <input type="hidden" id="viewed_driver_count" value="2">
            @endif
            </div>
        </div>
        @endif

        

    @else

    <!--------------------------------------------  Bookings+   ---------------------------------------->

        <div class="field ">
            <label> {!! Form::label('check_in_date', 'Check in date') !!} </label>
            <div class="form_sec_rht">
                @php $b_checkin = date('Y-m-d', strtotime(old('check_in_date', $booking_room->check_in_date))) > date("Y-m-d") ? date('d-m-Y', strtotime(old('check_in_date', $booking_room->check_in_date))) : date("d-m-Y") @endphp
                {!! Form::text('check_in_date',$b_checkin,['required', 'class' => 'form-control datepicker', 'id' => 'check_in_date', 'data-parent-class' => $booking_room->id != null ? 'edit_booking_form' : 'new_form' ]) !!}
            </div>
        </div>
        
        <div class="field">
            <label> {!! Form::label('check_out_date', 'Check out date') !!} </label>
            <div class="form_sec_rht">
            @php $b_checkout = date('Y-m-d', strtotime(old('check_out_date', $booking_room->check_out_date))) > date("Y-m-d") ? date('d-m-Y', strtotime(old('check_out_date', $booking_room->check_out_date))) : date("d-m-Y", strtotime(' + 1 days')) @endphp

                {!! Form::text('check_out_date',$b_checkout,['required', 'class' => 'form-control datepicker', 'id' => 'check_out_date', 'data-parent-class' => $booking_room->id != null ? 'edit_booking_form' : 'new_form' ]) !!}
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
                @if($bed_status_ar['all_booked'] == \App\Room::IS_AVAILABLE && isset($bed_status_ar['beds']))
                    @foreach($bed_status_ar['beds'] as $bed_ar)
                        <input type="radio" name="bed_no"
                               value="{{ $bed_ar['bed_no'] }}" {{ $bed_ar['bed_status'] == \App\Room::IS_BLOCKED  ? "disabled" : "" }}>
                        &nbsp;&nbsp;&nbsp; Bed {{ $bed_ar['bed_no'] }}
                    @endforeach
                @endif
            </div>
        </div>

        @if(!empty($booking->getCurrentBooking()))
            <div class="field external_services_div">
                <input type="hidden" id="childprice"  value="{{ $child_price }}" > 
                <input type="hidden" id="driverprice"  value="{{ $driver_price }}"> 
                {!! Form::label('external_services', 'Extra Services') !!}
                <div class="form_sec_rht multi_select"> 
                    <!----------------------------------- Booking Child -------------------------------------->

                    @if(!empty($services) && $service_child_count != 0)
                        <div id="child_div">
                            @for($x = 1; $x <= $service_child_count; $x++)
                                <div id="row_child_div_{{ $x }}">
                                    <div class="row">
                                        <div class="col-md-2" style="min-width: 24%;">
                                            <input type="checkbox" id="service_child_{{ $x }}" data-price="{{ $child_price }}" name="is_child[]" @if(in_array('1',$services))  checked @endif> Child - {{ $child_price }} /night
                                        </div>
                                        <div class="col-md-4">

                                            @php
                                                if(in_array('1',$services)) {
                                                $date = date('Y-m-d') > $child_check_in[$x-1] ? date("Y-m-d") : $child_check_in[$x-1];
                                                } @endphp
                                            <input type="text" class="datepicker form-control service_check_in_date"
                                                   id="start_date_child_{{ $x }}"
                                                   name="child_start_date[]" placeholder="Start Date" @if(in_array('1',$services)) 
                                                   value="{{ date('d-m-Y', strtotime($date)) }}" @endif>
                                        </div>
                                        <div class="col-md-4">
                                        @if(in_array('1',$services))
                                            @php 
                                                $checkout = $child_check_out[$x-1];
                                                $s_checkout = date('Y-m-d', strtotime($checkout)) > date("Y-m-d") ? date('d-m-Y', strtotime($child_check_out[$x-1])) : date("d-m-Y", strtotime(' + 1 days'))
                                            @endphp
                                        @endif

                                            <input type="text" class="datepicker form-control service_check_out_date"
                                                   id="end_date_child_{{ $x }}"
                                                   name="child_end_date[]"
                                                   placeholder="End Date" @if(in_array('1',$services))
                                                   value="{{ $s_checkout }}" @endif>
                                        </div>
                                        @if($x == 1)
                                            <div class="col-md-2">
                                                <span class="add_more_cd" id="add_child_{{ $x }}"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                            </div>
                                        @else
                                            <div class="col-md-2">
                                                <span class="remove_cd" id="remove_child_{{ $x }}"><i class="fa fa-times" aria-hidden="true"></i></span>
                                            </div>
                                        @endif
                                    </div><div class="clearfix"></div><br>
                                </div>
                            @endfor
                        </div>
                        <input type="hidden" value="{{ $x }}" id="viewed_child_count"> 
                    @else
                        <div id="child_div"> 
                            <div id="row_child_div_1">
                                <div class="row">
                                    <div class="col-md-2" style="min-width: 24%;">
                                        <input type="checkbox" id="service_child_1" data-price="{{ $child_price }}" name="is_child[]"> Child - {{ $child_price }} /night
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="datepicker form-control service_check_in_date"
                                               id="start_date_child_1"
                                               name="child_start_date[]" placeholder="Start Date">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="datepicker form-control service_check_out_date"
                                               id="end_date_child_1"
                                               name="child_end_date[]"
                                               placeholder="End Date">
                                    </div>
                                    <div class="col-md-2">
                                        <span class="add_more_cd" id="add_child_1"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                    </div>
                                </div><div class="clearfix"></div><br>
                            </div>
                        </div>
                        <input type="hidden" value="2" id="viewed_child_count">
                    @endif
                    <!-------------------------------------- Booking Driver ---------------------------->
                    @if(!empty($services) && $service_driver_count != 0)
                        <div id="driver_div">
                            @for($x = 1; $x <= $service_driver_count; $x++)
                                <div id="row_driver_div_{{ $x }}">
                                    <div class="row">
                                        <div class="col-md-2" style="min-width: 24%">
                                            <input type="checkbox"  id="service_driver_{{ $x }}" data-price="{{ $driver_price }}"  name="is_driver[]" @if(in_array('2',$services))  checked @endif>Driver<span id="driver_price_{{ $x }}" @if(in_array('2',$services) && $driver_stay[$x-1] == '1') style="display: none;" @endif >-{{ $driver_price }}/night</span>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="radio" name="driver_stay[{{ $x }}][]" id="driver_date_inside_{{ $x }}"  value="inside" @if(in_array('2',$services) && $driver_stay[$x-1] == '1')  checked @endif > Inside
                                        </div>
                                        <div class="col-md-4">
                                            <input type="radio" name="driver_stay[{{ $x }}][]" id="driver_date_outside_{{ $x }}" value="outside" @if(in_array('2',$services) && $driver_stay[$x-1] == '2')  checked @endif> Outside
                                        </div>
                                        @if($x == 1)
                                            <div class="col-md-2">
                                                <span class="add_more_cd" id="add_driver_{{ $x }}"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                            </div>
                                        @else
                                            <div class="col-md-2">
                                                <span class="remove_cd" id="remove_driver_{{ $x }}"><i class="fa fa-times" aria-hidden="true"></i></span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="clearfix"></div><br>
                                    <div class="row" id="driver_dates_{{ $x }}" @if(in_array('2',$services) && $driver_stay[$x-1] == '1') style="display: none;" @endif>
                                        <div class="col-md-2" style="min-width: 24%"></div>
                                        <div class="col-md-4">

                                            @php
                                                if(in_array('2',$services)) {
                                                   $date = date('Y-m-d') > $driver_check_in[$x-1] ? date("Y-m-d") : $driver_check_in[$x-1];
                                                }
                                            @endphp
                                            <input type="text" class="datepicker form-control service_check_in_date"
                                                   id="start_date_driver_{{ $x }}"
                                                   name="driver_start_date[]" placeholder="Start Date" @if(in_array('2',$services))
                                                   value="{{ date('d-m-Y', strtotime($date))  }}" @endif>
                                        </div>
                                        <div class="col-md-4">
                                        @if(in_array('2',$services) && $driver_stay[$x-1] == '2')
                                            @php 
                                                $checkout = $driver_check_out[$x-1];
                                                $s_checkout = date('Y-m-d', strtotime($checkout)) > date("Y-m-d") ? date('d-m-Y', strtotime($driver_check_out[$x-1])) : date("d-m-Y", strtotime(' + 1 days'))
                                            @endphp
                                        @else
                                            @php 
                                                $checkout = $date;
                                                $s_checkout = date('Y-m-d', strtotime($checkout)) > date("Y-m-d") ? date('d-m-Y', strtotime($date)) : date("d-m-Y", strtotime(' + 1 days'))
                                            @endphp
                                        @endif

                                            <input type="text" class="datepicker form-control service_check_out_date"
                                                   id="end_date_driver_{{ $x }}"
                                                   name="driver_end_date[]"
                                                   placeholder="End Date" 
                                                   value="{{ $s_checkout }}" >
                                        </div>
                                    </div><div class="clearfix"></div><br>
                                </div>
                            @endfor
                        </div>
                        <input type="hidden" value="{{ $x }}" id="viewed_driver_count">
                    @else
                        <div id="driver_div"> <div id="row_driver_div_1">
                            <div class="row">
                                <div class="col-md-2" style="min-width: 24%">
                                    <input type="checkbox"  id="service_driver_1" data-price="{{ $driver_price }}"  name="is_driver[]">Driver<span id="driver_price_1" style="display: none;" >-{{ $driver_price }}/night</span>
                                </div>
                                <div class="col-md-4">
                                    <input type="radio" name="driver_stay[1][]" id="driver_date_inside_1"  value="inside" checked> Inside
                                </div>
                                <div class="col-md-4">
                                    <input type="radio" name="driver_stay[1][]" id="driver_date_outside_1" value="outside"> Outside
                                </div>
                                <div class="col-md-2">
                                    <span class="add_more_cd" id="add_driver_1"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                </div>
                            </div>
                            <div class="clearfix"></div><br>
                            <div class="row" id="driver_dates_1" style="display: none;">
                                    <div class="col-md-2" style="min-width: 24%"></div>
                                    <div class="col-md-4">
                                        <input type="text" class="datepicker form-control service_check_in_date"
                                               id="start_date_driver_1"
                                               name="driver_start_date[]" placeholder="Start Date">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="datepicker form-control service_check_out_date"
                                               id="end_date_driver_1"
                                               name="driver_end_date[]"
                                               placeholder="End Date">
                                    </div>
                            </div>
                            <div class="clearfix"></div><br>
                        </div></div>
                        <input type="hidden" value="2" id="viewed_driver_count">
                    @endif
                </div>
            </div>
        @else
            <div class="field external_services_div">
                <input type="hidden" id="childprice"  value="{{ $child_price }}" > 
                <input type="hidden" id="driverprice"  value="{{ $driver_price }}"> 
                {!! Form::label('external_services', 'Extra Services') !!}
                <div class="form_sec_rht multi_select">
                @if($booking->is_child == '1')
                    <div id="child_div"> 
                        @for($x = 1; $x <= $booking->child_count; $x++)
                            <div id="row_child_div_{{ $x }}">
                                <div class="row">
                                    <div class="col-md-2" style="min-width: 24%;">
                                        <input type="checkbox"  name="is_child[]" id="service_child_{{ $x }}" data-price="{{ $child_price }}" @if($booking->is_child == '1') checked @endif > Child - {{ $child_price }} /night
                                    </div>
                                    <div class="col-md-4">

                                        @php
                                            if(!empty($services)) {
                                            $date = date('Y-m-d') > $child_check_in ? date("Y-m-d") : $child_check_in;
                                        }else{
                                        $date =  date('d-m-Y', strtotime($booking->check_in_date));
                                        }
                                        @endphp
                                        <input type="text" class="datepicker form-control service_check_in_date"
                                               id="start_date_child_{{ $x }}"
                                               name="child_start_date[]" placeholder="Start Date" @if($booking->is_child == '1')
                                               @if(!empty($services)) value="{{ date('d-m-Y', strtotime($date)) }}" @else  value="{{ date('d-m-Y', strtotime($booking->check_in_date)) }}" @endif
                                                @endif >
                                    </div>
                                    <div class="col-md-4">
                                    @if($booking->is_child == '1') 
                                        @if(!empty($services))
                                            @php 
                                                $checkout = $child_check_out;
                                                $s_checkout = date('Y-m-d', strtotime($checkout)) > date("Y-m-d") ? date('d-m-Y', strtotime($child_check_out)) : date("d-m-Y", strtotime(' + 1 days'))
                                            @endphp
                                        @else
                                            @php 
                                                $checkout = $booking->check_out_date;
                                                $s_checkout = date('Y-m-d', strtotime($checkout)) > date("Y-m-d") ? date('d-m-Y', strtotime($booking->check_out_date)) : date("d-m-Y", strtotime(' + 1 days'))
                                            @endphp
                                        @endif
                                    @endif

                                        <input type="text" class="datepicker form-control service_check_out_date"
                                               id="end_date_child_{{ $x }}"
                                               name="child_end_date[]"
                                               placeholder="End Date"  @if($booking->is_child == '1') value="{{ $s_checkout }}" @endif >
                                    </div>
                                    @if($x == 1)
                                        <div class="col-md-2">
                                            <span class="add_more_cd" id="add_child_{{ $x }}"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                        </div>
                                    @else
                                        <div class="col-md-2">
                                            <span class="remove_cd" id="remove_child_{{ $x }}"><i class="fa fa-times" aria-hidden="true"></i></span>
                                        </div>
                                    @endif
                                </div><div class="clearfix"></div><br>
                            </div>
                        @endfor
                    </div>
                    <input type="hidden" value="{{ $x }}" id="viewed_child_count">
                @else
                    <div id="child_div"> <div id="row_child_div_1">
                        <div class="row">
                            <div class="col-md-2" style="min-width: 24%;">
                                <input type="checkbox"  name="is_child[]" id="service_child_1" data-price="{{ $child_price }}"> Child - {{ $child_price }} /night
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="datepicker form-control service_check_in_date"
                                       id="start_date_child_1"
                                       name="child_start_date[]" placeholder="Start Date">
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="datepicker form-control service_check_out_date"
                                       id="end_date_child_1"
                                       name="child_end_date[]"
                                       placeholder="End Date">
                            </div>
                            <div class="col-md-2">
                                <span class="add_more_cd" id="add_child_1"><i class="fa fa-plus" aria-hidden="true"></i></span>
                            </div>
                        </div><div class="clearfix"></div><br>
                    </div></div>
                    <input type="hidden" value="2" id="viewed_child_count"> 
                    
                @endif
                @if($booking->is_driver == '1')
                    <div id="driver_div">
                        @for($x = 1; $x <= $booking->driver_count; $x++)
                            <div id="row_driver_div_{{ $x }}">
                                <div class="row">
                                    <div class="col-md-2" style="min-width: 24%">
                                        <input type="checkbox"  name="is_driver[]" id="service_driver_{{ $x }}" data-price="{{ $driver_price }}" @if($booking->is_driver == '1') checked @endif>Driver<span id="driver_price_{{ $x }}" style="display: none;">-{{ $driver_price }}/night</span>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="radio" name="driver_stay[{{ $x }}][]" id="driver_date_inside_{{ $x }}" value="inside" checked> Inside
                                    </div>
                                    <div class="col-md-4">
                                        <input type="radio" name="driver_stay[{{ $x }}][]" id="driver_date_outside_{{ $x }}" value="outside"> Outside
                                    </div>
                                    @if($x == 1)
                                        <div class="col-md-2">
                                            <span class="add_more_cd" id="add_driver_{{ $x }}"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                        </div>
                                    @else
                                        <div class="col-md-2">
                                            <span class="remove_cd" id="remove_driver_{{ $x }}"><i class="fa fa-times" aria-hidden="true"></i></span>
                                        </div>
                                    @endif
                                </div>
                                <div class="clearfix"></div><br>
                                <div class="row" id="driver_dates_{{ $x }}" style="display: none;">
                                    <div class="col-md-2" style="min-width: 24%"></div>
                                    <div class="col-md-4">
                                        
                                        <input type="text" class="datepicker form-control service_check_in_date"
                                               id="start_date_driver_{{ $x }}"
                                               name="driver_start_date[]" placeholder="Start Date">
                                    </div>
                                    <div class="col-md-4">
                                        
                                        <input type="text" class="datepicker form-control service_check_out_date"
                                               id="end_date_driver_{{ $x }}"
                                               name="driver_end_date[]"
                                               placeholder="End Date">
                                    </div>
                                </div>
                                <div class="clearfix"></div><br>
                            </div>
                        @endfor
                    </div> <input type="hidden" value="{{ $x }}" id="viewed_driver_count"> 
                @else
                <div id="driver_div" ><div id="row_driver_div_1">
                    <div class="row">
                        <div class="col-md-2" style="min-width: 24%;">
                            <input type="checkbox"  name="is_driver[]" id="service_driver_1" data-price="{{ $driver_price }}">Driver<span id="driver_price_1" style="display: none;">-{{ $driver_price }}/night</span>
                        </div>
                        <div class="col-md-4">
                            <input type="radio" name="driver_stay[1][]" id="driver_date_inside_1" checked value="inside"> Inside
                        </div>
                        <div class="col-md-4">
                            <input type="radio" name="driver_stay[1][]" id="driver_date_outside_1" value="outside"> Outside
                        </div>
                        <div class="col-md-2">
                            <span class="add_more_cd" id="add_driver_1"><i class="fa fa-plus" aria-hidden="true"></i></span>
                        </div>
                    </div>
                    <div class="clearfix"></div><br>
                    <div class="row" id="driver_dates_1" style="display: none;">
                            <div class="col-md-2" style="min-width: 24%"></div>
                            <div class="col-md-4">
                                <input type="text" class="datepicker form-control service_check_in_date"
                                       id="start_date_driver_1"
                                       name="driver_start_date[]" placeholder="Start Date">
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="datepicker form-control service_check_out_date"
                                       id="end_date_driver_1"
                                       name="driver_end_date[]"
                                       placeholder="End Date">
                            </div>
                    </div>
                    <div class="clearfix"></div><br>
                </div> </div>
                <input type="hidden" id="viewed_driver_count" value="2">
                @endif
                </div>
            </div>
        @endif
        <br>        
    @endif


    {{--<div class="field">
        <label>  {!! Form::label('requested_external_services', 'Requested External Services') !!} </label>
        <div class="form_sec_rht">
            @php echo implode(', ', \App\ExternalService::whereIn('id', explode(',', $booking->external_services))->pluck('name', 'id')->toArray()) @endphp
        </div>
    </div>--}}

    <?php
    if(count($booked_rooms) > 0){
        $services_array = isset($booked_rooms[0]->room_id) ? explode(',', $booked_rooms[0]->room->services) : array();
    }
    else{
        $services_array = isset($booking->room->id) ? explode(',', $booking->room->services) : array();
    } 
    $ext_services = \App\ExternalService::whereIn('id', $services_array)->get(); 
    ?>

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
    <input type="hidden" id="booking_room_id" name="booking_room_id" value="{{ @$booking_room->id }}">

    <div class="field room_book">
        <label> &nbsp; </label>
        {!! Form::submit('Book') !!}
    </div>

</div>

<div id="clone_child" style="display: none;">
    <div class="row">
        <div class="col-md-2" style="min-width: 24%;">
            <input type="checkbox"  name="is_child[]" id="service_child_" data-price="{{ $child_price }}"> Child - {{ $child_price }} /night
        </div>
        <div class="col-md-4">
            <input type="text" class="datepicker form-control service_check_in_date" id="start_date_child_" name="child_start_date[]" placeholder="Start Date">
        </div>
        <div class="col-md-4">
            <input type="text" class="datepicker form-control service_check_out_date" id="end_date_child_" name="child_end_date[]"
            placeholder="End Date">
        </div>
        <div class="col-md-2">
            <span class="remove_cd" id="remove_child_"><i class="fa fa-times" aria-hidden="true"></i></span>
        </div>
    </div><div class="clearfix"></div><br>
</div>

<div id="clone_driver" style="display: none;">
    <div class="row">
        <div class="col-md-2" style="min-width: 24%;">
            <input type="checkbox"  name="is_driver[]" id="service_driver_" data-price="{{ $driver_price }}">Driver<span id="driver_price_" style="display: none;">-{{ $driver_price }}/night</span>
        </div>
        <div class="col-md-4">
            <input type="radio" name="driver_stay[][]" id="driver_date_inside_"  value="inside" > Inside
        </div>
        <div class="col-md-4">
            <input type="radio" name="driver_stay[][]" id="driver_date_outside_" value="outside"> Outside
        </div>
        <div class="col-md-2">
            <span class="remove_cd" id="remove_driver_"><i class="fa fa-times" aria-hidden="true"></i></span>
        </div>
    </div>
    <div class="clearfix"></div><br>
    <div class="row" id="driver_dates_" style="display: none;">
            <div class="col-md-2" style="min-width: 24%"></div>
            <div class="col-md-4">
                <input type="text" class="datepicker form-control service_check_in_date"
                       id="start_date_driver_"
                       name="driver_start_date[]" placeholder="Start Date">
            </div>
            <div class="col-md-4">
                <input type="text" class="datepicker form-control service_check_out_date"
                       id="end_date_driver_"
                       name="driver_end_date[]"
                       placeholder="End Date">
            </div>
    </div>
    <div class="clearfix"></div><br>
</div>

{!! Form::close() !!}


<script>
    $(".datepicker").datepicker({dateFormat: "dd-mm-yy", autoclose: true/*, minDate: "+0d"*/});

    var check_in_date = $("#check_in_date").val();

        $("[id^=start_date_child_]").each(function(){
            $(this).datepicker({dateFormat: "dd-mm-yy", autoclose: true/*, minDate: 0*/}/*"option", "minDate", check_in_date */);
        });

    $(document).delegate("#check_in_date", "change", function () {
        var date_in = $("#check_in_date").val();
        var date_out = $("#check_out_date").val();
        var building = $("#building_id").val();
        var floor_number = $("#floor_number").val();
        var room = $("#room_id").val();
        updateBedDropdown(building);
        updateRoomDropdown(building,floor_number);
        updateServicesDropdown(room);

        if (date_in != "" && date_out != "") {
            getPrice();
            // getTypes();
        }
    })
    $(document).delegate("#check_out_date", "change", function () {
        var date_in = $("#check_in_date").val();
        var date_out = $("#check_out_date").val();
        var val = $("#building_id").val();
        var building = $("#building_id").val();
        var floor_number = $("#floor_number").val();
        var room = $("#room_id").val();
        updateBedDropdown(building);
        updateRoomDropdown(building,floor_number);
        updateServicesDropdown(room);

        if (date_in != "" && date_out != "") {
            getPrice();
            // getTypes();
        }
    })


    $(document).delegate("#start_date_child", "change", function () {
        var date_in = $("#start_date_child").val();
        var date_out = $("#end_date_child").val();

        if (date_in != "" && date_out != "") {
            getPrice();

        }
    })

    $(document).delegate("#end_date_child", "change", function () {
        var date_in = $("#start_date_child").val();
        var date_out = $("#end_date_child").val();
        if (date_in != "" && date_out != "") {
            getPrice();

        }
    })


    $(document).delegate("#start_date_driver", "change", function () {
        var date_in = $("#start_date_driver").val();
        var date_out = $("#end_date_driver").val();

        if (date_in != "" && date_out != "") {
            getPrice();
        }
    })

    $(document).delegate("#end_date_driver", "change", function () {
        var date_in = $("#start_date_driver").val();
        var date_out = $("#end_date_driver").val();

        if (date_in != "" && date_out != "") {
            getPrice();
        }
    })


    $(document).delegate("[id^=start_date_]", "change", function () {
        var id = $(this).attr('id').split('start_date_')[1];
        var date_in = $('#start_date_'+id).val();
        var date_out = $('#end_date_'+id).val();
        console.log(date_in  +  date_out);
        if (date_in != "" && date_out != "") {
            getPrice();
        }

       
    })

    $(document).delegate("[id^=end_date_]", "change", function () {
        var id = $(this).attr('id').split('end_date_')[1];
        var date_in = $('#start_date_'+id).val();
        var date_out = $('#end_date_'+id).val();
        if (date_in != "" && date_out != "") {
            getPrice();
        }        
    })

    $(document).delegate("[id^=driver_date_inside_]", 'change', function () {
        var id = $(this).attr('id').split('driver_date_inside_')[1];
        $('#driver_dates_'+id).hide();
        $('#start_date_driver_'+id).val('');
        $('#end_date_driver_'+id).val('');
        $('#start_date_driver_'+id).prop('required',false);
        $('#end_date_driver_'+id).prop('required',false);
        $('#driver_price_'+id).hide();
        getPrice();

    })

    $(document).delegate("[id^=driver_date_outside_]", 'change', function () {
        var id = $(this).attr('id').split('driver_date_outside_')[1];
        $('#driver_dates_'+id).show();
        $('#driver_price_'+id).show();
        $('#start_date_driver_'+id).prop('required',true);
        $('#end_date_driver_'+id).prop('required',true);
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
         updateServicesDropdown(val);

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
        //alert('here');
        if(val != '' ){
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
        else{
            $('.bed_checklist').hide();
            $('#bed_no_div').hide();
            getPrice();
            getNights();
        }
        
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
                'member_id' : member_id,
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
                var booked_rooms = "{{ $booked_rooms }}";
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
        var booking_room_id = "{{ $booking_room->id }}";
        $.ajax({
            type: 'POST',
            url: '{{ url('get_room_services') }}',
            data: {
                'room_id': room_id,
                '_token': "{{ csrf_token() }}",
                'booking_id': booking_id,
                'booking_room_id': booking_room_id
            },
            success: function (data) {
                $("#ext_services").html(data.html);
                getPrice();
            }
        });

    }

    function updateDropdown(building_id) {
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


    function getNights2() {
        var diff = ($("#end_date_child").datepicker("getDate") -
            $("#start_date_child").datepicker("getDate")) /
            1000 / 60 / 60 / 24; // days
        return diff;
    }

    function getNights3() {
        var diff = ($("#end_date_driver").datepicker("getDate") -
            $("#start_date_driver").datepicker("getDate")) /
            1000 / 60 / 60 / 24; // days
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


    $(document).delegate("[id^=service_child_]", 'change', function () {
        if (!$(this).is(":checked")) {
            var id = $(this).attr('id').split('service_child_')[1];
            $('#start_date_child_'+id).val('');
            $('#end_date_child_'+id).val('');
        }
        getPrice();
    })


    $(document).delegate("[id^=service_driver_]", 'change', function () {
        if (!$(this).is(":checked")) {
            var id = $(this).attr('id').split('service_driver_')[1];
            $('#start_date_driver_'+id).val('');
            $('#end_date_driver_'+id).val('');
        }
        getPrice();
    })

    function getPrice() {
        var r_price = $("#room_id").find('option:selected').attr('data-price');
        var service_price = 0;

        $("[id^=user_external_service_]").each(function () {
            var id = $(this).attr('id').split('user_external_service_')[1];
            var price = $(this).attr('data-price');


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

        $("[id^=service_child_]").each(function () {
            var id = $(this).attr('id').split('service_child_')[1];
            var price = $(this).attr('data-price');

            price = parseInt(price);

            if ($(this).is(":checked")) {
                var diff = ($("#end_date_child_"+id).datepicker("getDate") -
                    $("#start_date_child_"+id).datepicker("getDate")) /
                    1000 / 60 / 60 / 24; // days
                // Round down.
                var diff = Math.floor(diff);
                if (diff > 0) {
                    service_price += (price * diff);
                }
            }
        });


        $("[id^=service_driver_]").each(function () {
            var id = $(this).attr('id').split('service_driver_')[1];
            var price = $(this).attr('data-price');
            price = parseInt(price);
            if ($(this).is(":checked")) {
                var diff = ($("#end_date_driver_"+id).datepicker("getDate") -
                    $("#start_date_driver_"+id).datepicker("getDate")) /
                    1000 / 60 / 60 / 24; // days
                var diff = Math.floor(diff);

                if (diff > 0) {
                    service_price += (price * diff);
                }
            }
        });



        var nights = getNights();

        var final_price = r_price * nights;


        if (isNaN(final_price))
            final_price = 0;

        var total_price = final_price;
        var now_price = $("#total_price").html(); 

        if (!isNaN(service_price)) {
            total_price = eval(final_price) + eval(service_price);
        }

        $("#total_price").html(total_price);
        $("#total_price_val").val(total_price);
    }

    //add child
    $("[id^=add_child_]").click(function (e) {
        e.preventDefault();
        var x = $("#viewed_child_count").val();
        $("#clone_child").clone().prop('id', 'row_child_div_' + x).appendTo("#child_div").show();
        $("#row_child_div_"+x).find('[id^=remove_child_]').prop('id', 'remove_child_'+x);
        $("#row_child_div_"+x).find('[id^=service_child_]').prop('id', 'service_child_'+x);
        $("#row_child_div_"+x).find('[id^=start_date_child_]').prop('id', 'start_date_child_'+x);
        $("#row_child_div_"+x).find('[id^=end_date_child_]').prop('id', 'end_date_child_'+x);
        $("#row_child_div_"+x).find('[id^=start_date_child_]').removeClass('hasDatepicker');
        $("#row_child_div_"+x).find('[id^=end_date_child_]').removeClass('hasDatepicker');
        $("#row_child_div_"+x).find('[id^=start_date_child_]').datepicker({dateFormat: "dd-mm-yy", autoclose: true/*, minDate: 0*/});
        $("#row_child_div_"+x).find('[id^=end_date_child_]').datepicker({dateFormat: "dd-mm-yy", autoclose: true/*, minDate: 0*/});
        x++;
        $('#viewed_child_count').val(x);
    })

    $(document).delegate("[id^=remove_child_]", 'click', function (e) {
        var id = $(this).attr('id').split('remove_child_')[1];
        $("#row_child_div_"+id).remove();
        getPrice();
    })


    //add driver
    $("[id^=add_driver_]").click(function (e) {
        e.preventDefault();
        var x = $("#viewed_driver_count").val();
        $("#clone_driver").clone().prop('id', 'row_driver_div_' + x).appendTo("#driver_div").show();
        $("#row_driver_div_"+x).find('[id^=remove_driver_]').prop('id', 'remove_driver_'+x);
        $("#row_driver_div_"+x).find('[id^=driver_price_]').prop('id', 'driver_price_'+x);
        $("#row_driver_div_"+x).find('[id^=service_driver_]').prop('id', 'service_driver_'+x);
        $("#row_driver_div_"+x).find('[id^=driver_date_inside_]').prop('id', 'driver_date_inside_'+x);

        $("#row_driver_div_"+x).find('[id^=driver_date_outside_]').prop('id', 'driver_date_outside_'+x);
        $("#row_driver_div_"+x).find('[id^=driver_date_inside_]').attr('name','driver_stay['+x+'][]');
        $("#row_driver_div_"+x).find('[id^=driver_date_outside_]').attr('name','driver_stay['+x+'][]');
        $("#row_driver_div_"+x).find('[id^=driver_date_inside_'+x+']').prop('checked', true);
        $("#row_driver_div_"+x).find('[id^=driver_dates_]').prop('id', 'driver_dates_'+x);
        $("#row_driver_div_"+x).find('[id^=start_date_driver_]').prop('id', 'start_date_driver_'+x);
        $("#row_driver_div_"+x).find('[id^=end_date_driver_]').prop('id', 'end_date_driver_'+x);
        $("#row_driver_div_"+x).find('[id^=start_date_driver_]').removeClass('hasDatepicker');
        $("#row_driver_div_"+x).find('[id^=end_date_driver_]').removeClass('hasDatepicker');
        $("#row_driver_div_"+x).find('[id^=start_date_driver_]').datepicker({dateFormat: "dd-mm-yy", autoclose: true/*, minDate: 0*/});
        $("#row_driver_div_"+x).find('[id^=end_date_driver_]').datepicker({dateFormat: "dd-mm-yy", autoclose: true/*, minDate: 0*/});
        x++;
        $('#viewed_driver_count').val(x);
    })

    //remove driver
    $(document).delegate("[id^=remove_driver_]", 'click', function () {
        var id = $(this).attr('id').split('remove_driver_')[1];
        $("#row_driver_div_"+id).remove();
        getPrice();
    })




</script>