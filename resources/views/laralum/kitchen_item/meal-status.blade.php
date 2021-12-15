@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Meal Status</div>
    </div>
@endsection
@section('title', 'Meal Status')
@section('icon', "pencil")
@section('content')
    <div class="ui one column doubling stackable">
        <div class="column">
            <div class="ui very padded segment table_header_row table-responsive" id="department_list">
                <div class="column table_top_btn">
                    <div class="btn-group pull-right">
                        <div class="item no-disable">
                            <a style="color:white" href="{{ url("admin/kitchen-item/meal-status-combined/print") }}">
                                <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button">
                                    <i class="print icon"></i><span class="text responsive-text">Print</span></div>
                            </a>
                            <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                                <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                                <div class="menu">
                                    <a id="clicked" class="item no-disable"
                                       href="{{ url('/admin/kitchen-item/export-meal-status-combined/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}">Export
                                        as CSV
                                    </a>
                                    <a id="clicked" class="item no-disable"
                                       href="{{ url('/admin/kitchen-item/export-meal-status-combined/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}">Export
                                        as PDF
                                    </a>
                                    <a id="clicked" class="item no-disable"
                                       href="{{ url('/admin/kitchen-item/export-meal-status-combined/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}">Export
                                        as Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <table class="ui table table_cus_v last_row_bdr">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Total Patient</th>
                        <th>Had Meal</th>
                        <th>Pending</th>
                        <th>Didn't Come</th>

                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th>BreakFast</th>
                        <td>{{ $breakfast['total_patient'] }}</td>
                        <td>{{ $breakfast['had_meal'] }}</td>
                        <td>{{ $breakfast['pending']  }}</td>
                        <td>{{ $breakfast['not_come'] }}</td>
                    </tr>
                    <tr>
                        <th>Lunch</th>
                        <td>{{ $lunch['total_patient'] }}</td>
                        <td>{{ $lunch['had_meal'] }}</td>
                        <td>{{ $lunch['pending']  }}</td>
                        <td>{{ $lunch['not_come'] }}</td>

                    </tr>
                    <tr>
                        <th>Post Lunch</th>
                        <td>{{ $post_lunch['total_patient'] }}</td>
                        <td>{{ $post_lunch['had_meal'] }}</td>
                        <td>{{ $post_lunch['pending']  }}</td>
                        <td>{{ $post_lunch['not_come'] }}</td>

                    </tr>
                    <tr>
                        <th>Dinner</th>
                        <td>{{ $dinner['total_patient'] }}</td>
                        <td>{{ $dinner['had_meal'] }}</td>
                        <td>{{ $dinner['pending']  }}</td>
                        <td>{{ $dinner['not_come'] }}</td>
                    </tr>
                    <tr class="last">
                        <th class="no_bdr_btm">Special</th>
                        <td>{{ $special['total_patient'] }}</td>
                        <td>{{ $special['had_meal'] }}</td>
                        <td>{{ $special['pending']  }}</td>
                        <td>{{ $special['not_come'] }}</td>
                    </tr>
                    </tbody>
                </table>

                <div class="column table_top_btn">
                    <div class="btn-group pull-right">
                        <div class="item no-disable">
                            <a style="color:white" href="{{ url("admin/kitchen-item/meal-status/print") }}">
                                <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button">
                                    <i class="print icon"></i><span class="text responsive-text">Print</span></div>
                            </a>
                            <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                                <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                                <div class="menu">
                                    <a id="clicked" class="item no-disable"
                                       href="{{ url('/admin/kitchen-item/export-meal-status/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}">Export
                                        as CSV
                                    </a>
                                    <a id="clicked" class="item no-disable"
                                       href="{{ url('/admin/kitchen-item/export-meal-status/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}">Export
                                        as PDF
                                    </a>
                                    <a id="clicked" class="item no-disable"
                                       href="{{ url('/admin/kitchen-item/export-meal-status/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}">Export
                                        as Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <table class="ui table table_cus_v last_row_bdr span_mr_30">
                    <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>UHID</th>
                        <th>Breakfast</th>
                        <th>Lunch</th>
                        <th>Post Lunch</th>
                        <th>Dinner</th>
                        <th>Special</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($patients as $patient)
                        <tr>
                            <td>{{ $patient->userProfile->first_name.' '.$patient->userProfile->last_name }}</td>
                            <td>{{ $patient->getProfile('uhid') }}</td>
                            <td>{{ $patient->getDietStatus(\App\DietChartItems::TYPE_BREAKFAST) }}</td>
                            <td>{{ $patient->getDietStatus(\App\DietChartItems::TYPE_LUNCH) }}</td>
                            <td>{{ $patient->getDietStatus(\App\DietChartItems::TYPE_POST_LUNCH) }}</td>
                            <td>{{ $patient->getDietStatus(\App\DietChartItems::TYPE_DINNER) }}</td>
                            <td>{{ $patient->getDietStatus(\App\DietChartItems::TYPE_SPECIAL) }}</td>
                            <td>
                                <div id="book-table"  class="ui  top icon blue left pointing dropdown button">
                                    <i class="configure icon"></i>
                                    <div class="menu">
                                        <div class="header">{{ trans('laralum.editing_options') }}</div>
                                        <a href="{{ url('admin/diet-chart/'.$patient->id) }}" class="item no-disable">
                                            <i class="edit icon"></i>
                                            Daily Diet Status
                                        </a>
                                    </div>
                                </div>
                                {{--  @else
                                      <div class="ui disabled blue icon button">
                                          <i class="lock icon"></i>
                                      </div>
                                  @endif--}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection


