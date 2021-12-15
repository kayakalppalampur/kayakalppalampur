@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::rooms') }}">{{ trans('laralum.rooms') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.room_edit') }}</div>
    </div>
@endsection

@section('title', trans('laralum.room_edit_title'))
@section('icon', "edit")
@section('subtitle', trans('laralum.room_edit_title_name', ['name' => $room->room_number]))
@section('content')
    <div class="ui one column doubling stackable">
        <div class="column">
            <div class="ui very padded segment">
                <div class="ui stackable grid">
                    <div class="row">
                        <div class="column permission_list">
                            @if(!empty($room))
                                {!! Form::open(['route' => array('Laralum::room.services.store',$room->id),'class' => 'ui form']) !!}
                                {{ csrf_field() }}

                                <div class="field ">
                                    {!! Form::label('text', 'Services') !!}
                                    @foreach(\App\ExternalService::all() as $service)
                                      <p class="checbox_ch">
                                        {!! Form::checkbox('services[]', $service->id, $room->serviceChecked($service->id)) !!}
                                        <label>{{ $service->name }}</label>
                                      </p>
                                    @endforeach
                                </div>
                                <div class="permission_con ">
                                    {!! Form::submit('Submit', ['class' => 'ui blue submit button']) !!}
                                </div>
                                {!! Form::close() !!}
                            @else
                                <div class="ui very padded segment">No data Found</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                url: '{{ url('admin/accommodation/room/get_building_floor') }}',
                data: { 'building_id' : building_id },
                success: function (data) {
                    $('.select_floor_number').html(data.floors);
                    var floor_num = '{{ $room->floor_number }}';
                    $('.select_floor_number').val(floor_num);
                    $('#room_type').html(data.room_types);
                    var room_type_id = '{{ $room->room_type_id }}';
                    $('#room_type').val(room_type_id);
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
                console.log('remove'+removeids);
                console.log('id'+id);
                console.log('no'+no);
                if (no < id) {
                    var removeids = id - no;

                    for (i = id; i  > no; i --) {
                        var $div = $("#room_number_"+i).remove();
                    }
                }
            }
        }
    </script>

@endsection