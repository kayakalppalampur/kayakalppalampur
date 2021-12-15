@if(!isset($print))
    <div class="column table_top_btn">
        <div class="btn-group pull-right">
            <div class="item no-disable">
                @if(isset($archived))
                    <a style="color:white"
                       href="{{ url("admin/archived-patient-list/print/".'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">
                        <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                    class="print icon"></i><span class="text responsive-text">Print</span></div>
                    </a>
                @else
                    @if (!empty($future))
                        <a style="color:white"
                           href="{{ url("admin/future-bookings/print".'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">
                            <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                        class="print icon"></i><span class="text responsive-text">Print</span></div>
                        </a>
                    @elseif(!empty($pending))
                        <a style="color:white"
                           href="{{ url("admin/pending-bookings/print".'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">
                            <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                        class="print icon"></i><span class="text responsive-text">Print</span></div>
                        </a>
                    @elseif(!empty($ipd))
                        <a style="color:white"
                           href="{{ url("admin/ipd-bookings/print".'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">
                            <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                        class="print icon"></i><span class="text responsive-text">Print</span></div>
                        </a>
                    @else
                        <a style="color:white"
                           href="{{ url("admin/bookings/print/".'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">
                            <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                        class="print icon"></i><span class="text responsive-text">Print</span></div>
                        </a>
                    @endif
                @endif

                @if(isset($archived))
                    <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                        <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                        <div class="menu">
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/archived-bookings/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                                as CSV
                            </a>
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/archived-bookings/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                                as PDF
                            </a>
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/archived-bookings/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                                as Excel
                            </a>
                        </div>
                    </div>
                @elseif (!empty($pending))
                    <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                        <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                        <div class="menu">
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/pending-bookings/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}&s={{@json_encode($search_data)}}">Export
                                as CSV
                            </a>
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/pending-bookings/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}&s={{@json_encode($search_data)}}">Export
                                as PDF
                            </a>
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/pending-bookings/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}&s={{@json_encode($search_data)}}">Export
                                as Excel
                            </a>
                        </div>
                    </div>
                @elseif (!empty($future))
                    <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                        <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                        <div class="menu">
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/future-bookings/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}&s={{@json_encode($search_data)}}">Export
                                as CSV
                            </a>
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/future-bookings/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}&s={{@json_encode($search_data)}}">Export
                                as PDF
                            </a>
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/future-bookings/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}&s={{@json_encode($search_data)}}">Export
                                as Excel
                            </a>
                        </div>
                    </div>
                @elseif (!empty($ipd))
                    <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                        <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                        <div class="menu">
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/ipd-bookings/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}&s={{@json_encode($search_data)}}">Export
                                as CSV
                            </a>
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/ipd-bookings/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}&s={{@json_encode($search_data)}}">Export
                                as PDF
                            </a>
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/ipd-bookings/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}&s={{@json_encode($search_data)}}">Export
                                as Excel
                            </a>
                        </div>
                    </div>
                @else
                    <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                        <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                        <div class="menu">
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/bookings/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}&s={{@json_encode($search_data)}}">Export
                                as CSV
                            </a>
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/bookings/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}&s={{@json_encode($search_data)}}">Export
                                as PDF
                            </a>
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/bookings/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}&s={{@json_encode($search_data)}}">Export
                                as Excel
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endif

@php
    $data_action =  url('admin/bookings?page='.@$_REQUEST['page'].'&per_page='.@$_REQUEST['per_page']);
    if (\Route::getCurrentRoute()->getName() == 'Laralum::archived.patients.list' || !empty($archived)) {
        $data_action =  url('admin/archived-patient-list?page='.@$_REQUEST['page'].'&per_page='.@$_REQUEST['per_page']);
    }

    if (!empty($future)) {
        $data_action =  url('admin/future-patient-list?page='.@$_REQUEST['page'].'&per_page='.@$_REQUEST['per_page']);
    }

    if (!empty($ipd)) {
        $data_action =  url('admin/ipd-bookings?page='.@$_REQUEST['page'].'&per_page='.@$_REQUEST['per_page']);
    }

    if (!empty($pending)) {
           $data_action =  url('admin/pending-search?page='.@$_REQUEST['page'].'&per_page='.@$_REQUEST['per_page']);
    }


@endphp
@if(count($models) > 0)
    @if(!isset($print))
        <div class="pagination_con paggination_top" role="toolbar">
            <div class="pull-right">
                {!!  \App\Settings::perPageOptions($count)  !!}
            </div>
        </div>
    @endif

    <div class="pagination_con paggination_top" role="toolbar">
        <div class="pull-left">
            <div class="col-md-12">
                <b>Males:</b> {{ $males }}
                <b>Females:</b> {{ $females }}
            </div>
        </div>
    </div>
    <div class="table_outer">
    <table class="ui table table_cus_v last_row_bdr"
           data-action="{{ $data_action }}">
        <thead>
            <tr>
                @if(!isset($print))
                    <th>Actions</th>
                @endif
                <th>UHID</th>
                @if(!isset($pending))
                    @if(!isset($archived) && !isset($future))
                        <th>Registration Id</th>
                    @endif
                    <th>Booking Id</th>

                @endif
                {{--<th>Patient Type</th>--}}
                <th>Patient Name</th>
                {{--<th>Name of the Person</th>--}}
                {{--<th>Email ID</th>--}}
                <th>Contact No.</th>
                <th>City, State, Country</th>
                @if(isset($future))
                    <th>Check In Date</th>
                    <th>Check Out Date</th>
                    <th>Building/floor</th>
                @elseif(isset($ipd))
                    <th>Accommodation Details</th>
                @endif
                {{--<th>Created On</th>--}}
                {{--<th>Booking Status</th>--}}
                @if(isset($future) || isset($ipd))
                    <th>Accommodation Status</th>
                @endif
            </tr>
        </thead>
        <tbody>
        @if(!isset($print))
            <tr class="table_search">
                <td class="icons">
                    &nbsp;
                </td>
                <td class="icons">

                    <input type="text" class="table_search" id="table_search_uhid"
                           value="{{ @$search_data['uhid'] }}" name="slug" placeholder="search uh id"/> <i
                            class="fa fa-filter"></i>
                </td>

                @if(!isset($pending))
                    @if(!isset($archived) && !isset($future))
                        <td class="icons">
                            <input type="text" class="table_search" id="table_search_kid"
                                   value="{{ @$search_data['kid'] }}" name="slug" placeholder="search patient id"/> <i
                                    class="fa fa-filter"></i>
                        </td>
                    @endif
                    <td class="icons">

                        <input type="text" class="table_search" id="table_search_booking_id"
                               value="{{ @$search_data['booking_id'] }}" name="slug"
                               placeholder="search Booking id"/> <i
                                class="fa fa-filter"></i>
                    </td>

                @endif

                {{--<td class="icons">
                    <select class="table_search" id="table_search_patient_type" name="patient_type"
                            value="{{ @$search_data['patient_type'] }}">
                        <option value="">All Patient Types</option>
                        <option value="{{ \App\Booking::PATIENT_TYPE_IPD }}" {{ @$search_data['patient_type'] == \App\Booking::PATIENT_TYPE_IPD ? "selected" : "" }}>
                            IPD
                        </option>
                        <option value="{{ \App\Booking::PATIENT_TYPE_OPD}}" {{ @$search_data['patient_type'] == \App\Booking::PATIENT_TYPE_OPD ? "selected" : "" }}>
                            OPD
                        </option>
                    </select><i
                            class="fa fa-filter"></i>
                </td>--}}

                <td class="icons">
                    <input type="text" class="table_search" id="table_search_first_name"
                           value="{{ @$search_data['first_name'] }}"
                           name="slug"
                           placeholder="search name"/> <i
                            class="fa fa-filter"></i>
                </td>
                {{--<td class="icons">
                    <input type="text" class="table_search" id="table_search_email"
                           value="{{ @$search_data['email'] }}"
                           name="slug"
                           placeholder="search email"/> <i
                            class="fa fa-filter"></i>
                </td>--}}
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_mobile"
                           value="{{ @$search_data['mobile'] }}"
                           name="slug"
                           placeholder="search mobile no"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_city"
                           value="{{ @$search_data['city'] }}"
                           name="city"
                           placeholder="search city/state/country"/> <i
                            class="fa fa-filter"></i>
                </td>

                @if(isset($future))
                    <td><input type="text" class="table_search filter_datepicker" id="table_search_check_in_date"
                               name="check_in_date"
                               placeholder="search check in date"
                               value="{{ @$search_data['check_in_date'] }}"/> <i
                                class="fa fa-filter"></i></td>
                    <td><input type="text" class="table_search filter_datepicker" id="table_search_check_out_date"
                               value="{{ @$search_data['check_out_date'] }}"
                               name="check_out_date"
                               placeholder="search check in date"/> <i
                                class="fa fa-filter"></i></td>
                    <td class="icons">
                    </td>
                @elseif(isset($ipd))
                    <td class="icons">
                        {{--  <input type="text" class="table_search" id="table_search_mobile"
                                 value="{{ @$search_data['accomodation'] }}"
                                 name="slug"
                                 placeholder="search accomodation"/> <i
                                  class="fa fa-filter"></i>--}}
                    </td>
                @endif


                {{--<td class="icons">
                    @if(!\Auth::user()->isDoctor())
                        <select class="table_search" id="table_search_status" name="status"
                                value="{{ @$search_data['status'] }}">
                            <option value="">Booking Status</option>
                            <option value="{{ \App\Booking::STATUS_PENDING }}" {{ @$search_data['status'] == \App\Booking::STATUS_PENDING && @$search_data['status'] != "" ? "selected" : "" }}>
                                Pending
                            </option>
                            <option value="{{ \App\Booking::STATUS_COMPLETED}}" {{ @$search_data['status'] == \App\Booking::STATUS_COMPLETED ? "selected" : "" }}>
                                Completed
                            </option>
                        </select><i
                                class="fa fa-filter"></i>
                    @endif
                </td>--}}
                @if(isset($future) || isset($ipd))
                    <td class="icons">
                        @if(!\Auth::user()->isDoctor())
                            <select class="table_search" id="table_search_accommodation_status"
                                    name="accommodation_status"
                                    value="{{ @$search_data['accommodation_status'] }}">
                                <option value="">Accommodation Status</option>
                                <option value="{{ \App\Booking::ACCOMMODATION_STATUS_PENDING}}" {{ @$search_data['accommodation_status'] == \App\Booking::ACCOMMODATION_STATUS_PENDING ? "selected" : "" }}>
                                    Pending
                                </option>
                                <option value="{{ \App\Booking::ACCOMMODATION_STATUS_CONFIRMED}}" {{ @$search_data['accommodation_status'] == \App\Booking::ACCOMMODATION_STATUS_CONFIRMED? "selected" : "" }}>
                                    Confirmed
                                </option>
                            </select><i
                                    class="fa fa-filter"></i>
                        @endif
                    </td>
                @endif

            </tr>
        @endif
        @foreach($models as $row)
            <tr class="{{ $row->getStatusClass() }}">

                {{--<td>{{ ($row->patient_type == 1)? 'IPD':'OPD' }}</td>--}}
                @if(!isset($print))
                    <td>
                        @if(Laralum::loggedInUser()->hasPermission('admin.bookings.list'))
                            @if($row->status == \App\Booking::STATUS_COMPLETED || $row->status == \App\Booking::STATUS_PENDING)
                                @if(\Auth::user()->isDoctor() )
                                    <div id="book-table"
                                         class="ui  top icon {{ \App\Http\Controllers\Laralum\Laralum::settings()->button_color }} left pointing dropdown button">
                                        <i class="configure icon"></i>
                                        <div class="menu">
                                            <div class="header">{{ trans('laralum.editing_options') }}</div>

                                            <a href="{{ route('Laralum::patient.show', ['id' => $row->id]) }}"
                                               class="item no-disable">
                                                <i class="fa fa-eye"></i>
                                                First Visit
                                            </a>

                                            <a
                                                    href="{{ route('Laralum::patient.vital_data', ['token_id' => $row->id]) }}"
                                                    class="item no-disable"><i class="fa fa-eye"></i>Vital
                                                Data</a>

                                            <a
                                                    href="{{ route('Laralum::patient_lab_test.index', ['patient_id' => $row->id]) }}"
                                                    class="item no-disable"><i class="fa fa-eye"></i>Lab
                                                Tests</a>

                                            <a href="{{ route('Laralum::patient.diagnosis', ['id' => $row->id]) }}"
                                               class="item no-disable">
                                                <i class="fa fa-eye"></i>
                                                Provisional Diagnosis
                                            </a>
                                            <a href="{{ route('Laralum::summary', ['id' => $row->id]) }}"
                                               class="item no-disable">
                                                <i class="fa fa-eye"></i>
                                                Summary
                                            </a>
                                            <a href="{{ url("admin/patient-treatment/".$row->id) }}"
                                               class="item no-disable">
                                                <i class="fa fa-eye"></i>
                                                Allot Treatment
                                            </a>
                                            <a href="{{ url("admin/patient-diet-chart/".$row->id) }}"
                                               class="item no-disable">
                                                <i class="fa fa-eye"></i>
                                                Allot Diet
                                            </a>
                                            <a href="{{ url("admin/patient/discharge/".$row->id) }}"
                                               class="item no-disable">
                                                <i class="fa fa-eye"></i>
                                                Discharge Patient
                                            </a>
                                            <a href="{{ route('Laralum::attachments', ['id' => $row->id]) }}"
                                               class="item no-disable">
                                                <i class="fa fa-eye"></i>
                                                Attachments
                                            </a>
                                            @if(Laralum::loggedInUser()->hasPermission('patients.delete') && !\Auth::user()->isPatient())
                                                <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                                @if($row->status != \App\Booking::STATUS_DISCHARGED)
                                                    <a href="{{ route('Laralum::bookings.delete', ['id' => $row->id]) }}"
                                                       class="item no-disable">
                                                        <i class="trash bin icon"></i>
                                                        Cancel Booking
                                                    </a>
                                                    {{--   @else
                                                       <a href="{{ route('Laralum::bookings.delete', ['id' => $row-->id]) }}" class="item no-disable">
                                                           <i class="trash bin icon"></i>
                                                           Delete Booking
                                                       </a>--}}
                                                @endif
                                                {{--{!! Form::open(['style' => 'display: inline-block;', 'method' => 'DELETE', 'route' => ['Laralum::patients.destroy', $row->patient->id]]) !!}
                                                {!! Form::submit('Delete Booking', array('class' => 'btn btn-xs btn-danger')) !!}
                                                {!! Form::close() !!}--}}
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div id="book-table" class="ui  top icon blue left pointing dropdown button">
                                        <i class="configure icon"></i>
                                        <div class="menu">
                                            <div class="header">{{ trans('laralum.editing_options') }}</div>
                                            @php
                                                $route = route('Laralum::booking.show', ['user_id' => $row->id]);

                                                if ($row->patient_type == \App\Booking::PATIENT_TYPE_OPD) {
                                                    $route = route('Laralum::opd.booking.show', ['user_id' => $row->id]);
                                                }

                                                if (isset($future)) {
                                                    $route = route('Laralum::future.booking.show', ['user_id' => $row->id]);
                                                }

                                                if ($row->accommodation_status == \App\Booking::ACCOMMODATION_STATUS_CONFIRMED) {
                                                    $route = route('Laralum::ipd.booking.show', ['user_id' => $row->id]);
                                                }
                                            @endphp

                                            <a href="{{ $route }}"
                                               class="item no-disable">
                                                <i class="fa fa-eye"></i>
                                                Booking Details
                                            </a>

                                            @if(Laralum::loggedInUser()->hasPermission('patients.delete') && !\Auth::user()->isPatient() && $row->status != \App\Booking::STATUS_DISCHARGED)
                                                <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                                <a href="{{ route('Laralum::bookings.delete', ['id' => $row->id]) }}"
                                                   class="item no-disable">
                                                    <i class="trash bin icon"></i>
                                                    {{ $row->isCancelled() ? "Reason for Cancellation" : "Cancel Booking" }}
                                                </a>
                                            @endif
                                            @if(isset($archived))
                                                @if(\Auth::user()->isReception() || \Auth::user()->isAdmin())
                                                    <a href="{{ url('admin/booking/registration/personal_details/'.$row->user_id.'?reregister='.$row->profile_id) }}"
                                                       class="item no-disable">
                                                        <i class="edit icon"></i>
                                                        Re Register
                                                    </a>
                                                @endif
                                            @endif
                                            {{--{!! Form::open(['style' => 'display: inline-block;', 'method' => 'DELETE', 'route' => ['Laralum::bookings.destroy', $row->id]]) !!}
                                            {!! Form::submit('Delete Booking', array('class' => 'btn btn-xs btn-danger')) !!}
                                            {!! Form::close() !!}--}}

                                        </div>
                                    </div>
                                @endif

                            @elseif($row->status == \App\Booking::STATUS_DISCHARGED)

                                <div id="book-table" class="ui  top icon blue left pointing dropdown button">
                                    <i class="configure icon"></i>
                                    <div class="menu">
                                        <div class="header">{{ trans('laralum.editing_options') }}</div>
                                        @php
                                            $route = route('Laralum::booking.show', ['user_id' => $row->id]);

                                            if ($row->patient_type == \App\Booking::PATIENT_TYPE_OPD) {
                                                $route = route('Laralum::opd.booking.show', ['user_id' => $row->id]);
                                            }

                                            if (isset($future)) {
                                                $route = route('Laralum::future.booking.show', ['user_id' => $row->id]);
                                            }

                                            if ($row->accommodation_status == \App\Booking::ACCOMMODATION_STATUS_CONFIRMED) {
                                                $route = route('Laralum::ipd.booking.show', ['user_id' => $row->id]);
                                            }
                                        @endphp

                                        <a href="{{ $route }}"
                                           class="item no-disable">
                                            <i class="fa fa-eye"></i>
                                            Booking Details
                                        </a>

                                        <a href="{{ route('Laralum::archived-summary', ['user_id' => $row->id]) }}"
                                               class="item no-disable">
                                                <i class="fa fa-eye"></i>
                                                Summary
                                            </a>

                                        @if(\Auth::user()->isReception() || \Auth::user()->isAdmin())
                                        

                                            <a href="{{ url('admin/booking/registration/personal_details/'.$row->user_id.'?reregister='.$row->profile_id) }}"
                                               class="item no-disable">
                                                <i class="edit icon"></i>
                                                Re Register
                                            </a>
                                        @endif
                                        {{--{!! Form::open(['style' => 'display: inline-block;', 'method' => 'DELETE', 'route' => ['Laralum::bookings.destroy', $row->id]]) !!}
                                        {!! Form::submit('Delete Booking', array('class' => 'btn btn-xs btn-danger')) !!}
                                        {!! Form::close() !!}--}}

                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="ui disabled blue icon button">
                                <i class="lock icon"></i>
                            </div>
                        @endif
                    </td>
                @endif
                <td>{{ $row->getProfile('uhid') }}</td>
                @if(!isset($pending))
                    @if(!isset($archived) && !isset($future))
                        <td>{{ $row->booking_kid }}</td>
                    @endif
                    <td>{{ $row->booking_id }}</td>
                @endif
                {{--<td>{{ $row->patient_type != null ? $row->getPatientType($row->patient_type) : "OPD"}}</td>--}}
                <td>{{ $row->getProfile('first_name').' '.$row->getProfile('last_name') }}</td>
                {{--<td>{{ isset($row->user->email) ? $row->user->email : ""}}</td>--}}
                <td>{{ $row->getProfile('mobile') ?  $row->getProfile('mobile') : ""}}</td>
                <td>{{ $row->getAddress('city') ? $row->getAddress('city') .','. $row->getAddress('state') .','. $row->getAddress('country') : "" }} </td>

                @if(isset($future))
                    <td>{{ date("d-m-Y", strtotime($row->check_in_date)) }}</td>
                    <td>{{ date("d-m-Y", strtotime($row->check_out_date)) }}</td>
                    <td>{{ $row->building_name }}/{{ $row->building_floor_name }}</td>
                @elseif(isset($ipd))
                    <td>{{ $row->getCurrentAccomodationDetails() }}</td>
                @endif

                {{--<td>{{ date('d-m-Y h:i:s',strtotime($row->created_at)) }}</td>--}}
                {{--<td>{{ !empty($row->status) ? $row->getStatusOptions($row->status) : "Pending" }}</td>--}}
                @if(isset($future) || isset($ipd))
                    <td>{{ $row->accommodationStatus() }}</td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
    @if(method_exists($models, "links"))
        <div class="pagination_con main_paggination" role="toolbar">
            {{ $models->links() }}
        </div>
    @endif


@else
    <div class="table-responsive table_sec_row">
        <table class="ui table table_cus_v last_row_bdr"
               data-action="{{ $data_action }}">
            <thead>
            <tr>
                @if(!isset($print))
                    <th>Actions</th>
                @endif
                <th>UHID</th>
                @if(!isset($pending))
                    @if(!isset($future))
                        <th>Registration Id</th>
                    @endif
                    <th>Booking Id</th>
                @endif
                <th>Patient Name</th>
                {{--<th>Name of the Person</th>--}}
                {{--<th>Email ID</th>--}}
                <th>Contact No.</th>
                <th>City, State, Country</th>
                @if(isset($future))
                    <th>Check In Date</th>
                    <th>Check Out Date</th>
                    <th>Building/floor</th>
                @elseif(isset($ipd))
                    <th>Accommodation Details</th>
                @endif
                {{--<th>Created On</th>--}}
                {{--<th>Booking Status</th>--}}
                @if(isset($future) || isset($ipd))
                    <th>Accommodation Status</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @if(empty($print))
                <tr class="table_search">
                    <td></td>

                    <td class="icons">
                        <input type="text" class="table_search" id="table_search_uhid"
                               value="{{ @$search_data['uhid'] }}"
                               name="slug"
                               placeholder="search uh id"/> <i
                                class="fa fa-filter"></i>
                    </td>
                    @if(!isset($pending))
                        @if(!isset($future))
                            <td class="icons">
                                <input type="text" class="table_search" id="table_search_kid"
                                       value="{{ @$search_data['kid'] }}"
                                       name="kid"
                                       placeholder="search patient id"/> <i
                                        class="fa fa-filter"></i>
                            </td>
                        @endif
                        <td class="icons">
                            <input type="text" class="table_search" id="table_search_booking_id"
                                   value="{{ @$search_data['booking_id'] }}"
                                   name="booking_id"
                                   placeholder="search booking id"/> <i
                                    class="fa fa-filter"></i>
                        </td>

                    @endif

                    <td class="icons">
                        <input type="text" class="table_search" id="table_search_first_name"
                               value="{{ @$search_data['first_name'] }}"
                               name="slug"
                               placeholder="search name"/> <i
                                class="fa fa-filter"></i>
                    </td>
                    <td class="icons">
                        <input type="text" class="table_search" id="table_search_mobile"
                               value="{{ @$search_data['mobile'] }}"
                               name="mobile"
                               placeholder="search mobile no"/> <i
                                class="fa fa-filter"></i>
                    </td>
                    <td class="icons">
                        <input type="text" class="table_search" id="table_search_city"
                               value="{{ @$search_data['city'] }}"
                               name="city"
                               placeholder="search city/state/country"/> <i
                                class="fa fa-filter"></i>
                    </td>
                    @if(isset($future))
                        <td><input type="text" class="table_search filter_datepicker" id="table_search_check_in_date"
                                   name="check_in_date"
                                   placeholder="search check in date"
                                   value="{{ @$search_data['check_in_date'] }}"/> <i
                                    class="fa fa-filter"></i></td>
                        <td><input type="text" class="table_search filter_datepicker" id="table_search_check_out_date"
                                   value="{{ @$search_data['check_out_date'] }}"
                                   name="check_out_date"
                                   placeholder="search check in date"/> <i
                                    class="fa fa-filter"></i></td>
                        <td></td>
                    @elseif(isset($ipd))
                        <td class="icons"></td>
                    @endif

                    {{-- <td class="icons">
                         <select class="table_search" id="table_search_status" name="status"
                                 value="{{ @$search_data['status'] }}">
                             <option value="">Booking Status</option>
                             <option value="{{ \App\Booking::STATUS_PENDING}}" {{ @$search_data['status'] === \App\Booking::STATUS_PENDING && @$search_data['status'] != "" ? "selected" : "" }}>
                                 Pending
                             </option>
                             <option value="{{ \App\Booking::STATUS_COMPLETED}}" {{ @$search_data['status'] == \App\Booking::STATUS_COMPLETED ? "selected" : "" }}>
                                 Completed
                             </option>
                         </select><i
                                 class="fa fa-filter"></i>
                     </td>--}}
                    @if(isset($future) || isset($ipd))
                        <td class="icons">
                            <select class="table_search" id="table_search_accommodation_status"
                                    name="accommodation_status"
                                    value="{{ @$search_data['accommodation_status'] }}">
                                <option value="">Accommodation Status</option>
                                <option value="{{ \App\Booking::ACCOMMODATION_STATUS_PENDING}}" {{ @$search_data['accommodation_status'] == \App\Booking::ACCOMMODATION_STATUS_PENDING ? "selected" : "" }}>
                                    Pending
                                </option>
                                <option value="{{ \App\Booking::ACCOMMODATION_STATUS_CONFIRMED}}" {{ @$search_data['accommodation_status'] == \App\Booking::ACCOMMODATION_STATUS_CONFIRMED? "selected" : "" }}>
                                    Confirmed
                                </option>
                            </select><i
                                    class="fa fa-filter"></i>
                        </td>
                    @endif
                    <td class="icons">
                        &nbsp;
                    </td>
                </tr>
            @endif
            <tr>
                <td colspan={{ isset($future) ? 10 : 8 }}>
                    <div class="ui negative icon message">
                        <i class="frown icon"></i>
                        <div class="content">
                            <div class="header">
                                {{ @$search == true ? $error : trans('laralum.missing_title') }}
                            </div>
                            <p>There are currently no bookings</p>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
@endif
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $(".filter_datepicker").datepicker({dateFormat: "dd-mm-yy", autoclose: true});
</script>
<script>
    $(document).ready(function () {
        $('div#book-table').click(function () {
            $('.table-responsive.table_sec_row').toggleClass('padding_slide-btm');
        });

        $(document).click(function (e) {
            if (e.target.id == "book-table") {
                //alert('hoga');
                return;
            }
            $('.table-responsive.table_sec_row').removeClass('padding_slide-btm');
        });
    });
</script>


