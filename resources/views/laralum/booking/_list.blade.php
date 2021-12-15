@if(count($kitchen_items) > 0)
    @if(isset($count))
    <div class="pagination_con" role="toolbar">
        <div class="pull-right">
            {!!  \App\Settings::perPageOptions($count)  !!}
        </div>
    </div>
    {{csrf_field()}}
    @endif
    <table class="ui five column table ">
        <thead>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Meal Type</th>
            {{--  <th>Quantity</th>--}}
            {{--<th>Description</th>--}}
            @if(!isset($print))
            <th>Actions</th>
                @endif
        </tr>
        </thead>
        <tbody>
        @foreach($kitchen_items as $kitchen_item)
            <tr>
                <td>{{ $kitchen_item->name }}</td>
                <td>{{ $kitchen_item->price }}</td>
                <td>{{ $kitchen_item->type !== null ? $kitchen_item->getTypeOptions($kitchen_item->type) : ""}}</td>
                {{-- <td>{{ $kitchen_item->quantity }}</td>--}}
               {{-- <td>{{ $kitchen_item->description }}</td>--}}
                @if(!isset($print))
                <td>
                <div id="book-table" class="ui  top icon blue left pointing dropdown button">
                    <i class="configure icon"></i>
                    <div class="menu">
                        @if(Laralum::loggedInUser()->hasPermission('kitchen_items'))
                        <div class="header">{{ trans('laralum.editing_options') }}</div>
                        <a href="{{ route('Laralum::kitchen-item.edit', ['id' => $kitchen_item->id]) }}" class="item no-disable">
                            <i class="edit icon"></i>
                            {{ trans('laralum.edit_kitchen_item') }}
                        </a>
                        @endif
                        @if(Laralum::loggedInUser()->hasPermission('kitchen_items'))
                            <div class="header">{{ trans('laralum.advanced_options') }}</div>
                            <a href="{{ route('Laralum::kitchen-item.delete', ['id' => $kitchen_item->id]) }}" class="item no-disable">
                                <i class="trash bin icon"></i>
                                {{ trans('laralum.delete_kitchen_item') }}
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
    @if(method_exists($kitchen_items, "links"))
    <div class="pagination_con" role="toolbar">
        <div class="pull-right">
            {{ $kitchen_items->links() }}
        </div>
    </div>
        @endif
@else
    <div class="ui negative icon message">
        <i class="frown icon"></i>
        <div class="content">
            <div class="header">
                {{ trans('laralum.missing_title') }}
            </div>
            <p>There are currently no kitchen Items added.</p>
        </div>
    </div>
@endif