@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Tokens</div>
    </div>
@endsection
@section('title', 'Tokens')
@section('icon', "pencil")
@section('subtitle', 'List of all tokens')
@section('content')
    <div class="ui one column doubling stackable">
        <div class="ui one column doubling stackable">
            <div class="column">
                <div class="ui very padded segment table_header_row table-responsive" id="department_list">

                    @if(count($tokens) > 0)
                        <div class="pagination_con" role="toolbar">
                            <div class="pull-right">
                                {!!  \App\Settings::perPageOptions($count)  !!}
                            </div>
                        </div>
                        {{csrf_field()}}
                        <table class="ui five column table ">
                            <thead>
                            <tr>
                                <th>Actions</th>
                                <th>Token Number</th>
                                <th>Status</th>
                                <th>Patient Id</th>
                                <th>Name of the Person</th>
                                <th>Email ID</th>
                                <th>Contact No.</th>
                                <th>City, State, Country</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tokens as $row)
                                <tr>
                                    <td>
                                        @if(Laralum::loggedInUser()->hasPermission('doctor.tokens'))
                                            <div class="ui  top icon {{ \App\Http\Controllers\Laralum\Laralum::settings()->button_color }} left pointing dropdown button">
                                                <i class="configure icon"></i>
                                                <div class="menu">
                                                    <div class="header">{{ trans('laralum.editing_options') }}</div>

                                                    <a href="{{ route('Laralum::patient.show', ['id' => $row->booking_id]) }}"
                                                       class="item no-disable">
                                                        <i class="fa fa-eye"></i>
                                                        First Visit
                                                    </a>
                                                    <a href="{{ route('Laralum::patient.diagnosis', ['id' => $row->booking_id]) }}"
                                                       class="item no-disable">
                                                        <i class="fa fa-eye"></i>
                                                        Provisional Diagnosis
                                                    </a>
                                                    <a href="{{ url("admin/patient-treatment/".$row->booking_id) }}"
                                                       class="item no-disable">
                                                        <i class="fa fa-eye"></i>
                                                        Allot Treatment
                                                    </a>
                                                    <a href="{{ url("admin/patient-diet-chart/".$row->booking_id) }}"
                                                       class="item no-disable">
                                                        <i class="fa fa-eye"></i>
                                                        Allot Diet
                                                    </a>
                                                    <a href="{{ url("admin/patient/discharge/".$row->booking_id) }}"
                                                       class="item no-disable">
                                                        <i class="fa fa-eye"></i>
                                                        Discharge Patient
                                                    </a>
                                                </div>
                                            </div>
                                        @else
                                            <div class="ui disabled blue icon button">
                                                <i class="lock icon"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $row->token_no }}</td>
                                    <td>{{ $row->getStatusOptions($row->status) }}</td>
                                    <td>{{ $row->booking->getProfile('kid') }}</td>
                                    <td>{{ $row->booking->getProfile('first_name').' '. $row->booking->getProfile('last_name') }}</td>
                                    <td>{{ isset($row->patient->email) ? $row->patient->email : ""}}</td>
                                    <td>{{ $row->booking->getProfile('mobile') ?   $row->booking->getProfile('mobile') : ""}}</td>
                                    <td>{{ $row->booking->getAddress('city')  ? $row->booking->getAddress('city') .','. $row->booking->getAddress('state') .','. $row->booking->getAddress('country'): "" }} </td>
                                    {{--<td>{{ ($row->patient->patient_type == 1)? 'IPD':'OPD' }}</td>--}}
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
                                <p>There are currently no tokens</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection


