@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Follow up List</div>
    </div>
@endsection
@section('title', 'Follow ups')
@section('icon', "pencil")
@section('subtitle', 'List of all followups')
@section('content')
    <div class="ui one column doubling stackable">
            <div class="column">
                <div class="ui very padded segment table_header_row" id="department_list">
                    <div class="column table_top_btn">
                        <div class="btn-group pull-right">
                            <div class="item no-disable">
                                <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                                    <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                                    <div class="menu">
                                        <a id="clicked" class="item no-disable"
                                           href="{{ url('/admin/follow-ups/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}">Export
                                            as CSV
                                        </a>
                                        <a id="clicked" class="item no-disable"
                                           href="{{ url('/admin/follow-ups/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}">Export
                                            as PDF
                                        </a>
                                        <a id="clicked" class="item no-disable"
                                           href="{{ url('/admin/follow-ups/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}">Export
                                            as Excel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(count($followups) > 0)
                        <div class="pagination_con paggination_top" role="toolbar">
                            <div class="pull-right">
                                {!!  \App\Settings::perPageOptions($count)  !!}
                            </div>
                        </div>
                        {{csrf_field()}}
                        <table class="ui table table_cus_v last_row_bdr">
                            <thead>
                            <tr>
                                <th>Patient Id</th>
                                <th>Follow Up Date</th>
                                <th>Name of the Person</th>
                                <th>Email ID</th>
                                <th>Contact No.</th>
                                <th>City, State, Country</th>
                                {{--<th>Actions</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($followups as $row)
                                @if(isset($row->patient->patient->userProfile->mobile))
                                    <tr>
                                        <td>{{ $row->patient->patient->userProfile->kid }}</td>
                                        <td>{{ date('d-m-Y',strtotime($row->followup_date)) }}</td>
                                        <td>{{ $row->patient->patient->name }}</td>
                                        <td>{{ isset($row->patient->patient->email) ? $row->patient->patient->email : ""}}</td>
                                        <td>{{ isset($row->patient->patient->userProfile->mobile) ?  $row->patient->patient->userProfile->mobile : ""}}</td>
                                        <td>{{ isset($row->patient->patient->address->city ) ? $row->patient->patient->address->city .','. $row->patient->patient->address->state .','. $row->patient->patient->address->country : "" }} </td>
                                        {{--<td>{{ ($row->patient_type == 1)? 'IPD':'OPD' }}</td>--}}
                                        {{-- <td>
                                             @if(Laralum::loggedInUser()->hasPermission('followups.show'))
                                                 <div id="book-table" class="ui  top icon blue left pointing dropdown button">
                                                     <i class="configure icon"></i>
                                                     <div class="menu">
                                                         <div class="header">{{ trans('laralum.editing_options') }}</div>
                                                         <a href="{{ route('Laralum::followup.show', ['user_id' => $row->user_id]) }}" class="item no-disable">
                                                             <i class="fa fa-eye"></i>
                                                             Follow up Details
                                                         </a>
                                                         @if(Laralum::loggedInUser()->hasPermission('followups.delete'))
                                                             <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                                             <a href="{{ route('Laralum::followups.delete', ['id' => $row->user_id]) }}" class="item no-disable">
                                                                 <i class="trash bin icon"></i>
                                                                 Delete Follow up
                                                             </a>
                                                             --}}{{--{!! Form::open(['style' => 'display: inline-block;', 'method' => 'DELETE', 'route' => ['Laralum::followups.destroy', $row->id]]) !!}
                                                             {!! Form::submit('Delete Follow up', array('class' => 'btn btn-xs btn-danger')) !!}
                                                             {!! Form::close() !!}--}}{{--
                                                         @endif
                                                     </div>
                                                 </div>
                                             @else
                                                 <div class="ui disabled blue icon button">
                                                     <i class="lock icon"></i>
                                                 </div>
                                             @endif
                                         </td>--}}
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                        @if(method_exists($followups, "links"))
                            <div class="pagination_con main_paggination" role="toolbar">
                                 {{ $followups->links() }}
                            </div>
                        @endif
                    @else
                        <div class="ui negative icon message">
                            <i class="frown icon"></i>
                            <div class="content">
                                <div class="header">
                                    {{ $search == true ? $error : trans('laralum.missing_title') }}
                                </div>
                                <p>There are currently no followups</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
    </div>
@endsection


