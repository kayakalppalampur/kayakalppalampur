    <div class="table_head_lft">
        <table id="w-comman-1" class="ui table table_cus_v bs">
            <tbody>
            <tr>
                <th colspan="2"><h5>Discharge Details</h5></th>
            </tr>
            <tr>
                <th>DOA*</th>
                <td style="border-top: 1px solid #ddd">{{ date('d-m-Y', strtotime($booking->getDate('check_in_date'))) }}</td>
                
            </tr>
            <tr>
                <th>DOD</th>
                <td style="border-top: 1px solid #ddd">{{ $discharge_patient->date_of_discharge != null && $discharge_patient->date_of_discharge != "0000-00-00" ? date("d-m-Y", strtotime($discharge_patient->date_of_discharge)) : date("d-m-Y") }}</td>
             
            </tr>
            <tr>
                <th>Diagnosis.</th>
                 <td>{!! isset($diagnosis) ? $diagnosis->description != "" ? $diagnosis->description : $booking->getComplaints() : $booking->getComplaints()  !!}</td>
              
            </tr>
            @if($discharge_patient->discharge_summary != '')
                <tr>
                    <th>Discharge Summary</th>
                    <td>{!! $discharge_patient->discharge_summary !!}</td>
                    
                </tr>
            @endif
            @if($discharge_patient->investigation_report != '')
                <tr>
                    <th>Investigation Report (if any)</th>
                    <td>{!! $discharge_patient->investigation_report !!}</td>
                    
                </tr>
            @endif
            @if($discharge_vital->bp != '')
                <tr>
                    <th>BP (On Discharge) </th>
                    <td>{!! $discharge_vital->bp !!}</td>
                </tr>
            @endif
            @if($discharge_vital->pulse != '')
                <tr>
                    <th>PR  (On Discharge) </th>
                    <td> {!! $discharge_vital->pulse !!}</td>
                </tr>
            @endif
            @if($discharge_vital->weight != '')
                <tr>
                    <th>WT (On Discharge) </th>
                    <td>{!! $discharge_vital->weight !!}</td>
                </tr>
            @endif
             @foreach(\App\Department::all() as $department)
                 @php $department_discharge = \App\DepartmentDischargeBooking::where('booking_id', $booking->id)->where('department_id', $department->id)->first();
                                                if ($department_discharge == null) {
                                                    $department_discharge = new \App\DepartmentDischargeBooking();
                                                }
                            @endphp
                @if($department_discharge->summary != '')
                    <tr>
                        <th><h5>{{ $department->title }}</h5></th>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Summary</th>
                        <td>{!! $department_discharge->summary !!}</td>
                    </tr>
                    <tr>
                        <th>Things To Avoid</th>
                        <td>{!! $department_discharge->things_to_avoid !!}</td>
                    </tr>
                    <tr>
                        <th>Follow up Advice</th>
                        <td>{!! $department_discharge->follow_up_advice !!}</td>
                    </tr>
                @endif
            @endforeach
            @if($discharge_patient->diet_plan_duration > 0)
                <tr>
                    <th>Diet Plan for</th>
                    <td>@if($discharge_patient->diet_plan_duration > 0) {{ $discharge_patient->diet_plan_duration }}  @if($discharge_patient->diet_plan_duration > 1) Days @else Day @endif @endif</td>
                </tr>
            @endif
            @if($discharge_patient->getFollowupDays() > 0)
                <tr>
                    <th>Next Follow Up Plan After</th>
                    <td>@if($discharge_patient->getFollowupDays() > 0) {{ $discharge_patient->getFollowupDays() }} @if($discharge_patient->getFollowupDays() > 1) Days @else Day @endif @endif</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
