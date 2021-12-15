@if(!isset($print))
    <div class="column table_top_btn">
        <div class="btn-group pull-right">
            <div class="item no-disable">
                <a style="color:white"
                   href="{{ url("admin/stock-item-requests/print/".'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                class="print icon"></i><span class="text responsive-text">Print</span></div>
                </a>
                <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                    <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                    <div class="menu">
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/stock-item-requests/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as CSV
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/stock-item-requests/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as PDF
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/stock-item-requests/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@if($item_requests->count() > 0)
    @if(!isset($print))
    <div class="pagination_con" role="toolbar">
        <div class="pull-right">
            {!!  \App\Settings::perPageOptions($count)  !!}
        </div>
    </div>
    {{csrf_field()}}
    @endif
    <table class="ui five column table ">
        <thead>
        <tr>
            @if(!isset($print))
            <th>Actions</th>
            @endif
            <th>Name</th>
            <th>Requested By</th>
            <th>Requested On</th>
            <th>Current Quantity (When Requested)</th>
            <th>Required Quantity</th>
            <th>Approved Quantity</th>
            <th>Approved Date</th>
            <th>Status</th>
            {{--<th>Price</th>
            <th>Quantity</th>--}}
            {{--<th>Description</th>--}}

        </tr>
        </thead>
        <tbody>
        @foreach($item_requests as $item_request)
            <tr>
                @if(!isset($print))
                <td>

                    <div id="book-table" class="ui  top icon blue left pointing dropdown button">
                        <i class="configure icon"></i>
                        <div class="menu">
                            @if(Laralum::loggedInUser()->hasPermission('admin.stock'))
                                <div class="header">{{ trans('laralum.editing_options') }}</div>
                                <a href="{{  url("admin/item-request/".$item_request->id."/approve") }}"
                                   class="item no-disable">
                                    <i class="edit icon"></i>
                                    Approve
                                </a>
                                <a href="{{ route('Laralum::stock.add_remove_stock', ['id' => $item_request->item->id]) }}"
                                   class="item no-disable">
                                    <i class="edit icon"></i>
                                    Add/Remove Stock
                                </a>

                            @endif
                            @if(Laralum::loggedInUser()->hasPermission('admin.stock'))
                                <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                <a href="{{ route('Laralum::item_request.delete', ['id' => $item_request->id]) }}"
                                   class="item no-disable">
                                    <i class="trash bin icon"></i>
                                    Delete
                                </a>
                            @endif
                        </div>
                    </div>

                </td>
                @endif
                <td>{{ $item_request->item->name }}</td>
                <td>{{ $item_request->createUser->name }}</td>
                <td>{{ $item_request->created_at != null ? date("d-m-Y h:i a", strtotime($item_request->created_at->setTimezone(env('TIMEZONE'))->toDateTimeString())) : ""}}</td>
                <td>{{ $item_request->status == \App\StockItemRequest::STATUS_PENDING ? $item_request->item->current_quantity : $item_request->item_qty }}</td>
                <td>{{ $item_request->quantity }}</td>
                <td>{{ $item_request->approved_qty }}</td>
                <td>{{ $item_request->approved_date != null ? date("d-m-Y h:i a", strtotime($item_request->approved_date)) : ''}}</td>
                <td>{{ $item_request->getStatusOptions($item_request->status) }}</td>

                {{-- <td>
                     <div  id="book-table"  class="ui  top icon blue left pointing dropdown button">
                         <i class="configure icon"></i>
                         <div class="menu">
                             @if(Laralum::loggedInUser()->hasPermission('item_request'))
                                 <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                 <a href="{{ route('Laralum::item_request.delete', ['id' => $item_request->id]) }}" class="item no-disable">
                                     <i class="trash bin icon"></i>
                                     {{ trans('laralum.delete_item_request') }}
                                 </a>
                             @endif
                         </div>
                     </div>
                     --}}{{--  @else
                           <div class="ui disabled blue icon button">
                               <i class="lock icon"></i>
                           </div>
                       @endif--}}{{--
                 </td>--}}
            </tr>
        @endforeach
        </tbody>
    </table>
    @if(!isset($print))
    @if(method_exists($item_requests, "links"))
        <div class="pagination_con" role="toolbar">
            <div class="pull-right">
                {{ $item_requests->links() }}
            </div>
        </div>
    @endif
        @endif
@else
    <div class="ui negative icon message">
        <i class="frown icon"></i>
        <div class="content">
            <div class="header">
                {{ trans('laralum.missing_title') }}
            </div>
            <p>There are currently no item_request Item Requests added.</p>
        </div>
    </div>
@endif
