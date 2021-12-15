@if(!isset($print))
    <div class="column table_top_btn">
        <div class="btn-group pull-right">
            <div class="item no-disable">
                <a style="color:white" href="{{ url("admin/stock/add/") }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                class="add icon"></i><span class="text responsive-text">Add</span></div>
                </a>
                <a style="color:white"
                   href="{{ url("admin/stock/print/".'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                class="print icon"></i><span class="text responsive-text">Print</span></div>
                </a>
                <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                    <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                    <div class="menu">
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/stock/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as CSV
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/stock/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as PDF
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/stock/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if(count($stock_items) > 0)
    @if(!isset($print))
    <div class="pagination_con paggination_top" role="toolbar">
        <div class="pull-right">
            {!!  \App\Settings::perPageOptions($count)  !!}
        </div>
    </div>
    @endif
    {{csrf_field()}}
    <table class="ui table table_cus_v last_row_bdr"
           data-action="{{ url('admin/stock') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
        <thead>
        <tr>
            @if(!isset($print))
            <th>Actions</th>
            @endif
            <th>Item Name</th>
            {{--<th>Quantity</th>--}}
            <th>Product Type</th>
            <th>Product Name</th>
            {{--<th>Price</th>--}}
            <th>In Stock Quantity</th>
            {{--<th>Description</th>--}}



        </tr>
        </thead>
        <tbody>
        @if(!isset($print))
        <tr class="table_search">
            <td></td>
            <td class="icons">
                <input type="text" class="table_search" id="table_search_name"
                       value="{{ @$search_data['name'] }}"
                       name="name"
                       placeholder="search Name"/> <i
                        class="fa fa-filter"></i>
            </td>
            <td class="icons">
                <input type="text" class="table_search" id="table_search_product_type"
                       value="{{ @$search_data['product_type'] }}"
                       name="product_name"
                       placeholder="search Product Type"/> <i
                        class="fa fa-filter"></i>
            </td>
            <td class="icons">
               {{-- <input type="text" class="table_search" id="table_search_product_name"
                       value="{{ @$search_data['product_name'] }}"
                       name="product_name"
                       placeholder="search Product Name"/> <i
                        class="fa fa-filter"></i>--}}
            </td>
            <td class="icons">
                <input type="text" class="table_search" id="table_search_quantity"
                       value="{{ @$search_data['quantity'] }}"
                       name="quantity"
                       placeholder="search Quantity"/> <i
                        class="fa fa-filter"></i>
            </td>
        </tr>
        @endif
        @foreach($stock_items as $stock)
            <tr class="{{ $stock->current_quantity <= $stock->alert_quantity ? 'danger' : ''}}">
                @if(!isset($print))
                <td>
                    <div id="book-table"  class="ui  top icon blue left pointing dropdown button">
                        <i class="configure icon"></i>
                        <div class="menu">
                            @if(Laralum::loggedInUser()->hasPermission('admin.stock'))
                                <div class="header">{{ trans('laralum.editing_options') }}</div>
                                <a href="{{ route('Laralum::stock.edit', ['id' => $stock->id]) }}" class="item no-disable">
                                    <i class="edit icon"></i>
                                    {{ trans('laralum.edit_stock') }}
                                </a>
                                <a href="{{ route('Laralum::stock.add_remove_stock', ['id' => $stock->id]) }}" class="item no-disable">
                                    <i class="edit icon"></i>
                                    Add/Remove Stock
                                </a>

                            @endif
                            @if(Laralum::loggedInUser()->hasPermission('admin.stock'))
                                <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                <a href="{{ route('Laralum::stock.delete', ['id' => $stock->id]) }}" class="item no-disable">
                                    <i class="trash bin icon"></i>
                                    {{ trans('laralum.delete_stock') }}
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
                <td>{{ $stock->name }}</td>
                <td>{{ $stock->getGroup()}}</td>
                <td>{{ $stock->getProducts() }}</td>
                <td>{{ $stock->current_quantity.' '.$stock->quantity_units.'' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @if(!isset($print))
    @if(method_exists($stock_items, "links"))
        <div class="pagination_con main_paggination" role="toolbar">
            {{ $stock_items->links() }}
        </div>
    @endif
    @endif
@else
    <table class="ui table table_cus_v last_row_bdr"
           data-action="{{ url('admin/stock') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
        <thead>
        <tr>
            @if(!isset($print))
            <th>Actions</th>
            @endif
            <th>Item Name</th>
            {{--<th>Quantity</th>--}}
            <th>Product Type</th>
            <th>Product Name</th>
            {{--<th>Price</th>--}}
            <th>Quantity</th>
            {{--<th>Description</th>--}}
            
        </tr>
        </thead>
        <tbody>
        @if(!isset($print))
        <tr class="table_search">

            <td></td>
            <td class="icons">
                <input type="text" class="table_search" id="table_search_name"
                       value="{{ @$search_data['name'] }}"
                       name="name"
                       placeholder="search Name"/> <i
                        class="fa fa-filter"></i>
            </td>
            <td class="icons">
                <input type="text" class="table_search" id="table_search_product_type"
                       value="{{ @$search_data['product_type'] }}"
                       name="product_type"
                       placeholder="search Product Type"/> <i
                        class="fa fa-filter"></i>
            </td>
            <td class="icons">
               {{-- <input type="text" class="table_search" id="table_search_product_name"
                       value="{{ @$search_data['product_name'] }}"
                       name="product_name"
                       placeholder="search Product Name"/> <i
                        class="fa fa-filter"></i>--}}
            </td>
            <td class="icons">
                <input type="text" class="table_search" id="table_search_quantity"
                       value="{{ @$search_data['quantity'] }}"
                       name="quantity"
                       placeholder="search Quantity"/> <i
                        class="fa fa-filter"></i>
            </td>
        </tr>
        @endif
        <tr>
            <td colspan="4">
                <div class="ui negative icon message">
                    <i class="frown icon"></i>
                    <div class="content">
                        <div class="header">
                            {{ isset($error) ? $error : "" }}
                        </div>
                        <p>There are currently no stock Items added.</p>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
@endif