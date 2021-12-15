@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @if($booking->isEditable())
            <a class="section" href="{{ route('Laralum::patients') }}">{{ trans('laralum.patient_list') }}</a>
        @else
            <a class="section"
               href="{{ route('Laralum::archived.patients.list') }}">{{ trans('laralum.archived_patients') }}</a>
        @endif
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.view_token') }}</div>
    </div>
@endsection
@section('title', 'Patient Details')
@section('icon', "pencil")
@section('subtitle', 'Patient Details')

@section('content')
    <link rel="stylesheet" type="text/css" media="screen"
          href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">

    <div class="admin_wrapper signup">

        <div class="ui one column doubling stackable">
            <div class="segment  main_wrapper">
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
                            <li>
                                <div class="active section">Provisional Diagnosis</div>
                                {{--<a class="section"
                                   href="{{ route('Laralum::patient.diagnosis', ['patient_id' => $booking->id]) }}">Provisional Diagnosis</a>--}}
                            </li>


                            <li><a class="section"
                                   href="{{ route('Laralum::patient.treatment', ['patient_id' => $booking->id]) }}">Allot
                                    Treatments</a></li>


                            <li><a class="section"
                                   href="{{ route('Laralum::patient.diet-chart', ['patient_id' => $booking->id]) }}">Diet
                                    Chart</a></li>
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
                                <li><a
                                       href="{{ route('Laralum::recommend-exercise.assign', ['patient_id' => $booking->id]) }}">Attachments
                                    </a></li>
                                <li>
                            @endif
                                </li>--}}
                        </ul>
                </div>
            </div>
        </div>

        <div class="ui one column doubling stackable">
            {{--  <div>
                  <button onclick="return window.history.go(-1);" class="btn btn-primary ui button blue">
                      Back
                  </button>
              </div>--}}
            <div class="diagnosis_con column admin_basic_detail1">
                <div class="segment form_spacing_inn sp_no">


                    <div class="about_sec signup_bg">

                        {{-- <h3 class="title_3">Diagnosis</h3>--}}

                        @if($booking->isEditable())
                            <form method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="booking_id" value="{{ $booking->id }}"/>
                                <input type="hidden" name="patient_id" value="{{ $patient->id }}"/>
                                @endif
                                <div class="vital-data-wrap1">

                                    <div class="vital-head">
                                        <h2>{{ $booking->getProfile('first_name').' '.$booking->getProfile('last_name')}}</h2>
                                    </div>
                                    <div class="vital-row1 no_mar-top">

                                        <div class="vital-inner">
                                            <label>Provisional Diagnosis</label>
                                            <textarea cols=60 rows=5 name="description" class="form-control" type="text"
                                                      class="form-control"
                                                      value="{{ isset($model->description) ? $model->description: "" }}">{{ isset($model->description) ? $model->description : "" }}</textarea>
                                        </div>
                                        <div class="vital-btn1">
                                            @if($booking->isEditable())
                                                <button id="save-vital"
                                                        class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                            </form>
                    </div>
                </div>
            </div>

            {{--<div class="column">

                <div class="ui very padded segment">
                    @if(count($diagnosis) > 0)
                        <div class="pagination_con" role="toolbar">
                            <div class="pull-right">
                                {!!  \App\Settings::perPageOptions($count)  !!}
                            </div>
                        </div>
                        {{csrf_field()}}
                        <table class="ui table table_cus_v last_row_bdr">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Doctor</th>
                                <th>Actions</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($diagnosis as $row)
                                <tr>
                                    <td>{{ $row->date }}</td>
                                    <td><textarea class="form-control" style="width:100%"
                                                  disabled>{{ $row->description }}</textarea></td>
                                    <td>{{ $row->doctor->name }}</td>
                                    <td>
                                        <form action="{{ route('patient.diagnosis.delete',['id'=>$row->id]) }}"
                                              method="post">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if(method_exists($diagnosis, "links"))
                            <div class="pagination_con main_paggination" role="toolbar">
                                {{ $diagnosis->links() }}
                            </div>
                        @endif
                    @else
                        <div class="ui negative icon message">
                            <i class="frown icon"></i>
                            <div class="content">
                                <div class="header">
                                    {{ trans('laralum.missing_title') }}
                                </div>
                                <p>There are currently no diagnosis data</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>--}}
        </div>

    </div>


















@endsection
