@if(!isset($print))
    <div class="column table_top_btn">
        <div class="btn-group pull-right">
            <div class="item no-disable">
                <a style="color:white" href="{{ url("admin/email-templates/create") }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button">
                        <i class="plus icon"></i><span class="text responsive-text">Create Email Template</span>
                    </div>
                </a>
                <a style="color:white" href="{{ url("admin/email-templates/print/".'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                class="print icon"></i><span class="text responsive-text">Print</span></div>
                </a>
                <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                    <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                    <div class="menu">
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/email-templates/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as CSV
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/email-templates/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as PDF
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/email-templates/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@if(count($models) > 0)
    {{csrf_field()}}
    @if(!isset($print))
    <div class="pagination_con paggination_top" role="toolbar">
        <div class="pull-right">
            {!!  \App\Settings::perPageOptions(\App\EmailTemplate::count())  !!}
        </div>
    </div>
    @endif
    <table class="ui table_cus_v table last_row_bdr">
        <thead>
        <tr>
            <th class="column-title">Event</th>
            <th class="column-title">Subject</th>
            <th class="column-title">Description</th>
            @if(!isset($print))
            <th class="column-title">Actions</th>
                @endif
        </tr>
        </thead>
        <tbody>
        {{--  <form class="table_search_form"
                action="{{ url('admin/email-templates/ajax') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
              <tr class="table_search">
                  <td class="icons">
                      <input type="text" class="table_search" id="table_search_event"
                             value="{{ @$search_data['event'] }}"
                             name="event"
                             placeholder="search Event"/> <i
                              class="fa fa-filter"></i>
                  </td>
                  <td class="icons">
                      <input type="text" class="table_search" id="table_search_subject"
                             value="{{ @$search_data['subject'] }}"
                             name="subject"
                             placeholder="search Subject"/> <i
                              class="fa fa-filter"></i>
                  </td>
                  <td>
                      &nbsp;
                  </td>
                  <td>
                      &nbsp;
                  </td>
              </tr>
          </form>--}}
        @foreach($models as $model)
            <tr>
                <td>{{ $model->getEvent() }}</td>
                <td>{{ $model->subject }}</td>
                <td>{!! substr($model->content, 0, 150 ) !!}</td>
                @if(!isset($print))
                <td>
                    <div id="book-table"  class="ui  top icon blue left pointing dropdown button">
                        <i class="configure icon"></i>
                        <div class="menu">
                            @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.email_templates'))
                                <div class="header">{{ trans('laralum.editing_options') }}</div>
                                <a href="{{ url("admin/email-templates/".$model->id.'/edit') }}" class="item no-disable">
                                    <i class="edit icon"></i>
                                    {{ trans('laralum.edit_email_templates') }}
                                </a>
                            @endif
                            @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.email_templates'))
                                <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                <a href="{{ url("admin/email-templates/".$model->id.'/delete') }}" class="item no-disable">
                                    <i class="trash bin icon"></i>
                                    {{ trans('laralum.delete_email_templates') }}
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
    @if(method_exists($models, "links"))
        <div class="pagination_con main_paggination" role="toolbar">
            {{ $models->links() }}
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
            <p>There are currently no Email Templates added for the selected date</p>
        </div>
    </div>
@endif