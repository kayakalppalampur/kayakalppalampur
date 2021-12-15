@php 
        $data_action =  url('admin/lab-test-patients?page='.@$_REQUEST['page'].'&per_page='.@$_REQUEST['per_page']); 
    @endphp
@if(count($data) > 0)
    <div class="table_outer">
    <table class="ui table table_cus_v last_row_bdr" data-action="{{ $data_action }}">
        <thead>
        <tr>
            <th>Actions</th>
            <th>Patient Name</th>
            <th>UHID</th>
            <th>Registration ID</th>
            <th>Contact</th>      
        </tr>
        </thead>
        <tbody>
        @if(!isset($print))
            <tr class="table_search">
                <td class="icons">
                    &nbsp;
                </td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_name"
                           value="{{ @$search_data['name'] }}" name="slug" placeholder="search Patient name"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_uhid"
                           value="{{ @$search_data['uhid'] }}" name="slug" placeholder="search uh id"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_kid"
                           value="{{ @$search_data['kid'] }}" name="slug" placeholder="search Registration id"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_contact"
                           value="{{ @$search_data['contact'] }}" name="slug" placeholder="search Contact"/> <i
                            class="fa fa-filter"></i>
                </td>
            </tr>
        @endif
        @foreach($data as $row)
            <tr>
                <td>
                    <div id="book-table"  class="ui  top icon blue left pointing dropdown button">
                        <i class="configure icon"></i>
                        <div class="menu">
                            @if(Laralum::loggedInUser()->hasPermission('view_lab_test'))
                                <div class="header">{{ trans('laralum.editing_options') }}</div>
                                <a href="{{ route('Laralum::patient.patient-details', ['booking_id' => $row['booking']->id]) }}"
                                   class="item no-disable">
                                    <i class="edit icon"></i>
                                    Patient Details
                                </a>
                            @endif
                            @if(Laralum::loggedInUser()->hasPermission('view_lab_test'))
                                <a href="{{ route('Laralum::patient.lab-details', ['booking_id' => $row['booking']->id]) }}"
                                   class="item no-disable">
                                    <i class="edit icon"></i>
                                    Lab Tests
                                </a>
                            @endif
                        </div>
                    </div>
                </td> 
                <td>{{ $row['patient_profile']->first_name .' '. $row['patient_profile']->last_name  }} </td>     
                <td>{{ $row['patient']->uhid }}</td>    
                <td>{{ $row['patient_profile']->kid }}</td>
                <td>{{ $row['patient_profile']->mobile }}</td> 
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
    @if(!isset($print))
        @if(method_exists($lab_tests, "links"))
            <div class="pagination_con main_paggination" role="toolbar">
                {{ $lab_tests->links() }}
            </div>
        @endif
    @endif
@else
<div class="table_outer">
    <table class="ui table table_cus_v last_row_bdr" data-action="{{ $data_action }}">
        <thead>
        <tr>
            <th>Actions</th>
            <th>Patient Name</th>
            <th>UHID</th>
            <th>Registration ID</th>
            <th>Contact</th>      
        </tr>
        </thead>
        <tbody>
        @if(!isset($print))
            <tr class="table_search">
                <td class="icons">
                    &nbsp;
                </td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_name"
                           value="{{ @$search_data['name'] }}" name="slug" placeholder="search Patient name"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_uhid"
                           value="{{ @$search_data['uhid'] }}" name="slug" placeholder="search uh id"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_kid"
                           value="{{ @$search_data['kid'] }}" name="slug" placeholder="search Registeration id"/> <i
                            class="fa fa-filter"></i>
                </td>
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_contact"
                           value="{{ @$search_data['contact'] }}" name="slug" placeholder="search Contact"/> <i
                            class="fa fa-filter"></i>
                </td>
            </tr>
        @endif
        <tr>
            <td colspan=8>
                    <div class="ui negative icon message">
                        <i class="frown icon"></i>
                        <div class="content">
                            <div class="header">
                            </div>
                            <p>There are currently no Record</p>
                        </div>
                    </div>
                </td>
        </tr>
        </tbody>
    </table>
</div>
@endif