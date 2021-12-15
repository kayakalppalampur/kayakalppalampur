@if(!isset($print))
    <div class="column table_top_btn">
        <div class="btn-group pull-right">
            <div class="item no-disable">
                <a style="color:white" href="{{ url("admin/lab-test/add") }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button">
                        <i class="plus icon"></i><span class="text responsive-text">Add Lab Tests</span>
                    </div>
                </a>
                <a style="color:white" href="{{ url("admin/lab-tests/print/".'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                class="print icon"></i><span class="text responsive-text">Print</span></div>
                </a>
                <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                    <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                    <div class="menu">
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/lab-tests/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as CSV
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/lab-tests/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as PDF
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/lab-tests/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if(count($lab_tests) > 0)
    @if(!isset($print))
    @if(isset($count))
        {{csrf_field()}}
        <div class="pagination_con paggination_top" role="toolbar">
            <div class="pull-right">
                {!!  \App\Settings::perPageOptions(\App\LabTest::count())  !!}
            </div>
        </div>
    @endif
    @endif
    <div class="table_outer">
        <table class="ui table table_cus_v last_row_bdr">
            <thead>
            <tr>
                <th>Name</th>
                <th>Department</th>
                <th>Price</th>
                <th>Duration (in Minutes)</th>
                <th>Category</th>
                <th>Internal/External</th>
                @if(!isset($print))
                    <th>Actions</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @foreach($lab_tests as $lab_test)
                <tr>
                    <td>{{ $lab_test->name }}</td>
                    <td>{{ isset($lab_test->department->title) ? $lab_test->department->title : 'All' }}</td>
                    <td>{{ $lab_test->price }}</td>
                    <td>{{ $lab_test->duration }}</td>
                    <td>{{ $lab_test->category_id != null ? \App\LabTest::getCategoryOptions($lab_test->category_id) : ""}}</td>
                    <td>{{ $lab_test->type != null ? $lab_test->getTypeOptions($lab_test->type) : "Internal" }}</td>
                    @if(!isset($print))
                        <td>
                            <div id="book-table"  class="ui  top icon blue left pointing dropdown button">
                                <i class="configure icon"></i>
                                <div class="menu">
                                    @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.lab_tests'))
                                        <div class="header">{{ trans('laralum.editing_options') }}</div>
                                        <a href="{{ route('Laralum::lab-test.edit', ['id' => $lab_test->id]) }}"
                                           class="item no-disable">
                                            <i class="edit icon"></i>
                                            {{ trans('laralum.edit_lab_test') }}
                                        </a>
                                    @endif
                                    @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.lab_tests'))
                                        <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                        <a href="{{ route('Laralum::lab-test.delete', ['id' => $lab_test->id]) }}"
                                           class="item no-disable">
                                            <i class="trash bin icon"></i>
                                            {{ trans('laralum.delete_lab_test') }}
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
    </div>
    @if(!isset($print))
    @if(method_exists($lab_tests, "links"))
        <div class="pagination_con main_paggination" role="toolbar">
            {{ $lab_tests->links() }}
        </div>
    @endif
    @endif
@else
    <div class="ui negative icon message">
        <i class="frown icon"></i>
        <div class="content">
            <div class="header">
                {{ trans('laralum.missing_title') }}
            </div>
            <p>There are currently no Lab Tests added for the selected date</p>
        </div>
    </div>
@endif