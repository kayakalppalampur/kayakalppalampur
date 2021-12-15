{{-- table Button --}}
@if(!isset($print))
    @if(isset($archived))
        <div class="column table_top_btn">
            <div class="btn-group pull-right">
                <div class="item no-disable">
                    <a style="color:white"
                       href="{{ url("admin/users/print/".$user_type.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">
                        <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                    class="print icon"></i><span class="text responsive-text">Print</span></div>
                    </a>
                    <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                        <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                        <div class="menu">
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/archived-patients/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}">Export
                                as CSV
                            </a>
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/archived-patients/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}">Export
                                as PDF
                            </a>
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/archived-patients/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page']) }}">Export
                                as Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="column table_top_btn">
            <div class="btn-group pull-right">
                <div class="item no-disable">
                    @if($user_type == \App\User::USER_TYPE_ALL)
                        <a style="color:white" href="{{ url("admin/users/create/") }}">
                            <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                        class="plus icon"></i><span class="text responsive-text">Create User</span>
                            </div>
                        </a>
                    @elseif($user_type == \App\User::USER_TYPE_DOCTORS)
                        <a style="color:white" href="{{ url("admin/users/create/".\App\User::USER_TYPE_DOCTORS) }}">
                            <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                        class="plus icon"></i><span class="text responsive-text">Create Doctor</span>
                            </div>
                        </a>
                    @endif
                    @if($user_type == \App\User::USER_TYPE_DOCTORS)
                        <a style="color:white"
                           href="{{ url('admin/doctors/print/?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">
                            <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                        class="print icon"></i><span class="text responsive-text">Print</span></div>
                        </a>
                    @else
                        <a style="color:white"
                           href="{{ url("admin/users/print/".$user_type.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">
                            <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                        class="print icon"></i><span class="text responsive-text">Print</span></div>
                        </a>
                    @endif
                    <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                        <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                        <div class="menu">
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/users/export/'.\App\Settings::EXPORT_CSV.'/'.$user_type.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                                as CSV
                            </a>
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/users/export/'.\App\Settings::EXPORT_PDF.'/'.$user_type.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                                as PDF
                            </a>
                            <a id="clicked" class="item no-disable"
                               href="{{ url('/admin/users/export/'.\App\Settings::EXPORT_EXCEL.'/'.$user_type.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                                as Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- table Button --}}
@endif


@if(count($users) > 0 )
    @if(!isset($print))
        <div class="pagination_con paggination_top" role="toolbar">
            <div class="pull-right">
                {!!  \App\Settings::perPageOptions($count)  !!}
            </div>
        </div>
    @endif
    <table class="ui table table_cus_v last_row_bdr"
           data-action="{{ url('admin/users') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}&user_type={{ $user_type }}">
        <thead>
        <tr>
            <th>{{ trans('laralum.name') }}</th>
            @if(isset($patient))
                <th>{{ trans('laralum.patient_id') }}</th>
            @endif
            <th>{{ trans('laralum.email') }}</th>
            @if($user_type == \App\User::USER_TYPE_ALL)
                <th>{{ trans('laralum.role') }}</th>
            @endif
            @if($user_type == \App\User::USER_TYPE_DOCTORS)
                <th>{{ trans('laralum.department') }}</th>
            @endif
            @if($user_type == \App\User::USER_TYPE_PATIENTS)
                <th>{{ trans('laralum.country') }}</th>@endif

            @if(!isset($print))
                <th>{{ trans('laralum.options') }}</th>
            @endif
        </tr>
        </thead>
        <tbody>
        <?php
        $countries = Laralum::countries();
        ?>
        @if(!isset($print))
            <tr class="table_search">
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_name"
                           value="{{ @$search_data['name'] }}"
                           name="name"
                           placeholder="search name"/> <i
                            class="fa fa-filter"></i>
                </td>
                @if(isset($patient))
                    <td class="icons">
                        <input type="text" class="table_search" id="table_search_patient_id"
                               value="{{ @$search_data['patient_id'] }}"
                               name="patient_id"
                               placeholder="search name"/> <i
                                class="fa fa-filter"></i>
                    </td>
                @endif
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_email"
                           value="{{ @$search_data['email'] }}"
                           name="email"
                           placeholder="search email"/> <i
                            class="fa fa-filter"></i>
                </td>
                @if($user_type == \App\User::USER_TYPE_ALL)
                    <td class="icons">
                        <select class="table_search" id="table_search_role_id" name="role_id"
                                value="{{ @$search_data['role_id'] }}">
                            <option value="">All Roles</option>
                            @foreach(\App\Role::where('id', '!=', \App\Role::getDoctorId())->get() as $role)
                                <option value="{{ $role->id }}" {{ @$search_data['role_id'] == $role->id ? "selected" : "" }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <i class="fa fa-filter"></i>
                    </td>
                @endif
                @if($user_type == \App\User::USER_TYPE_DOCTORS)
                    <td class="icons">
                        <select class="table_search" id="table_search_department" name="department"
                                value="{{ @$search_data['department'] }}">
                            <option value="">All Departments</option>
                            @foreach(\App\Department::all() as $dept)
                                <option value="{{ $dept->id }}" {{ @$search_data['department'] == $dept->id ? "selected" : "" }}>{{ $dept->title }}</option>
                            @endforeach
                        </select> <i
                                class="fa fa-filter"></i>
                    </td>
                @endif
                @if($user_type == \App\User::USER_TYPE_PATIENTS)
                    <td class="icons">
                        <input type="text" class="table_search" id="table_search_country"
                               value="{{ @$search_data['country'] }}"
                               name="country"
                               placeholder="search country"/> <i
                                class="fa fa-filter"></i>
                    </td>
                @endif
                <td class="icons">
                    &nbsp;
                </td>
            </tr>
        @endif
        @foreach($users as $user)
            <tr>
                <td>
                    <div class="text">
                        @if(!isset($print))   <img id="avatar-div" class="ui avatar image"
                                                   src="{!! $user->avatar() !!}">
                        <a href="{{ route('Laralum::users_profile', ['id' => $user->id]) }}">{{ $user->name }}</a>@else {{ $user->name }} @endif
                        @if(!isset($print))
                            @if($user->su)
                                <div class="ui red tiny left pointing basic label pop"
                                     data-title="{{ trans('laralum.super_user') }}" data-variation="wide"
                                     data-content="{{ trans('laralum.super_user_desc') }}"
                                     data-position="top center">{{ trans('laralum.super_user') }}</div>
                            @elseif(Laralum::isAdmin($user))
                                <div class="ui blue tiny left pointing basic label pop"
                                     data-title="{{ trans('laralum.admin_access') }}" data-variation="wide"
                                     data-content="{{ trans('laralum.admin_access_desc') }}"
                                     data-position="top center">{{ trans('laralum.admin_access') }}</div>
                            @endif
                        @endif
                    </div>
                </td>
                @if(isset($patient))
                    <td>
                        {{ isset($user->userProfile->kid) ? $user->userProfile->kid : "" }}
                    </td>
                @endif
                <td>
                    @if($user->banned)
                        <i data-position="top center" data-content="{{ trans('laralum.users_status_banned') }}"
                           class="pop red close icon"></i>
                    @elseif(!$user->active)
                        <i data-position="top center" data-content="{{ trans('laralum.users_status_unactive') }}"
                           class="pop orange warning icon"></i>
                    @else
                        <i data-position="top center" data-content="{{ trans('laralum.users_status_ok') }}"
                           class="pop green checkmark icon"></i>
                    @endif
                    {{ $user->email }}
                </td>
                @if($user_type == \App\User::USER_TYPE_ALL)
                    <td>
                        {{ isset($user->userRole->role->id) ? $user->userRole->role->name : "" }}
                    </td>
                @endif
                @if($user_type == \App\User::USER_TYPE_DOCTORS)
                    <td> {{ isset($user->department->department->title) ? $user->department->department->title : ""}}</td>
                @endif
                @if($user_type == \App\User::USER_TYPE_PATIENTS)
                    <td>
                        @if($user->country_code != '')
                            @if(in_array($user->country_code, Laralum::noFlags()))<i
                                    class="help icon"></i> {{ $countries[$user->country_code] }}@else<i
                                    class="{{ strtolower($user->country_code) }} flag"></i> {{ $countries[$user->country_code] }}@endif
                        @endif
                    </td>
                @endif

                @if(!isset($print))
                    <td>
                        @if(isset($patient))
                            @if(Laralum::loggedInUser()->hasPermission('admin.users.list'))
                                <div id="book-table" class="ui  top icon blue left pointing dropdown button">
                                    <i class="configure icon"></i>
                                    <div class="menu">
                                        <div class="header">{{ trans('laralum.editing_options') }}</div>
                                        @if($user->is_discharged == \App\User::ADMIT)
                                            <a href="{{ route('Laralum::booking.show', ['user_id' => $user->id]) }}"
                                               class="item no-disable">
                                                <i class="fa fa-eye"></i>
                                                Booking Details
                                            </a>

                                            <a href="{{ route('Laralum::bookings.discharge-patient-billing-individual', ['transaction_id' => $user->id]) }}"
                                               class="item no-disable">
                                                <i class="fa fa-eye"></i>
                                                Discharge Patient
                                            </a>
                                        @else
                                            <a href="{{ route('Laralum::patient_details', ['patient_id' => $user->id]) }}"
                                               class="item no-disable">
                                                <i class="fa fa-eye"></i>
                                                Patient Details
                                            </a>
                                        @endif
                                        @if($user->is_discharged == \App\User::ADMIT)
                                            @if(Laralum::loggedInUser()->hasPermission('admin.users.list'))
                                                <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                                <a href="{{ route('Laralum::bookings.delete', ['id' => $user->id]) }}"
                                                   class="item no-disable">
                                                    <i class="trash bin icon"></i>
                                                    Delete Booking
                                                </a>
                                                {{--{!! Form::open(['style' => 'display: inline-block;', 'method' => 'DELETE', 'route' => ['Laralum::bookings.destroy', $row->id]]) !!}
                                                {!! Form::submit('Delete Booking', array('class' => 'btn btn-xs btn-danger')) !!}
                                                {!! Form::close() !!}--}}
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="ui disabled blue icon button">
                                    <i class="lock icon"></i>
                                </div>
                            @endif
                        @else

                            @if(!Laralum::isAdmin($user) or Laralum::loggedInUser()->su)

                                <div class="ui {{ Laralum::settings()->button_color }} top icon left pointing dropdown button">
                                    <i class="configure icon"></i>
                                    <div class="menu">
                                        <div class="header">{{ trans('laralum.editing_options') }}</div>
                                        @if($user_type == \App\User::USER_TYPE_DOCTORS )
                                            <a href="{{ route('Laralum::doctors_edit', ['id' => $user->id]) }}"
                                               class="item no-disable">
                                                <i class="edit icon"></i>
                                                {{ trans('laralum.doctors_edit') }}
                                            </a>
                                            <a href="{{ route('Laralum::users_department', ['id' => $user->id]) }}"
                                               class="item no-disable">
                                                <i class="star icon"></i>
                                                {{ trans('laralum.users_edit_department') }}
                                            </a>
                                            @if($user->id != Laralum::loggedInUser()->id)
                                                <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                                <a href="{{ route('Laralum::doctors_delete', ['id' => $user->id]) }}"
                                                   class="item no-disable">
                                                    <i class="trash bin icon"></i>
                                                    {{ trans('laralum.doctors_delete') }}
                                                </a>
                                            @endif

                                        @else
                                            <a href="{{ route('Laralum::users_edit', ['id' => $user->id]) }}"
                                               class="item no-disable">
                                                <i class="edit icon"></i>
                                                {{ trans('laralum.users_edit') }}
                                            </a>
                                            <a href="{{ route('Laralum::users_roles', ['id' => $user->id]) }}"
                                               class="item no-disable">
                                                <i class="star icon"></i>
                                                {{ trans('laralum.users_edit_roles') }}
                                            </a>

                                            @if($user->id != Laralum::loggedInUser()->id)
                                                <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                                <a href="{{ route('Laralum::users_delete', ['id' => $user->id]) }}"
                                                   class="item no-disable">
                                                    <i class="trash bin icon"></i>
                                                    {{ trans('laralum.users_delete') }}
                                                </a>
                                            @endif
                                        @endif


                                    </div>
                                </div>
                            @else
                                <div class="ui disabled {{ Laralum::settings()->button_color }} icon button">
                                    <i class="lock icon"></i>
                                </div>
                            @endif
                        @endif
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
    @if(!isset($print))
        @if(method_exists($users, 'links'))
            <div class="pagination_con main_paggination" role="toolbar">
                {{ $users->links() }}
            </div>
        @endif
    @endif
@else
    <table class="ui table table_cus_v last_row_bdr"
           data-action="{{ url('admin/users') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}&user_type={{ $user_type }}">
        <thead>
        <tr>
            <th>{{ trans('laralum.name') }}</th>
            @if(isset($patient))
                <th>{{ trans('laralum.patient_id') }}</th>
            @endif
            <th>{{ trans('laralum.email') }}</th>
            @if($user_type == \App\User::USER_TYPE_ALL)
                <th>{{ trans('laralum.role') }}</th>
            @endif
            @if($user_type == \App\User::USER_TYPE_DOCTORS)
                <th>{{ trans('laralum.department') }}</th>
            @endif
            @if($user_type == \App\User::USER_TYPE_PATIENTS)
                <th>{{ trans('laralum.country') }}</th>@endif

            @if(!isset($print))
                <th>{{ trans('laralum.options') }}</th>
            @endif
        </tr>
        </thead>
        <tbody>
        <?php
        $countries = Laralum::countries();
        ?>
        @if(!isset($print))
            <tr class="table_search">
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_name"
                           value="{{ @$search_data['name'] }}"
                           name="name"
                           placeholder="search name"/> <i
                            class="fa fa-filter"></i>
                </td>
                @if(isset($patient))
                    <td class="icons">
                        <input type="text" class="table_search" id="table_search_patient_id"
                               value="{{ @$search_data['patient_id'] }}"
                               name="patient_id"
                               placeholder="search name"/> <i
                                class="fa fa-filter"></i>
                    </td>
                @endif
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_email"
                           value="{{ @$search_data['email'] }}"
                           name="email"
                           placeholder="search email"/> <i
                            class="fa fa-filter"></i>
                </td>
                @if($user_type == \App\User::USER_TYPE_ALL)
                    <td class="icons">
                        <select class="table_search" id="table_search_role_id" name="role_id"
                                value="{{ @$search_data['role_id'] }}">
                            <option value="">All Roles</option>
                            @foreach(\App\Role::where('id', '!=', \App\Role::getDoctorId())->get() as $role)
                                <option value="{{ $role->id }}" {{ @$search_data['role_id'] == $role->id ? "selected" : "" }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <i class="fa fa-filter"></i>
                    </td>
                @endif
                @if($user_type == \App\User::USER_TYPE_DOCTORS)
                    <td class="icons">
                        <select class="table_search" id="table_search_department" name="department"
                                value="{{ @$search_data['department'] }}">
                            <option value="">All Departments</option>
                            @foreach(\App\Department::all() as $dept)
                                <option value="{{ $dept->id }}" {{ @$search_data['department'] == $dept->id ? "selected" : "" }}>{{ $dept->title }}</option>
                            @endforeach
                        </select> <i
                                class="fa fa-filter"></i>
                    </td>
                @endif
                @if($user_type == \App\User::USER_TYPE_PATIENTS)
                    <td class="icons">
                        <input type="text" class="table_search" id="table_search_country"
                               value="{{ @$search_data['country'] }}"
                               name="country"
                               placeholder="search country"/> <i
                                class="fa fa-filter"></i>
                    </td>
                @endif
                <td class="icons">
                    &nbsp;
                </td>
            </tr>
        @endif
        <tr>
            <td colspan="3">
                <div class="ui negative icon message">
                    <i class="frown icon"></i>
                    <div class="content">
                        <div class="header">
                            {{  $error }}
                        </div>
                        <p>There are currently no users</p>
                    </div>
                </div>
            </td>
        </tr>
    </table>

@endif