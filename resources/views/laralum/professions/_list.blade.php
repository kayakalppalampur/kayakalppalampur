@if(!isset($print))
    <div class="column table_top_btn">
        <div class="btn-group pull-right">
            <div class="item no-disable">
                <a style="color:white" href="{{ url("admin/professions/create") }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button">
                        <i class="plus icon"></i><span class="text responsive-text">Create Profession</span>
                    </div>
                </a>
                <a style="color:white" href="{{ url("admin/professions/print/".'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                class="print icon"></i><span class="text responsive-text">Print</span></div>
                </a>
                <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                    <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                    <div class="menu">
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/professions/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as CSV
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/professions/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as PDF
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/professions/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if(count($professions) > 0)
    {{csrf_field()}}
    @if(!isset($print))
    <div class="pagination_con paggination_top" role="toolbar">
        <div class="pull-right">
            {!!  \App\Settings::perPageOptions(\App\Profession::count())  !!}
        </div>
    </div>
    @endif
    <table class="ui table table_cus_v last_row_bdr" data-action="{{ url('admin/professions') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
        <thead>
        <tr>
            <th>Title</th>
            <th>Slug</th>
            @if(!isset($print))
            <th>Actions</th>
                @endif
        </tr>
        </thead>
        <tbody>
        @if(!isset($print))
            <tr class="table_search">
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_name" value="{{ @$search_data['name'] }}"
                           name="title"
                           placeholder="search title"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_slug" value="{{ @$search_data['slug'] }}"
                           name="slug"
                           placeholder="search slug"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td> &nbsp;</td>

            </tr>
        @endif
        @foreach($professions as $profession)
            <tr>
                <td>{{ $profession->name }}</td>
                <td>{{ $profession->slug }}</td>
                @if(!isset($print))
                <td>
                    <div id="book-table"  class="ui  top icon blue left pointing dropdown button">
                        <i class="configure icon"></i>
                        <div class="menu">
                            @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.professions'))
                                <div class="header">{{ trans('laralum.editing_options') }}</div>
                                <a href="{{ route('Laralum::profession_edit', ['id' => $profession->id]) }}"
                                   class="item no-disable">
                                    <i class="edit icon"></i>
                                    {{ trans('laralum.edit_profession') }}
                                </a>
                            @endif
                            @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.professions'))
                                <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                <a href="{{ route('Laralum::profession_delete', ['id' => $profession->id]) }}"
                                   class="item no-disable">
                                    <i class="trash bin icon"></i>
                                    {{ trans('laralum.delete_profession') }}
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
    @if(method_exists($professions, "links"))
        <div class="pagination_con main_paggination" role="toolbar">
             {{ $professions->links() }}
        </div>
    @endif
        @endif
@else
    <table class="ui table table_cus_v last_row_bdr" data-action="{{ url('admin/professions') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
        <thead>
        <tr>
            <th>Title</th>
            <th>Slug</th>
            @if(!isset($print))
                <th>Actions</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @if(!isset($print))
            <tr class="table_search">
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_name" value="{{ @$search_data['name'] }}"
                           name="title"
                           placeholder="search title"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_slug" value="{{ @$search_data['slug'] }}"
                           name="slug"
                           placeholder="search slug"/> <i
                            class="fa fa-filter"></i>
                </td>
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
                        <p>{{ isset($error) ? $error : "No professions found."}}</p>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
@endif