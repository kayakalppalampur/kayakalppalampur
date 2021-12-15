@if(!isset($print))
    <div class="column table_top_btn">
        <div class="btn-group pull-right">
            <div class="item no-disable">
                <a style="color:white" href="{{ url("admin/discount_offer/create") }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button">
                        <i class="plus icon"></i><span class="text responsive-text">Add Discount Offer</span>
                    </div>
                </a>
                <a style="color:white"
                   href="{{ url("admin/discount_offers/print/".'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                class="print icon"></i><span class="text responsive-text">Print</span></div>
                </a>
                <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                    <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                    <div class="menu">
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/discount_offers/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as CSV
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/discount_offers/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as PDF
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/discount_offers/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif


@if(count($discount_offers) > 0)
    {{csrf_field()}}
    @if(!isset($print))
        <div class="pagination_con paggination_top" role="toolbar">
            <div class="pull-right">
                {!!  \App\Settings::perPageOptions(\App\DiscountOffer::count())  !!}
            </div>
        </div>
    @endif

    <table class="ui table table_cus_v last_row_bdr" data-action="{{ url('admin/discount_offers') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
        <thead>
        <tr>
            <th>Code</th>
            <th>Type</th>
            <th>Discount Value</th>
            @if(!isset($print))
                <th>Actions</th>
            @endif
        </tr>
        </thead>

        <tbody>
        @if(!isset($print))
            <tr class="table_search">
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_code"
                           value="{{ @$search_data['code'] }}"
                           name="code"
                           placeholder="search code"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td>
                    &nbsp;
                </td>
                <td>
                    &nbsp;
                </td>
                <td>
                    &nbsp;
                </td>
            </tr>
        @endif

        @foreach($discount_offers as $discount_offer)
            <tr>
                <td>{{ $discount_offer->code }}</td>
                <td>{{ $discount_offer->getTypeOptions($discount_offer->type) }}</td>
                <td>{{ $discount_offer->discount_value }}</td>
                @if(!isset($print))
                    <td>
                        <div id="book-table" class="ui  top icon blue left pointing dropdown button">
                            <i class="configure icon"></i>
                            <div class="menu">
                                @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.discount_offers'))
                                    <div class="header">{{ trans('laralum.editing_options') }}</div>
                                    <a href="{{ route('Laralum::discount_offer_edit', ['id' => $discount_offer->id]) }}"
                                       class="item no-disable">
                                        <i class="edit icon"></i>
                                        {{ trans('laralum.edit_discount_offer') }}
                                    </a>
                                @endif
                                @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.discount_offers'))
                                    <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                    <a href="{{ route('Laralum::discount_offer_delete', ['id' => $discount_offer->id]) }}"
                                       class="item no-disable">
                                        <i class="trash bin icon"></i>
                                        {{ trans('laralum.delete_discount_offer') }}
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
        @if(method_exists($discount_offers, "links"))
            <div class="pagination_con main_paggination" role="toolbar">
                {{ $discount_offers->links() }}
            </div>
        @endif
    @endif
@else
    <table class="ui table table_cus_v last_row_bdr" data-action="{{ url('admin/discount_offers') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
        <thead>
        <tr>
            <th>Code</th>
            <th>Type</th>
            <th>Discount Value</th>
            @if(!isset($print))
            <th>Actions</th>
                @endif
        </tr>
        </thead>
        <tbody>
        @if(!isset($print))
        @if(!isset($print))
            <tr class="table_search">
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_code"
                           value="{{ @$search_data['code'] }}"
                           name="code"
                           placeholder="search code"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td>
                    &nbsp;
                </td>
                <td>
                    &nbsp;
                </td>
                <td>
                    &nbsp;
                </td>
            </tr>
        @endif
@endif
        <tr>
            <td colspan=" @if(!isset($print)) 4 @else 3 @endif">
                <div class="ui negative icon message">
                    <i class="frown icon"></i>
                    <div class="content">
                        <div class="header">
                            {{ trans('laralum.missing_title') }}
                        </div>
                        <p>There are currently no discount_offers added for the selected date</p>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
@endif