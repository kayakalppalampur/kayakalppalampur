@if(!isset($print))
    <div class="column table_top_btn">
        <div class="btn-group pull-right">
            <div class="item no-disable">
                <a style="color:white" href="{{ url("admin/booking/generate-token") }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button">
                        <i class="plus icon"></i><span class="text responsive-text">Generate Token</span>
                    </div>
                </a>
                <a style="color:white"
                   href="{{ url("admin/token-list-all/print") }}?s={{@json_encode($search_data)}}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                class="print icon"></i><span class="text responsive-text">Print</span></div>
                </a>

                <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                    <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                    <div class="menu">
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/token-list-all/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}&s={{@json_encode($search_data)}}">Export
                            as CSV
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/token-list-all/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}&s={{@json_encode($search_data)}}">Export
                            as PDF
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/token-list-all/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}&s={{@json_encode($search_data)}}">Export
                            as Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif


@if(count($models) > 0)
    <div class="pagination_con paggination_top" role="toolbar">
        <div class="pull-right">
            {!!  \App\Settings::perPageOptions($count)  !!}
        </div>
    </div>
    {{csrf_field()}}
    <div class="table-responsive table_sec_row patient_list">
        <table class="ui table table_cus_v last_row_bdr custom_table"
               data-action="{{ url('admin/patient-tokens/ajax') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
            <thead>
            <tr>
                @if(!isset($print))
                <th>Actions</th>
                @endif
                <th>Token Number</th>
                <th>Name of the Patient</th>
                <th>Registration Id</th>
                <th>UHID</th>
                <th>Department</th>
                <th>Doctor</th>
                <th>Date</th>
                {{--<th>Expiry Date</th>--}}
            </tr>
            </thead>
            <tbody>
            @if(!isset($print))
            <tr class="table_search">
                <td></td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_token_no"
                           value="{{ @$search_data['token_no'] }}"
                           name="token_no"
                           placeholder="search token no"/> <i
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
                    <input type="text" class="table_search" id="table_search_kid"
                           value="{{ @$search_data['kid'] }}"
                           name="kid"
                           placeholder="search patient id"/> <i
                            class="fa fa-filter"></i>
                </td>

                <td class="icons">
                    <input type="text" class="table_search" id="table_search_uhid"
                           value="{{ @$search_data['uhid'] }}"
                           name="uhid"
                           placeholder="search uh id"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <select class="table_search" id="table_search_department_id" name="department_id"
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
                    <input type="text" class="table_search" id="table_search_doctor_name"
                           value="{{ @$search_data['doctor_name'] }}"
                           name="doctor_name"
                           placeholder="search doctor name"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td>
                    <input type="text" class="table_search filter_datepicker" id="table_search_start_date"
                                  value="{{ @$search_data['start_date'] }}"
                                  name="start_date"
                                  placeholder="search date"/> <i
                            class="fa fa-filter"></i>
                </td>

            </tr>
            @endif
            @foreach($models as $row)
                <tr>
                    @if(!isset($print))
                    <td>
                        <div id="book-table" class="ui  top icon blue left pointing dropdown button">
                            <i class="configure icon"></i>
                            <div class="menu">
                                @if(Laralum::loggedInUser()->hasPermission('admin.bookings.tokens.list'))
                                    <div class="header">{{ trans('laralum.editing_options') }}</div>
                                    <a href="{{ url('/admin/token-list/'.$row->id.'/print') }}"
                                       class="item no-disable">
                                        <i class="print icon"></i>
                                        Print Token
                                    </a>
                                @endif
                                @if(Laralum::loggedInUser()->hasPermission('admin.bookings.tokens.list'))
                                    <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                    <a href="{{ route('Laralum::bookings.delete_token', ['id' => $row->id]) }}"
                                       class="item no-disable">
                                        <i class="trash bin icon"></i>
                                        Delete
                                    </a>
                                @endif
                            </div>
                        </div>
                        {{--  @else
                              <div class="ui disabled blue icon button">
                                  <i class="lock icon"></i>
                              </div>
                          @endif--}}
                    </td>
                    @endif
                    <td>{{ $row->token_no }}</td>
                    <td>{{ $row->booking->getProfile('first_name').' '.$row->booking->getProfile('last_name') }}</td>
                    <td>{{ $row->booking->getProfile('kid') }}</td>
                     <td>{{ $row->booking->getProfile('uhid') }}</td>
                    <td>{{ $row->department->title }}</td>
                    <td>{{ $row->doctor->name }}</td>
                    <td>{{ date("d-m-Y", strtotime($row->start_date)) }}</td>
                    {{--<td>{{ $row->end_date }}</td>--}}
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @if(method_exists($models, "links"))
        <div class="pagination_con main_paggination" role="toolbar">
            {{ $models->links() }}
        </div>
    @endif
@else
    <div class="table-responsive table_sec_row patient_list">
        <table class="ui table table_cus_v last_row_bdr custom_table"
               data-action="{{ url('admin/patient-tokens/ajax') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
            <thead>
            <tr>
                <th>Token Number</th>
                <th>Name of the Patient</th>
                <th>Registration Id</th>
                <th>UHID</th>
                <th>Department</th>
                <th>Doctor</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            @if(!isset($print))
            <tr class="table_search">
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_token_no"
                           value="{{ @$search_data['token_no'] }}"
                           name="token_no"
                           placeholder="search token no"/> <i
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
                    <input type="text" class="table_search" id="table_search_kid"
                           value="{{ @$search_data['kid'] }}"
                           name="kid"
                           placeholder="search patient id"/> <i
                            class="fa fa-filter"></i>
                </td>

                <td class="icons">
                    <input type="text" class="table_search" id="table_search_uhid"
                           value="{{ @$search_data['uhid'] }}"
                           name="uhid"
                           placeholder="search uh id"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <select class="table_search" id="table_search_department_id" name="department_id"
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
                    <input type="text" class="table_search" id="table_search_doctor_name"
                           value="{{ @$search_data['doctor_name'] }}"
                           name="doctor_name"
                           placeholder="search doctor name"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td>
                    <input type="text" class="table_search filter_datepicker" id="table_search_start_date"
                           value="{{ @$search_data['start_date'] }}"
                           name="start_date"
                           placeholder="search date"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td></td>
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

<script>
    $(".filter_datepicker").datepicker({dateFormat: "dd-mm-yy", autoclose: true});
</script>