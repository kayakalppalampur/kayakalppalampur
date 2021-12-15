@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::kitchen-items') }}">{{ trans('laralum.kitchen_item_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.kitchen_item_create') }}</div>
    </div>
@endsection
@section('title', 'Add Kitchen Item')
@section('icon', "plus")
@section('subtitle', 'Kitchen Item')
@section('content')
    <div class="ui one column doubling stackable">
        <div class="ui very padded segment">
            <div class="column about_sec hospital_info role_edt">
                <form method="POST" class="form form form_cond_lft custom_form_sec">
                    {{ csrf_field() }}
                    @include('laralum.forms.master')
                    <div class="create-items field">
                        <label>Ingredients</label>
                        <div id="items_div" class="select_option">
                             <input type="text" name="ingredients[]" class="form-control" id="create_item"/>
                        </div>
                    </div>

                    <div class="add_member_sec no_pdd">
                        <button id="add_more" class="save_btn_signup form-control">Add More Ingredients <i class="fa fa-plus"></i> </button>
                    </div>

                    <div class="form-button_row">
                        <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $("#add_more").click(function (e) {
            e.preventDefault();
            var $div = $('[id^="items_div"]:last');
            console.log($div);

// Read the Number from that DIV's ID (i.e: 3 from "klon3")
// And increment that number by 1
            var id = parseInt($div.prop("id").match(/\d+/g), 10);
            if (isNaN(id))
                id = 0;
            var num = id + 1;
            $("#items_div").clone().prop('id', 'items_div' + num).appendTo(".create-items");
            $("#items_div"+num).addClass('add_ingrident');
            $("<button id='remove" + num + "' class='remove'> <i class='fa fa-times-circle fa-2x '></i> </button>").appendTo('#items_div' + num);
            $("#items_div" + num).find('input').val("");
        })
        $(document).delegate("[id^=remove]", "click", function (e) {
            e.preventDefault();
            var id = $(this).attr("id").split("remove")[1];
            $("#items_div" + id).remove();
            $("#remove" + id).remove();
        })
    </script>
@endsection
@section('js')

@endsection