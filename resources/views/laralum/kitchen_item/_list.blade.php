@if(!isset($print))
    <div class="column table_top_btn">
        <div class="btn-group pull-right">
            <div class="item no-disable">
                <a style="color:white" href="{{ route("Laralum::kitchen-item.create") }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                class="plus icon"></i><span class="text responsive-text">Add Kitchen Item</span></div>
                </a>
                <a style="color:white"
                   href="{{ url("admin/kitchen-items/print/".'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                class="print icon"></i><span class="text responsive-text">Print</span></div>
                </a>
                <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                    <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                    <div class="menu">
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/kitchen-items/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as CSV
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/kitchen-items/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as PDF
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/kitchen-items/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif



@if(count($kitchen_items) > 0)
    @if(isset($count))
        @if(!isset($print))
            <div class="pagination_con paggination_top" role="toolbar">
                <div class="pull-right">
                    {!!  \App\Settings::perPageOptions($count)  !!}
                </div>
            </div>
            {{csrf_field()}}
        @endif
    @endif
    <div class="table_outer">
    <table class="ui table table_cus_v last_row_bdr"
           data-action="{{ url('admin/kitchen-items') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
        <thead>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Meal Type</th>
            <th>Ingredients</th>
            {{--  <th>Quantity</th>--}}
            {{--<th>Description</th>--}}
            @if(!isset($print))
                <th>Actions</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @if(!isset($print))
            <tr class="table_search">
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_name"
                           value="{{ @$search_data['name'] }}"
                           name="name"
                           placeholder="search Name"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_price"
                           value="{{ @$search_data['price'] }}"
                           name="price"
                           placeholder="search Price"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <select class="table_search" id="table_search_type" name="type"
                            value="{{ @$search_data['type'] }}">
                        <option value="">All Types</option>
                        @foreach(\App\KitchenItem::getTypeOptions() as $key => $val)
                            <option value="{{ $key }}" {{ @$search_data['type'] == $key ? "selected" : "" }}>{{ $val }}</option>
                        @endforeach
                    </select>
                    <i class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_ingredients"
                           value="{{ @$search_data['ingredients'] }}"
                           name="ingredients"
                           placeholder="search ingredients"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td>
                    &nbsp;
                </td>
            </tr>
        @endif
        @foreach($kitchen_items as $kitchen_item)
            <tr>
                <td>{{ $kitchen_item->name }}</td>
                <td>{{ $kitchen_item->price }}</td>
                <td>{{ $kitchen_item->type != null ? $kitchen_item->getTypeOptions($kitchen_item->type) : ""}}</td>
                <td>{{ $kitchen_item->getStockItemsList() }}</td>
                {{-- <td>{{ $kitchen_item->quantity }}</td>--}}
                {{-- <td>{{ $kitchen_item->description }}</td>--}}
                @if(!isset($print))
                    <td>
                        <div id="book-table" class="ui  top icon blue left pointing dropdown button">
                            <i class="configure icon"></i>
                            <div class="menu">
                                @if(Laralum::loggedInUser()->hasPermission('admin.kitchen_items'))
                                    <div class="header">{{ trans('laralum.editing_options') }}</div>
                                    <a href="{{ route('Laralum::kitchen-item.edit', ['id' => $kitchen_item->id]) }}"
                                       class="item no-disable">
                                        <i class="edit icon"></i>
                                        {{ trans('laralum.edit_kitchen_item') }}
                                    </a>
                                @endif
                                @if(Laralum::loggedInUser()->hasPermission('admin.kitchen_items'))
                                    <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                    <a href="{{ route('Laralum::kitchen-item.delete', ['id' => $kitchen_item->id]) }}"
                                       class="item no-disable">
                                        <i class="trash bin icon"></i>
                                        {{ trans('laralum.delete_kitchen_item') }}
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
</div>
    @if(!isset($print))
        @if(method_exists($kitchen_items, "links"))
            <div class="pagination_con main_paggination" role="toolbar">
                {{ $kitchen_items->links() }}
            </div>
        @endif
    @endif
@else
<div class="table_outer">
    <table class="ui table table_cus_v last_row_bdr"
           data-action="{{ url('admin/kitchen-items') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
        <thead>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Meal Type</th>
            {{--  <th>Quantity</th>--}}
            {{--<th>Description</th>--}}
            @if(!isset($print))
                <th>Actions</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @if(!isset($print))
            <tr class="table_search">
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_name"
                           value="{{ @$search_data['name'] }}"
                           name="name"
                           placeholder="search Name"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_price"
                           value="{{ @$search_data['price'] }}"
                           name="price"
                           placeholder="search Price"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <select class="table_search" id="table_search_type" name="type"
                            value="{{ @$search_data['type'] }}">
                        <option value="">All Types</option>
                        @foreach(\App\KitchenItem::getTypeOptions() as $key => $val)
                            <option value="{{ $key }}" {{ @$search_data['type'] == $key ? "selected" : "" }}>{{ $val }}</option>
                        @endforeach
                    </select>
                    <i class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_ingredients"
                           value="{{ @$search_data['ingredients'] }}"
                           name="ingredients"
                           placeholder="search ingredients"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td>
                    &nbsp;
                </td>
            </tr>
            <tr>
        @endif
        <tr>
            <td colspan="4">
                <div class="ui negative icon message">
                    <i class="frown icon"></i>
                    <div class="content">
                        <div class="header">
                            {{ trans('laralum.missing_title') }}
                        </div>
                        <p>There are currently no kitchen Items added.</p>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
@endif