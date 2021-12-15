@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @if($booking->isEditable())
            <a class="section" href="{{ route('Laralum::patients') }}">{{ trans('laralum.patient_list') }}</a>
        @else
            <a class="section" href="{{ route('Laralum::archived.patients.list') }}">{{ trans('laralum.archived_patients') }}</a>
        @endif
        <i class="right angle icon divider"></i>
        <div class="active section">Patient's Diet Chart</div>
    </div>
@endsection
@section('title', 'Patient\'s Diet Chart')
@section('icon', "pencil")
@section('subtitle', '')
@section('content')
    <div class="ui one column doubling stackable">
        <div class="column admin_basic_detail1 admin_wrapper">
            <div class="segment main_wrapper">
                <div class="ui breadcrumb steps clearfix">
                    <ul>
                        <li><a class="section"
                               href="{{ route('Laralum::patient.show', ['token_id' => $booking->id]) }}">Personal
                                Details</a>
                        </li>
                        {{-- <a class="section" href="{{ route('Laralum::tokens') }}">Case History</a>
                         <i class="right angle icon divider"></i>--}}
                        <li><a class="section"
                               href="{{ route('Laralum::patient.vital_data', ['token_id' => $booking->id]) }}">Vital
                                Data</a>
                        </li>
                        <li><a class="section"
                               href="{{ route('Laralum::patient_lab_test.index', ['patient_id' => $booking->id]) }}">Lab
                                Tests</a></li>
                        <li><a class="section"
                               href="{{ route('Laralum::patient.diagnosis', ['patient_id' => $booking->id]) }}">Provisional Diagnosis</a>
                        </li>
                        <li>  <a class="section" href="{{ route('Laralum::patient.treatment', ['patient_id' => $booking->id]) }}">Allot Treatments</a></li>

                        <li>  <div class="active section">Diet Chart</div></li>
                        <li>
                            <a class="section"
                               href="{{ route('Laralum::discharge.patient', ['token_id' => $booking->id]) }}">Discharge
                                Patient</a></li>

                        <li><a class=" section"
                               href="{{ route('Laralum::attachments', ['booking_id' => $booking->id]) }}">Attachments
                            </a></li>

                        <li><a class="section"
                               href="{{ route('Laralum::summary', ['id' => $booking->id]) }}">Summary</a></li>

                        {{--@php
                            $dept_model=\App\Department::where('title','like',"%Physiotherapy%")->first();
                        @endphp

                        @if(\Auth::user()->isAdmin()||(!empty($dept_model) && \Auth::user()->department->department_id==$dept_model->id))
                            <li><a class=" section"
                                   href="{{ route('Laralum::recommend-exercise.assign', ['patient_id' => $booking->id]) }}">Attachments
                                </a></li>
                            <li>
                        @endif--}}
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div id="show_details">
    <div class="admin_wrapper signup">
        <div class="main_wrapper">
            <div class="ui one column doubling stackable">
                <div class="column admin_basic_detail1">
                    <div class="ui very padded segment sp_no">

                        <div class="column2 table_top_btn signup_bg">
                            <div class="vital-head">Patient Diet chart for {{ $booking->getProfile('first_name').' '.$booking->getProfile('last_name')}}</div>

                            <div class="btn-group pull-right">
                                @if ($booking->isEditable())
                                    <a class="btn btn-primary ui button {{ \App\Http\Controllers\Laralum\Laralum::settings()->button_color }}" href="{{ url(("admin/add-patient-diet-chart/".$booking->id)) }}">New Diet Plan</a>
                                @endif
                            </div>
                        </div>

{{--                        <div  class="page_title">

                            <div class="pull-right">
                                --}}{{--       <a class="btn btn-primary ui button blue" href="{{ url('admin/token/first-visit/'.$patient->id) }}">First Visit</a>--}}{{--
                            </div>
                        </div>--}}

                        <div class="about_sec1 signup_bg discharge_form no_mar-top">
                            <!--  <h3 class="title_3"></h3> -->
                            <div class="discharge-form-row table-responsive patient-diet-outer">
                               <div class="diet-chart-inner">
                                   <div class="pagination_con" role="toolbar">
                                       <div class="pull-right">
                                        @if($data_count > 0)
                                           {!!  \App\Settings::perPageOptions($data_count)  !!}
                                            @endif
                                       </div>
                                   </div>
                                   <table class="ui table table_cus_v last_row_bdr">
                                    <tr>
                                        <th> </th>
                                        @foreach(\App\DietChartItems::getTypeOptions() as $id => $type)
                                            <th> {{ $type }} </th>
                                        @endforeach
                                        <th> Notes </th>
                                        <th> Actions </th>
                                    </tr>
                                    @forelse($data as $date => $diet)
                                        <tr>
                                        <th>{{ $date }}</th>
                                        @foreach(\App\DietChartItems::getTypeOptions() as $id => $type)
                                            <td>
                                                @foreach($diet[$id] as $diet_item)
                                                    <p>{{ $diet_item }}</p>
                                                @endforeach
                                            </td>
                                        @endforeach
                                            <td> {{ $diet['notes'] }} </td>
                                            <td>
                                                @if(\App\DietChart::isEditable($diet['id'], $date))
                                                    <a title="Edit" href="{{ url("admin/patient/edit-diet-chart/".$diet['id']) }}"><i class="fa fa-pencil"></i> </a>
                                                    <a title="Delete" href="{{ route('Laralum::diet_chart.delete', ['id' => $diet['id']]) }}" class="item no-disable">
                                                        <i class="trash bin icon"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                       @empty
                                           <tr>
                                               <td colspan="6">
                                           <div class="ui negative icon message">
                                               <i class="frown icon"></i>
                                               <div class="content">
                                                   <div class="header">
                                                       There are currently no diet assigned
                                                   </div>
                                               </div>
                                           </div></td>
                                        </tr>
                                    @endforelse

                                   {{-- <tr>
                                    <th>
                                    </th>
                                        @foreach(\App\DietChartItems::getTypeOptions() as $id => $type)
                                            <th>
                                                {{ $type }}
                                            </th>
                                         @endforeach
                                    </tr>
                                    @for($i = 0; $i < 30; $i++)
                                    <tr id="row_{{ $i }}" @if($i > 7) style="display: none;" @endif>
                                        <th>
                                            @php $date = date("Y-m-d", strtotime("+".$i." days"))  @endphp
                                            {{ date("d M, Y", strtotime($date)) }}
                                        </th>
                                        @foreach(\App\DietChartItems::getTypeOptions() as $id => $type)
                                            <td>
                                                @php $items =  \App\DietChartItems::getItems($patient->id, $id, $date)  @endphp
                                                @if($items != null)
                                                @foreach($items as $item)
                                                    <p>{{ $item->item->name }}</p>
                                                    @endforeach
                                                @endif

                                            </td>
                                        @endforeach
                                    </tr>
                                     @endfor--}}

                                </table></div>
                                <div class="pagination_con main_paggination" role="toolbar">
                                     {{ $data->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("js")
<script>
    $("#seven-days").click(function () {
        for (var i = 0; i < 30; i++) {
            if (i > 7)
                $("#row_"+i).hide();
            else
                $("#row_"+i).show();
        }
    });

    $("#fifteen-days").click(function () {
        for (var i = 0; i < 30; i++) {
            if (i > 15)
                $("#row_"+i).hide();
            else
                $("#row_"+i).show();
        }
    });

    $("#twenty-days").click(function () {
        for (var i = 0; i < 30; i++) {
            if (i > 20)
                $("#row_"+i).hide();
            else
                $("#row_"+i).show();
        }
    });

    $("#thirty-days").click(function () {
        for (var i = 0; i < 30; i++) {
            $("#row_"+i).show();
        }
    });
    
</script>
@endsection
