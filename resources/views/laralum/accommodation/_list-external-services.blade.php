@if(!isset($print))
    <div class="column table_top_btn">
        <div class="btn-group pull-right">
            <div class="item no-disable">
                <a style="color:white" href="{{ url("admin/accommodation/external_service/create") }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i class="plus icon"></i><span class="text responsive-text">Add  external service</span>
                    </div>
                </a>
                <a style="color:white"
                   href="{{ url("admin/accommodation/external_service/print/".'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                class="print icon"></i><span class="text responsive-text">Print</span></div>
                </a>
                <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                    <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                    <div class="menu">
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/accommodation/external_service/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as CSV
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/accommodation/external_service/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as PDF
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/accommodation/external_service/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
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
        @if(!isset($print))
            <div class="pull-right">
                {!!  \App\Settings::perPageOptions($count)  !!}
            </div>
        @endif
    </div>
    <table class="ui table table_cus_v last_row_bdr" data-action="{{ url('admin/accommodation/external_services') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
        <thead>
        <tr>
            <th>{{ trans('laralum.room_type') }}</th>
            <th>Price </th>
            <th>Description</th>
            @if(!isset($print))
                <th>{{ trans('laralum.operations') }}</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @if(!isset($print))
            <tr class="table_search">

                <td class="icons" style="vertical-align:text-bottom;"><input type="text" class="table_search" id="table_search_name" value="{{ @$search_data['name'] }}"
                                         name="name"
                                         placeholder="search name"/> <i
                            class="fa fa-filter"></i></td>
                <td><input type="text" class="table_search" id="table_search_price" value="{{ @$search_data['price'] }}"
                           name="price"
                           placeholder="search price"/> <i
                            class="fa fa-filter"></i>&nbsp;</td>

                <td> &nbsp;</td>
                <td> &nbsp;</td>
            </tr>
        @endif
        @foreach($models as $model)
            <tr>
                <td>
                    <div class="text">
                        {{ $model->name }}
                    </div>
                </td>

                <td>
                    <div class="text">
                        {{ $model->price }}
                    </div>
                </td>

                <td>
                    <div class="text">
                        {{ $model->description }}
                    </div>
                </td>

                @if(!isset($print))
                    <td>
                        <div id="book-table" class="ui  top icon blue left pointing dropdown button">
                            <i class="configure icon"></i>
                            <div class="menu">
                                @if(Laralum::loggedInUser()->hasPermission('admin.room_types'))
                                    <div class="header">Edit</div>
                                    <a href="{{ route('Laralum::external_service.edit', ['id' => $model->id]) }}"
                                       class="item no-disable">
                                        <i class="edit icon"></i>
                                        Edit
                                    </a>

                                    <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                    <a href="{{ route('Laralum::external_service.delete',$model->id) }}"
                                       class="item no-disable">
                                        <i class="trash bin icon"></i>Delete
                                    </a>
                                @endif
                            </div>
                        </div>
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
    <table class="ui table table_cus_v last_row_bdr" data-action="{{ url('admin/accommodation/external_services') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
        <thead>
        <tr>
            <th>{{ trans('laralum.room_type') }}</th>
            <th>Price </th>
            <th>Description</th>
            @if(!isset($print))
                <th>{{ trans('laralum.operations') }}</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @if(!isset($print))
            <tr class="table_search">

                <td class="icons"><input type="text" class="table_search" id="table_search_name" value="{{ @$search_data['name'] }}"
                                         name="name"
                                         placeholder="search name"/> <i
                            class="fa fa-filter"></i></td>
                <td><input type="text" class="table_search" id="table_search_price" value="{{ @$search_data['price'] }}"
                           name="price"
                           placeholder="search price"/> <i
                            class="fa fa-filter"></i>&nbsp;</td>

                <td> &nbsp;</td>
                <td> &nbsp;</td>
            </tr>
        @endif
        <tr>
            <td colspan="5">
                <div class="ui negative icon message">
                    <i class="frown icon"></i>
                    <div class="content">
                        <div class="header">
                            {{ trans('laralum.missing_title') }}
                        </div>
                        <p>{{ isset($error) ? $error : "No external services found."}}</p>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
@endif