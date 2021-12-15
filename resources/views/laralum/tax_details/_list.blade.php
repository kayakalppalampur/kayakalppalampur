@if(!isset($print))
    <div class="column table_top_btn">
        <div class="btn-group pull-right">
            <div class="item no-disable">
                <a style="color:white" href="{{ url("admin/tax-details/print/".'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                class="print icon"></i><span class="text responsive-text">Print</span></div>
                </a>
                <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                    <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                    <div class="menu">
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/tax-details/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as CSV
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/tax-details/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as PDF
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/tax-details/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
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
            <a style="color:white" href="{{ url("admin/tax-details/add") }}">
                <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button">
                    <i class="plus icon"></i><span class="text responsive-text">Add Tax Details</span>
                </div>
            </a>
        </div>
    </div>
</div>
@endif
@if(count($models) > 0)
    @if(isset($count))
        @if(!isset($print))
        {{csrf_field()}}
        <div class="pagination_con paggination_top" role="toolbar">
            <div class="pull-right">
                {!!  \App\Settings::perPageOptions(\App\TaxDetail::count())  !!}
            </div>
        </div>
        @endif
    @endif

    <table class="ui table table_cus_v last_row_bdr"
           data-action="{{ url('admin/tax-details') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
        <thead>
        <tr>
            <th>Tax Type</th>
            <th>Tax Percentage</th>
            @if(!isset($print))
                <th>Actions</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @if(!isset($print))
        <tr class="table_search">
            <td class="icons">
                <input type="text" class="table_search" id="table_search_tax_type"
                       value="{{ @$search_data['tax_type'] }}"
                       name="tax_type"
                       placeholder="search Tax type"/> <i
                        class="fa fa-filter"></i>
            </td>
            <td class="icons">
                <input type="text" class="table_search" id="table_search_tax_amount"
                       value="{{ @$search_data['tax_amount'] }}"
                       name="tax_amount"
                       placeholder="search Tax Amount"/> <i
                        class="fa fa-filter"></i>
            </td>
            <td>
                &nbsp;
            </td>
        </tr>
        @endif
        @foreach($models as $model)
            <tr>
                <td>{{ $model->tax_type }}</td>
                <td>{{ $model->tax_amount}}</td>
                @if(!isset($print))
                    <td>
                        <div id="book-table"  class="ui  top icon blue left pointing dropdown button">
                            <i class="configure icon"></i>
                            <div class="menu">
                                @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.tax_details'))
                                    <div class="header">{{ trans('laralum.editing_options') }}</div>
                                    <a href="{{ route('Laralum::admin.tax_details.edit', ['id' => $model->id]) }}"
                                       class="item no-disable">
                                        <i class="edit icon"></i>
                                        Edit
                                    </a>
                                @endif
                                @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.tax_details'))
                                    <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                    <a href="{{ route('Laralum::tax_details.delete', ['id' => $model->id]) }}"
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
            </tr>
        @endforeach
        </tbody>
    </table>
    @if(!isset($print))
    @if(method_exists($models, "links"))
        <div class="pagination_con main_paggination" role="toolbar">
            {{ $models->links() }}
        </div>
    @endif
        @endif
@else
    <table class="ui table table_cus_v last_row_bdr">
        <thead>
        <tr>
            <th>Tax Type</th>
            <th>Tax Percentage</th>
            @if(!isset($print))
                <th>Actions</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @if(!isset($print))
        <tr class="table_search">
            <td class="icons">
                <input type="text" class="table_search" id="table_search_tax_type"
                       value="{{ @$search_data['tax_type'] }}"
                       name="tax_type"
                       placeholder="search Tax type"/> <i
                        class="fa fa-filter"></i>
            </td>
            <td class="icons">
                <input type="text" class="table_search" id="table_search_tax_amount"
                       value="{{ @$search_data['tax_amount'] }}"
                       name="tax_amount"
                       placeholder="search Tax Amount"/> <i
                        class="fa fa-filter"></i>
            </td>
            <td>
                &nbsp;
            </td>
        </tr>
        @endif
        <tr>
            <td colspan="3">
                <div class="ui negative icon message">
                    <i class="frown icon"></i>
                    <div class="content">
                        <div class="header">
                            {{ $search == true ? $error : "No tax type found" }}
                        </div>
                        <p>There are currently no tax types</p>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
@endif