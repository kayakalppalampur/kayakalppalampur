@if(!isset($print))
        <div class="column table_top_btn">
            <div class="btn-group pull-right">
                <div class="item no-disable">
                    <a style="color:white" href="{{ url("admin/permissions/print/".'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">
                        <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                    class="print icon"></i><span class="text responsive-text">Print</span></div>
                    </a>
                    <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                        <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                        <div class="menu">
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/permissions/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                                as CSV
                            </a>
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/permissions/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                                as PDF
                            </a>
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/permissions/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                                as Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endif
@if(count($permissions) > 0)
    <div class="pagination_con paggination_top" role="toolbar">
        @if(!isset($print))
        <div class="pull-right">
            {!!  \App\Settings::perPageOptions($count)  !!}
        </div>
            @endif
    </div>
    <div class="table_outer">
    <table class="ui table table_cus_v last_row_bdr"
           data-action="{{ url('admin/permissions') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
        <thead>
        <tr>
            <th>{{ trans('laralum.name') }}</th>
            <th>{{ trans('laralum.description') }}</th>
            <th>{{ trans('laralum.slug') }}</th>
            <th>{{ trans('laralum.roles') }}</th>
            @if(!isset($print))
            <th>{{ trans('laralum.options') }}</th>
                @endif
        </tr>
        </thead>
        <tbody>
        @if(!isset($print))
        <tr class="table_search">

            <td class="icons"></td>
            <td>&nbsp;</td>
            <td class="icons">
                <input type="text" class="table_search" id="table_search_slug" value="{{ @$search_data['slug'] }}"
                       name="slug"
                       placeholder="search slug"/> <i
                        class="fa fa-filter"></i>
            </td>
            <td class="icons">
                <select class="table_search" id="table_search_role_id" name="role_id"
                        value="{{ @$search_data['role_id'] }}">
                    <option value="">All Roles</option>
                    @foreach(\App\Role::all() as $role)
                        <option value="{{ $role->id }}" {{ @$search_data['role_id'] == $role->id ? "selected" : "" }}>{{ $role->name }}</option>
                    @endforeach
                </select>
                <i class="fa fa-filter"></i>
            </td>
            <td> &nbsp; </td>

        </tr>
        @endif
        @foreach($permissions as $perm)
            <tr>
                <td>
                    <div class="text">
                        {{ Laralum::permissionName($perm->slug) }}
                    </div>
                </td>
                <td>
                    <div class="text">
                        {{ Laralum::permissionDescription($perm->slug) }}
                    </div>
                </td>
                <td>
                    <div class="text">
                        {{ $perm->slug }}
                    </div>
                </td>
                {{--<td>{{ trans('laralum.permissions_roles', ['number' => count($perm->roles)]) }}</td>--}}
                <td>{{ $perm->getRoleNames() }}</td>
                @if(!isset($print))
                <td>
                    @if(Laralum::loggedInUser()->hasPermission('admin.permissions.list') or (Laralum::loggedInUser()->hasPermission('admin.permissions.list') and !$perm->su))
                        <div class="ui {{ Laralum::settings()->button_color }} top icon left pointing dropdown button">
                            <i class="configure icon"></i>
                            <div class="menu">
                                <div class="header">{{ trans('laralum.editing_options') }}</div>
                                <a href="{{ route('Laralum::permissions_roles_edit', ['id' => $perm->id]) }}"
                                   class="item no-disable">
                                    <i class="edit icon"></i>
                                    Edit Roles
                                </a>
                                {{-- @if(Laralum::loggedInUser()->hasPermission('laralum.permissions.delete') and !$perm->su)
                                 <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                 <a href="{{ route('Laralum::permissions_delete', ['id' => $perm->id]) }}" class="item no-disable">
                                   <i class="trash bin icon"></i>
                                   {{ trans('laralum.permissions_delete') }}
                                 </a>
                                 @endif--}}
                            </div>
                        </div>
                    @else
                        <div class="ui disabled {{ Laralum::settings()->button_color }} icon button">
                            <i class="lock icon"></i>
                        </div>
                    @endif
                </td>
                    @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
    @if(!isset($print))
        @if(method_exists($permissions, "links"))
            <div class="pagination_con main_paggination" role="toolbar">
                {{ $permissions->links() }}
            </div>
        @endif
    @endif

@else
<div class="table_outer">
    <table class="ui table table_cus_v"
           data-action="{{ url('admin/permissions') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
        <thead>
        <tr>
            <th>{{ trans('laralum.name') }}</th>
            <th>{{ trans('laralum.description') }}</th>
            <th>{{ trans('laralum.slug') }}</th>
            <th>{{ trans('laralum.roles') }}</th>
            <th>{{ trans('laralum.options') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr class="table_search">
            <td class="icons">
                &nbsp;
            </td>
            <td>&nbsp;</td>
            <td class="icons">
                <input type="text" class="table_search" id="table_search_slug" value="{{ @$search_data['slug'] }}"
                       name="slug"
                       placeholder="search slug"/> <i
                        class="fa fa-filter"></i>
            </td>
            <td class="icons">
                <select class="table_search" id="table_search_role_id" name="role_id"
                        value="{{ @$search_data['role_id'] }}">
                    <option value="">All Roles</option>
                    @foreach(\App\Role::all() as $role)
                        <option value="{{ $role->id }}" {{ @$search_data['role_id'] == $role->id ? "selected" : "" }}>{{ $role->name }}</option>
                    @endforeach
                </select>
                <i class="fa fa-filter"></i>
            </td>

        </tr>
        <tr>
            <td colspan="5">
                <div class="ui negative icon message">
                    <i class="frown icon"></i>
                    <div class="content">
                        <div class="header">
                            {{ trans('laralum.missing_title') }}
                        </div>
                        <p>{{ isset($error) ? $error : "No permission found."}}</p>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
@endif