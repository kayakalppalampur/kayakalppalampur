@if(!isset($print))
    <div class="column table_top_btn">
        <div class="btn-group pull-right">
            <div class="item no-disable">
                <a class="no-disable" style="color:white" id="add_feedback_question">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button">
                        <i class="plus icon"></i><span class="text responsive-text">Add Feedback Questions</span>
                    </div>
                </a>
                <a style="color:white"
                   href="{{ url("admin/feedback-questions/print/".'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                class="print icon"></i><span class="text responsive-text">Print</span></div>
                </a>
                <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                    <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                    <div class="menu">
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/feedback-questions/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as CSV
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/feedback-questions/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as PDF
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/feedback-questions/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="add_fd_qustn" id="add_question_form_div" style="display:none;">
            <form method="POST" action="{{ url("admin/feedback-question/add") }}" id="add_question_form">
                {{ csrf_field() }}
                <div class="form-group">
                    <label>Question</label>
                    <input class="user_name form-control" required type="text" name="question" id="question"
                           placeholder="Question" autofocus>
                </div>

                <div class="form-group">
                    <button id="cancel" class="ui default submit button">{{ trans('laralum.cancel') }}</button>
                    <button id="save"
                            class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
@endif

@if(count($feedback_questions) > 0)
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
            <th>Question</th>
            {{--<th>Description</th>--}}
            @if(!isset($print))
                <th>Actions</th>
            @endif
        </tr>
        </thead>
        <tbody>

        @foreach($feedback_questions as $feedback_question)
            <tr>
                <td><span id="view{{ $feedback_question->id }}">{{ $feedback_question->question }}</span>
                    <span style="display:none;" id="edit_{{ $feedback_question->id }}">
                        <form class="feedback_form" method="post">
                            {!! csrf_field() !!}
                            <div class="feedback_input">
                                <input type="text" class="form-control" name="question_{{ $feedback_question->id }}"
                                       value="{{ $feedback_question->question }}">

                                <input type="hidden" name="question_id" value="{{ $feedback_question->id }}"/>
                                <button class="btn ui blue">Save</button>
                            </div>
                        </form>

                    </span>
                </td>
                {{-- <td>{{ $feedback_question->description }}</td>--}}
                @if(!isset($print))
                    <td>
                        <div id="book-table" class="ui  top icon blue left pointing dropdown button">
                            <i class="configure icon"></i>
                            <div class="menu">
                                @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.feedback_questions'))
                                    <div class="header">{{ trans('laralum.editing_options') }}</div>
                                    <button id="edit_question_{{$feedback_question->id}}" class="item no-disable">
                                        <i class="edit icon"></i>
                                        {{ trans('laralum.edit_feedback_question') }}
                                    </button>
                                @endif
                                @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.feedback_questions'))
                                    <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                    <a href="{{ route('Laralum::feedback-question.delete', ['id' => $feedback_question->id]) }}"
                                       class="item no-disable">
                                        <i class="trash bin icon"></i>
                                        {{ trans('laralum.delete_feedback_question') }}
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
        @if(method_exists($feedback_questions, "links"))
            <div class="pagination_con main_paggination" role="toolbar">
                {{ $feedback_questions->links() }}
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
            <p>There are currently no feedback questions added.</p>
        </div>
    </div>
@endif

