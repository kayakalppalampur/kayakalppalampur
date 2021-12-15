@if(!isset($print))
<div class="column table_top_btn">
    <div class="btn-group pull-right">
        <div class="item no-disable">
            <a style="color:white" href="{{ url("admin/attendance/create") }}">
                <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button">
                    <i class="plus icon"></i><span class="text responsive-text">Mark  Attendance</span>
                </div>
            </a>
            <a style="color:white" href="{{ url("admin/attendances/print/".$range.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">
                <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                            class="print icon"></i><span class="text responsive-text">Print</span></div>
            </a>
            <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                <div class="menu">
                    <a id="clicked" class="item no-disable"
                       href="{{ url('/admin/attendance/export/'.\App\Settings::EXPORT_CSV.'?date='.$range.'&per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                        as CSV
                    </a>
                    <a id="clicked" class="item no-disable"
                       href="{{ url('/admin/attendance/export/'.\App\Settings::EXPORT_PDF.'?date='.$range.'&per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                        as PDF
                    </a>
                    <a id="clicked" class="item no-disable"
                       href="{{ url('/admin/attendance/export/'.\App\Settings::EXPORT_EXCEL.'?date='.$range.'&per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                        as Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@if(count($users) > 0 )
    @if(isset($count))
        <div class="pagination_con paggination_top" role="toolbar">
            <div class="pull-right">
                {!!  \App\Settings::perPageOptions($count)  !!}
            </div>
        </div>
    @endif
    <div class="table_outer">
    <table class="ui table_cus_v table last_row_bdr"
           data-action="{{ url('admin/attendance') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
        <thead>
        <tr>
            @if(!isset($print))
                <th>Add Leave</th>
            @endif
            <th>Name</th>
            @foreach($dates as $date)
                <th>{{ date('d-m-Y',strtotime($date)) }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @if(!isset($print))
        <tr class="table_search">
            <td>&nbsp;</td>
            <td class="icons">
                <input type="text" class="table_search" id="table_search_name" value="{{ @$search_data['name'] }}"
                       name="name"
                       placeholder="search title"/> <i
                        class="fa fa-filter"></i>
            </td>
            <input type="hidden" name="dates" id="table_search_dates" value="{{ $range }}" class="table_search">
            @foreach($dates as $date)

                <td class="icons">
                    <select class="table_search" id="table_search_status_{{ $date }}" name="status_{{ $date }}"
                            value="{{ @$search_data['status_'.$date] }}">
                        <option value="">Search Status</option>
                        @foreach(\App\Attendance::getStatusOptions() as $key => $status)
                            <option value="{{ $key }}" {{ @$search_data['status_'.$date] == $key ? "selected" : "" }}>{{ $status }}</option>
                        @endforeach
                    </select> <i
                            class="fa fa-filter"></i>
                </td>
            @endforeach
        </tr>
        @endif
        @foreach($users as $row)
            <tr>
                @if(!isset($print))
                    <td>
                        {!! link_to_route('Laralum::attendance.add_leave', 'Add Leave', ['user_id' => $row->id], ['class' => 'ui button']) !!}
                        {{--<div class="ui {{ Laralum::settings()->button_color }} top icon left pointing dropdown button">
                            <i class="configure icon"></i>
                            <div class="menu">
                                <div class="header">{{ trans('laralum.editing_options') }}</div>

                            </div>
                        </div>--}}
                    </td>
                @endif
                <td>{{ $row->name }}</td>
                @foreach($dates as $date)
                    <td>{!! $row->attendance($date, @$search_data['status_'.$date])  !!} </td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
    @if(method_exists($users, "links"))
        <div class="pagination_con main_paggination" role="toolbar">
            {{-- $users->setPath(\Request::fullUrl())->render() --}}
            {{ $users->links() }}
        </div>
    @endif
@else
    <div class="table_outer">
    <table class="ui table_cus_v table last_row_bdr"
           data-action="{{ url('admin/attendance') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
        <thead>
        <tr>
            <th>Name</th>
            @foreach($dates as $date)
                <th>{{ $date }}</th>
            @endforeach
            <th>Add Leave</th>
        </tr>
        </thead>
        <tbody>
        @if(!isset($print))
        <tr class="table_search">
            <td class="icons">
                <input type="text" class="table_search" id="table_search_name" value="{{ @$search_data['name'] }}"
                       name="name"
                       placeholder="search title"/> <i
                        class="fa fa-filter"></i>
            </td>
            <input type="hidden" name="dates" id="table_search_dates" value="{{ $range }}" class="table_search">
            @foreach($dates as $date)

                <td class="icons">
                    <select class="table_search" id="table_search_status_{{ $date }}" name="status_{{ $date }}"
                            value="{{ @$search_data['status_'.$date] }}">
                        <option value="">Search Status</option>
                        @foreach(\App\Attendance::getStatusOptions() as $key => $status)
                            <option value="{{ $key }}" {{ @$search_data['status_'.$date] == $key ? "selected" : "" }}>{{ $status }}</option>
                        @endforeach
                    </select> <i
                            class="fa fa-filter"></i>
                </td>
            @endforeach
            <td>&nbsp;</td>
        </tr>
        @endif
        <tr>
            <td colspan="{{  7 }}">
                <div class="ui negative icon message">
                    <i class="frown icon"></i>
                    <div class="content">
                        <div class="header">
                            {{ trans('laralum.missing_title') }}
                        </div>
                        <p>There are currently no attendances added for the selected date</p>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
@endif
