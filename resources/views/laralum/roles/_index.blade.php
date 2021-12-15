@if(!isset($print))
    <div class="column table_top_btn">
        <div class="btn-group pull-right">
            <div class="item no-disable">
                <a style="color:white" href="{{ url("admin/roles/create") }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button">
                        <i class="plus icon"></i><span class="text responsive-text">Create Role</span>
                    </div>
                </a>
                <a style="color:white" href="{{ url('/admin/roles/print/').'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)}}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                class="print icon"></i><span class="text responsive-text">Print</span></div>
                </a>

                <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                    <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                    <div class="menu">
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/roles/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}&s={{@json_encode($search_data)}}">Export
                            as CSV
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/roles/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}&s={{@json_encode($search_data)}}">Export
                            as PDF
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/roles/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}&s={{@json_encode($search_data)}}">Export
                            as Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@if(!isset($print))
<div class="pagination_con paggination_top" role="toolbar">
    <div class="pull-right">
        {!!  \App\Settings::perPageOptions($count)  !!}
    </div>
</div>
@endif

<div class="table-responsive table_sec_row">

    <table class="ui table table_cus_v">
        <thead>
        <tr>
            <th>{{ trans('laralum.name') }}</th>
            <th>{{ trans('laralum.users') }}</th>
            <th>{{ trans('laralum.permissions') }}</th>
            @if(!isset($print))
                <th>{{ trans('laralum.options') }}</th>
            @endif
        </tr>
        </thead>
        <tbody>

        @foreach($roles as $role)
            <tr>
                <td>
                    <div class="text">
                        {{ $role->name }}
                        @if($role->su)
                            <div class="ui red tiny left pointing basic label pop"
                                 data-title="{{ trans('laralum.super_user_role') }}" data-variation="wide"
                                 data-content="{{ trans('laralum.super_user_role_desc') }}"
                                 data-position="top center">{{ trans('laralum.super_user_role') }}</div>
                        @elseif($role->hasPermission('access'))
                            <div class="ui blue tiny left pointing basic label pop"
                                 data-title="{{ trans('laralum.admin_access_role') }}" data-variation="wide"
                                 data-content="{{ trans('laralum.admin_access_role_desc') }}"
                                 data-position="top center">{{ trans('laralum.admin_access_role') }}</div>
                        @endif
                    </div>
                </td>
                <td>{{ trans('laralum.roles_users', ['number' => count($role->users)]) }}</td>
                <td>{{ trans('laralum.roles_permissions', ['number' => count($role->permissions)]) }}</td>
                @if(!isset($print))
                    <td>
                        @if($role->allow_editing or Laralum::loggedInUser()->su)
                            <div class="ui {{ Laralum::settings()->button_color }} top icon left pointing dropdown button">
                                <i class="configure icon"></i>
                                <div class="menu">
                                    <div class="header">{{ trans('laralum.editing_options') }}</div>
                                    <a href="{{ route('Laralum::roles_edit', ['id' => $role->id]) }}"
                                       class="item no-disable">
                                        <i class="edit icon"></i>
                                        {{ trans('laralum.roles_edit') }}
                                    </a>
                                    <a href="{{ route('Laralum::roles_permissions', ['id' => $role->id]) }}"
                                       class="item no-disable">
                                        <i class="lightning icon"></i>
                                        {{ trans('laralum.roles_edit_permissions') }}
                                    </a>
                                    @if(!$role->su and $role->id != Laralum::defaultRole()->id)
                                        <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                        <a href="{{ route('Laralum::roles_delete', ['id' => $role->id]) }}"
                                           class="item no-disable">
                                            <i class="trash bin icon"></i>
                                            {{ trans('laralum.roles_delete') }}
                                        </a>
                                    @endif
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
    @if(!isset($print))
        @if(method_exists($roles, "links"))
            <div class="pagination_con main_paggination" role="toolbar">
                {{ $roles->links() }}
            </div>
        @endif
    @endif

</div>