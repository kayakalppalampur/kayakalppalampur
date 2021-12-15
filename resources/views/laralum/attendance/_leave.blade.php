@if(!isset($print))
    <div class="column table_top_btn">
        <div class="btn-group pull-right">
            <div class="item no-disable">
                <a style="color:white" href="{{ url("admin/attendance/add-leave") }}">
                    <div tabindex="0"
                         class="ui secondary top labeled icon left  button responsive-button">
                        <i class="plus icon"></i><span class="text responsive-text">Add  Leave</span>
                    </div>
                </a>
                <a style="color:white" href="{{ url("admin/attendance-leaves/print/".'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                class="print icon"></i><span class="text responsive-text">Print</span></div>
                </a>
                <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                    <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                    <div class="menu">
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/attendance-leaves/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as CSV
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/attendance-leaves/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as PDF
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/attendance-leaves/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<form id="attendance_form" class="form form_listing_table" method="POST"
      action="{{ route('Laralum::attendance.create') }}">
    {{ csrf_field() }}
    <input type="hidden" name="created_by" value="{{ Auth::id() }}">
</form>
<div class="column">
    @if(count($leaves) > 0)
    <div class="table_outer">
    <table class="ui table_cus_v table last_row_bdr">
        <thead>
        <tr>
            <th style="width:30%;">Employee</th>
            <th style="width: 21%;text-align: center">Department</th>
            <th style="width:30%;">Start Date</th>
            <th style="width:30%;">End Date</th>
            <th style="width: 21%;text-align: center">Comment<br/></th>
            @if(!isset($print))
            <th style="width:30%;">Action<br/></th>
                @endif
        </tr>
        </thead>
        <tbody>
        @if(!isset($print))
            <tr class="table_search">

                <td class="icons"><input type="text" class="table_search" id="table_search_name"
                                         value="{{ @$search_data['name'] }}"
                                         name="name"
                                         placeholder="search name"/> <i
                            class="fa fa-filter"></i></td>
                <td><input type="text" class="table_search" id="table_search_department"
                           value="{{ @$search_data['department'] }}"
                           name="slug"
                           placeholder="search department"/> <i
                            class="fa fa-filter"></i></td>
                <td class="icons">
                    <input type="text" class="table_search datepicker" id="table_search_date_start"
                           value="{{ @$search_data['date_start'] }}"
                           name="slug"
                           placeholder="search start date"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search datepicker" id="table_search_date_end"
                           value="{{ @$search_data['date_end'] }}"
                           name="slug"
                           placeholder="search end date"/> <i
                            class="fa fa-filter"></i>
                    <i class="fa fa-filter"></i>
                </td>
                <td> &nbsp;</td>
                <td> &nbsp;</td>
            </tr>
        @endif

        @foreach($leaves as $leave)
            <tr>
                <td>{{ $leave->user->name}}</td>
                <td>{{ $leave->user->staffDepartment->title }}</td>
                <td id="selected_date">{{ $leave->date_start_date }}</td>
                <td id="selected_date">{{ $leave->date_end_date }}</td>
                <td id="selected_date">{{ $leave->comment}}</td>
                @if(!isset($print))
                <td>
                    <div class="display_block">
                        <a href="{{ route('Laralum::leave_edit', ['id' => $leave->id]) }}"
                           class="button ui no-disable edit-room-patient_new">Edit</a> <a class="button ui no-disable"
                                                                                          href="{{ route('Laralum::leave_delete', ['id' => $leave->id]) }}">Delete</a>

                    </div>
                </td>
                    @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
    @if(!isset($print))
        @if(method_exists($leaves, "links"))
            <div class="pagination_con main_paggination" role="toolbar">
                {{ $leaves->links() }}
            </div>
        @endif
    @endif
        @else
        <div class="table_outer">
        <table class="ui table_cus_v table last_row_bdr">
            <thead>
            <tr>
                <th style="width:30%;">Employee</th>
                <th style="width: 21%;text-align: center">Department</th>
                <th style="width:30%;">Start Date</th>
                <th style="width:30%;">End Date</th>
                <th style="width: 21%;text-align: center">Comment<br/></th>
                <th style="width:30%;">Action<br/></th>
            </tr>
            </thead>
            <tbody>
            @if(!isset($print))
                <tr class="table_search">

                    <td class="icons"><input type="text" class="table_search" id="table_search_name"
                                             value="{{ @$search_data['name'] }}"
                                             name="name"
                                             placeholder="search name"/> <i
                                class="fa fa-filter"></i></td>
                    <td><input type="text" class="table_search" id="table_search_department"
                               value="{{ @$search_data['department'] }}"
                               name="slug"
                               placeholder="search department"/> <i
                                class="fa fa-filter"></i></td>
                    <td class="icons">
                        <input type="text" class="table_search datepicker" id="table_search_date_start"
                               value="{{ @$search_data['date_start'] }}"
                               name="slug"
                               placeholder="search start date"/> <i
                                class="fa fa-filter"></i>
                    </td>
                    <td class="icons">
                        <input type="text" class="table_search datepicker" id="table_search_date_end"
                               value="{{ @$search_data['date_end'] }}"
                               name="slug"
                               placeholder="search end date"/> <i
                                class="fa fa-filter"></i>
                        <i class="fa fa-filter"></i>
                    </td>
                    <td> &nbsp;</td>
                    <td> &nbsp;</td>
                </tr>
    @endif
            <tr>
                <td colspan={{6 }}>
                    <div class="ui negative icon message">
                        <i class="frown icon"></i>
                        <div class="content">
                            <div class="header">
                                {{ @$search == true ? $error : trans('laralum.missing_title') }}
                            </div>
                            <p>There are currently no leaves</p>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    @endif
</div>
<br>


<script>
    $(".datepicker").datepicker({dateFormat: "dd-mm-yy", autoclose: true});
</script>