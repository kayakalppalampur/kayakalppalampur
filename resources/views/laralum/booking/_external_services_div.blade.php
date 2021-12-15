{{--<select name="external_services[]" multiple class="form-control external_services"></select>--}}
@if(count($ext_services) > 0)
 {!! Form::label('external_services', 'Extra Services') !!}
 @endif
<div class="form_sec_rht multi_select" id="ext_services">
@foreach($ext_services as $ext_service)
<?php //print_r($booking_room->servicesCheck($ext_service->id,$booked_rooms[0]->id)); die;?>
    <div class="row">

        <div class="col-md-4">
            <input data-price="{{ $ext_service->price }}" type="checkbox"
                   name="external_services[{{ $loop->iteration }}]"
                   value="{{ $ext_service->id }}"
                   id="user_external_service_{{ $ext_service->id }}" @if(count($booked_rooms) != 0) {{ $booking_room->servicesCheck($ext_service->id,$booked_rooms[0]->id) ? 'checked' : '' }} @else 
                   {{ $booking_room->servicesCheck($ext_service->id) ? 'checked' : '' }} @endif> {{ $ext_service->name }}
            - {{ $ext_service->price }}/night
        </div>
        <div class="col-md-4">
            <input type="text" class="datepicker form-control service_check_in_date"
                   id="start_date_{{ $ext_service->id }}"
                   data-parent-class="{{ $booking_room->id != null ? 'edit_booking_form' : 'new_form'}}"
                   name="start_date[{{ $loop->iteration }}]" placeholder="Start Date"
                   @if(count($booked_rooms) != 0)
                   value="{{ $booking_room->servicesCheck($ext_service->id,$booked_rooms[0]->id,'service_start_date') }}" @else value="{{ $booking_room->servicesCheck($ext_service->id,null,'service_start_date') }}" @endif >
        </div>
        <div class="col-md-4">
            <input type="text" class="datepicker form-control service_check_out_date"
                   id="end_date_{{ $ext_service->id }}"
                   name="end_date[{{ $loop->iteration }}]"
                   data-parent-class="{{ $booking_room->id != null ? 'edit_booking_form' : 'new_form'}}"
                   placeholder="End Date" @if(count($booked_rooms) != 0)
                   value="{{ $booking_room->servicesCheck($ext_service->id,$booked_rooms[0]->id,'service_end_date') }}" @else value="{{ $booking_room->servicesCheck($ext_service->id,null,'service_end_date') }}" @endif  >
        </div>
    </div>
    <div class="clearfix"></div>
    <br>
@endforeach
</div>

<script>
    $(".datepicker").datepicker({dateFormat: "dd-mm-yy", autoclose: true/*, minDate: "+0d"*/});

    $(document).delegate(".datepicker", "change", function () {
        var parent_class = $(this).attr("data-parent-class");



        var checkin = $("." + parent_class + ' #check_in_date').datepicker('getDate');
        $("." + parent_class + ' #check_out_date').datepicker({dateFormat: "dd-mm-yy", autoclose: true/*, minDate: 0*/}/*'option', 'minDate', checkin*/);

        $("." + parent_class + " .service_check_in_date").each(function () {
            var id = $(this).attr('id').split('start_date_')[1];
            var checkin = $("." + parent_class + ' #check_in_date').datepicker('getDate');
            var checkout = $("." + parent_class + ' #check_out_date').datepicker('getDate');

            var this_val = $("." + parent_class + ' #start_date_' + id).val();
            var this_end_date_val = $("." + parent_class + ' #end_date_' + id).val();

            $(this).datepicker(/*'option', 'minDate', checkin*/);

            $("." + parent_class + " #end_date_" + id).datepicker({dateFormat: "dd-mm-yy", autoclose: true/*, minDate: 0*/}/*'option', 'minDate', checkin*/);
            $(this).datepicker('option', 'maxDate', checkout);
            $("." + parent_class + " #end_date_" + id).datepicker({dateFormat: "dd-mm-yy", autoclose: true/*, minDate: 0*/}/*'option', 'maxDate', checkout*/);

            var this_val = $(this).datepicker('getDate');

            if (this_val != "") {
                $("." + parent_class + " #end_date_" + id).datepicker({dateFormat: "dd-mm-yy", autoclose: true/*, minDate: 0*/}/*'option', 'minDate', this_val*/);
            }
        })
    });

    $(document).delegate("[id^=user_external_service_]", 'change', function () {
        getPrice();
    })
</script>