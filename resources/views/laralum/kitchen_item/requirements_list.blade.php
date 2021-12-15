@if(!isset($print))
    <div class="column table_top_btn">
    <div class="btn-group pull-right">
        <div class="item no-disable">
            <a style="color:white" href="{{ url("admin/kitchen-item/requirements/print".'?daterange='.@$_REQUEST['daterange'].'&s='.@json_encode($search_data)) }}">
                <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                            class="print icon"></i><span class="text responsive-text">Print</span></div>
            </a>

            <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                <div class="menu">
                    <a id="clicked" class="item no-disable"
                       href="{{ url('/admin/kitchen-item/exportRequirements/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&daterange='.@$_REQUEST['daterange'].'&s='.@json_encode($search_data)) }}">Export
                        as CSV
                    </a>
                    <a id="clicked" class="item no-disable"
                       href="{{ url('/admin/kitchen-item/exportRequirements/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&daterange='.@$_REQUEST['daterange'].'&s='.@json_encode($search_data)) }}">Export
                        as PDF
                    </a>
                    <a id="clicked" class="item no-disable"
                       href="{{ url('/admin/kitchen-item/exportRequirements/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&daterange='.@$_REQUEST['daterange'].'&s='.@json_encode($search_data)) }}">Export
                        as Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="btn btn-group">
    <span class="col-md-1"> Date:</span><span class="col-md-6"> <input type="text" class="form-control daterange" value="01/01/2018 - 01/15/2018" name="daterange" id="date" /> </span>
    <input type="hidden" id="date1" value="{{ $date_1 }}">
    <input type="hidden" id="date2" value="{{ $date_2 }}">
</div>
@endif

@if(count($kitchen_items) > 0)
    <table class="ui table table_cus_v last_row_bdr"
           data-action="{{ url('admin/kitchen-item/requirements') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
        <thead>
        <tr>
            <th>Name</th>
            <th>Meal Type</th>
            @foreach($date_array as $date)
                <th> {{ $date }} </th>
                @endforeach

            @if(!isset($print))
                <th> Action</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @if(!isset($print))
        <tr class="table_search">
            <td class="icons">
                <input type="text" class="table_search" id="table_search_name"
                       value="{{ @$search_data['name'] }}"
                       name="name"
                       placeholder="search Name"/> <i
                        class="fa fa-filter"></i>
            </td>
            <td></td>
            @foreach($date_array as $date)
                <th></th>
            @endforeach{{--
            @for($i = 0; $i < 7; $i++)
                <td></td>
            @endfor--}}
            <td></td>
        </tr>
        @endif
        @foreach($kitchen_items as $kitchen_item)
            <tr>
                <td>{{ $kitchen_item->name }}</td>
                <td>{{ $kitchen_item->getTypeOptions($kitchen_item->type) }}</td>
                @foreach($date_array_ymd as $date)
                    <td>
                        {{ $kitchen_item->getRequiredItems($date) }}
                    </td>
                @endforeach
                @if(!isset($print))
                    <td>
                        <a class="no-disable" href="{{ url("admin/kitchen-item/".$kitchen_item->id."/request") }}"
                           id="request_item_{{$kitchen_item->id}}">Request Stock</a>
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
    @if(method_exists($kitchen_items, 'links'))
        <div class="pagination_con main_paggination" role="toolbar">
            {{ $kitchen_items->links() }}
        </div>
    @endif
@else
    <table class="ui table table_cus_v last_row_bdr"
           data-action="{{ url('admin/kitchen-item/requirements') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
        <thead>
        <tr>
            <th>Name</th>
            @for($i = 0; $i < 7; $i++)
                <th> {{ date("d, M", strtotime("+".$i."days")) }} </th>
            @endfor
            @if(!isset($print))
                <th> Action</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @if(!isset($print))
        <tr class="table_search">
            <td class="icons">
                <input type="text" class="table_search" id="table_search_name"
                       value="{{ @$search_data['name'] }}"
                       name="name"
                       placeholder="search Name"/> <i
                        class="fa fa-filter"></i>
            </td>
            @for($i = 0; $i < 7; $i++)
                <td></td>
            @endfor
            <td></td>
        </tr>
        @endif
        <tr>
            <td colspan="7">
                <div class="ui negative icon message">
                    <i class="frown icon"></i>
                    <div class="content">
                        <div class="header">
                            {{ trans('laralum.missing_title') }}
                        </div>
                        <p>There are currently no kitchen Items added.</p>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
@endif