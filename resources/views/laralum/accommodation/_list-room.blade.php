@if(!isset($print))
    <div class="column table_top_btn">
        <div class="btn-group pull-right">
            <div class="item no-disable">
                <a style="color:white"
                   href="{{ url("admin/accommodation/rooms/print/".'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                class="print icon"></i><span class="text responsive-text">Print</span></div>
                </a>
                <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                    <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                    <div class="menu">
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/accommodation/rooms/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as CSV
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/accommodation/rooms/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as PDF
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/accommodation/rooms/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if(!isset($print))
<div class="column table_top_btn">
    <div class="btn-group pull-right">
        <div class="item no-disable">
            <a style="color:white" href="{{ route('Laralum::room.create') }}">
                <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button">
                    <i class="plus icon"></i><span class="text responsive-text">Add New Rooms</span>
                </div>
            </a>
        </div>
    </div>
</div>
@endif

<div class="pagination_con paggination_top" role="toolbar">
    <div class="pull-right">
        {!!  \App\Settings::perPageOptions(\App\Department::count())  !!}
    </div>
</div>

@if($rooms->count() > 0)
    <div class="table-responsive table_sec_row">
        <table class="ui table table_cus_v last_row_bdr"
               data-action="{{ url('admin/accommodation/rooms') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
            <thead>
            <tr>
                <th>{{ trans('laralum.building_name') }}</th>
                <th>{{ trans('laralum.floor_number') }}</th>
                <th>{{ trans('laralum.room_number') }}</th>
                <th>{{ trans('laralum.room_type') }}</th>
                <th>{{ trans('laralum.gender') }}</th>
                {{--<th>{{ trans('laralum.bed_type') }}</th>--}}
                <th>{{ trans('laralum.bed_count') }}</th>
                <th>{{ trans('laralum.bed_price') }}</th>
                <th>{{ trans('laralum.room_price') }}</th>{{--
                            <th>{{ trans('laralum.blocked') }}</th>--}}
                @if(!isset($print))
                <th>{{ trans('laralum.operations') }}</th>
                    @endif
            </tr>
            </thead>
            <tbody>
            @if(!isset($print))
            <tr class="table_search">
                <td class="icons">
                    <select class="table_search" id="table_search_building_id" name="building_id"
                            value="{{ @$search_data['building_id'] }}">
                        <option value="">All Buildings</option>
                        @foreach(\App\Building::all() as $building)
                            <option value="{{ $building->id }}" {{ @$search_data['building_id'] == $building->id ? "selected" : "" }}>{{ $building->name }}</option>
                        @endforeach
                    </select><i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <select class="table_search" id="table_search_floor_number" name="floor_number"
                            value="{{ @$search_data['floor_number'] }}">
                        <option value="">All Floors</option>
                        @foreach(\App\Building::getFloorOptions() as $key => $floor)
                            <option value="{{ $key }}" {{ @$search_data['floor_number'] == $key ? "selected" : "" }}>{{ $floor }}</option>
                        @endforeach
                    </select><i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_room_number"
                           value="{{ @$search_data['room_number'] }}"
                           name="room_number"
                           placeholder="search room number"/> <i
                            class="fa fa-filter"></i>
                </td>

                <td class="icons">
                    <select class="table_search" id="table_search_room_type_id" name="room_type_id"
                            value="{{ @$search_data['room_type_id'] }}">
                        <option value="">All Room Types</option>
                        @foreach(\App\Room_Type::all() as $type)
                            <option value="{{ $type->id }}" {{ @$search_data['room_type_id'] == $type->id ? "selected" : "" }}>{{ $type->name }}</option>
                        @endforeach
                    </select><i
                            class="fa fa-filter"></i>
                </td>

                <td class="icons">
                    <select class="table_search" id="table_search_gender" name="gender"
                            value="{{ @$search_data['gender'] }}">
                        <option value="">All Gender</option>
                        @foreach(\App\Room::getGenderOptions() as $gkey => $gender)
                            <option value="{{ $gkey }}" {{ @$search_data['gender'] == $gkey ? "selected" : "" }}>{{ $gender }}</option>
                        @endforeach
                    </select><i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_bed_count"
                           value="{{ @$search_data['bed_count'] }}"
                           name="bed_count"
                           placeholder="search bed count"/> <i
                            class="fa fa-filter"></i>
                </td>

                <td class="icons">
                    <input type="text" class="table_search" id="table_search_bed_price"
                           value="{{ @$search_data['bed_price'] }}"
                           name="bed_price"
                           placeholder="search bed price"/> <i
                            class="fa fa-filter"></i>
                </td>

                <td class="icons">
                    <input type="text" class="table_search" id="table_search_room_price"
                           value="{{ @$search_data['room_price'] }}"
                           name="room_price"
                           placeholder="search room price"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    &nbsp;
                </td>
            </tr>
@endif
            @foreach($rooms as $room)
                <tr>
                    <td>
                        <div class="text">
                            {{ @$room->building->name }}
                        </div>
                    </td>

                    <td>
                        <div class="text">
                            {{ @\App\Building::getFloorName($room->floor_number) }}
                        </div>
                    </td>
                    <td>
                        <div class="text">
                            {{ @$room->room_number }}
                        </div>
                    </td>
                    <td>
                        <div class="text">
                            {{ @$room->roomType->name }}
                        </div>
                    </td>
                    <td>
                        <div class="text">
                            {{ @$room->getGenderOptions($room->gender) }}
                        </div>
                    </td>
                    <td>
                        <div class="text">
                            {{ @$room->bed_count }}
                        </div>
                    </td>
                    <td>
                        <div class="text">
                            {{ @$room->bed_price }}
                        </div>
                    </td>
                    <td>
                        <div class="text">
                            {{ @$room->room_price }}
                        </div>
                    </td>
                    {{--<th>{{ $room->is_blocked ? "Yes" : "No"}}</th>--}}
                    @if(!isset($print))
                    <td>
                        <div id="book-table" class="ui  top icon blue left pointing dropdown button">
                            <i class="configure icon"></i>
                            <div class="menu">
                                @if(Laralum::loggedInUser()->hasPermission('admin.rooms'))
                                    <div class="header">{{ trans('laralum.room_edit') }}</div>
                                    <a href="{{ route('Laralum::room.edit', ['id' => $room->id]) }}"
                                       class="item no-disable">
                                        <i class="edit icon"></i>
                                        {{ trans('laralum.room_edit') }}
                                    </a>
                                    <a href="{{ route('Laralum::room.services', ['id' => $room->id]) }}"
                                       class="item no-disable">
                                        <i class="edit icon"></i>
                                        {{ trans('laralum.manage_services') }}
                                    </a>
                                    <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                    <form role="form" class="item no-disable" method="post"
                                          action="{{ URL::route('Laralum::room.delete',$room->id) }}"
                                          onSubmit="return confirm('Are you sure want to Delete?')">
                                        <input type="hidden" name="_method" value="DELETE">
                                        {{ csrf_field() }}
                                        <button type="submit" class=""><i
                                                    class="trash bin icon"></i>Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </td>
                        @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @if(!isset($print))
    @if(method_exists($rooms, "links"))
        <div class="pagination_con main_paggination" role="toolbar">
            {{ $rooms->links() }}
        </div>
    @endif
    @endif
@else
    <div class="table-responsive table_sec_row">
        <table class="ui table table_cus_v last_row_bdr">
            <thead>
            <tr>
                <th>{{ trans('laralum.building_name') }}</th>
                <th>{{ trans('laralum.floor_number') }}</th>
                <th>{{ trans('laralum.room_number') }}</th>
                <th>{{ trans('laralum.room_type') }}</th>
                <th>{{ trans('laralum.gender') }}</th>
                {{--<th>{{ trans('laralum.bed_type') }}</th>--}}
                <th>{{ trans('laralum.bed_count') }}</th>
                <th>{{ trans('laralum.bed_price') }}</th>
                <th>{{ trans('laralum.room_price') }}</th>{{--
                            <th>{{ trans('laralum.blocked') }}</th>--}}
                <th>{{ trans('laralum.operations') }}</th>
            </tr>
            </thead>
            <tbody>
            <tr class="table_search">
                <td class="icons">
                    <select class="table_search" id="table_search_building_id" name="building_id"
                            value="{{ @$search_data['building_id'] }}">
                        <option value="">All Buildings</option>
                        @foreach(\App\Building::all() as $building)
                            <option value="{{ $building->id }}" {{ @$search_data['building_id'] == $building->id ? "selected" : "" }}>{{ $building->name }}</option>
                        @endforeach
                    </select><i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <select class="table_search" id="table_search_floor_number" name="floor_number"
                            value="{{ @$search_data['floor_number'] }}">
                        <option value="">All Floors</option>
                        @foreach(\App\Building::getFloorOptions() as $key => $floor)
                            <option value="{{ $key }}" {{ @$search_data['floor_number'] == $key ? "selected" : "" }}>{{ $floor }}</option>
                        @endforeach
                    </select><i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_room_number"
                           value="{{ @$search_data['room_number'] }}"
                           name="room_number"
                           placeholder="search room number"/> <i
                            class="fa fa-filter"></i>
                </td>

                <td class="icons">
                    <select class="table_search" id="table_search_room_type_id" name="room_type_id"
                            value="{{ @$search_data['room_type_id'] }}">
                        <option value="">All Room Types</option>
                        @foreach(\App\Room_Type::all() as $type)
                            <option value="{{ $type->id }}" {{ @$search_data['room_type_id'] == $type->id ? "selected" : "" }}>{{ $type->name }}</option>
                        @endforeach
                    </select><i
                            class="fa fa-filter"></i>
                </td>

                <td class="icons">
                    <select class="table_search" id="table_search_gender" name="gender"
                            value="{{ @$search_data['gender'] }}">
                        <option value="">All Gender</option>
                        @foreach(\App\Room::getGenderOptions() as $gkey => $gender)
                            <option value="{{ $gkey }}" {{ @$search_data['gender'] == $gkey ? "selected" : "" }}>{{ $gender }}</option>
                        @endforeach
                    </select><i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_bed_count"
                           value="{{ @$search_data['bed_count'] }}"
                           name="bed_count"
                           placeholder="search bed count"/> <i
                            class="fa fa-filter"></i>
                </td>

                <td class="icons">
                    <input type="text" class="table_search" id="table_search_bed_price"
                           value="{{ @$search_data['bed_price'] }}"
                           name="bed_price"
                           placeholder="search bed price"/> <i
                            class="fa fa-filter"></i>
                </td>

                <td class="icons">
                    <input type="text" class="table_search" id="table_search_room_price"
                           value="{{ @$search_data['room_price'] }}"
                           name="room_price"
                           placeholder="search room price"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    &nbsp;
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <div class="ui negative icon message">
                        <i class="frown icon"></i>
                        <div class="content">
                            <div class="header">
                                {{ trans('laralum.missing_title') }}
                            </div>
                            <p>{{ isset($error) ? $error : "No departments found."}}</p>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
@endif