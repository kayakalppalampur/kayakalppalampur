@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @if($booking->isEditable())
            <a class="section" href="{{ route('Laralum::patients') }}">{{ trans('laralum.patient_list') }}</a>
        @else
            <a class="section" href="{{ route('Laralum::archived.patients.list') }}">{{ trans('laralum.archived_patients') }}</a>
        @endif
        <i class="right angle icon divider"></i>
        <div class="active section">Booking List</div>
    </div>
@endsection
@section('title', 'Patients')
@section('icon', "pencil")
@section('subtitle', 'List of all patients')
@section('content')
    <div class="ui one column doubling stackable grid container">
        <div class="column">
            <section class="booking_filter booking_search_patient ui padded segment">
                <div class="row">
                    <div class="col-md-12">
                        <div class="about_sec white_bg signup_bg">
                            <div class="patient_head2">
                                <h3 class="title_3">SEARCH PATIENT</h3>
                                <h4>(Through Anyone Option)</h4>
                            </div>
                            <form id="bookingFilter" action="{{ route('Laralum::patients') }}" method="POST">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <input class="user_namer form-control required" type="text" id="filter_first_name" value="{{ @$_REQUEST['filter_patient_id'] }}" name="filter_patient_id" placeholder="Patient Id" autofocus >
                                </div>
                                <div class="form-group">
                                    <input class="user_namer form-control required" type="text" id="filter_name" value="{{ @$_REQUEST['filter_name'] }}" name="filter_name" placeholder="Name" autofocus>
                                </div>
                                {{--<div class="form-group">
                                    <input class="user_last form-control required" type="text" id="filter_last_name" value="{{ @$_REQUEST['filter_last_name'] }}" name="filter_last_name" placeholder="Last Name">
                                </div>--}}
                                <div class="form-group">
                                    <input class="user_email form-control required" type="email" id="filter_email" value="{{ @$_REQUEST['filter_email'] }}" name="filter_email" placeholder="Email Id">
                                </div>
                                <div class="form-group">
                                    <input class="user_password form-control required" type="text" name="filter_mobile" value="{{ @$_REQUEST['filter_mobile'] }}" id="filter_mobile" placeholder="Mobile Number">
                                </div>
                                <div class="form-button_row"><button class="ui button no-disable {{ \App\Http\Controllers\Laralum\Laralum::settings()->button_color }}">Search</button></div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="column">

            <div class="ui very padded segment">
                @if(count($tokens) > 0)
                    <div class="pagination_con" role="toolbar">
                        <div class="pull-right">
                            {!!  \App\Settings::perPageOptions(count($tokens))  !!}
                        </div>
                    </div>
                    {{csrf_field()}}
                    <table class="ui five column table ">
                        <thead>
                        <tr>
                            <th>Patient Id</th>
                            <th>Name of the Person</th>
                            <th>Email ID</th>
                            <th>Contact No. </th>
                            <th>City, State, Country </th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($tokens as $row)
                            <tr>
                                <td>{{ $row->booking->getProfile('kid') }}</td>
                                <td>{{ $row->booking->getProfile('first_name').' '. $row->booking->getProfile('last_name') }}</td>
                                <td>{{ isset($row->patient->email) ? $row->patient->email : ""}}</td>
                                <td>{{ $row->booking->getProfile('mobile') ?   $row->booking->getProfile('mobile') : ""}}</td>
                                <td>{{ $row->booking->getAddress('city')  ? $row->booking->getAddress('city') .','. $row->booking->getAddress('state') .','. $row->booking->getAddress('country'): "" }} </td>
                                {{--<td>{{ ($row->patient->patient_type == 1)? 'IPD':'OPD' }}</td>--}}
                                <td>
                                    @if(Laralum::loggedInUser()->hasPermission('bookings.show'))
                                        <div class="ui  top icon {{ \App\Http\Controllers\Laralum\Laralum::settings()->button_color }} left pointing dropdown button">
                                            <i class="configure icon"></i>
                                            <div class="menu">
                                                <div class="header">{{ trans('laralum.editing_options') }}</div>

                                                <a href="{{ route('Laralum::patient.show', ['id' => $row->patient_id]) }}" class="item no-disable">
                                                    <i class="fa fa-eye"></i>
                                                    First Visit
                                                </a>
                                                        <a class="section"
                                                           href="{{ route('Laralum::patient.vital_data', ['token_id' => $row->patient_id]) }}">Vital
                                                            Data</a>

                                                        <a class="section"
                                                           href="{{ route('Laralum::patient_lab_test.index', ['patient_id' => $row->patient_id]) }}">Lab
                                                            Tests</a><a href="{{ route('Laralum::patient.diagnosis', ['id' => $row->patient_id]) }}" class="item no-disable">
                                                    <i class="fa fa-eye"></i>
                                                    Provisional Diagnosis
                                                </a>
                                                <a href="{{ url("admin/patient-treatment/".$row->patient_id) }}" class="item no-disable">
                                                    <i class="fa fa-eye"></i>
                                                    Allot Treatment
                                                </a>
                                                <a href="{{ url("admin/patient-diet-chart/".$row->patient_id) }}" class="item no-disable">
                                                    <i class="fa fa-eye"></i>
                                                    Allot Diet
                                                </a>
                                                <a href="{{ url("admin/patient/discharge/".$row->patient_id) }}" class="item no-disable">
                                                    <i class="fa fa-eye"></i>
                                                    Discharge Patient
                                                </a>
                                                @if(Laralum::loggedInUser()->hasPermission('patients.delete'))
                                                    <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                                    <a href="{{ route('Laralum::patients.delete', ['id' => $row->patient->id]) }}" class="item no-disable">
                                                        <i class="trash bin icon"></i>
                                                        Cancel Booking
                                                    </a>
                                                    {{--{!! Form::open(['style' => 'display: inline-block;', 'method' => 'DELETE', 'route' => ['Laralum::patients.destroy', $row->patient->id]]) !!}
                                                    {!! Form::submit('Delete Booking', array('class' => 'btn btn-xs btn-danger')) !!}
                                                    {!! Form::close() !!}--}}
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="ui disabled blue icon button">
                                            <i class="lock icon"></i>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @if(method_exists($tokens, "links"))
                    <div class="pagination_con" role="toolbar">
                        <div class="pull-right">
                            {{ $tokens->links() }}
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
                            <p>There are currently no patients</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection


