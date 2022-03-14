@if(!isset($print))
    <div class="column table_top_btn">
        <div class="btn-group pull-right">
            <div class="item no-disable">
                <a style="color:white" href="{{ url("admin/booking/generate-opd-token") }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button opd_btn">
                        <i class="plus icon"></i><span class="text responsive-text">Generate OPD Consulation Slip</span>
                    </div>
                </a>
                        <a style="color:white"
                           href="{{ url("admin/opd-token-list/print") }}?per_page={{@$_REQUEST['per_page']}}&page={{@$_REQUEST['page']}}&s={{@json_encode($search_data)}}">
                            <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                        class="print icon"></i><span class="text responsive-text">Print</span></div>
                        </a>

                    <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                        <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                        <div class="menu">
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/opd-token-list/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}&s={{@json_encode($search_data)}}">Export
                                as CSV
                            </a>
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/opd-token-list/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}&s={{@json_encode($search_data)}}">Export
                                as PDF
                            </a>
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/opd-token-list/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}&s={{@json_encode($search_data)}}">Export
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
@if(count($models) > 0)
    @if(!isset($print))
    <div class="pagination_con paggination_top" role="toolbar">
        <div class="pull-right">
            {!!  \App\Settings::perPageOptions($count)  !!}
        </div>
    </div>

    {{csrf_field()}}
    @endif
    <div class="table-responsive table_sec_row">
        <table class="ui table table_cus_v last_row_bdr"
               data-action="{{ url('admin/opd-token-list') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
            <thead>
            <tr>
                @if(!isset($print))
                <th>Actions</th>
                @endif
                <th>Name of the Patient</th>
                <th>Registration Id</th>
                <th>Department</th>
               <!--  <th>Charges</th> -->
                <th>Doctor</th>
                <th>Complaints</th>
            </tr>
            </thead>
            <tbody>
            @if(!isset($print))
            <tr class="table_search">
                <td></td>
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
                <td> &nbsp;
                </td>
                <td>                    &nbsp;
                </td>

            </tr>
            @endif
            @foreach($models as $row)
                <tr>
                    @if(!isset($print))
                    <td>
                        <div id="book-table"  class="ui  top icon blue left pointing dropdown button">
                            <i class="configure icon"></i>
                            <div class="menu">
                                @if(Laralum::loggedInUser()->hasPermission('admin.bookings.opd.tokens.list'))
                                    <div class="header">{{ trans('laralum.editing_options') }}</div>
                                    <a href="{{ route('Laralum::opd.tokens.print.token', ['id' => $row->id]) }}"
                                       class="item no-disable">
                                        <i class="print icon"></i>
                                        Print Token
                                    </a>
                                @endif

                                @if(Laralum::loggedInUser()->hasPermission('admin.bookings.opd.tokens.list'))
                                    <div class="header">{{ trans('laralum.editing_options') }}</div>
                                    <a href="{{ route('Laralum::opd.tokens.print.token-bill', ['id' => $row->id]) }}"
                                       class="item no-disable">
                                        <i class="print icon"></i>
                                        Print Bill
                                    </a>
                                @endif

                                @if($row->booking == null)
                                    <a href="{{ route('Laralum::opd.tokens.convert', ['id' => $row->id]) }}"
                                       class="item no-disable">
                                        <i class="print icon"></i>
                                        Convert to OPD
                                    </a>
                                @endif
                                @if(Laralum::loggedInUser()->hasPermission('admin.bookings.tokens.list'))
                                    <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                    <a href="{{ route('Laralum::bookings.delete_opd_token', ['id' => $row->id]) }}"
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
                    <td>{{ $row->first_name.' '.$row->last_name }}</td>
                    <td>@if($row->booking != null)
                            {{ $row->booking->getProfile('kid') }}@endif</td>
                    <td>{{ $row->department->title }}</td>
                    <!-- <td>{{ $row->charges}}</td> -->
                    <td>{{ @$row->doctor->name }}</td>
                    <td>{{ $row->complaints }}</td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @if(!isset($print))
        @if(method_exists($models, "links"))
            <div class="pagination_con main_paggination" role="toolbar">
                {{ $models->links() }}
            </div>
        @endif
    @endif
@else
    <div class="table-responsive table_sec_row">
        <table class="ui table table_cus_v last_row_bdr"
               data-action="{{ url('admin/opd-token-list/ajax') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
            <thead>
            <tr>
                <th>Name of the Patient</th>
                <th>Registratio Id</th>
                <th>Department</th>
                <th>Doctor</th>
                <th>Complaints</th>
            </tr>
            </thead>
            <tbody>
            <tr class="table_search">
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
                <td> &nbsp;
                </td>
            </tr>
            <tr>
                <td colspan="5">
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
    $(document).ready(function(){
        $('div#book-table').click(function(){
            $('.table-responsive.table_sec_row').toggleClass('padding_slide-btm');
        });

        $(document).click(function(e){
            if (e.target.id == "book-table") {
                return;
            }
            $('.table-responsive.table_sec_row').removeClass('padding_slide-btm');
        });
    });
</script>