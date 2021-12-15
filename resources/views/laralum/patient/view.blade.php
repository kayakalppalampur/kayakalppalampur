@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">

        <a class="section" href="{{ $user->is_discharged == \App\User::ADMIT ? route('Laralum::patient-history') : route('Laralum::archived.patients.list')  }}">{{ trans('laralum.patients_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.patient_details') }}</div>
    </div>
@endsection
@section('title', 'Patient Details')
@section('icon', "pencil")
@section('subtitle', 'Patient Details')

@section('content')
    <br><br>
    <div class="ui one column doubling stackable grid container">
        {{--  <div>
              <button onclick="return window.history.go(-1);" class="btn btn-primary ui button blue">
                  Back
              </button>
          </div>--}}
        <div class="column admin_basic_detail1">
            <div class="ui very padded segment">
                <div  class="page_title">
                    <h2 class="pull-left">Basic Details</h2>
                    {{--@if(Laralum::loggedInUser()->hasPermission('generate.token'))--}}
                        <div class="pull-right">
                            <button class="btn btn-primary ui button blue" id="accommodation_details" href="{{ url('admin/patient/accommodation/'.$user->id) }}">Accommodation Details</button>
                            <button class="btn btn-primary ui button blue" id="account_details" href="{{ url('admin/patient/account/'.$user->id) }}">Account Details</button>
                        </div>
                    {{--@endif--}}
                </div>
                <table class="ui table">
                    <thead>
                    <tbody>
                    <tr>
                        <th>Name</th>
                        <td style="border-right:1px solid #ddd">{{ $user->name }}</td>
                        <th>Type</th>
                        <td>{{ $user->userProfile->patient_type != null ? $user->userProfile->getPatientType($user->userProfile->patient_type) : "" }}</td>
                    </tr>
                    <tr>
                        <th>S/o, D/o, W/o </th>
                        <td style="border-right:1px solid #ddd">{{ $user->userProfile->relative_name }}</td>
                        <th>Gender</th>
                        <td>{{ $user->userProfile->gender != null ? $user->userProfile->getGenderOptions($user->userProfile->gender) : "" }}</td>
                    </tr>
                    <tr>
                        <th>Age</th>
                        <td style="border-right:1px solid #ddd">{{ $user->userProfile->age }}</td>
                        <th>Contact Number</th>
                        <td>{{ $user->userProfile->mobile }}</td>
                    </tr>
                    <tr>
                        <th>Landline Number</th>
                        <td style="border-right:1px solid #ddd">{{ $user->userProfile->landline_number }}</td>
                        <th>Whatsapp Number</th>
                        <td>{{ $user->userProfile->whatsapp_number }}</td>
                    </tr>
                    <tr>
                        <th>Marital Status</th>
                        <td style="border-right:1px solid #ddd">{{ $user->userProfile->marital_status != null ? $user->userProfile->getMaritalStatus($user->userProfile->marital_status) : ""}}</td>
                        <th>Profession</th>
                        <td>{{ @$user->userProfile->getProfessionType($user->userProfile->profession_id) }}</td>
                    </tr>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-12">
                        <div class="title">
                            <div class="space10"></div>
                            <div class="page_title"><h2>Health Issues</h2></div>
                            <div class="divider space10"></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table ui">
                                <tbody>
                                <tr>
                                    <th width="20%">Health Issues</th>
                                    <td width="80%">{{ $user->userProfile->health_issues }}</td>

                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="title">
                            <div class="space10"></div>
                            <div class="page_title"><h2>Address Details</h2></div>
                            <div class="divider space10"></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table ui">
                                <tbody>
                                <tr>
                                    <th width="30%">Address</th>
                                    <td width="70%">{!! isset($user->address->address1) ? $user->address->address1.', '.$user->address->address2.'<br>'.$user->address->city.', '.$user->address->zip.'<br>'.$booking->getAddress('state').'<br>'.$user->address->country : ""!!}</td>
                                </tr>
                                <tr>
                                    <th width="30%">Referral Source</th>
                                    <td width="70%">{{ isset($user->address->address1) ?  $user->address->referral_source : ""}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
              {{--  @if($user->getBooking() != null)

                    <div class="row">
                        <div class="col-md-12">
                            <div class="title">
                                <div class="space10"></div>
                                <div class="page_title"><h2>Accomodation Details</h2></div>
                                <div class="divider space10"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table ui">
                                    <tbody>
                                    <tr>
                                        <th>Booking From</th>
                                        <td>{!! date('d-m-Y', strtotime($user->getBooking()->check_in_date)) !!}</td>
                                        <th>Booking End</th>
                                        <td>{!! date('d-m-Y', strtotime($user->getBooking()->check_out_date)) !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Building Name</th>
                                        <td>{!! $user->getBooking()->room->building->name !!}</td>
                                        <th>Booking Type</th>
                                        <td>{!! $user->getBooking()->getBookingType($user->getBooking()->booking_type)!!}</td>

                                    </tr>
                                    <tr>
                                        <th>Room No</th>
                                        <td>{!! $user->getBooking()->room->room_number !!}</td>
                                        <th>Room Type</th>
                                        <td>{!! $user->getBooking()->room->roomType->name !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif--}}
              {{--  <div class="row">
                    <div class="col-md-12">
                        <div class="title">
                            <div class="space10"></div>
                            <div class="page_title"><h2>Account Details</h2></div>
                            <div class="divider space10"></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table ui">
                                <tbody>
                                @if($user->checkAccommodation())
                                    <tr>
                                        <th>Booking Price</th>
                                        <td>{{ $user->getBookingPrice() }}</td>
                                    </tr>
                                    @foreach($user->getServices() as $service)
                                        <tr>
                                            <th>{{ $service->service->name }}</th>
                                            <td>{{ $service->service->price }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                <tr>
                                    <th>Basic Price</th>
                                    <td>{!! \App\Settings::BASIC_PRICE !!}</td>
                                </tr>
                                <tr>
                                    <th>Discount</th>
                                    <td>{!! $user->getDiscount()!!}</td>
                                </tr>
                                <tr>
                                    <th>Total Amount</th>
                                    <td>{!!  $user->getTotalAmountPaid() !!}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>--}}

            </div>
        </div>
    </div>
    <div class="modal fade" id="bookingModal" role="dialog" data-backdrop="static">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="modal-close close"  data-dismiss="modal">&times;</button>
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
@section("js")
<script>
    $("#accommodation_details").click(function(e) {
        e.preventDefault();
        console.log("asdsa");
        // var pageName = $(this).attr('pageName');
        var url = '{{ url('/admin/patient/get-accommodation-details/'.$user->id) }}';
        var title = "Accommodation Details";
        openModal(url, title);
    });

    $("#account_details").click(function(e) {
        e.preventDefault();
        console.log("asdsa");
        // var pageName = $(this).attr('pageName');
        var url = '{{ url('/admin/patient/get-account-details/'.$user->id) }}';
        var title = "Account Details";
        openModal(url, title);
    });
    $(document).delegate("[id^=show_details_]", "click", function () {
        var id = $(this).split("show_details_")[1];
        $("#details_"+id).show();
    });

    function openModal(url, title ) {
        $(".modal .modal-title").html(title);
        $(".modal .modal-body").html("Content loading please wait...");
        $(".modal").modal("show");
        $(".modal").modal({
            backdrop: 'static',
            keyboard: false,
        });
        $(".modal .modal-body").load(url);
    }
    $(".modal-close").click(function() {
        $(".modal").modal("hide");
    });
</script>
@endsection