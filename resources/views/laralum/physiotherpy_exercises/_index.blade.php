 @if(!isset($print))
                <div class="column table_top_btn">
                    <div class="btn-group pull-right">
                        <div class="item no-disable">
                            <a style="color:white" href="{{ url("admin/physiotherpy_exercises/create") }}">
                                <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button">
                                    <i class="plus icon"></i><span class="text responsive-text">Create Exercises</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                @if(!isset($print))
                    <div class="column table_top_btn">
                        <div class="btn-group pull-right">
                            <div class="item no-disable">
                                <a style="color:white" href="{{ url("admin/physiotherpy_exercises/print") }}">
                                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                                class="print icon"></i><span class="text responsive-text">Print</span></div>
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
                            <th>{{ trans('laralum.physiotherpy_exercises_category') }}</th>

                            <th>{{ trans('laralum.physiotherpy_exercises_description') }}</th>
                            <th>{{ trans('laralum.physiotherpy_exercises_name_of_exercise') }}</th>
                            @if(!isset($print))
                            <th>{{ trans('laralum.options') }}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>


                        @foreach($exercises as $exercise)

                            @if(!empty($exercise))
                            <tr>
                                <td>
                                    <div class="text">
                                        {{ $exercise->getCategory()->title }}
                                    </div>
                                </td>

                                <td>
                                    <div class="text">
                                        {{ $exercise->name_of_excercise }}
                                    </div>
                                </td>

                                <td>
                                    <div class="text">
                                        {{ strip_tags($exercise->description) }}
                                    </div>
                                </td>
                                @if(!isset($print))
                                <td>
                                    <div class="ui {{ Laralum::settings()->button_color }} top icon left pointing dropdown button">
                                        <i class="configure icon"></i>
                                        <div class="menu">
                                            <div class="header">{{ trans('laralum.editing_options') }}</div>
                                            <a href="{{ route('Laralum::physiotherpy_exercise_edit', ['id' => $exercise->id]) }}"
                                               class="item no-disable">
                                                <i class="edit icon"></i>
                                                {{ trans('laralum.physiotherpy_exercise_categories_edit') }}
                                            </a>

                                            <form method="post" action="{{route('Laralum::physiotherpy_exercise_delete',$exercise->id)}}">
                                                <input type="hidden" name="value_method" value="{{$exercise->id}}" />
                                               {{csrf_field()}}
                                                <button type="submit" class="my-delete-button">
                                                    <i class="trash bin icon"></i>
                                                    {{ trans('laralum.physiotherpy_exercise_categories_delete') }}
                                                </button>
                                            </form>
                                            </a>

                                        </div>
                                    </div>


                                </td>
                                @endif

                            </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>

                </div>