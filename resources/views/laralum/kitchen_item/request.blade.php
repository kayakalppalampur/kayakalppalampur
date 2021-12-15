@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb" xmlns="http://www.w3.org/1999/html">
        <a class="section" href="{{ route('Laralum::kitchen-item.requirements') }}">{{ trans('laralum.requirements_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.request_items') }}</div>
    </div>
@endsection
@section('title', 'Request Kitchen Item')
@section('icon', "plus")
@section('subtitle', 'Request Item to stock Section')
@section('content')
    <div class="ui one column doubling stackable">
        <div class="column">
            <div class="ui very padded segment">
                <form method="POST" class="form">
                    {{ csrf_field() }}
                    <div class="ui stackable">
                        <div class="column">
                            <form method="POST">
                                {{ csrf_field() }}
                                        <div class="kitchen_field_wrapper">
                                            <label>Kitchen Product</label>

                                            <div class="kitchen_field">
                                                <select id="product_id" name="product_id" class="form-control">
                                                    @foreach(\App\KitchenItem::all() as $kitchen_item)
                                                        <option value="{{ $kitchen_item->id }}" {{ $kitchen_item->id == $item->id ? "Selected" : "" }}>{{ $kitchen_item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="search_kitchen_pro">
                                                <button class="ui blue submit button" id="save">Submit</button>
                                            </div>

                                        </div>

                                        <div id="item-list" class="kitchen_listing_con">
                                            @foreach($item->stockItems as $stock_item)
                                                <div class="ui stackable">
                                                    <label>{{ $stock_item->name }}</label>
                                                    <div class="field_cont">
                                                        <input value="{{ $stock_item->lastRequested()  ? $stock_item->lastRequested() : "" }}" type="text" name="item_{{ $stock_item->id }}" placeholder="Quantity i.e. 1 litter or 250 gms" class="form-control" />
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>


                            </form>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section("js")
<script>
    var val = $("#product_id").val();
    getDropdown(val);

    $("#product_id").change(function () {
        var val = $("#product_id").val();
        getDropdown(val);
    });

    function getDropdown(id)
    {
        $.ajax({
            url:"{{ url("admin/item/get-stock-items-list") }}/"+id,
            type:"GET",
            success:function(data){
                var html = "";
                for (key in data) {
                    html += '<div class="ui stackable"> <label>'+data[key].name+'</label><div class="field_cont"><input value="'+data[key].lastRequested+'" type="text" name="item_'+data[key].id+'" placeholder="Quantity i.e. 1 litter or 250 gms" class="form-control" /></div></div>';
                }
                $("#item-list").html(html);
            }
        });
    }

</script>
@endsection