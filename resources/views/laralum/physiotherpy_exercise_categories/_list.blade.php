@if(!isset($print))
    @if(isset($archived))
        <div class="column table_top_btn">
            <div class="btn-group pull-right">
                <div class="item no-disable">
                    <a style="color:white" href="{{ url("admin/physiotherpy_exercise_categories/print/") }}">
                        <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                    class="print icon"></i><span class="text responsive-text">Print</span></div>
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="column table_top_btn">
            <div class="btn-group pull-right">
                <div class="item no-disable">
                    <a style="color:white" href="{{ url('admin/physiotherpy_exercise_categories/print') }}">
                        <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                    class="print icon"></i><span class="text responsive-text">Print</span></div>
                    </a>
                </div>
            </div>
        </div>
    @endif
    {{-- table Button --}}
@endif
@if(!isset($print))
<div class="column table_top_btn">
    <div class="btn-group pull-right">
        <div class="item no-disable">
            <a style="color:white" href="{{ url("admin/physiotherpy_exercise_categories/create") }}">
                <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button">
                    <i class="plus icon"></i><span class="text responsive-text">Create Category</span>
                </div>
            </a>
        </div>
    </div>
</div>
@endif
<div class="pagination_con paggination_top" role="toolbar">
    <div class="pull-right">
    </div>
</div>

<div class="table-responsive table_sec_row">

    <table class="ui table table_cus_v">
        <thead>
        <tr>
            <th>{{ trans('laralum.title') }}</th>
            @if(!isset($print))
            <th>{{ trans('laralum.options') }}</th>
                @endif
        </tr>
        </thead>
        <tbody>


        @foreach($categories as $category)
            <tr>
                <td>
                    <div class="text">
                        {{ $category->title }}
                    </div>
                </td>
                @if(!isset($print))
                <td>
                    <div class="row">

                        <div class="col-md-6">


                            <div class="ui {{ Laralum::settings()->button_color }} top icon left pointing dropdown button">
                                <i class="configure icon"></i>
                                <div class="menu">
                                    <div class="header">{{ trans('laralum.editing_options') }}</div>
                                    <a href="{{ route('Laralum::physiotherpy_exercise_category_edit', ['id' => $category->id]) }}"
                                       class="item no-disable">
                                        <i class="edit icon"></i>
                                        {{ trans('laralum.physiotherpy_exercise_categories_edit') }}
                                    </a>


                                </div>
                            </div>


                        </div>

                        <div class="col-md-6">
                            <form method="post"
                                  action="{{route('Laralum::physiotherpy_exercise_category_delete',$category->id)}}">
                                {{csrf_field()}}
                                <input type="hidden" name="ddd_method" value="{{$category->id}}"/>
                                <button type="submit"
                                        class="btn btn-success">
                                    <i class="trash bin icon"></i>
                                </button>
                            </form>
                        </div>

                    </div>


                </td>
                @endif

            </tr>
        @endforeach
        </tbody>
    </table>

</div>
