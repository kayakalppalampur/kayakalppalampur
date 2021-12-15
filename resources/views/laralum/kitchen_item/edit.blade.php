@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::kitchen-items') }}">{{ trans('laralum.kitchen_item_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{ trans('laralum.kitchen_items_edit_title') }}</div>
    </div>
@endsection
@section('title', trans('laralum.kitchen_items_edit_title'))
@section('icon', "edit")
@section('subtitle', trans('laralum.kitchen_items_edit_title_name', ['name' => $row->name]))
@section('content')
<div class="ui one column doubling stackable">
    <div class="ui very padded segment">
        <div class="column about_sec hospital_info role_edt">
            <form class="form form form_cond_lft custom_form_sec" method="POST">
                {{ csrf_field() }}
                @include('laralum/forms/master')
                <div class="create-items field">
                    <label>Ingredients</label>
                    <div id="old-items_div" class="select_option">
                        @php $x = 0 @endphp
                        @foreach($row->stockItems as $item)
                        <div id="items_div{{ $x }}" class="select_option add_ingrident">
                            <input type="text" name="ingredients[]" value="{{ $item->name }}" class="form-control" id="create_item"/>
                             <button id='remove{{ $x }}' class='remove'> <i class='fa fa-times-circle fa-2x '></i> </button>
                        </div>
                        @php $x++; @endphp
                        @endforeach
                        <input type="hidden" value="{{ $x }}" class="lastdiv">
                    </div>
                    <div id="items_div" class="select_option add_ingrident">
                            <input type="text" name="ingredients[]" class="form-control" id="create_item"/>
                    </div>
                </div>

                <div class="add_member_sec no_pdd">
                     <button id="add_more" class="save_btn_signup form-control">Add More Ingredients <i class="fa fa-plus"></i>  </button>
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
        var num = $('.lastdiv').val();
        console.log('gfhgfhg'+num);
        $("#items_div").clone().prop('id', 'items_div' + num).appendTo(".create-items");
        $("#items_div"+num).addClass('add_ingrident');
        $("<button id='remove" + num + "' class='remove'> <i class='fa fa-times-circle fa-2x'></i> </button>").appendTo('#items_div' + num);
        $("#items_div" + num).find('input').val("");
        var newnum = num++;
        $('.lastdiv').val(newnum);
    })
    $(document).delegate("[id^=remove]", "click", function (e) {
        e.preventDefault();
        var id = $(this).attr("id").split("remove")[1];
        $("#items_div" + id).remove();
        $("#remove" + id).remove();
    })
</script>
@endsection
