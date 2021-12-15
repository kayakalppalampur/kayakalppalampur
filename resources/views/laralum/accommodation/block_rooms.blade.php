@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::buildings') }}">{{ trans('laralum.building_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{ trans('laralum.block_rooms') }}</div>
    </div>
@endsection
@section('title', trans('laralum.block_rooms'))
@section('icon', "edit")
@section('content')
    <div class="ui doubling stackable">
        <div class="column">
            <div class="ui very padded segment">
                <div class="ui">
                    <form method="POST">
                        {!! csrf_field() !!}
                        <div class="column2 table_top_btn">
                            <div class="btn-group pull-right">
                                <button type="submit" class="btn ui button no-disable">Save</button>
                            </div>
                        </div>
                        <input type="hidden" name="room_id" id="room_id" value="{{ $room_id }}">

                        @foreach(\App\Building::all() as $building)

                            <h2>{{ $building->name }}</h2>
                            <div class="Building-rooms table-responsive">
                                <table class="ui table table_cus_v last_row_bdr">
                                    <tr>
                                        <th>
                                            Room no
                                        </th>
                                        <th> Block Yearly</th>

                                        @foreach(\App\Settings::months() as $month)
                                            <th> {{ $month }} </th>
                                        @endforeach

                                    </tr>
                                    @foreach(\App\Room::where('building_id', $building->id)->get() as $room)
                                        <tr>
                                            <td>{{ $room->room_number }}</td>
                                            <td>
                                                <span class="checbox_ch">
                                                    <input {{ $room->isBlocked() ? "checked" : ""}} type="checkbox" id="blocked_yearly_{{ $room->id }}" name="blocked_yearly_{{ $room->id }}" value="{{ \App\BlockedRoom::BLOCK_YEAR }}">
                                                    <label></label>
                                                </span>
                                            </td>
                                            @foreach(\App\Settings::months() as $month)
                                                <td>
                                                     <span class="checbox_ch">
                                                        <input {{ $room->isBlocked($month) ? "checked" : ""}}  class="blocked_month_{{ $room->id}}" type="checkbox" name="blocked_month_{{ $room->id }}[]" id="blocked_month_{{ $room->id }}" value="{{ $month }}">
                                                         <label></label>
                                                     </span>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        @endforeach
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $("[id^=blocked_yearly_]").click(function () {
            var id = $(this).attr('id').split('blocked_yearly_')[1];
            console.log("check" + id);
            if ($(this).is(":checked")) {
                console.log("checked" + id);
                $(".blocked_month_" + id).each(function () {
                    $(this).prop("checked", true).trigger('change');
                });
                var val = $("#room_id").val();
                var val_ar = new Array;
                if (val != "")
                    var val_ar = val.split(",");
                console.log('val_ar' + typeof val_ar);

                if (val_ar.indexOf(id) == -1) {
                    val_ar.push(id);
                    console.log('val_ar' + typeof val_ar);
                }

                $("#room_id").val(val_ar);
            } else {/*
                console.log("not checked");
                console.log("ncheck" + id);*/
                $(".blocked_month_" + id).each(function () {
                    $(this).prop("checked", false).trigger('change');
                })
                /*var val = $("#room_id").val();
                console.log('val'+val);
                var val_ar = val.split(",");
                console.log('val'+val_ar);
                if (val_ar.indexOf(id) > -1) {
                    console.log('val'+val_ar);
                    val_ar = val_ar.pop(id);
                    $("#room_id").val(val_ar.join(","));
                }*/


            }
        })

        $("[id^=blocked_month_]").change(function () {
            console.log('month__changed');
            var id = $(this).attr('id').split('blocked_month_')[1];
            var val = $("#room_id").val();
            var val_ar = [];
            if (val != "")
                var val_ar = val.split(",");

            if ($(this).is(":checked")) {
                if (val_ar.indexOf(id) == -1) {
                    val_ar.push(id);
                }
            } else {
                var checked = false;
                $(".blocked_month_" + id).each(function () {
                    if ($(this).is(":checked")) {
                        checked = true;
                    }
                })
                console.log('checked' + checked);

                if (checked == false) {
                    console.log('not check'+id);
                    console.log('index'+val_ar.indexOf(id));
                    if (val_ar.indexOf(id) > -1) {
                        var key =  val_ar.indexOf(id);
                        $("#blocked_yearly_" + id).prop("checked", false);
                        val_ar[key] = "";
                        //val_ar = val_ar.pop(id);
                    }
                    console.log('val_ar'+val_ar);

                }
            }

            $("#room_id").val(val_ar);
        });
    </script>
@endsection
