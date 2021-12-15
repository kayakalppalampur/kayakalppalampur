@if(!isset($print))
    <div class="column table_top_btn">
        <div class="btn-group pull-right">
            <div class="item no-disable">
                <a style="color:white" href="{{ url("admin/group-item/add") }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button">
                        <i class="plus icon"></i><span class="text responsive-text">Create Group Item</span>
                    </div>
                </a>
                <a style="color:white"
                   href="{{ url("admin/group-items/print/".'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                class="print icon"></i><span class="text responsive-text">Print</span></div>
                </a>
                <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                    <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                    <div class="menu">
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/group-items/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as CSV
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/group-items/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as PDF
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/group-items/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif



@if(count($group_items) > 0)
    @if(!isset($print))
        <div class="pagination_con paggination_top" role="toolbar">
            <div class="pull-right">
                {!!  \App\Settings::perPageOptions($count)  !!}
            </div>
        </div>

        {{csrf_field()}}
    @endif
    <table class="ui table table_cus_v last_row_bdr"
           data-action="{{ url('admin/group-item') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
        <thead>
        <tr>
            <th>Title</th>
            <th>Group</th>
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
                    <input type="text" class="table_search" id="table_search_title"
                           value="{{ @$search_data['title'] }}"
                           name="title"
                           placeholder="search Title"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_group"
                           value="{{ @$search_data['group'] }}"
                           name="group"
                           placeholder="search Group"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td>
                    &nbsp;
                </td>
            </tr>
        @endif
        @foreach($group_items as $group_item)
            <tr>
                <td><span id="view{{ $group_item->id }}">{{ $group_item->title }}</span>
                </td>
                <td>{{ $group_item->group->title }}</td>
                @if(!isset($print))
                    <td>
                        <div id="book-table" class="ui  top icon blue left pointing dropdown button">
                            <i class="configure icon"></i>
                            <div class="menu">
                                @if(Laralum::loggedInUser()->hasPermission('admin.inventory_group_items'))
                                    <div class="header">{{ trans('laralum.editing_options') }}</div>
                                    <a href="{{ route('Laralum::group-item.edit', ['id' => $group_item->id]) }}"
                                       class="item no-disable">
                                        <i class="edit icon"></i>
                                        {{ trans('laralum.edit_group_item') }}
                                    </a>
                                @endif
                                @if(Laralum::loggedInUser()->hasPermission('admin.inventory_group_items'))
                                    <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                    <a href="{{ route('Laralum::group-item.delete', ['id' => $group_item->id]) }}"
                                       class="item no-disable">
                                        <i class="trash bin icon"></i>
                                        {{ trans('laralum.delete_group_item') }}
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
        @if(method_exists($group_items, "links"))
            <div class="pagination_con main_paggination" role="toolbar">
                {{ $group_items->links() }}
            </div>
        @endif
    @endif
@else
    <table class="ui table table_cus_v last_row_bdr"
           data-action="{{ url('admin/group-item') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
        <thead>
        <tr>
            <th>Title</th>
            <th>Group</th>
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
                <input type="text" class="table_search" id="table_search_title"
                       value="{{ @$search_data['title'] }}"
                       name="title"
                       placeholder="search Title"/> <i
                        class="fa fa-filter"></i>
            </td>
            <td class="icons">
                <input type="text" class="table_search" id="table_search_group"
                       value="{{ @$search_data['group'] }}"
                       name="group"
                       placeholder="search Group"/> <i
                        class="fa fa-filter"></i>
            </td>
            <td>
                &nbsp;
            </td>
        </tr>
        @endif
        <tr>
            <td colspan="3">
                @if($search == true)
                    <div class="ui negative icon message">
                        <i class="frown icon"></i>
                        <div class="content">
                            <div class="header">
                                {{ $error }}
                            </div>
                            <p>There are currently no Group Items added</p>
                        </div>
                    </div>
                @else
                    <div class="ui negative icon message">
                        <i class="frown icon"></i>
                        <div class="content">
                            <div class="header">
                                {{ trans('laralum.missing_title') }}
                            </div>
                            <p>There are currently no Group Items added.</p>
                        </div>
                    </div>
                @endif
            </td>
        </tr>
        </tbody>
    </table>
@endif

