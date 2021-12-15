@if(!isset($print))
    <div class="column table_top_btn">
        <div class="btn-group pull-right">
            <div class="item no-disable">
                <a style="color:white" href="{{ url("admin/accommodation/room_type/create") }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i class="plus icon"></i><span class="text responsive-text">Add  Room Type</span>
                    </div>
                </a>
                <a style="color:white"
                   href="{{ url("admin/accommodation/room_type/print/".'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                class="print icon"></i><span class="text responsive-text">Print</span></div>
                </a>
                <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                    <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                    <div class="menu">
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/accommodation/room_type/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as CSV
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/accommodation/room_type/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as PDF
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/accommodation/room_type/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if(count($room_types) > 0)
    <div class="pagination_con paggination_top" role="toolbar">
        @if(!isset($print))
            <div class="pull-right">
                {!!  \App\Settings::perPageOptions($count)  !!}
            </div>
        @endif
    </div>
    <table class="ui table table_cus_v last_row_bdr" data-action="{{ url('admin/accommodation/room_type') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
        <thead>
        <tr>
            <th>{{ trans('laralum.room_type') }}</th>
            {{--<th>{{ trans('laralum.room_type_price') }}</th>--}}
            <th>{{ trans('laralum.room_type_short_name') }}</th>
            @if(!isset($print))
                <th>{{ trans('laralum.operations') }}</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @if(!isset($print))
            <tr class="table_search">

                <td class="icons" style="vertical-align:text-bottom;"><input type="text" class="table_search" id="table_search_room_type_name" value="{{ @$search_data['room_type_name'] }}"
                                         name="room_type_name"
                                         placeholder="search room type name"/> <i
                            class="fa fa-filter"></i></td>
                <td><input type="text" class="table_search" id="table_search_room_type_short_name" value="{{ @$search_data['room_type_short_name'] }}"
                           name="room_type_short_name"
                           placeholder="search room type short name"/> <i
                            class="fa fa-filter"></i>&nbsp;</td>

                <td> &nbsp;</td>

            </tr>
        @endif
        @foreach($room_types as $room_type)
            <tr>
                <td>
                    <div class="text">
                        {{ $room_type->name }}
                    </div>
                </td>
                <td>
                    <div class="text">
                        {{ $room_type->short_name }}
                    </div>
                </td>
                @if(!isset($print))
                    <td>
                        <div id="book-table" class="ui  top icon blue left pointing dropdown button">
                            <i class="configure icon"></i>
                            <div class="menu">
                                @if(Laralum::loggedInUser()->hasPermission('admin.room_types'))
                                    <div class="header">{{ trans('laralum.room_type_edit') }}</div>
                                    <a href="{{ route('Laralum::room_type.edit', ['id' => $room_type['id']]) }}"
                                       class="item no-disable">
                                        <i class="edit icon"></i>
                                        {{ trans('laralum.room_type_edit') }}
                                    </a>

                                    <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                    <a href="{{ route('Laralum::room_type.delete',$room_type['id']) }}"
                                       class="item no-disable">
                                        <i class="trash bin icon"></i>Delete
                                    </a>
                                @endif
                            </div>
                        </div>
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
    @if(!isset($print))
        @if(method_exists($room_types, "links"))
            <div class="pagination_con main_paggination" role="toolbar">
                {{ $room_types->links() }}
            </div>
        @endif
    @endif
@else
    <table class="ui table table_cus_v last_row_bdr" data-action="{{ url('admin/accommodation/room_type') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
        <thead>
        <tr>
            <th>{{ trans('laralum.room_type') }}</th>
            {{--<th>{{ trans('laralum.room_type_price') }}</th>--}}
            <th>{{ trans('laralum.room_type_short_name') }}</th>
            @if(!isset($print))
                <th>{{ trans('laralum.operations') }}</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @if(!isset($print))
            <tr class="table_search">

                <td class="icons"><input type="text" class="table_search" id="table_search_room_type_name" value="{{ @$search_data['room_type_name'] }}"
                                         name="room_type_name"
                                         placeholder="search room type name"/> <i
                            class="fa fa-filter"></i></td>
                <td><input type="text" class="table_search" id="table_search_room_type_short_name" value="{{ @$search_data['room_type_short_name'] }}"
                           name="room_type_short_name"
                           placeholder="search room type short name"/> <i
                            class="fa fa-filter"></i>&nbsp;</td>

                <td> &nbsp;</td>

            </tr>
        @endif
        <tr>
            <td colspan="5">
                <div class="ui negative icon message">
                    <i class="frown icon"></i>
                    <div class="content">
                        <div class="header">
                            {{ trans('laralum.missing_title') }}
                        </div>
                        <p>{{ isset($error) ? $error : "No room type found."}}</p>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
@endif