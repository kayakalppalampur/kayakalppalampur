@if(!isset($print))
    @if(!isset($query))
        <div class="column table_top_btn">
            <div class="btn-group pull-right">
                <div class="item no-disable">

                    <a style="color:white" href="{{ url("admin/issues/print/".'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">
                        <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                    class="print icon"></i><span class="text responsive-text">Print</span></div>
                    </a>
                    <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                        <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                        <div class="menu">
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/issues/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                                as CSV
                            </a>
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/issues/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                                as PDF
                            </a>
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/issues/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                                as Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif

@if(count($issues) > 0)
    @if(!isset($print))
    <div class="pagination_con paggination_top" role="toolbar">
        <div class="pull-right">
            {!!  \App\Settings::perPageOptions(count($issues))  !!}
        </div>
    </div>
    {{csrf_field()}}
    @endif
<div class="table_outer">
    <table class="ui table_cus_v table"
           data-action="{{ url('admin/issues') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
        <thead>
        <tr>
            <th>@if(isset($query)) Subject @else Title @endif</th>
            <th>@if(isset($query)) Query @else Description @endif</th>

            @if(!isset($query))
                <th>Status</th>@endif

            @if(isset($query))
                <th>Email</th> @endif
            @if(isset($query))
                <th>Submitted By</th>
            @else
                @if(\Auth::user()->isAdmin())
                    <th>Submitted By</th>
                @endif
            @endif
            <th>Submitted on</th>
            @if(!isset($query))
                <th>Actions</th>@endif
        </tr>
        </thead>
        <tbody>
        @if(!isset($print))
            @if(!isset($query))
                <tr class="table_search">

                    <td class="icons">
                        <input type="text" class="table_search" id="table_search_title" value="{{ @$search_data['title'] }}"
                               name="slug"
                               placeholder="search title"/> <i
                                class="fa fa-filter"></i>
                    </td>
                    <td>
                        &nbsp;
                    </td>
                    <td class="icons">
                        <select class="table_search" id="table_search_status" name="status"
                                value="{{ @$search_data['status'] }}">
                            <option value="">Search Status</option>
                            <option value="{{ \App\Issue::STATUS_PENDING}}" {{ @$search_data['status'] == \App\Issue::STATUS_PENDING ? "selected" : "" }}>
                                Pending
                            </option>
                            <option value="{{ \App\Issue::STATUS_PROCESSING}}" {{ @$search_data['status'] == \App\Issue::STATUS_PROCESSING ? "selected" : "" }}>
                                Processing
                            </option>
                            <option value="{{ \App\Issue::STATUS_RESOLVED}}" {{ @$search_data['status'] == \App\Issue::STATUS_RESOLVED ? "selected" : "" }}>
                                Resolved
                            </option>
                        </select>
                    </td>
                    <td class="icons">
                        <input type="text" class="table_search" id="table_search_name" value="{{ @$search_data['name'] }}"
                               name="name"
                               placeholder="search submitted by"/> <i
                                class="fa fa-filter"></i>
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

        @foreach($issues as $issue)
            <tr>
                <td>{{ $issue->title }}</td>
                <td>{{ $issue->description }}</td>
                @if(!isset($query))
                    <td id="status_{{ $issue->id }}">{!! \App\Issue::getStatusLabelOptions($issue->status)  !!}
                        @if (Laralum::loggedInUser()->hasPermission('issue.change_status') && !isset($query) )
                            <input type="hidden" id="selected_state_{{ $issue->id }}" value="{{ $issue->status }}">
                            <i id="edit_{{ $issue->id}}" class="fa fa-edit edit_span"></i>
                            <div id="change_status_div_{{ $issue->id }}" style="display:none;">
                                <form method="post" id="issue_form_{{ $issue->id }}"
                                      action="{{ route('Laralum::issue.change_status', ['issue_id' => $issue->id]) }}">
                                    <select id="change_status_option_{{ $issue->id }}">
                                        @foreach(\App\Issue::getStatusOptions() as $k => $status)
                                            <option {{ $issue->status == $k ? 'selected' : '' }} value="{{ $k }}">{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                        @endif
                    </td>
                @endif
                @if(isset($query))
                    <td>{{ $issue->email_id }}</td> @endif
                <td>{{ $issue->name }}</td>
                {{-- @if(isset($query))
                     <td>{{ $issue->name }}</td>
                 @else
                     @if(\Auth::user()->isAdmin())
                         <td>{{ isset($issue->user->name) ? $issue->user->name : ""}}</td>
                     @endif
                 @endif--}}
                <td>{{ $issue->created_at != null ? $issue->created_at->setTimezone(env('TIMEZONE'))->toDateTimeString() : ""}}</td>
                @if(!isset($print))
                @if(!isset($query))
                    <td>
                        <div id="book-table"  class="ui  top icon blue left pointing dropdown button">
                            <i class="configure icon"></i>
                            <div class="menu">
                                <div class="header">{{ trans('laralum.issue_view') }}</div>
                                <a href="{{ isset($query) ? route('Laralum::query.view', ['id' => $issue->id])  : route('Laralum::issue.view', ['id' => $issue->id]) }}"
                                   class="item no-disable">
                                    <i class="edit icon"></i>
                                    {{ trans('laralum.issue_view') }}
                                </a>
                                @if(!isset($query))
                                    @if(Laralum::loggedInUser()->hasPermission('issues.edit'))
                                        <div class="header">{{ trans('laralum.editing_options')  }}</div>
                                        <a href="{{ route('Laralum::issue.edit', ['id' => $issue->id]) }}" class="item no-disable">
                                            <i class="edit icon"></i>
                                            {{ trans('laralum.edit_issue') }}
                                        </a>
                                    @endif
                                @endif
                                @if(Laralum::loggedInUser()->hasPermission('issues.delete'))
                                    <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                    <a href="{{ isset($query) ? route('Laralum::query.delete', ['id' => $issue->id])  : route('Laralum::issue.delete', ['id' => $issue->id]) }}"
                                       class="item no-disable">
                                        <i class="trash bin icon"></i>
                                        {{ trans('laralum.delete') }}
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
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
    @if(!isset($print))
    @if(method_exists($issues, "links"))
        <div class="pagination_con main_paggination" role="toolbar">
            {{ $issues->links() }}
        </div>
    @endif
        @endif
@else
<div class="table_outer">
    <table class="ui table_cus_v table"
           data-action="{{ url('admin/issues') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
        <thead>
        <tr>
            <th>@if(isset($query)) Subject @else Title @endif</th>
            <th>@if(isset($query)) Query @else Description @endif</th>

            @if(!isset($query))
                <th>Status</th>@endif

            @if(isset($query))
                <th>Email</th> @endif
            @if(isset($query))
                <th>Submitted By</th>
            @else
                @if(\Auth::user()->isAdmin())
                    <th>Submitted By</th>
                @endif
            @endif
            <th>Submitted on</th>
            @if(!isset($print))
            @if(!isset($query))
                <th>Actions</th>@endif
                @endif
        </tr>
        </thead>
        <tbody>
        @if(!isset($print))
        <tr class="table_search">

            <td class="icons">
                <input type="text" class="table_search" id="table_search_title" value="{{ @$search_data['title'] }}"
                       name="slug"
                       placeholder="search title"/> <i
                        class="fa fa-filter"></i>
            </td>
            <td>
                &nbsp;
            </td>
            <td class="icons">
                <select class="table_search" id="table_search_status" name="status"
                        value="{{ @$search_data['status'] }}">
                    <option value="">Status</option>
                    <option value="{{ \App\Issue::STATUS_PENDING}}" {{ @$search_data['status'] == \App\Issue::STATUS_PENDING ? "selected" : "" }}>
                        Pending
                    </option>
                    <option value="{{ \App\Issue::STATUS_PROCESSING}}" {{ @$search_data['status'] == \App\Issue::STATUS_PROCESSING ? "selected" : "" }}>
                        Processing
                    </option>
                    <option value="{{ \App\Issue::STATUS_RESOLVED}}" {{ @$search_data['status'] == \App\Issue::STATUS_RESOLVED ? "selected" : "" }}>
                        Resolved
                    </option>
                </select>
            </td>
            <td class="icons">
                <input type="text" class="table_search" id="table_search_name" value="{{ @$search_data['name'] }}"
                       name="name"
                       placeholder="search submitted by"/> <i
                        class="fa fa-filter"></i>
            </td>
            <td>
                &nbsp;
            </td>
            <td>
                &nbsp;
            </td>
        </tr>
        @endif
        <tr>
            <td colspan="6">
                <div class="ui negative icon message">
                    <i class="frown icon"></i>
                    <div class="content">
                        <div class="header">
                            {{ trans('laralum.missing_title') }}
                        </div>
                        <p>{{ isset($error) ? $error : "There are currently no issues added" }}</p>
                    </div>
                </div>
            </td>
        </tr>

        </tbody>
    </table>
</div>
@endif
@section('js')
    <script>
        $(document).delegate("[id^=edit_]", 'click', function () {
            var id = $(this).attr('id').split('edit_')[1];
            var html = $("#change_status_div_" + id).html();
            $("#status_" + id).html(html);
        });
        $(document).delegate("[id^=change_status_option_]", 'change', function () {
            var id = $(this).attr('id').split('change_status_option_')[1];
            var val = $("#change_status_option_" + id).val();
            var post_data = {'status': val};
            //console.log(post_data); return false;
            $.ajax({
                url: $("#issue_form_" + id).attr('action'),
                type: "POST",
                data: post_data,
                success: function (response) {
                    $("#status_" + id).html(response.status);
                }
            })

        });

        $(function () {
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': $('[name="_token"]').val()}
            });
        });


    </script>
@endsection