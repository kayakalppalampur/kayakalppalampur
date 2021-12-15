@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">{{ trans('laralum.feedback_questions_list') }}</div>
    </div>
@endsection
@section('title',  trans('laralum.feedback_questions'))
@section('icon', "pencil")
@section('subtitle', 'List of all Feedback Questions')
@section('content')
    <div class="ui one column doubling stackable">
        <div class="column">
            <div class="ui very padded segment table_header_row table-responsive" id="department_list">
                @include('laralum.feedback_question._list')
            </div>
        </div>
    </div>
@endsection
@section("js")
    <script>
        $(document).delegate("[id^=edit_question_]", 'click', function () {
            var id = $(this).attr("id").split("edit_question_")[1];
            console.log("id" + id);
            $("#edit_" + id).show();
            $("#view" + id).hide();
        })
        $(document).delegate("#add_feedback_question", 'click', function () {
            $("#add_question_form_div").show();
        });
        $(document).delegate("#cancel", 'click', function () {
            $("#add_question_form_div").hide();
            $("#add_question_form").reset();
        })
    </script>
@endsection

