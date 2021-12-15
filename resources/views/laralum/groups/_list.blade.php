@if(!isset($print))
    <div class="column table_top_btn">
        <div class="btn-group pull-right">
            <div class="item no-disable">
                <a style="color:white" href="{{ url("admin/group/add") }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button">
                        <i class="plus icon"></i><span class="text responsive-text">Create Group</span>
                    </div>
                </a>
                <a style="color:white"
                   href="{{ url("admin/groups/print/".'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                class="print icon"></i><span class="text responsive-text">Print</span></div>
                </a>
                <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                    <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                    <div class="menu">
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/groups/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as CSV
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/groups/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as PDF
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/groups/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if(count($groups) > 0)
    @if(!isset($print))
        <div class="pagination_con paggination_top" role="toolbar">
            <div class="pull-right">
                {!!  \App\Settings::perPageOptions($count)  !!}
            </div>
        </div>
    @endif

    {{csrf_field()}}
    <table class="ui table table_cus_v last_row_bdr">
        <thead>
        <tr>
            <th>Title</th>
            <th>Description</th>
            @if(!isset($print))
                <th>Actions</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($groups as $group)
            <tr>
                <td><span id="view{{ $group->id }}">{{ $group->title }}</span>
                    <span style="display:none;" id="edit_{{ $group->id }}">
                        <form method="post">
                            {!! csrf_field() !!}
                            <input type="text" class="form-control" name="question_{{ $group->id }}"
                                   value="{{ $group->question }}">
                            <input type="hidden" name="question_id" value="{{ $group->id }}"/>
                              <br/>
                            <button class="btn ui blue">Save</button>
                        </form>

                    </span>
                </td>
                <td>{{ $group->description }}</td>
                @if(!isset($print))
                    <td>
                        <div id="book-table" class="ui  top icon blue left pointing dropdown button">
                            <i class="configure icon"></i>
                            <div class="menu">
                                @if(Laralum::loggedInUser()->hasPermission('admin.inventory_groups'))
                                    <div class="header">{{ trans('laralum.editing_options') }}</div>
                                    <a href="{{ route('Laralum::group.edit', ['id' => $group->id]) }}"
                                       class="item no-disable">
                                        <i class="edit icon"></i>
                                        {{ trans('laralum.edit_group') }}
                                    </a>
                                @endif
                                @if(Laralum::loggedInUser()->hasPermission('admin.inventory_groups'))
                                    <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                    <a href="{{ route('Laralum::group.delete', ['id' => $group->id]) }}"
                                       class="item no-disable">
                                        <i class="trash bin icon"></i>
                                        {{ trans('laralum.delete_group') }}
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
        @if(method_exists($groups, "links"))
            <div class="pagination_con main_paggination" role="toolbar">
                {{ $groups->links() }}
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
            <p>There are currently no groups added.</p>
        </div>
    </div>
@endif

