@if(!isset($print))
    <div class="column table_top_btn">
        <div class="btn-group pull-right">
            <div class="item no-disable">
                <a style="color:white"
                   href="{{ url("admin/treatment-token-list/print") }}?s={{@json_encode($search_data)}}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                class="print icon"></i><span class="text responsive-text">Print</span></div>
                </a>

                <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                    <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                    <div class="menu">
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/treatment-token-list/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}&s={{@json_encode($search_data)}}">Export
                            as CSV
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/treatment-token-list/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}&s={{@json_encode($search_data)}}">Export
                            as PDF
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/treatment-token-list/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}&s={{@json_encode($search_data)}}">Export
                            as Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
<div class="column table_top_btn">
    <div class="btn-group pull-right">
        <div class="item no-disable">
            <!-- Print and Export Options-->
        </div>
    </div>
</div>
@if(count($tokens) > 0)
    @if(!isset($print))
        <div class="pagination_con paggination_top" role="toolbar">
            <div class="pull-right">
                {!!  \App\Settings::perPageOptions($count)  !!}
            </div>
        </div>

        {{csrf_field()}}
    @endif

    <div class="table-responsive table_sec_row">
        <table class="ui table table_cus_v"
               data-action="{{ url('admin/booking/treatment-tokens') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
            <thead>
            <tr>
                <th>Registration Id</th>
                <th>Name of the Person</th>
                <th>Department</th>
                <th>Treatments</th>
                @if(!isset($print))
                <th>Actions</th>
                    @endif
            </tr>
            </thead>
            <tbody>
            @if(!isset($print))
            <tr class="table_search">
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_kid"
                           value="{{ @$search_data['kid'] }}"
                           name="kid"
                           placeholder="search patient id"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_first_name"
                           value="{{ @$search_data['first_name'] }}"
                           name="first_name"
                           placeholder="search patient name"/> <i
                            class="fa fa-filter"></i>
                </td>

                <td class="icons">
                    <select class="table_search" id="table_search_department_id"
                            name="department_id"
                            value="{{ @$search_data['department_id'] }}">
                        <option value="">All Department</option>
                        @foreach(\App\Department::all() as $dept)
                            <option value="{{ $dept->id }}" {{ @$search_data['department_id'] == $dept->id ? "selected" : "" }}>
                                {{ $dept->title }}
                            </option>
                        @endforeach
                    </select><i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <select class="table_search" id="table_search_treatment_id" name="treatment_id"
                            value="{{ @$search_data['treatment_id'] }}">
                        <option value="">All Department</option>
                        @foreach(\App\Treatment::all() as $treatment)
                            <option value="{{ $treatment->id }}" {{ @$search_data['treatment_id'] == $treatment->id ? "selected" : "" }}>
                                {{ $treatment->title }}
                            </option>
                        @endforeach
                    </select><i
                            class="fa fa-filter"></i>
                </td>
                <td>
                    &nbsp;
                </td>
            </tr>
            @endif
            @foreach($tokens as $row)
                <tr>
                    <td>{{ $row->booking->getProfile('kid') }}</td>
                    <td>{{ $row->booking->getProfile('first_name').' '.$row->booking->getProfile('last_name') }}</td>
                    <td>{{ $row->department->title }}</td>
                    <td>
                        @foreach($row->treatments as $pat_treat)
                            <span>{{ $pat_treat->treatment->title." (".$pat_treat->treatment->getDuration().')' }}</span>
                            <br/>
                        @endforeach
                    </td>
                    @if(!isset($print))
                    <td>
                        <a title="Print" href="{{ url("admin/booking/print-treatment/".$row->id) }}"><i
                                    class="fa fa-print"></i> </a>
                    </td>
                        @endif
                </tr>
            @endforeach
            </tbody>
        </table>
        @if(method_exists($tokens, "links"))
            <div class="pagination_con" role="toolbar">
                <div class="pull-right">
                    {{ $tokens->links() }}
                </div>
            </div>
        @endif

    </div>
@else
    <div class="table-responsive table_sec_row">
        <table class="ui table table_cus_v"
               data-action="{{ url('admin/booking/treatment-tokens') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
            <thead>
            <tr>
                <th>Registration Id</th>
                <th>Name of the Person</th>
                <th>Department</th>
                <th>Treatments</th>
                @if(!isset($print))
                    <th>Actions</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @if(!isset($print))
                <tr class="table_search">
                    <td class="icons">
                        <input type="text" class="table_search" id="table_search_kid"
                               value="{{ @$search_data['kid'] }}"
                               name="kid"
                               placeholder="search patient id"/> <i
                                class="fa fa-filter"></i>
                    </td>
                    <td class="icons">
                        <input type="text" class="table_search" id="table_search_first_name"
                               value="{{ @$search_data['first_name'] }}"
                               name="first_name"
                               placeholder="search patient name"/> <i
                                class="fa fa-filter"></i>
                    </td>

                    <td class="icons">
                        <select class="table_search" id="table_search_department_id"
                                name="department_id"
                                value="{{ @$search_data['department_id'] }}">
                            <option value="">All Department</option>
                            @foreach(\App\Department::all() as $dept)
                                <option value="{{ $dept->id }}" {{ @$search_data['department_id'] == $dept->id ? "selected" : "" }}>
                                    {{ $dept->title }}
                                </option>
                            @endforeach
                        </select><i
                                class="fa fa-filter"></i>
                    </td>
                    <td class="icons">
                        <select class="table_search" id="table_search_treatment_id" name="treatment_id"
                                value="{{ @$search_data['treatment_id'] }}">
                            <option value="">All Department</option>
                            @foreach(\App\Treatment::all() as $treatment)
                                <option value="{{ $treatment->id }}" {{ @$search_data['treatment_id'] == $treatment->id ? "selected" : "" }}>
                                    {{ $treatment->title }}
                                </option>
                            @endforeach
                        </select><i
                                class="fa fa-filter"></i>
                    </td>
                    <td>
                        &nbsp;
                    </td>
                </tr>
                @endif
            <tr>
                <td colspan="6">
                    <div class="ui negative icon message">
                        <i class="frown icon"></i>
                        <div class="content">
                            <div class="header">
                                {{ isset($error) ? $error : trans('laralum.missing_title') }}
                            </div>
                            <p>There are currently no tokens</p>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
@endif

