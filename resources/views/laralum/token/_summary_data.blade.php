<div class="table_head_lft">
    <table class="ui table table_cus_v bs">
        <tr>
            <th>UHID</th>
            <td style="word-break: break-all;">{{ $booking->user->uhid}}</td>
            <th>Patient Id</th>
            <td style="word-break: break-all;">{{ $booking->getProfile('kid') }}</td>
            <th>Booking Id</th>
            <td style="word-break: break-all;">{{ $booking->booking_id }}</td>
        </tr>
    </table>
</div>

<div class="table_head_lft">
    <table id="w-comman-1" class="ui table table_cus_v bs">
        <thead>
        <tbody>

        <tr>
            <th>Name</th>
            <td style="border-right:1px solid #ddd; word-break: break-all;">{{ $booking->getProfile('first_name').' '.$booking->getProfile('last_name')}}</td>
            <th>Type</th>
            <td style="word-break: break-all;">{{ $booking->patient_type != null ? $booking->getPatientType($booking->patient_type) : "" }}</td>
        </tr>
        <tr>
            <th>S/o, D/o, W/o</th>
            <td style="border-right:1px solid #ddd; word-break: break-all;">{{ $booking->getProfile('relative_name')}}</td>
            <th>Gender</th>
            <td style="word-break: break-all;">{{ \App\UserProfile::getGenderOptions($booking->getProfile('gender')) }}</td>
        </tr>
        <tr>
            <th>Age</th>
            <td style="border-right:1px solid #ddd; word-break: break-all;">{{ $booking->getProfile('age') }}</td>
            <th>Contact Number</th>
            <td style="word-break: break-all;">{{ $booking->getProfile('mobile') }}</td>
        </tr>
        <tr>
            <th>Landline Number</th>
            <td style="border-right:1px solid #ddd; word-break: break-all;">{{ $booking->getProfile('landline_number') }}</td>
            <th>Whatsapp Number</th>
            <td style="word-break: break-all;">{{ $booking->getProfile('whatsapp_number') }}</td>
        </tr>
        <tr>
            <th>Marital Status</th>
            <td style="border-right:1px solid #ddd;word-break: break-all;">{{ $booking->getProfile('marital_status') != null ? \App\UserProfile::getMaritalStatus($booking->getProfile('marital_status')) : ""}}</td>
            <th>Profession</th>
            <td style="word-break: break-all;">{{ $booking->getProfile('profession_id') != null ? \App\UserProfile::getProfessionType($booking->getProfile('profession_id')) : "" }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td style="border-right:1px solid #ddd;word-break: break-all;">{{ $booking->getStatusOptions($booking->status) }}</td>
            <th>Address</th>
            <td style="word-break: break-all;">{!! $booking->getAddress('address1').', '.$booking->getAddress('address2').', '.$booking->getAddress('city').', '.$booking->getAddress('zip').'<br>'.$booking->getAddress('country') !!}</td>
        </tr>
        <tr>
            <th>Referral Source</th>
            <td style="word-break: break-all;">{{ $booking->getAddress('referral_source')}}</td>
            <th>Date of admission</th>
            <td style="word-break: break-all;">{{ date('d-m-Y', strtotime($booking->getDate('check_in_date'))) }}</td>
        </tr>

        </tbody>
    </table>
</div>
@if($patient_detail->id != null)
    <div class="table_head_lft">
        <table class="ui table table_cus_v bs">
            <tbody>

            <tr>
                @if($patient_detail->bp)
                    <th>BP (mm Hg)</th>
                    <td style="word-break: break-all;">{{ $patient_detail->bp }}</td>
                @endif
                @if($patient_detail->blood_group)
                    <th>Blood Group</th>
                    <td style="word-break: break-all;">{{ $patient_detail->blood_group }}
                @endif
                @if($patient_detail->height)
                    <th>Height (in cm)</th>
                    <td style="word-break: break-all;">{{ $patient_detail->height }}</td>
                @endif
                @if($patient_detail->weight)
                    <th>Weight (Kgs)</th>
                    <td style="word-break: break-all;">{{ $patient_detail->weight }}</td>
                @endif
                @if($patient_detail->bmi)
                    <th>BMI</th>
                    <td style="word-break: break-all;">{{ $patient_detail->bmi }}</td>
                @endif
            </tr>
            </tbody>
        </table>
    </div>
@endif

@include('laralum.token.discharge_data_summary')

@include('laralum.token.vital_data_summary')

@include('laralum.token.physiotherpy_vital_data_summary')

@include('laralum.token.ayurved_vital_data_summary')

@include('laralum.token.lab_data_summary')

@if($treatments->count() > 0)
    <table class="ui table table_cus_v last_row_bdr">
        <thead>
        <tr><th colspan="7"><h5>Treatments</h5></th></tr>
        <tr>
            <th>Date</th>
            <th>Department</th>
            <th>Treatments</th>
            <th>Note</th>
            <th>Ratings</th>
            <th>Brief Feedback</th>
            <th>Doctor Remark</th>
        </tr>
        </thead>
        <tbody>
        @foreach($treatments as $treatment)
            <tr class="{{ $treatment->isSpecial() ? "special-treatment" : "" }}">
                <td style="word-break: break-all;">    @if( $treatment->isSpecial()) <strong>(Special Treatment)</strong>
                    <br>@endif{{ date('d-m-Y',strtotime($treatment->treatment_date)) }} {{ date('h:i a',strtotime($treatment->created_at)) }}
                    @if($treatment->getDetails())<br/>
                    <span>Weight: {{ $treatment->weight }}</span><br/>
                    <span>BP: {{ $treatment->bp }}</span>
                    <span>Pulse: {{ $treatment->pulse }}</span>
                    @endif
                </td>
                <td style="word-break: break-all;">{{ $treatment->department->title }}</td>
                <td style="word-break: break-all;">

                    @foreach($treatment->treatments as $pat_treat)
                        <span>{{ $pat_treat->treatment->title." (".$pat_treat->treatment->getDuration().')' }}</span>
                        <br/>
                    @endforeach
                </td>
                <td style="word-break: break-all;">{{ $treatment->note }}</td>
                <td style="word-break: break-all;">  @foreach($treatment->treatments as $pat_treat)
                        <span class="col-lg-fg"> {{ $pat_treat->ratings }}</span><br/>
                    @endforeach</td>

                <td style="word-break: break-all;">{!!  $treatment->feedback  !!}</td>
                <td style="word-break: break-all;">{!! $treatment->doctor_remark !!}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif