@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @if($booking->isEditable())
            <a class="section" href="{{ route('Laralum::patients') }}">{{ trans('laralum.patient_list') }}</a>
        @else
            <a class="section" href="{{ route('Laralum::archived.patients.list') }}">{{ trans('laralum.archived_patients') }}</a>
        @endif
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.lab_test_list') }}</div>
    </div>
@endsection
@section('title', 'Lab Tests')
@section('icon', "pencil")
@section('subtitle', '')
@section('content')
    <div class="ui one column doubling stackable">
        <div class="column admin_wrapper">
            
        </div>
    </div>

    <div class="ui one column doubling stackable">
        <div class="column admin_wrapper">
            <div class="segment main_wrapper">
                <div class="ui breadcrumb steps clearfix">
                    <ul>
                        <li> <a class="section" href="{{ route('Laralum::patient.patient-details', ['booking_id' => $booking->id]) }}">Personal Details</a>
                        </li>
                        
                        <li>  <div class="active section">Lab Tests</div></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="admin_wrapper signup">
        <div class="main_wrapper">
            <div class="ui very padded segment">
                       

                       
                        <div class="white_bg signup_bg discharge_form">

                            <div class="page_title table_top_btn rell">
                                <div class="vital-head">{{ $booking->getProfile('first_name').' '.$booking->getProfile('last_name')}}</div>
                                <div class="pull-left btn-group">
                                 @if($lab_tests->count() > 0)
                                 @php $price = 0; @endphp
                                    @foreach($lab_tests as $lab_test) 
                                      @php $price +=  $lab_test->getAllPrice(); @endphp
                                    @endforeach
                                   <b> Total Price: </b> {{ $price }}
                                 @endif
                                </div>
                            </div>
                            <br>



                            
                            <div class="history_table1">
                                @if($lab_tests->count() > 0)
                            	<table class="ui table table_cus_v last_row_bdr">
								  <thead>
									  <tr>
										  <th>Date</th>
										  <th>Tests</th>
                                          <th>Result</th>
                                          <th>Price</th>
                                          <th>Actions</th>
									  </tr>
								  </thead>
								  <tbody>
								  @foreach($lab_tests as $lab_test)
									  <tr>
										  <td>{{ $lab_test->date_date }}</td>
                                          <td>{{ $lab_test->getTestsName() }}</td>
                                          <td>{{ $lab_test->note }}</td>
                                          <td>{{ $lab_test->getAllPrice() }}</td>
                                          <td>
                                              <!-- <a title="Print"  href="{{ url("admin/patient/print-lab-test/".$lab_test->id) }}"><i class="fa fa-print"></i> </a> -->
                                            <div class="upload_btn" title="Upload Report" id="upload_report_{{ $lab_test->id }}">
                                              <i class="fa fa-upload" aria-hidden="true" style="color:#4183C4;"></i>
                                            </div>
                                            &nbsp &nbsp 
                                            @if($lab_test->test_status == 1)
                                            <a class="upload_btn" title="Download Report" id="download_report_{{ $lab_test->id }}" href="{{ url("admin/patient/download_report/".$lab_test->id) }}">
                                                <i class="fa fa-download" aria-hidden="true"></i>
                                            </a>
                                            @endif
                                          </td>

									  </tr>
								  @endforeach
                                  </tbody>
							    </table>
                                    @else
                                    <div class="ui negative icon message">
                                        <i class="frown icon"></i>
                                        <div class="content">

                                            <p>There are currently no tests assigned to this patient</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                        </div>

                </div>

        </div>
    </div>
    <!-- Modal -->
    <style type="text/css" media="print">
        @page {
            size: auto;   /* auto is the initial value */
            margin: 0mm;  /* this affects the margin in the printer settings */
        }
    </style>



    <div class="modal fade" id="bookingModal" role="dialog" data-backdrop="static">
        <div class="modal-dialog" style="width:800px;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="modal-close close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Booking Wizard</h4>
                </div>
                <div class="modal-body">
                    <p>Some text in the modal.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default modal-close" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('js')



    <script>
        $(document).delegate('[id^=upload_report_]', 'click', function (e) {
            e.preventDefault();
            var id = $(this).attr('id').split('upload_report_')[1];
            var url = "{{ url('admin/patient/lab_test_report/') }}"+'/'+id;
            var title = "Print Noc";
            openModal(url, title);
        });

        function openModal(url, title) {
         // console.log(title);
            $(".modal .modal-title").html(title);
            $(".modal .modal-body").html("Content loading please wait...");
            $(".modal").modal("show");
            $(".modal").modal({
                backdrop: 'static',
                keyboard: false,
            });
           // console.log(url);
            $(".modal .modal-body").load(url);
        }

        $(".modal-close").click(function () {
            $(".modal").modal("hide");
        });

        $("[id^=download_report_]").click(function () {
            setInterval(hideloader2, 1000);
        })
        
        function hideloader2(){
            location.reload();
        }
    </script>
@endsection