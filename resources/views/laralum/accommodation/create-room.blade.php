@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::rooms') }}">{{ trans('laralum.rooms') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.room_add') }}</div>
    </div>
@endsection
@section('title', trans('laralum.room_add'))
@section('icon', "plus")
@section('content')
    <div class="ui one column doubling stackable">
        <div class="column">
            <div class="ui very padded segment">
                <div class="ui stackable">
                    <div class="about_sec hospital_info addroom_con column">
                        {!! Form::open(['route' => 'Laralum::room.store','class' => 'form']) !!}
                        {{ csrf_field() }}
                       {{-- <div class="col-md-2">
                            {!! Form::label('text', 'Block for Admin use') !!} {!! Form::checkbox('is_blocked', old('is_blocked')) !!}
                        </div>--}}
                            <div class="add_room_main_inner">
                                <div class="add_room_row">
                                    <div class="block3">
                                        {!! Form::label('text', 'Select Building') !!}
                                        {!! Form::select('select_building', \App\Building::getBuildingOptions(),old('select_building'),['placeholder' => 'Select Building','id' =>  'building' , 'class' => 'form-control']) !!}
                                    </div>
                                    <div class="block3">
                                        {!! Form::label('text', 'Select Floor') !!}
                                        {!! Form::select('select_floor_number',[],old('select_floor_number'),['required'=>'required','placeholder' => 'Select Floor Number' , 'class' => 'form-control select_floor_number' ]) !!}
                                    </div>
                                    <div class="block3">
                                        {!! Form::label('text', 'Room Type') !!}
                                        {!! Form::select('room_type',[],old('room_type'),['placeholder' => 'Select Room Type', 'id' => 'room_type' , 'class' => 'form-control' ]) !!}
                                    </div>
                                </div>

                                <div class="add_room_row">
                                    <div class="block3">
                                        {!! Form::label('text', 'Number of Rooms on floor') !!}
                                        {!! Form::text('rooms','',['required', 'id' => 'rooms' , 'class' => 'form-control' ]) !!}
                                    </div>
                                    <div class="block3" id="room_numbers_div">
                                        <div id="room_number_1">
                                            {!! Form::label('text', 'Room Number 1') !!}
                                            {!! Form::text('room_number_1','',['required', 'placeHolder' => '101', 'id' => 'room_number' , 'class' => 'form-control' ]) !!}
                                        </div>
                                    </div>
                                    <div class="block3">
                                        {!! Form::label('text', 'Bed Count') !!}
                                        {!! Form::text('bed_count','',['required','min' => '1', 'max' => '2', 'class' => 'form-control' ]) !!}
                                    </div>
                                </div>

                                <div class="add_room_row">
                                    <div class="block3">
                                        {!! Form::label('text', 'Room Price') !!}
                                        {!! Form::text('room_price','',['required' , 'class' => 'form-control' ]) !!}
                                    </div>
                                    <div class="block3">
                                        {!! Form::label('text', 'Per Bed Price') !!}
                                        {!! Form::text('bed_price','',['required' , 'class' => 'form-control' ]) !!}
                                    </div>
                                    <div class="block3">
                                        {!! Form::label('text', 'Gender') !!}
                                        {!! Form::select('gender',['1' => 'Male', '2' => 'Female', '3' => 'N/A'],'3',['required' , 'class' => 'form-control' ]) !!}
                                    </div>
                                </div>
                            {{--<div class="field ">
                                {!! Form::label('text', 'Bed Type') !!}
                                {!! Form::select('bed_type',[\App\Room::BED_TYPE_SINGLE => 'Single Bed',\App\Room::BED_TYPE_DOUBLE => 'Double Bed'],['required'],['placeholder' => 'Select Bed Type']) !!}
                            </div>--}}
                                <div class="field_check permission_list">
                                    {!! Form::label('text', 'Services') !!}
                                    @foreach(\App\ExternalService::all() as $service)
                                     <p class="checbox_ch">
                                         {!! Form::checkbox('services[]', $service->id) !!}
                                         <label> {{ $service->name }} </label>
                                     </p>
                                    @endforeach
                                </div>

                                <div class="form-button_row">
                                    {!! Form::submit('Submit', ['class' => 'ui blue submit button']) !!}
                                </div>
                            </div>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @for($i = 1; $i <= old('rooms'); $i ++)
    <input type="hidden" id="old_room_number_{{ $i }}" value="{{ old('room_number_'.$i) }}" />
    @endfor
@endsection

@section('js')

    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        $(window).load(function () {
            var room_type = $('select[name=room_type]').val();
            if(room_type == 5)
                $('input[name=bed_count]').prop('max',20);

            var building_id = $('select[name=select_building]').val();
            updateDropdown(building_id);

        });


        function updateDropdown(building_id)
        {
            console.log('building_id : ' + building_id);
            $.ajax({
                type: 'POST',
                url: '{{ url('get_building_floor') }}',
                data: { 'building_id' : building_id },
                success: function (data) {
                    $('.select_floor_number').html(data.floors);
                    $('#room_type').html(data.room_types);
                }
            });
        }

        $('select[name=select_building]').change(function () {
            var building_id = $(this).val();
            updateDropdown(building_id);
        });

        $('select[name=room_type]').change(function () {
            var room_type = $(this).val();
            if(room_type == 5)
                $('input[name=bed_count]').prop('max',20);
        });

        var no = $("#rooms").val();
        updateRoomnumber(no);
        $("#rooms").change(function () {
            var no = $(this).val();
            updateRoomnumber(no);
        });

        function updateRoomnumber(no) {
            if(no > 0) {

                    var count = $('div[id^="room_number_"]').length;
                    var $div = $('div[id^="room_number_"]:last');
                    var id = $div.attr('id').split('room_number_')[1];
                console.log('id' + id);
                console.log('no'+no);
                no = parseInt(no);
                id = parseInt(id);
                if (no > id) {
                    for (i = id; i < no; i++) {
                        var $div = $('div[id^="room_number_"]:last');
                        var num = parseInt($div.prop("id").match(/\d+/g), 10) + 1;
                        var $clon = $div.clone().prop('id', 'room_number_' + num);
                        $clon.find('label').text('Room Number ' + num);
                        $clon.find('input').prop('name', 'room_number_' + num);
                        var val = $("#old_room_number_" + num).val();
                        $clon.find('input').val(val);
                        var html = $("#room_numbers_div").append($clon);
                    }
                }else{

                    var removeids = id - no;

                    console.log('remove' + removeids);


                    for (i = id; i  > no; i --) {
                        var $div = $("#room_number_"+i).remove();
                    }
                }
            }
        }



    </script>

@endsection