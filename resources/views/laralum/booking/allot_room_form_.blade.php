@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Allot Rooms</div>
    </div>
@endsection
@section('title', 'Bookings')
@section('icon', "pencil")
@section('subtitle', 'List of all bookings')
@section('content')
  <div class="ui one column doubling stackable grid container">

      <div class="column">

           <div class="ui very padded segment">
               <table class="ui table">
                   <tbody>
                   <tr>
                       <th>Name</th>
                       <td style="border-right:1px solid #ddd">{{ $booking->user->name }}</td>
                       <th>Type</th>
                       <td>{{ $booking->patient_type != null ? $booking->getPatientType($booking->patient_type) : "" }}</td>
                   </tr>
                   <tr>
                       <th>S/o, D/o, W/o </th>
                       <td style="border-right:1px solid #ddd">{{ $booking->user->userProfile->relative_name }}</td>
                       <th>Gender</th>
                       <td>{{ $booking->user->userProfile->gender != null ? $booking->user->userProfile->getGenderOptions($booking->user->userProfile->gender) : "" }}</td>
                   </tr>
                   <tr>
                       <th>Age</th>
                       <td style="border-right:1px solid #ddd">{{ $booking->user->userProfile->getAge() }}</td>
                       <th>Contact Number</th>
                       <td>{{ $booking->user->userProfile->mobile }}</td>
                   </tr>
                   <tr>
                       <th>Marital Status</th>
                       <td style="border-right:1px solid #ddd">{{ $booking->user->userProfile->marital_status != null ? $booking->user->userProfile->getMaritalStatus($booking->user->userProfile->marital_status) : ""}}</td>
                       <th>Profession</th>
                       <td>{{ @$booking->user->userProfile->getProfessionType($booking->user->userProfile->profession_id) }}</td>
                   </tr>
                   </tbody>
               </table>
               <div class="row">
                   <div class="">
                       <div class="title">
                           <div class="space10"></div>
                           <div class="page_title"><h2>Accommodation Request</h2></div>
                           <div class="divider space10"></div>
                       </div>
                   </div>
                   <div class="">
                       <div class="table-responsive">
                           <table class="table ui">
                               <tbody>
                               <tr>
                                   <th>Building</th>
                                   <th>Floor</th>
                                   <th>Booking Type</th>
                                   <th>Check In Date</th>
                                   <th>Check Out Date</th>
                                   <th>Allotted Room</th>
                                   <th></th>
                               </tr>

                               <tr>
                                   <td>{{ $booking->building->name }}</td>
                                   <td>{{ \App\Room::getFloorNumber($booking->floor_number) }}</td>
                                   <td>{{ $booking->getBookingType($booking->booking_type) }}</td>
                                   <td>{{ date("d-m-Y", strtotime($booking->check_in_date)) }}</td>
                                   <td>{{ date("d-m-Y", strtotime($booking->check_out_date) }}</td>
                                   <td>{{ $booking->getRoomDetails() }}</td>
                                   <th><a id="allot-room-patient" class="button ui">Allot Room/Bed</a></th>
                               </tr>
                               </tbody>
                           </table>
                       </div>
                   </div>
               </div>

               <div class="row">
                   <div class="">
                       <div class="title">
                           <div class="space10"></div>
                           <div class="page_title"><h2>Members</h2></div>
                           <div class="divider space10"></div>
                       </div>
                   </div>
                   <div class="">
                       <div class="table-responsive">
                           <table class="table ui">
                               <tr>
                                   <th>Name</th>
                                   <th>Age</th>
                                   <th>Gender</th>
                                   <th>Id Proof</th>
                                   <th>Check In Date</th>
                                   <th>Check Out Date</th>
                                   <th>Allotted Room</th>
                                   <th>Action</th>
                               </tr>
                               <tbody>
                               @foreach($booking->members as $member)
                               <tr>
                                   <th>{{ $member->name }}</th>
                                   <td>{{ $member->age }}</td>
                                   <th>{{ $member->getGenderOptions($member->gender) }}</th>
                                   <td>@if($member->id_proof != null) <a href="{{  \App\Settings::getDownloadUrl($member->id_proof)}}">Download</a> @else -- @endif</td>
                                   <th>{{ date("d-m-Y", strtotime($member->booking->check_in_date)) }}</th>
                                   <th>{{ date("d-m-Y", strtotime($member->booking->check_out_date)) }}</th>
                                   <td></td>
                                   <th><a id="allot-room_{{ $member->id }}" class="button ui">Allot Room/Bed</a></th>
                               </tr>
                                   @endforeach
                               </tbody>
                           </table>
                       </div>
                   </div>
               </div>

          </div>
      </div>
  </div>

  <!-- Modal -->
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
                  <button type="button" class="ui blue btn btn-default modal-close" data-dismiss="modal">Close</button>
              </div>
          </div>

      </div>
  </div>
@endsection
@section('js') 
<script>
    $("#allot-room-patient").click(function () {
        var allot_room_url = "{{ url("admin/booking/allot-room-form/".$booking->id) }}";
        $(".modal .modal-body").load(allot_room_url);
    })
</script>
@endsection



