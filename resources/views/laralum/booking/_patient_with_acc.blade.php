@if(!isset($print))
    <div class="column table_top_btn">
        <div class="btn-group pull-right">
            <div class="item no-disable">
                @if(!isset($archived))
                    <a style="color:white" href="{{ url("admin/patient/acc/print/") }}">
                        <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                    class="print icon"></i><span class="text responsive-text">Print</span></div>
                    </a>
                @endif

                @if(!isset($archived))
                    <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                        <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                        <div class="menu">
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/patient/acc/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}">Export
                                as CSV
                            </a>
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/patient/acc/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}">Export
                                as PDF
                            </a>
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/patient/acc/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}">Export
                                as Excel
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif
@if(count($models) > 0)
    @if(!isset($print))
    <div class="pagination_con paggination_top" role="toolbar">
        <div class="pull-right">
            {!!  \App\Settings::perPageOptions($count)  !!}
        </div>
    </div>
    @endif
    <div class="table-responsive table_sec_row">
        <table class="ui table table_cus_v last_row_bdr" data-action="{{ \Route::getCurrentRoute()->getName() == 'Laralum::archived.patients.searchlist' ? url('admin/archived-patient-with-accomodation-list')  : url('admin/accomodations')  }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}"
  >
            <thead>
            <tr>
                <th>Patient Id</th>
                <th>UH Id</th>
                <th>Patient Type</th>
                <th>Name of the Person</th>
                <th>Email ID</th>
                <th>Contact No.</th>
                <th>City, State, Country</th>
                <th>Created On</th>
                <th>Booking Status</th>
                <th>Accommodation Status</th>
                @if(!isset($print))
                    <th>Actions</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @if(!isset($print))
            <tr class="table_search">
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_kid"
                           value="{{ @$search_data['kid'] }}"
                           name="slug"
                           placeholder="search patient id"/> <i
                            class="fa fa-filter"></i>
                </td>
                   <td class="icons">
                    <input type="text" class="table_search" id="table_search_uhid"
                           value="{{ @$search_data['uhid'] }}"
                           name="slug"
                           placeholder="search uh id"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <select class="table_search" id="table_search_patient_type" name="patient_type"
                            value="{{ @$search_data['patient_type'] }}">
                        <option value="">All Patient Types</option>
                        <option value="{{ \App\Booking::PATIENT_TYPE_IPD}}" {{ @$search_data['patient_type'] == \App\Booking::PATIENT_TYPE_IPD ? "selected" : "" }}>
                            IPD
                        </option>

                    </select><i
                            class="fa fa-filter"></i>
                </td>

                <td class="icons">
                    <input type="text" class="table_search" id="table_search_first_name"
                           value="{{ @$search_data['first_name'] }}"
                           name="slug"
                           placeholder="search name"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_email"
                           value="{{ @$search_data['email'] }}"
                           name="slug"
                           placeholder="search email"/> <i
                            class="fa fa-filter"></i>
                </td>
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
                <td class="icons">
                    &nbsp; <input type="text" class="table_search filter_datepicker" id="table_search_date"
                                  value="{{ @$search_data['date'] }}"
                                  name="date"
                                  placeholder="search date"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
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
                </td>
                <td class="icons">
                    @if(!\Auth::user()->isDoctor())
                        <select class="table_search" id="table_search_accommodation_status" name="accommodation_status"
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
                <td class="icons">
                    &nbsp;
                </td>
            </tr>
            @endif
            @foreach($models as $row)
                <tr class="{{ $row->getStatusClass() }}">
                    <td>{{ $row->getProfile('kid') }}</td>
                      <td>{{ $row->getProfile('uhid') }}</td>
                    <td>{{ $row->patient_type != null ? $row->getPatientType($row->patient_type): "OPD"}}</td>
                    <td>{{ $row->getProfile('first_name').' '.$row->getProfile('last_name') }}</td>
                    <td>{{ isset($row->user->email) ? $row->user->email : ""}}</td>
                    <td>{{ $row->getProfile('mobile') ?  $row->getProfile('mobile') : ""}}</td>
                    <td>{{ $row->getAddress('city') ? $row->getAddress('city') .','. $row->getAddress('state') .','. $row->getAddress('country') : "" }} </td>
                    <td>{{ date("d-m-Y h:i a", strtotime($row->created_at)) }}</td>
                    <td>{{ !empty($row->status) ? $row->getStatusOptions($row->status) : "Pending" }}</td>
                    <td>{{ $row->accommodationStatus() }}</td>
                    {{--<td>{{ ($row->patient_type == 1)? 'IPD':'OPD' }}</td>--}}
                    @if(!isset($print))
                        <td>
                            @if(Laralum::loggedInUser()->hasPermission('admin.bookings.list'))
                                @if(\Auth::user()->isDoctor())
                                    <div class="ui  top icon {{ \App\Http\Controllers\Laralum\Laralum::settings()->button_color }} left pointing dropdown button">
                                        <i class="configure icon"></i>
                                        <div class="menu">
                                            <div class="header">{{ trans('laralum.editing_options') }}</div>

                                            <a href="{{ route('Laralum::patient.show', ['id' => $row->id]) }}"
                                               class="item no-disable">
                                                <i class="fa fa-eye"></i>
                                                First Visit
                                            </a>
                                            <a href="{{ route('Laralum::patient.diagnosis', ['id' => $row->id]) }}"
                                               class="item no-disable">
                                                <i class="fa fa-eye"></i>
                                                Provisional Diagnosis
                                            </a>
                                            <a href="{{ url("admin/patient-treatment/".$row->id) }}" class="item no-disable">
                                                <i class="fa fa-eye"></i>
                                                Allot Treatment
                                            </a>
                                            <a href="{{ url("admin/patient-diet-chart/".$row->id) }}" class="item no-disable">
                                                <i class="fa fa-eye"></i>
                                                Allot Diet
                                            </a>
                                            <a href="{{ url("admin/patient/discharge/".$row->id) }}" class="item no-disable">
                                                <i class="fa fa-eye"></i>
                                                Discharge Patient
                                            </a>
                                            @if(Laralum::loggedInUser()->hasPermission('patients.delete') && !\Auth::user()->isPatient())
                                                <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                                @if($row->status != \App\Booking::STATUS_DISCHARGED)
                                                    <a href="{{ route('Laralum::bookings.delete', ['id' => $row->id]) }}"
                                                       class="item no-disable">
                                                        <i class="trash bin icon"></i>
                                                        Cancel Booking
                                                    </a>

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
                                           {{-- <a href="{{ route('Laralum::booking.allot.rooms', ['user_id' => $row->id]) }}"
                                               class="item no-disable">
                                                <i class="fa fa-pencil"></i>
                                                Allot Accomodations
                                            </a>--}}
                                            <a href="{{ route('Laralum::booking.show', ['user_id' => $row->id]) }}"
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
        <table class="ui table table_cus_v last_row_bdr" data-action="{{ \Route::getCurrentRoute()->getName() == 'Laralum::archived.patients.list' ? url('admin/archived-patient-list')  : url('admin/accomodations')  }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
            <thead>
            <tr>
                <th>Patient Id</th>
                <th>UH Id</th>
                <th>Patient Type</th>
                <th>Name of the Person</th>
                <th>Email ID</th>
                <th>Contact No.</th>
                <th>City, State, Country</th>
                <th>Created On</th>
                <th>Booking Status</th>
                <th>Accommodation Status</th>
                @if(!isset($print))
                    <th>Actions</th>
                @endif
            </tr>
            </thead>
            <tbody>
            <tr class="table_search">
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_kid"
                           value="{{ @$search_data['kid'] }}"
                           name="slug"
                           placeholder="search patient id"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_uhid"
                           value="{{ @$search_data['uhid'] }}"
                           name="slug"
                           placeholder="search uh id"/> <i
                            class="fa fa-filter"></i>
                </td>

                <td class="icons">
                    <select class="table_search" id="table_search_patient_type" name="patient_type"
                            value="{{ @$search_data['patient_type'] }}">
                        <option value="">All Patient Types</option>
                        <option value="{{ \App\Booking::PATIENT_TYPE_IPD}}" {{ @$search_data['patient_type'] == \App\Booking::PATIENT_TYPE_IPD ? "selected" : "" }}>
                            IPD
                        </option>
                        <option value="{{ \App\Booking::PATIENT_TYPE_OPD}}" {{ @$search_data['patient_type'] == \App\Booking::PATIENT_TYPE_OPD ? "selected" : "" }}>
                            OPD
                        </option>
                    </select><i
                            class="fa fa-filter"></i>
                </td>

                <td class="icons">
                    <input type="text" class="table_search" id="table_search_first_name"
                           value="{{ @$search_data['first_name'] }}"
                           name="slug"
                           placeholder="search name"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_email"
                           value="{{ @$search_data['email'] }}"
                           name="slug"
                           placeholder="search email"/> <i
                            class="fa fa-filter"></i>
                </td>
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
                <td class="icons">
                    &nbsp;<input type="text" class="table_search filter_datepicker" id="table_search_date"
                                 value="{{ @$search_data['date'] }}"
                                 name="date"
                                 placeholder="search date"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
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
                </td>
                <td class="icons">
                    <select class="table_search" id="table_search_accommodation_status" name="accommodation_status"
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
                <td class="icons">
                    &nbsp;
                </td>
            </tr>
            <tr>
                <td colspan=7>
                    <div class="ui negative icon message">
                        <i class="frown icon"></i>
                        <div class="content">
                            <div class="header">
                                {{ $search == true ? $error : trans('laralum.missing_title') }}
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

<script>
    $(".filter_datepicker").datepicker({dateFormat: "dd-mm-yy", autoclose: true});
</script>
