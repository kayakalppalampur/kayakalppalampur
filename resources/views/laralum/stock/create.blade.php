@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::stock') }}">{{ trans('laralum.stock_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.stock_create') }}</div>
    </div>
@endsection
@section('title', 'Add Stock Item')
@section('icon', "plus")
@section('subtitle', 'Stock Item')
@section('css')
    <style>
        .bootstrap-select {
            min-height: 120px;
        }
    </style>
@endsection
@section('content')
    <div class="ui one column doubling stackable">
        <div class="ui very padded segment">
            <div class="column about_sec hospital_info role_edt">

                @if(!empty($stock_add))
                    <form method="POST" class="form form form_cond_lft">
                        {{ csrf_field() }}
                        <span class="form-control">Total Available Quantity: {{ $item->current_quantity }}</span>
                        @include('laralum.forms.master')
                        <div class="form-button_row">
                            <button type="submit"
                                    class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                        </div>
                    </form>
                @else
                    <form method="POST" class="form form form_cond_lft">
                        {{ csrf_field() }}
                        <div class="field" id="product_name_div">
                            <label>Select Group</label>
                            <select name="product_type" class="form-control" id="group_id">
                                <option>Select Group</option>
                                <option value="kitchen-item">Kitchen Item</option>
                                @foreach(\App\InventoryGroup::all() as $group)
                                    <option value="{{ $group->id }}">{{ $group->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field" id="meal_type_div" style="display:none;">
                            <label>Select Meal Type</label>
                            <select id="meal_type_id" class="form-control bootstrap-select" multiple>
                                @foreach(\App\DietChartItems::getTypeOptions() as $key => $type)
                                    <option {{ old('meal_type_id') == $key ? "selected" : "" }} value="{{ $key }}"> {{ $type }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field" id="meal_div" style="display:none;">
                            <label>Select Meal</label>
                            <select name="product_id[]" id="product_id" class="form-control bootstrap-select meal_item"
                                    multiple>
                                <option>Select Meal</option>
                                @foreach(\App\KitchenItem::all() as $item)
                                    <option data-type="{{ $item->type }}"
                                            value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field" id="group_items_div" style="display: none;">
                            <label>Select Group Item</label>
                            <select name="product_id[]" id="product_id" class="form-control bootstrap-select group_item"
                                    multiple>
                                @foreach(\App\InventoryGroupItem::all() as $item)
                                    <option data-type="{{ $item->group_id }}"
                                            value="{{ $item->id }}">{{ $item->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="kitchen-item-ingredients">
                            <div class="field" id="product_name_div">
                                <label>Item Name</label>
                                <input type="text" id="name" class="form-control" name="name"
                                       placeholder="Item Name"
                                       value="">
                            </div>
                        </div>

                        <div class="kitchen-item-ingredients">
                            <div class="field" id="product_name_div">
                                <label>Quantity</label>
                                <input type="text" id="quantity" class="form-control" name="quantity"
                                       placeholder="Quantity"
                                       value="" required>
                            </div>
                        </div>

                        <div class="kitchen-item-ingredients">
                            <div class="field" id="product_name_div">
                                <label>Quantity units</label>
                                <input type="text" id="quantity_units" class="form-control" name="quantity_units"
                                       placeholder="Quantity units"
                                       value="" required>
                            </div>
                        </div>
                        <div class="form-button_row">
                            <button type="submit"
                                    class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
    <script>

        $(document).delegate("#group_id", "change", function () {
            var val = $(this).val();
            $("#meal_div").hide();
            $("#product_id").val('');

            if (val == 'kitchen-item') {
                $("#meal_type_div").show();
                $(".meal_item").attr('disabled', false);
                $(".group_item").attr('disabled', true);
                $("#group_items_div").hide();
            } else {
                $(".meal_item").attr('disabled', true);
                $(".group_item").attr('disabled', false);
                $(".group_item").val('');
                $(".group_item").find('option').each(function () {
                    $(this).hide();
                });
                $("#product_id").find('option[data-type="no-visible"]').hide();
                var visible = 0;

                $(".group_item").find('option[data-type="' + val + '"]').each(function () {
                    $(this).show();
                    visible = 1;
                })
                if (visible == 0) {
                    $(".group_item").append('<option data-type="no-visible" value="">No Items</option>');
                } else {
                    $(".group_item").find('option[data-type="no-visible"]').hide();
                }
                $("#group_items_div").show();
                $("#meal_type_div").hide();
            }
        })

        $(document).delegate("#meal_type_id", "change", function () {
            var val = $(this).val();

            $(".meal_item").val('');
            $(".meal_item").find('option').each(function () {
                $(this).hide();
            });

            $(".meal_item").find('option[data-type="no-visible"]').hide();
            var visible = 0;


            for (key in val) {
                $(".meal_item").find('option[data-type="' + val[key] + '"]').each(function () {
                    $(this).show();
                    visible = 1;
                })
            }

            if (visible == 0) {
                $(".meal_item").append('<option data-type="no-visible" value="">No Items</option>');
            } else {
                $(".meal_item").find('option[data-type="no-visible"]').hide();
            }
            $("#meal_div").show();
        });

        $(document).delegate(".item", "click", function () {
            var val = $(this).attr("data-value");

            if (val == 0) {
                $("#product_name_div").show();
                $("#product_type").val("");
            } else {
                var i_val = $(this).text();
                console.log("i_val" + i_val);
                $("#product_type").val(i_val);
                $("#product_name_div").hide();
            }
        })
    </script>

@endsection