@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Treatment Tokens</div>
    </div>
@endsection
@section('title', 'Treatments')
@section('icon', "pencil")
@section('subtitle', 'List of all Treatments tokens assigned to patients')
@section('content')
    <div class="ui one column doubling stackable">
        <div class="column">
            <div class="ui very padded segment table_header_row table-responsive" id="department_list">
                @include('laralum.treatment._token_list')
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $('body').delegate(".datepicker", 'focus', function () {
            $(this).datepicker({
                dateFormat: "dd-mm-yy",
                autoclose: true
            })
        })

        $(document).delegate("[id^=edit_]", 'click', function () {
            console.log("df");
            var id = $(this).attr('id').split('edit_')[1];
            var html = $("#change_status_div_" + id).show();
        });

        $(document).delegate("[id^=edit_reason_]", 'click', function () {
            console.log("df");
            var id = $(this).attr('id').split('edit_reason_')[1];
            var html = $("#change_reason_div_" + id).show();
        });

        function update(id) {
            var val = $("#change_status_option_" + id).val();
            var reason = $("#not_attended_reason_" + id).val();
            var post_data = {'status': val, 'not_attended_reason': reason};
            //console.log(post_data); return false;
            $.ajax({
                url: $("#treatment_token_form_" + id).attr('action'),
                type: "POST",
                data: post_data,
                success: function (response) {
                    $("#status_" + id).html(response.status);
                    $("#change_status_div_" + id).hide();
                    $("#reason_" + id).html(response.reason);
                    $("#change_reason_div_" + id).hide();
                }
            })
        }

        $(document).delegate("[id^=change_status_option_], [id^=not_attended_reason_]", 'change', function () {
            var id = $(this).attr('id').split('change_status_option_')[1];
            if (typeof id == 'undefined')
                var id = $(this).attr('id').split('not_attended_reason_')[1];
            update(id);

        });

        $(function () {
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': $('[name="_token"]').val()}
            });
        });


    </script>
    <script>
        $(".datepicker").datepicker({dateFormat: "dd-mm-yy", autoclose: true,});
    </script>
@endsection
