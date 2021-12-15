@if(count($treatments) > 0)
    <div class="pagination_con paggination_top" role="toolbar">
        <div class="pull-right">
            {!!  \App\Settings::perPageOptions($count)  !!}
        </div>
    </div>

    <div class="table-responsive table_sec_row">
        <table class="ui table table_cus_v last_row_bdr"
               data-action="{{ url('admin/treatment/tokens') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'].'&'.@http_build_query($search_data) }}">
            <thead>
            <tr>
                <th>Treatment Id</th>
                <th>Registration Id</th>
                <th>Name of the Person</th>
                <th>Treatment Date</th>
                <th>Treatments</th>
                <th>Department</th>
                <th>Allocated By Doctor</th>
                <th>Attended By Superviser</th>
                <th>Note</th>
                @if(\Auth::user()->isReception())
                    <th>Print</th>
                @endif
            </tr>
            </thead>
            <tbody>
            <tr class="table_search">
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_id"
                           value="{{ @$search_data['id'] }}"
                           name="id"
                           placeholder="search Treatment Id"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_kid"
                           value="{{ @$search_data['kid'] }}"
                           name="kid"
                           placeholder="search Patient Id"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_first_name"
                           value="{{ @$search_data['name'] }}"
                           name="first_name"
                           placeholder="search Name of the Person"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search datepicker" id="table_search_treatment_date"
                           value="{{ date_format(date_create($search_data['treatment_date']),'d-m-Y') }}"
                           name="treatment_date"
                           placeholder="search Treatment Date"/> <i
                            class="fa fa-filter"></i>
                </td>
               
                <td class="icons">
                    <select class="table_search" id="table_search_treatments">
                        <option value="">Select Treatments</option>
                        @foreach(\App\Treatment::all() as $treament)
                            <option value="{{ $treament->id }}" {{ @$search_data['treatments'] == $treament->id ? "selected" : ""}}>{{ $treament->title }}</option>
                        @endforeach
                        <i class="fa fa-filter"></i>
                    </select>
                </td>
                 <td class="icons">
                    <select class="table_search" id="table_search_department_id">
                        <option value="">Select Department</option>
                        @foreach(\App\Department::all() as $department)
                            <option value="{{ $department->id }}" {{ @$search_data['department_id'] == $department->id ? "selected" : ""}}>{{ $department->title }}</option>
                        @endforeach
                        <i class="fa fa-filter"></i>
                    </select>
                </td>
                <td class="icons">
                    <select class="table_search" id="table_search_created_by">
                        <option value="">Select Doctors</option>
                        @foreach(\App\User::getDoctors() as $doctor)
                            <option value="{{ $doctor->id }}" {{ @$search_data['created_by'] == $doctor->id ? "selected" : ""}}>{{ $doctor->name }}</option>
                        @endforeach
                        <i class="fa fa-filter"></i>
                    </select>
                </td>
                <td></td>
                <td></td>
                @if(\Auth::user()->isReception())
                    <td></td>
                @endif
            </tr>
            @foreach($treatments as $row)
                @if($row->booking->getProfile('kid') != "")
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->booking->getProfile('kid') }}</td>
                        <td>{{ $row->booking->getProfile('first_name').' '. $row->booking->getProfile('last_name') }}</td>

                        <td>{{ date_format(date_create($row->treatment_date),'d-m-Y')  }}</td>
                        
                        <td>
                            @foreach($row->treatments as $pat_treatment)
                                {{ $pat_treatment->treatment->title.' (Rs.'.$pat_treatment->treatment->price.') ' }}
                                <br/>
                            @endforeach
                        </td>
                        <td>{{ $row->departmentname()  }}</td>
                        <td>{{ $row->createUser->name.'('.$row->department->title.')' }}</td>
                        <td>
                            @foreach($row->treatments as $pat_treatment)
                                <span id="status_{{$pat_treatment->id}}">{{ $pat_treatment->getStatusOptions($pat_treatment->status) }}</span>
                                <input type="hidden" id="selected_state_{{ $pat_treatment->id }}"
                                       value="{{ $pat_treatment->status }}">
                                <i id="edit_{{ $pat_treatment->id}}" class="fa fa-edit "></i>
                                <div id="change_status_div_{{ $pat_treatment->id }}" style="display:none;">
                                    <form method="post" id="treatment_token_form_{{ $pat_treatment->id }}"
                                          action="{{ route('Laralum::treatment_tokens.update', ['treatment_id' => $pat_treatment->id]) }}">
                                        <select id="change_status_option_{{ $pat_treatment->id }}">
                                            <option {{ $pat_treatment->status == \App\PatientTreatment::STATUS_PENDING ? 'selected' : '' }} value="{{ \App\PatientTreatment::STATUS_PENDING }}">
                                                NO
                                            </option>
                                            <option {{ $pat_treatment->status == \App\PatientTreatment::STATUS_COMPLETED ? 'selected' : '' }} value="{{ \App\PatientTreatment::STATUS_COMPLETED }}">
                                                YES
                                            </option>
                                        </select>
                                    </form>
                                </div>
                                <br/>
                            @endforeach
                        </td>
                        <td>
                            @foreach($row->treatments as $pat_treatment)
                                <span id="reason_{{$pat_treatment->id}}">{{ $pat_treatment->not_attended_reason }}</span>
                                <i id="edit_reason_{{ $pat_treatment->id}}" class="fa fa-edit "></i><br>
                                <div id="change_reason_div_{{ $pat_treatment->id }}" style="display:none;">
                                    <form method="post" id="treatment_token_form_{{ $pat_treatment->id }}"
                                          action="{{ route('Laralum::treatment_tokens.update', ['treatment_id' => $pat_treatment->id]) }}">
                                        <textarea type="text" class="form-control"
                                                  placeholder="Note(if any)" name="not_attended_reason"
                                                  id="not_attended_reason_{{$pat_treatment->id}}">{{ $pat_treatment->not_attended_reason}}</textarea>
                                    </form>
                                    <br>
                                </div>
                            @endforeach
                        </td>
                        @if(\Auth::user()->isReception())
                            <td>
                                <a title="Print" href="{{ url("admin/patient/print-treatment/".$row->id) }}"><i
                                            class="fa fa-print"></i> </a>
                            </td>
                        @endif
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
    @if(method_exists($treatments, "links"))
        <div class="pagination_con main_paggination" role="toolbar">
            {{ $treatments->links() }}
        </div>
    @endif


@else
    <div class="table-responsive table_sec_row">
        <table class="ui table table_cus_v last_row_bdr"
               data-action="{{ url('admin/treatment/tokens') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'].'&s='.@json_encode($search_data)  }}">
            <thead>
            <tr>
                <th>Treatment Id</th>
                <th>Patient Id</th>
                <th>Name of the Person</th>
                <th>Treatment Date</th>
                <th>Treatments</th>
                <th>Department</th>
                <th>Allocated By Doctor</th>
                <th>Attended By Superviser</th>
                <th>Note</th>
                @if(\Auth::user()->isReception())
                    <th>Print</th>
                @endif
            </tr>
            </thead>
            <tbody>
            <tr class="table_search">
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_id"
                           value="{{ @$search_data['id'] }}"
                           name="id"
                           placeholder="search Treatment Id"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_kid"
                           value="{{ @$search_data['kid'] }}"
                           name="kid"
                           placeholder="search Patient Id"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_first_name"
                           value="{{ @$search_data['first_name'] }}"
                           name="first_name"
                           placeholder="search Name of the Person"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search datepicker" id="table_search_treatment_date"
                           value="{{ date_format(date_create($search_data['treatment_date']),'d-m-Y') }}"
                           name="treatment_date"
                           placeholder="search Treatment Date"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <select class="table_search" id="table_search_treatments">
                        <option value="">Select Treatments</option>
                        @foreach(\App\Treatment::all() as $treament)
                            <option value="{{ $treament->id }}" {{ @$search_data['treatments'] == $treament->id ? "selected" : ""}}>{{ $treament->title }}</option>
                        @endforeach
                        <i class="fa fa-filter"></i>
                    </select>
                </td>
                <td class="icons">
                    <select class="table_search" id="table_search_department_id">
                        <option value="">Select Department</option>
                        @foreach(\App\Department::all() as $department)
                            <option value="{{ $department->id }}" {{ @$search_data['department_id'] == $department->id ? "selected" : ""}}>{{ $department->title }}</option>
                        @endforeach
                        <i class="fa fa-filter"></i>
                    </select>
                </td>
                <td class="icons">
                    <select class="table_search" id="table_search_created_by">
                        <option value="">Select Doctors</option>
                        @foreach(\App\User::getDoctors() as $doctor)
                            <option value="{{ $doctor->id }}" {{ @$search_data['created_by'] == $doctor->id ? "selected" : ""}}>{{ $doctor->name }}</option>
                        @endforeach
                        <i class="fa fa-filter"></i>
                    </select>
                </td>
                <td></td>
                <td></td>
                @if(\Auth::user()->isReception())
                    <td></td>
                @endif
            </tr>
            <tr>
                <td colspan=7>
                    <div class="ui negative icon message">
                        <i class="frown icon"></i>
                        <div class="content">
                            <div class="header">
                                {{ $search == true ? $error : trans('laralum.missing_title') }}
                            </div>
                            <p>There are currently no tokens</p>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
@endif

@script
<script>
    $(document).ready(function () {
 $(".pagination_con").not('.paggination_top').find("a").each(function () {
        var text = $(this).text();

        console.log("Text" + text);
	var href = $(this).attr("href")
console.log("ref", $(this).attr("href"))
 var  url = $('.table_cus_v').attr('data-action');
var newurl1 = updateQueryStringParameter(url, 'page' , text );
console.log('ewurl',newurl1);

	$(this).attr("href", newurl1)
    })

function updateQueryStringParameter(uri, key, value) {
            console.log('uri:::'+uri);
          var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
          //alert(uri);

          var separator = uri.indexOf('?') !== -1 ? "&" : "?";

          if (uri.match(re)) {
            return uri.replace(re, '$1' + key + "=" + value + '$2');
          }
          else {
            return uri + separator + key + "=" + value;
          }
        }
})
</script>
@endscript
