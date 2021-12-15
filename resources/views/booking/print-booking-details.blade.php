@extends('layouts.front.web_layout')
@section('content')
    <style type="text/css" media="print">
        @page {
            size: auto;   /* auto is the initial value */
            margin: 0mm;  /* this affects the margin in the printer settings */
        }

        .date {
            background-color: #ccc;
            float: left;
            padding: 8px;
            width: 75%;
        }
    </style>
    <style>
        #mySelector.token-receipt {
            max-width: 800px;
        }

        .token-detail-box {
            display: inline-block;
            width: 100%;
            margin: 0px 0px 10px;
        }
    </style>

    <div class="ui one column doubling stackable grid container">
        <div class="column">

        </div>
    </div>


    <div class="token-receipt" id="mySelector" style="width:1000px;max-width:1000px;">

        <div class="receipt-for">
            <p>@if(!isset($print)) <a id="print" class="btn btn-primary ui button blue">Print</a> 
            <a id="back" class="btn btn-primary ui button blue" href="{{ isset($back_url) ? $back_url : url('/admin/booking/'.$booking->id.'/details') }}">Back</a>  @endif
            </p>
        </div>
        <div class="token-detail-box" id="">
        <div class="patient_form_wrap" style="text-align: center;display: inline-block;width: 100%;">
            <h2 style="text-transform: uppercase;font-size:16px;margin-top:0;font-weight:600;line-height:22px;margin-bottom:0;">
                Vivekanand medical research trust, holta, palampur, (Regd.)<br> Distt. Kangra, Himachal pradesh -176062
            </h2>
            <div class="logo_kaya" style="position: relative;min-height: 95px;">
                <div class="logo_form" style="float: left;">
                    <img width="100px" src="{{asset('images/slip_left_logo.jpg')}}">
                </div>
                <div class="center_head" style="position: absolute;left: 50%;transform: translateX(-50%)">
                    <h3 style="text-transform: uppercase;margin: 0;font-weight: bold;font-size:30px;">Kayakalp</h3>
                    <p style="text-transform: uppercase;font-size:16px;line-height:20px;">Himalayan research
                        institute<br> for yoga and naturopathy</p>
                </div>
                <div class="form_phone_detail" style="float: right;text-align:right;">
                    <img width="100px" src="{{ asset('images/slip_right_logo.jpg') }}">
                    <span style="display: block;font-size:16px;margin-top:10px;">Phone: (01894) 235676</span>
                    <span style="display: block;font-size:16px;">Tele Fax: (01894) 235666</span>
                    <span style="display: block;font-size:16px;">Mobile No: 7807310891</span>
                </div>
            </div>

        </div>


            <p class="visit-dr">Basic Details</p>
            <dl>
                <div class="dt-dd detail_inline">
                    <dt>UHID</dt>
                    <dd>{{ $booking->getProfile('uhid')}}</dd>
                    @if (!empty($booking->user->registration_id))
                        <dt>Booking Id:</dt>
                        <dd> {{ $booking->user->registration_id}}</dd>
                    @endif
                    <dt>Patient Id:</dt>
                    <dd> {{ $booking->getProfile('kid') }}</dd>
                    <dt>Registration Id:</dt>
                    <dd> {{ $booking->booking_id }}</dd>
                </div>

                <div class="dt-dd detail_inline">
                    @if (!empty($booking->user->name))
                        <dt>Name:</dt>
                        <dd> {{  $booking->user->name }}</dd>
                    @endif
                    @if (!empty($booking->user->email))
                        <dt>Email:</dt>
                        <dd> {{  $booking->user->email }}</dd>
                    @endif
                    <dt>Type:</dt>
                    <dd>{{ $booking->patient_type != null ? $booking->getPatientType($booking->patient_type) : "" }}</dd>
                </div>

                <div class="dt-dd detail_inline">
                    <dt>Patient's Name:</dt>
                    <dd>{{ $booking->getProfile('first_name').' '.$booking->getProfile('last_name')  }}</dd>
                    <dt>S/o, D/o, W/o:</dt>
                    <dd>{{ $booking->getProfile('relative_name') }}</dd>
                    <dt>Gender:</dt>
                    <dd>{{ $booking->getProfile('gender') != null ? \App\UserProfile::getGenderOptions($booking->getProfile('gender')) : "" }}</dd>
                </div>


                <div class="dt-dd detail_inline">
                    <dt>Age:</dt>
                    <dd>{{ $booking->getProfile('age') }}</dd>
                    <dt>Marital Status:</dt>
                    <dd>{{ $booking->getProfile('marital_status') != null ? \App\UserProfile::getMaritalStatus($booking->getProfile('marital_status')) : ""}}</dd>
                    <dt>Profession:</dt>
                    <dd>{{ @\App\UserProfile::getProfessionType($booking->getProfile('profession_id')) }}</dd>
                </div>

                <div class="dt-dd detail_inline">
                    <dt>Contact Number:</dt>
                    <dd>{{ $booking->getProfile('mobile') }}</dd>
                    <dt>Landline Number:</dt>
                    <dd>{{ $booking->getProfile('landline_number') }}</dd>
                    <dt>Whatsapp Number:</dt>
                    <dd>{{ $booking->getProfile('whatsapp_number') }}</dd>
                </div>

                <div class="dt-dd detail_inline">
                    <dt>Created at:</dt>
                    <dd>{{ date("d-m-Y H:i:s", strtotime($booking->created_at)) }}</dd>
                    <dt>Status:</dt>
                    <dd>{{ $booking->getStatusOptions($booking->status) }}</dd>
                </div>
            </dl>
        </div>

        <div class="token-detail-box" id="">
            <p class="visit-dr">Health Issues</p>
            <dl>
                <div class="dt-dd detail_inline">
                    {!!  $booking->getProfile('health_issues')  !!}
                </div>
            </dl>
        </div>

        <div class="token-detail-box" id="">
            <p class="visit-dr">Address Details</p>
            <dl>
                <div class="dt-dd detail_inline">
                    <dt>Address:</dt>
                    <dd>{!! $booking->getAddress('address1') ? $booking->getAddress('address1').', '.$booking->getAddress('address2').', '.$booking->getAddress('city').', '.$booking->getAddress('zip').' '.$booking->getAddress('country') : ""!!}</dd>
                </div>
                <div class="dt-dd detail_inline">
                    <dt>Referral Source:</dt>
                    <dd>{{ $booking->getAddress('address1') ?  $booking->getAddress('referral_source') : ""}}</dd>
                </div>
            </dl>
        </div>


        @if($booking->id != null && $booking->checkAccommodation())
            <div class="token-detail-box" id="">
                <p class="visit-dr">Accomodation Details</p>
                <dl>
                    <div class="dt-dd detail_inline">
                        <dt>Booking Requested Dates:</dt>
                        <dd>{!! $booking->check_in_date_date !!} - {!! $booking->check_out_date_date !!}</dd>
                    </div>
                    <div class="dt-dd detail_inline">
                        <dt>Requested Building Name:</dt>
                        <dd>{{ \App\Room::getBuildingName($booking->building_id)  }} - {{ \App\Room::getFloorNumber($booking->floor_number) }} ({{ $booking->getBookingType($booking->booking_type) }})</dd>
                    </div>
                    <div class="dt-dd detail_inline">
                        <dt>Requested Services:</dt>
                        <dd>@if($booking->is_child == '1') {{ $booking->child_count  }} Child @endif
                            @if($booking->is_driver == '1') {{ $booking->driver_count }} Driver @endif </dd>
                    </div>
                </dl>
                @if($booking->members->count() > 0)
                    <p class="visit-dr">Members Details</p>
                    <dl>
                        @foreach($booking->members as $member)

                            <table class="table ui">
                                <tr>
                                    <th>Member Name</th>
                                    <th>Age</th>
                                    <th>Gender</th>
                                    <th>Requested Room details</th>
                                    <th>Requested Dates</th>
                                    <th>Requested Service Details</th>
                                </tr>
                                    <tr>
                                        <td>{{ $member->name }}</td>
                                        <td>{{ $member->age }}</td>
                                        <td>{{ $member->getGenderOptions($member->gender) }}</td>
                                        <td>{{ \App\Room::getBuildingName($member->building_id)  }} - {{ \App\Room::getFloorNumber($member->floor_number) }} ({{ $booking->getBookingType($member->booking_type) }})</td>
                                        <td>{{ date("d-m-Y",strtotime($member->check_in_date)) }} to {{ date("d-m-Y",strtotime($member->check_out_date)) }}</td>
                                        <td>@if($member->is_child == '1') {{ $member->child_count  }} Child @endif
                                            @if($member->is_driver == '1') {{ $member->driver_count }} Driver @endif </td>

                                    </tr>
                            </table>
                        @endforeach
                    </dl>
                @endif
                <p class="visit-dr">Accomodation History</p>
                <dl>
                    <div class="dt-dd history_table">
                        @if(!empty($booking->bookingRooms))
                            <table class="table ui">
                                <tr>
                                    <th>Person Name</th>
                                    <th>Is Patient</th>
                                    <th>Booking Id</th>
                                    <th>Patient Id</th>
                                    <th>Building Name</th>
                                    <th>Room No</th>
                                    <th>Booking Type</th>
                                    <th>Staying dates</th>
                                    <th>Services Details</th>
                                    <th>Price</th>
                                </tr>
                                @foreach($booking->bookingRoomsAll as $d)
                                    <tr>
                                        <td>{{ $d->getName() }}</td>
                                        <td>{{ $d->checkIfPatient() }}</td>
                                        <td>{{ $d->booking->booking_id }}</td>
                                        <td>{{ $d->booking->userProfile->kid }}</td>
                                        <td>{{ $d->room->building->name }}</td>
                                        <td>{{ $d->getRoomNumber() }}</td>
                                        <td>{{ $d->getBookingType($d->type) }}</td>
                                        <td>{{ date("d-m-Y", strtotime($d->check_in_date)) }}
                                            to {{ $discharge == true ?  date("Y-m-d") : date("d-m-Y", strtotime($d->check_out_date)) }} {{--> date("Y-m-d") ? "Till date" : date("d-m-Y", strtotime($d->check_out_date)) }}--}}</td>
                                        <!-- <td>{{ $discharge == true ? $d->allDaysPrice($d->room_id, false) :  $d->allDaysPrice($d->room_id, false) }}</td> -->
                                        <td>
                                            <div>{!! $d->serviceDetails()  !!}</div>
                                        </td>
                                        <td>{{ $d->allDaysPrice($d->room_id) }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif
                    </div>
                </dl>
            </div>
        @endif

        @if (!empty($patient_details->id))
            <div class="token-detail-box" id="">
                <p class="visit-dr">Patient Details</p>
                <dl>
                    <div class="dt-dd detail_inline">
                        <dt>Pulse:</dt>
                        <dd>{!! $patient_details->pulse !!}</dd>
                    </div>
                    <div class="dt-dd detail_inline">
                        <dt>Bp:</dt>
                        <dd>{!! $patient_details->bp !!}</dd>
                    </div>
                    <div class="dt-dd detail_inline">
                        <dt>Blood Group:</dt>
                        <dd>{!! $patient_details->blood_group !!}</dd>
                    </div>
                    <div class="dt-dd detail_inline">
                        <dt>Weight(in kgs):</dt>
                        <dd>{!! $patient_details->weight !!}</dd>
                    </div>
                    <div class="dt-dd detail_inline">
                        <dt>Height(in cms):</dt>
                        <dd>{!! $patient_details->height !!}</dd>
                    </div>
                    <div class="dt-dd detail_inline">
                        <dt>BMI:</dt>
                        <dd>{!! $patient_details->bmi !!}</dd>
                    </div>
                </dl>
            </div>
        @endif
   

    <div class="detail_container">
        @if($vitalData->id != null)
        <div class="token-detail-box" id="">

            <p class="visit-dr">Examinations Details</p>

                <div class="table_head_lft">
                    <table class="ui table table_cus_v bs">
                        <tbody>
                        <tr>
                            @if($vitalData->present_complaints != null)
                                <th width="30%">Present Complaints/Illness</th>
                                <td>{{ $vitalData->present_complaints }}</td>
                            @endif
                            @if($vitalData->past_illness != null)
                                <th>Past Illness</th>
                                <td>{{ $vitalData->past_illness }}</td>
                            @endif
                        </tr>

                        <tr>
                            @if($vitalData->treatment_details != null)
                                <th>Treatment & Medication</th>
                                <td>{{ $vitalData->treatment_details }}</td>
                            @endif
                            @if($vitalData->past_investigation != null)
                                <th>Past Investigation</th>
                                <td>{{ $vitalData->past_investigation }}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>

        </div>
        @endif


            @if($physical->id != null)
                <div class="token-detail-box" id="">
                <div class="table_head_lft">
                    <h5><b>General Physical Examinations</b></h5>
                    <table class="ui table table_cus_v bs">
                        <tbody>
                        <tr>
                            @if($physical->built != null)
                                <th width="30%">Built</th>
                                <td style="border-top: 1px solid #ddd">{{ $physical->built }}</td>
                            @endif
                            @if($physical->nourishment != null)
                                <th style="border-top: 1px solid #ddd">Nourishment</th>
                                <td style="border-top: 1px solid #ddd">{{ $physical->nourishment }}</td>
                            @endif
                        </tr>
                        <tr>
                            @if($physical->temperature != null)
                                <th>Temperature</th>
                                <td>{{ $physical->temperature }}</td>
                            @endif
                            @if($physical->respiratory_rate != null)
                                <th>Respiratory Rate</th>
                                <td>{{ $physical->respiratory_rate }}</td>
                            @endif

                        </tr>
                        <tr>
                            @if($physical->icterus != null)
                                <th>Icterus</th>
                                <td>{{ $physical->icterus }}</td>
                            @endif
                            @if($physical->cyanosis != null)
                                <th>Cyanosis</th>
                                <td>{{ $physical->cyanosis }}</td>
                            @endif

                        </tr>
                        <tr>
                            @if($physical->nails != null)
                                <th>Nails</th>
                                <td>{{ $physical->nails }}</td>
                            @endif
                            @if($physical->clubbing != null)
                                <th>Clubbing</th>
                                <td>{{ $physical->clubbing }}</td>
                            @endif

                        </tr>
                        <tr>
                            @if($physical->lymph_nodes_enlargement != null)
                                <th>Lymph Nodes Enlargement</th>
                                <td>{{ $physical->lymph_nodes_enlargement }}</td>
                            @endif
                            @if($physical->oedema != null)
                                <th>Oedema</th>
                                <td>{{ $physical->oedema}}</td>
                            @endif
                        </tr>
                        <tr>
                            @if($physical->tongue != null)
                                <th>Tongue</th>
                                <td style="border-right:1px solid #ddd">{{ $physical->tongue }}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
                </div>
            @endif

            @if($respiratory->id != null || $cardio->id != null)
                <div class="token-detail-box" id="">
                <div class="table_head_lft">
                    <h5><b>Systematic Examinations</b></h5>
                    <h6><b>Cardiovascular System</b></h6>
                    <table>
                        <tbody>
                        <tr>
                            @if($respiratory->chest_pain_doctor != null)
                                <th width="30%">Chest Pain</th>
                                <td style="border-top: 1px solid #ddd">{{ $respiratory->getValue('chest_pain')}}</td>
                                <td style="border-top: 1px solid #ddd">{{ $respiratory->chest_pain_doctor }}</td>
                            @endif
                            @if($cardio->dyspnoea_doctor != null)
                                <th style="border-top: 1px solid #ddd">Dyspnoea</th>
                                <td style="border-top: 1px solid #ddd">{{ $cardio->getValue('dyspnoea') }}</td>
                                <td style="border-top: 1px solid #ddd">{{ $cardio->dyspnoea_doctor}}</td>
                            @endif
                        </tr>

                        <tr>
                            @if($cardio->palpitations_doctor != null)
                                <th>Palpitations</th>
                                <td>{{ $cardio->getValue('palpitations') }}</td>
                                <td>{{ $cardio->palpitations_doctor }}</td>
                            @endif
                            @if($cardio->dizziness_doctor != null)
                                <th>Dizziness</th>
                                <td>{{ $cardio->getValue('dizziness') }}</td>
                                <td>{{ $cardio->dizziness_doctor }}</td>
                            @endif
                        </tr>
                        <tr>
                            @if($cardio->doctor_details != null)
                                <th>On examination</th>
                                <td style="border-right:1px solid #ddd">{{ $cardio->doctor_details }}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table_head_lft">
                    <h6><b>Respiratory System</b></h6>
                    <table class="ui table table_cus_v bs">
                        <tbody>
                        <tr>
                            @if($respiratory->cough_doctor != null)
                                <th>Cough/ Sputum</th>
                                <td style="border-top: 1px solid #ddd">{{ $respiratory->getValue('cough') }}</td>
                                <td style="border-top: 1px solid #ddd">{{ $respiratory->cough_doctor }}</td>
                            @endif
                            @if($respiratory->fever_doctor != null)
                                <th style="border-top: 1px solid #ddd">Fever/ Sweat</th>
                                <td style="border-top: 1px solid #ddd">{{ $respiratory->getValue('fever') }}</td>
                                <td style="border-top: 1px solid #ddd">{{ $respiratory->fever_doctor }}</td>
                            @endif
                        </tr>

                        <tr>
                            @if($respiratory->sinusitis_doctor != null)
                                <th>Sinusitis</th>
                                <td>{{ $respiratory->getValue('sinusitis') }}</td>
                                <td style="border-right:1px solid #ddd">{{ $respiratory->sinusitis_doctor}}</td>
                            @endif
                            @if($respiratory->chest_pain_doctor != null)
                                <th>Chest Pain</th>
                                <td>{{ $respiratory->getValue('chest_pain') }}</td>
                                <td style="border-right:1px solid #ddd"> {{ $respiratory->chest_pain_doctor }}</td>
                            @endif
                        </tr>
                        <tr>
                            @if($respiratory->wheeze_doctor != null)
                                <th>Wheeze</th>
                                <td>{{ $respiratory->getValue('wheeze') }}</td>
                                <td style="border-right:1px solid #ddd">{{ $respiratory->wheeze_doctor }}</td>
                            @endif
                            @if($respiratory->hoarsness_doctor != null)
                                <th>Hoarsness</th>
                                <td>{{ $respiratory->getValue('hoarsness') }}</td>
                                <td style="border-right:1px solid #ddd">{{ $respiratory->hoarsness_doctor }}</td>
                            @endif
                        </tr>
                        <tr>
                            @if($respiratory->doctor_details != null)
                                <th>On examination</th>
                                <td style="border-right:1px solid #ddd">{{ $respiratory->doctor_details }}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
                </div>
            @endif
            @if($genitorinary->id != null)
                <div class="token-detail-box" id="">
                <div class="table_head_lft">
                    <h5><b>Genitourinary System</b></h5>
                    <table class="ui table table_cus_v bs">
                        <tbody>
                        <tr>
                            @if($genitorinary->fever_doctor != null)
                                <th>Fever</th>
                                <td style="border-top: 1px solid #ddd">{{ $genitorinary->getValue('fever') }}</td>
                                <td style="border-top: 1px solid #ddd">{{ $genitorinary->fever_doctor }}</td>
                            @endif
                            @if($genitorinary->loin_pain_doctor != null)
                                <th style="border-top: 1px solid #ddd">Loin Pain</th>
                                <td style="border-top: 1px solid #ddd">{{ $genitorinary->getValue('loin_pain') }}</td>
                                <td style="border-top: 1px solid #ddd">{{ $genitorinary->loin_pain_doctor }}</td>
                            @endif
                        </tr>

                        <tr>
                            @if($genitorinary->dysuria_doctor != null)
                                <th>Dysuria</th>
                                <td>{{ $genitorinary->getValue('dysuria') }}</td>
                                <td>{{ $genitorinary->dysuria_doctor }}</td>
                            @endif
                            @if($genitorinary->urethral_discharge_doctor != null)
                                <th>Urethral/ Vaginal discharge</th>
                                <td>{{ $genitorinary->getValue('urethral_discharge') }}</td>
                                <td>{{ $genitorinary->urethral_discharge_doctor }}</td>
                            @endif
                        </tr>

                        <tr>
                            @if($genitorinary->painful_sexual_intercourse_doctor != null)
                                <th>Painful sexual intercourse</th>
                                <td>{{ $genitorinary->getValue('painful_sexual_intercourse') }}</td>
                                <td>{{ $genitorinary->painful_sexual_intercourse_doctor }}</td>
                            @endif
                            @if($genitorinary->menarche_doctor != null)
                                <th>Menarche</th>
                                <td>{{ $genitorinary->getValue('menarche') }}</td>
                                <td>{{ $genitorinary->menarche_doctor }}</td>
                            @endif
                        </tr>
                        <tr>
                            @if($genitorinary->menopause_doctor != null)
                                <th>Menopause</th>
                                <td>{{ $genitorinary->getValue('menopause') }}</td>
                                <td>{{ $genitorinary->menopause_doctor }}</td>
                            @endif
                            @if($genitorinary->length_of_periods_doctor != null)
                                <th>Length of periods</th>
                                <td>{{ $genitorinary->getValue('length_of_periods') }}</td>
                                <td>{{ $genitorinary->length_of_periods_doctor }}</td>
                            @endif
                        </tr>
                        <tr>
                            @if($genitorinary->amount_pain_doctor != null)
                                <th>Amount/ Pain</th>
                                <td>{{ $genitorinary->getValue('amount_pain') }}</td>
                                <td>{{ $genitorinary->amount_pain_doctor }}</td>
                            @endif
                            @if($genitorinary->LMP != null || $genitorinary->LMP_doctor != null)
                                <th>LMP</th>
                                <td>{{ $genitorinary->LMP }}</td>
                                <td>{{ $genitorinary->LMP_doctor}}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
                </div>
            @endif
            @if($gastro->id != null)
                <div class="token-detail-box" id="">
                <div class="table_head_lft">
                    <h5><b>Gastrointestinal examination</b></h5>
                    <table class="ui table table_cus_v bs">
                        <tbody>
                        <tr>
                            @if($gastro->abdominal_pain_doctor != null)
                                <th>Abdominal pain</th>
                                <td style="border-top: 1px solid #ddd">{{ $gastro->getValue('abdominal_pain') }}</td>
                                <td style="border-top: 1px solid #ddd">{{ $gastro->abdominal_pain_doctor}}</td>
                            @endif
                            @if($gastro->nausea_doctor != null)
                                <th style="border-top: 1px solid #ddd">Nausea/ vomiting/haematemesis</th>
                                <td style="border-top: 1px solid #ddd">{{ $gastro->getValue('nausea') }}</td>
                                <td style="border-top: 1px solid #ddd">{{ $gastro->nausea_doctor }}</td>
                            @endif
                        </tr>

                        <tr>
                            @if($gastro->dysphagia_doctor != null)
                                <th>Dysphagia</th>
                                <td>{{ $gastro->getValue('dysphagia') }}</td>
                                <td>{{ $gastro->dysphagia_doctor }}</td>
                            @endif
                            @if($gastro->indigestion_doctor != null)
                                <th>Indigestion</th>
                                <td>{{ $gastro->getValue('indigestion') }}</td>
                                <td>{{ $gastro->indigestion_doctor}}</td>
                            @endif
                        </tr>
                        <tr>
                            @if($gastro->change_in_bowel_habits_doctor != null)
                                <th>Change in Bowel habits</th>
                                <td>{{ $gastro->getValue('change_in_bowel_habits') }}</td>
                                <td> {{ $gastro->change_in_bowel_habits_doctor }}</td>
                            @endif
                            @if($gastro->diarrhoea_constipation_doctor != null)
                                <th>Diarrhoea/ constipation</th>
                                <td>{{ $gastro->getValue('diarrhoea_constipation') }}</td>
                                <td>{{ $gastro->diarrhoea_constipation_doctor }}</td>
                            @endif
                        </tr>
                        <tr>
                            @if($gastro->rectal_bleeding_doctor != null)
                                <th>Rectal Bleeding</th>
                                <td>{{ $gastro->getValue('rectal_bleeding') }}</td>
                                <td>{{ $gastro->rectal_bleeding_doctor }}</td>
                            @endif
                            @if($gastro->weight_change_doctor != null)
                                <th>Appetite/ weight change</th>
                                <td>{{ $gastro->getValue('weight_change') }}</td>
                                <td>{{ $gastro->weight_change_doctor }}</td>
                            @endif
                        </tr>
                        <tr>
                            @if($gastro->dark_urine_doctor != null)
                                <th>Dark Urine or pale stools</th>
                                <td>{{ $gastro->getValue('dark_urine') }}</td>
                                <td style="border-right:1px solid #ddd">{{ $gastro->dark_urine_doctor }}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
                </div>
            @endif
            @if($neuro->id != null)
                <div class="token-detail-box" id="">
                <div class="table_head_lft">
                    <h3 style="border-top: 1px solid #ddd; border-right: 1px solid #ddd; border-left: 1px solid #ddd">
                        <center><b>Physiotherapy Examinations</b></center>
                    </h3>
                    <h5><b>Neurological System</b></h5>
                    <table class="ui table table_cus_v bs">
                        <tbody>
                        <tr>
                            @if($neuro->headache_doctor != null)
                                <th>Headache</th>
                                <td style="border-top: 1px solid #ddd">{{ $neuro->getValue('headache') }}</td>
                                <td style="border-top: 1px solid #ddd">{{ $neuro->headache_doctor}}</td>
                            @endif
                            @if($neuro->vision_hearing_doctor != null)
                                <th style="border-top: 1px solid #ddd">Problem with vision/ hearing etc.</th>
                                <td style="border-top: 1px solid #ddd">{{ $neuro->getValue('vision_hearing') }}</td>
                                <td style="border-top: 1px solid #ddd">{{ $neuro->vision_hearing_doctor}}</td>
                            @endif
                        </tr>
                        <tr>
                            @if($neuro->pain_doctor != null)
                                <th>Pain.</th>
                                <td>{{ $neuro->getValue('pain') }}</td>
                                <td>{{ $neuro->pain_doctor }}</td>
                            @endif
                            @if($neuro->numbness_doctor != null)
                                <th>Numbness/ Pins& Needles</th>
                                <td>{{ $neuro->getValue('numbness') }}</td>
                                <td>{{ $neuro->numbness_doctor }}</td>
                            @endif
                        </tr>
                        <tr>
                            @if($neuro->weakness_doctor != null)
                                <th>Weakness or balance problem</th>
                                <td>{{ $neuro->getValue('weakness') }}</td>
                                <td>{{ $neuro->weakness_doctor }}</td>
                            @endif
                            @if($neuro->abnormal_movements_doctor != null)
                                <th>Abnormal/ involuntary movements</th>
                                <td>{{ $neuro->getValue('abnormal_movements') }}</td>
                                <td>{{ $neuro->abnormal_movements_doctor }}</td>
                            @endif
                        </tr>
                        <tr>
                            @if($neuro->fits_doctor != null)
                                <th>Fits/ faints</th>
                                <td>{{ $neuro->getValue('fits') }}</td>
                                <td style="border-right:1px solid #ddd">{{ $neuro->fits_doctor }}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
                </div>
            @endif
            @if($systemic->id != null)
                <div class="token-detail-box" id="">
                <div class="table_head_lft">
                    <h6><b>On examination</b></h6>
                    <table class="ui table table_cus_v bs">
                        <tbody>
                        <tr>
                            @if($systemic->body_built != null)
                                <th>Body Built</th>
                                <td style="border-top: 1px solid #ddd">{{ $systemic->getValue('body_built') }}</td>
                                <td style="border-top: 1px solid #ddd"></td>
                            @endif
                            @if($systemic->gait != null)
                                <th style="border-top: 1px solid #ddd">Gait.</th>
                                <td style="border-top: 1px solid #ddd">{{ $systemic->getValue('gait') }}</td>
                                <td style="border-top: 1px solid #ddd"></td>
                            @endif
                        </tr>

                        <tr>
                            @if($systemic->posture_comment != null)
                                <th>Posture</th>
                                <td>{{ $systemic->getValue('posture') }}</td>
                                <td>{{ $systemic->posture_comment }}</td>
                            @endif
                            @if($systemic->deformity_comment != null)
                                <th>Deformity</th>
                                <td>{{ $systemic->getValue('deformity') }}</td>
                                <td>{{ $systemic->deformity_comment }}</td>
                            @endif
                        </tr>

                        <tr>
                            @if($systemic->tenderness_comment != null)
                                <th>Tenderness</th>
                                <td>{{ $systemic->getValue('tenderness') }}</td>
                                <td>{{ $systemic->tenderness_comment }}</td>
                            @endif
                            @if($systemic->warmth_comment != null)
                                <th>Warmth</th>
                                <td>{{ $systemic->getValue('warmth') }}</td>
                                <td>{{ $systemic->warmth_comment }}</td>
                            @endif
                        </tr>

                        <tr>
                            @if($systemic->swelling_comment != null)
                                <th>Swelling</th>
                                <td>{{ $systemic->getValue('swelling') }}</td>
                                <td style="border-right:1px solid #ddd">{{ $systemic->swelling_comment  }}</td>
                            @endif
                            @if($systemic->creiptus_comment != null)
                                <th>Crepitus</th>
                                <td>{{ $systemic->getValue('creiptus') }}</td>
                                <td style="border-right:1px solid #ddd">{{ $systemic->creiptus_comment  }}</td>
                            @endif
                        </tr>
                        <tr>
                            @if($systemic->muscle_spasm_comment != null)
                                <th>Muscle Spasm</th>
                                <td>{{ $systemic->getValue('muscle_spasm') }}</td>
                                <td>{{ $systemic->muscle_spasm_comment }}</td>
                            @endif
                            @if($systemic->muscle_tightness_comment != null)
                                <th>Muscle Tightness</th>
                                <td>{{ $systemic->getValue('muscle_tightness') }}</td>
                                <td>{{ $systemic->muscle_tightness_comment }}</td>
                            @endif
                        </tr>
                        <tr>
                            @if($systemic->edema != null)
                                <th>Edema</th>
                                <td>{{ $systemic->getValue('edema') }}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
                </div>
            @endif
            @if($sensory->id != null)
                <div class="token-detail-box" id="">
                <div class="table_head_lft">
                    <h5><b>Sensory Examination</b></h5>
                    <table class="ui table table_cus_v bs">
                        <tbody>
                        <tr>
                            @if($sensory->superficial_sensation_comment != null)
                                <th width="30%">Superficial Sensation</th>
                                <td style="border-top: 1px solid #ddd">{{ $sensory->getValue('superficial_sensation') }}</td>
                                <td style="border-top: 1px solid #ddd">{{ $sensory->superficial_sensation_comment }}</td>
                            @endif
                            @if($sensory->deep_sensation_comment != null)

                                <th style="border-top: 1px solid #ddd">Deep Sensation.</th>
                                <td style="border-top: 1px solid #ddd">{{ $sensory->getValue('deep_sensation') }}</td>
                                <td style="border-top: 1px solid #ddd">{{ $sensory->deep_sensation_comment }}</td>
                            @endif
                        </tr>
                        <tr>
                            @if($sensory->hot_or_cold_sensation_comment != null)
                                <th>Hot/Cold Sensation</th>
                                <td>{{ $sensory->getValue('hot_or_cold_sensation') }}</td>
                                <td>{{ $sensory->hot_or_cold_sensation_comment }}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
                </div>
            @endif
            @if($motor->id != null)
                <div class="token-detail-box" id="">
                <div class="table_head_lft">
                    <h5><b>Motor Examination</b></h5>
                    <table class="ui table table_cus_v bs">
                        <tbody>
                        <tr>
                            @if($motor->rom_of_joint_type != null)
                                <th width="30%">ROM Of Joint</th>
                                <td style="border-top: 1px solid #ddd">{{ $motor->getValue('rom_of_joint') }}</td>
                                <td style="border-top: 1px solid #ddd">{{ $motor->rom_of_joint_type }}</td>
                            @endif
                            @if($motor->muscle_power_grade_comment != null)
                                <th style="border-top: 1px solid #ddd">Muscle Power/Grade</th>
                                <td style="border-top: 1px solid #ddd">{{ $motor->getValue('muscle_power_grade') }}</td>
                                <td style="border-top: 1px solid #ddd">{{ $motor->muscle_power_grade_comment }}</td>
                            @endif
                        </tr>

                        <tr>
                            @if($motor->muscle_power_tone_comment != null)
                                <th>Muscle Tone</th>
                                <td style="border-right:1px solid #ddd">{{ $motor->getValue('muscle_power_tone') }}</td>
                                <td style="border-right:1px solid #ddd">{{ $motor->muscle_power_tone_comment  }}</td>
                                <td style="border-right:1px solid #ddd"></td>
                                <td style="border-right:1px solid #ddd"></td>
                                <td style="border-right:1px solid #ddd"></td>
                            @endif
                        </tr>
                        @if(count($motor->getJoints()) > 0)
                            @foreach($motor->getJoints() as $joint)
                                <tr>
                                    <th>Joint</th>
                                    <td style="border-right:1px solid #ddd;">{{ $joint['joint'] }}</td>
                                    <th>Sub Category</th>
                                    <td style="border-right:1px solid #ddd">{{ $joint['joint_sub_category'] }}</td>
                                    <th>Left Side/Right Side</th>
                                    <td style="border-right:1px solid #ddd">{{ $joint['right'] }}
                                        /{{ $joint['left'] }}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>

                <div class="table_head_lft">
                    <h5><b>Reflexes</b></h5>
                    <table class="ui table table_cus_v bs">
                        <tbody>
                        <tr>
                            @if($motor->deep_reflexes_comment != null)
                                <th>Deep Reflexes</th>
                                <td style="border-top: 1px solid #ddd">{{ $motor->getValue('deep_reflexes') }}</td>
                                <td style="border-top: 1px solid #ddd">{{ $motor->deep_reflexes_comment }}</td>
                            @endif
                            @if($motor->superficial_reflexes_comment != null)
                                <th style="border-top: 1px solid #ddd">Superficial Reflexes</th>
                                <td style="border-top: 1px solid #ddd">{{ $motor->getValue('superficial_reflexes') }}</td>
                                <td style="border-top: 1px solid #ddd">{{ $motor->superficial_reflexes_comment }}</td>
                            @endif
                        </tr>

                        <tr>
                            @if($motor->bower_and_bladder_comment != null)
                                <th>Bowel & Bladder</th>
                                <td>{{ $motor->getValue('bower_and_bladder') }}</td>
                                <td style="border-right:1px solid #ddd">{{ $motor->bower_and_bladder_comment  }}</td>
                            @endif
                            @if($motor->specific_test != null)
                                <th>Specific Test, if Any</th>
                                <td>{{ $motor->specific_test }}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
                </div>
            @endif
            @if($pain->id != null)
                <div class="token-detail-box" id="">
                <div class="table_head_lft">
                    <h5><b>Musculo Skeletal</b></h5>
                    <table class="ui table table_cus_v bs">
                        <tbody>
                        <tr>
                            @if($pain->muscle_pain_comment != null)
                                <th>Muscle Pain</th>
                                <td style="border-top: 1px solid #ddd">{{ $pain->getValue('muscle_pain') }}</td>
                                <td style="border-top: 1px solid #ddd">{{ $pain->muscle_pain_comment }}</td>
                            @endif
                            @if($pain->back_pain_comment != null)
                                <th style="border-top: 1px solid #ddd">Back Pain</th>
                                <td style="border-top: 1px solid #ddd">{{ $pain->getValue('back_pain') }}</td>
                                <td style="border-top: 1px solid #ddd">{{ $pain->back_pain_comment }}</td>
                            @endif
                        </tr>

                        <tr>
                            @if($pain->knee_pain_comment != null)
                                <th>Knee Pain</th>
                                <td>{{ $pain->getValue('knee_pain') }}</td>
                                <td>{{ $pain->knee_pain_comment }}</td>
                            @endif
                            @if($pain->joint_pain_comment != null)
                                <th>Joint Pain</th>
                                <td>{{ $pain->getValue('joint_pain') }}</td>
                                <td>{{ $pain->joint_pain_comment }}</td>
                        @endif
                        <tr>
                            @if($pain->spinal_injuries_comment != null)
                                <th>Spinal Injuries</th>
                                <td>{{ $pain->getValue('spinal_injuries') }}</td>
                                <td>{{ $pain->spinal_injuries_comment  }}</td>
                            @endif
                            @if($pain->side != null)
                                <th>Side</th>
                                <td style="border-right: 1px solid #ddd">{{ $pain->getValue('side') }}</td>
                            @endif
                        </tr>
                        <tr>
                            @if($pain->onset_of_symptoms != null)
                                <th>Onset of Symptoms</th>
                                <td>{{ $pain->onset_of_symptoms }}</td>
                            @endif
                            @if($pain->priorities_injuries_to_affected_area_comment != null)
                                <th>Prior injury To Affected Area</th>
                                <td>{{ $pain->getValue('priorities_injuries_to_affected_area') }}</td>
                                <td>{{ $pain->priorities_injuries_to_affected_area_comment }}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
                </div>
            @endif
            @if($pain_assesment->id != null)
                <div class="token-detail-box" id="">
                <div class="table_head_lft">
                    <h5><b>Pain Assessments</b></h5>
                    <table class="ui table table_cus_v bs">
                        <tbody>
                        <tr>
                            @if($pain_assesment->pain_at_rest != null)
                                <th>Pain At Rest</th>
                                <td style="border-top: 1px solid #ddd">{{ $pain_assesment->pain_at_rest }}</td>
                            @endif
                            @if($pain_assesment->pain_with_activity != null)
                                <th style="border-top: 1px solid #ddd">Pain With Activity</th>
                                <td style="border-top: 1px solid #ddd">{{ $pain_assesment->pain_with_activity }}</td>
                            @endif
                        </tr>

                        <tr>
                            @if($pain_assesment->pain_at_night != null)
                                <th>Pain At Night</th>
                                <td>{{ $pain_assesment->pain_at_night }}</td>
                            @endif
                            @if($pain_assesment->aggregation_factor != null)
                                <th>Aggravating Factor</th>
                                <td>{{ $pain_assesment->aggregation_factor }}</td>
                        @endif
                        <tr>
                            @if($pain_assesment->relieving_factor != null)
                                <th>Relieving Factor</th>
                                <td>{{ $pain_assesment->relieving_factor }}</td>
                            @endif
                            @if($pain_assesment->type_of_pain != null)
                                <th>Type of Pain</th>
                                <td>{{ $pain_assesment->getValue('type_of_pain') }}</td>
                            @endif
                        </tr>
                        <tr>
                            @if($pain_assesment->nature_of_pain != null)
                                <th>Nature of Pain</th>
                                <td>{{ $pain_assesment->getValue('nature_of_pain') }}</td>
                            @endif
                            @if($pain_assesment->symptoms_are_worse != null)
                                <th>Symptoms Are Worse</th>
                                <td>{{ $pain_assesment->getValue('symptoms_are_worse') }}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
                </div>
            @endif

            @if($ashtvidh->id != null || $aturpariksha->id != null || $doshpariksha->id != null || $dhatupariksha->id != null)
                <div class="table_head_lft">
                    <h3 style="border-top: 1px solid #ddd; border-right: 1px solid #ddd; border-left: 1px solid #ddd">
                        <center><b>Ayurvedic Examinations</b></center>
                    </h3>
                </div>
            @endif

            @if($ashtvidh->id != null)
                <div class="table_head_lft">
                    <h5><b>{{ trans('laralum.ashtvidh_pariksha') }}</b></h5>
                    <table class="ui table table_cus_v bs">
                        <tbody>
                        <tr>
                            @if($ashtvidh->pulse != null)
                                <th>{{ trans('laralum.pulse') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $ashtvidh->pulse }} {{ trans('laralum.speed_per_mins') }}</td>
                            @endif
                            @if($ashtvidh->pulse_issue != null || $ashtvidh->pulse_comment != null)
                                <th style="border-top: 1px solid #ddd">{{ trans('laralum.pulse_issue') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $ashtvidh->pulse_issue}}</td>
                                <td style="border-top: 1px solid #ddd">{{ $ashtvidh->pulse_comment}}</td>
                            @endif
                            @if($ashtvidh->faecal_matter != null)
                                <th style="border-top: 1px solid #ddd">{{ trans('laralum.faecal_matter') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $ashtvidh->getValue('faecal_matter') }}</td>
                            @endif
                            @if($ashtvidh->faecal_matter_speed_days != null || $ashtvidh->faecal_matter_comment != null)
                                <th style="border-top: 1px solid #ddd">{{ trans('laralum.speed_per_days') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $ashtvidh->faecal_matter_speed_days}}</td>
                                <td style="border-top: 1px solid #ddd">{{ $ashtvidh->faecal_matter_comment}}</td>
                            @endif

                        </tr>
                        <tr>
                            @if($ashtvidh->faecal_matter_liquid_speed_days != null)
                                <th>{{ trans('laralum.faecal_matter_liquid') }} {{ trans('laralum.speed_per_days') }}</th>
                                <td>{{ $ashtvidh->faecal_matter_liquid_speed_days }} </td>
                            @endif
                            @if($ashtvidh->faecal_matter_liquid != null)
                                <th style="border-top: 1px solid #ddd">{{ trans('laralum.varna') }}</th>
                                <td>{{ $ashtvidh->faecal_matter_liquid }}</td>
                            @endif
                            @if($ashtvidh->faecal_matter_liquid_speed_nights != null || $ashtvidh->faecal_matter_liquid_comment != null)
                                <th style="border-top: 1px solid #ddd">{{ trans('laralum.speed_per_nights') }}</th>
                                <td>{{ $ashtvidh->faecal_matter_liquid_speed_nights }}</td>
                                <td>{{ $ashtvidh->faecal_matter_liquid_comment }}</td>
                            @endif
                        </tr>
                        <tr>
                            @if($ashtvidh->tongue_comment != null)
                                <th> {{ trans('laralum.tongue') }}</th>
                                <td>{{ $ashtvidh->getValue('tongue') }}</td>
                                <td>{{ $ashtvidh->tongue_comment }}</td>
                            @endif
                            @if($ashtvidh->speech_comment != null)
                                <th>{{ trans('laralum.speech') }}</th>
                                <td>{{ $ashtvidh->getValue('speech') }}</td>
                                <td>{{ $ashtvidh->speech_comment }}</td>
                            @endif
                            @if($ashtvidh->skin_comment != null)
                                <th>{{ trans('laralum.skin') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $ashtvidh->getValue('skin')}}</td>
                                <td style="border-top: 1px solid #ddd">{{ $ashtvidh->skin_comment }}</td>
                            @endif

                        </tr>
                        <tr>
                            @if($ashtvidh->eyes_comment != null)
                                <th>{{ trans('laralum.eyes') }}</th>
                                <td>{{ $ashtvidh->getValue('eyes') }}</td>
                                <td style="border-right:1px solid #ddd">{{ $ashtvidh->eyes_comment }}</td>
                            @endif
                            @if($ashtvidh->body_build != null)
                                <th>{{ trans('laralum.body_build') }}</th>
                                <td style="border-right:1px solid #ddd">{{ $ashtvidh->getValue('body_build') }}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
            @endif
            @if($aturpariksha->id != null)
                <div class="table_head_lft">
                    <h5><b>{{ trans('laralum.atur_pariksha') }}</b></h5>
                    <table class="ui table table_cus_v bs">
                        <tbody>
                        <tr>
                            @if($aturpariksha->prakriti != null)
                                <th>{{  trans('laralum.prakriti') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $aturpariksha->getValue('prakriti') }}</td>
                            @endif
                            @if($aturpariksha->saar != null)
                                <th style="border-top: 1px solid #ddd">{{  trans('laralum.saar') }}</th>
                                <td style="border-top: 1px solid #ddd; border-right:1px solid #ddd">{{ $aturpariksha->getValue('saar') }}</td>
                            @endif
                        </tr>
                        <tr>
                            @if($aturpariksha->sanhanan != null)
                                <th>{{  trans('laralum.sanhanan') }}</th>
                                <td>{{ $aturpariksha->getValue('sanhanan') }}</td>
                            @endif
                            @if($aturpariksha->praman != null)
                                <th>{{  trans('laralum.praman') }}</th>
                                <td style="border-right:1px solid #ddd">{{ $aturpariksha->praman }} {{  trans('laralum.lambai') }}</td>
                            @endif
                        </tr>
                        <tr>
                            @if($aturpariksha->satmyaya != null)
                                <th>{{  trans('laralum.satmyaya') }}</th>
                                <td>{{ $aturpariksha->getValue('satmyaya') }}</td>
                            @endif
                            @if($aturpariksha->satva != null)
                                <th>{{  trans('laralum.satva') }}</th>
                                <td style="border-right:1px solid #ddd">{{ $aturpariksha->getValue('satva') }}</td>
                            @endif

                        </tr>
                        <tr>
                            @if($aturpariksha->ahaar_shakti != null)
                                <th>{{  trans('laralum.ahaar_shakti') }}</th>
                                <td>{{ $aturpariksha->getValue('ahaar_shakti') }}</td>
                            @endif
                            @if($aturpariksha->vyayaam_shakti != null)
                                <th>{{  trans('laralum.vyayaam_shakti') }}</th>
                                <td style="border-right:1px solid #ddd">{{ $aturpariksha->getValue('vyayaam_shakti') }}</td>
                            @endif
                        </tr>
                        <tr>
                            @if($aturpariksha->varsh != null)
                                <th>{{  trans('laralum.vaya') }}</th>
                                <td>{{ $aturpariksha->getValue('vaya') }}</td>
                                <td style="border-right:1px solid #ddd">{{ $aturpariksha->varsh }} {{  trans('laralum.varsh') }}</td>
                            @endif

                            @if($aturpariksha->bal != null)
                                <th>{{  trans('laralum.bal') }}</th>
                                <td style="border-top:1px solid #ddd">{{ $aturpariksha->getValue('bal') }}</td>
                            @endif
                        </tr>

                        <tr>
                            @if($aturpariksha->drishya != null)
                                <th>{{  trans('laralum.drishya') }}</th>
                                <td style="border-right:1px solid #ddd">{{ $aturpariksha->drishya }}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table_head_lft">
                    <table class="ui table table_cus_v bs">
                        <tbody>
                        <tr>
                            <th><h5>{{  trans('laralum.desh') }}</h5></th>
                        </tr>
                        <tr>
                            @if($aturpariksha->uttpatti_desh != null)
                                <th>{{  trans('laralum.utpatti_desh') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $aturpariksha->getValue('uttpatti_desh') }}</td>
                            @endif
                            @if($aturpariksha->vyadhit_desh != null)
                                <th style="border-top: 1px solid #ddd">{{  trans('laralum.vyadhit_desh') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $aturpariksha->getValue('vyadhit_desh') }}</td>
                            @endif
                        </tr>
                        <tr>
                            @if($aturpariksha->chikitsa_desh != null)
                                <th>{{  trans('laralum.chikitsa_desh') }}</th>
                                <td>{{ $aturpariksha->getValue('chikitsa_desh') }}</td>

                                @if($aturpariksha->kaal != null)
                                    <th>{{  trans('laralum.kaal_ritu') }}</th>
                                    <td>{{ $aturpariksha->getValue('kaal') }}</td>
                                @endif
                        </tr>
                        <tr>
                            @if($aturpariksha->anal != null)
                                <th>{{  trans('laralum.anal') }}</th>
                                <td>{{ $aturpariksha->getValue('anal') }}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table_head_lft">
                    <table class="ui table table_cus_v bs">
                        <tbody>
                        <tr>
                            <th><h5>{{  trans('laralum.anal') }}</h5></th>
                        </tr>
                        <tr>
                            @if($aturpariksha->rogi_awastha != null)
                                <th>{{  trans('laralum.rogi_awastha') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $aturpariksha->getValue('rogi_awastha') }}</td>
                            @endif
                            @if($aturpariksha->rog_awastha != null)
                                <th style="border-top: 1px solid #ddd">{{  trans('laralum.rog_awastha') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $aturpariksha->getValue('rog_awastha') }}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
            @endif

            @if($doshpariksha->id != null)
                <div class="table_head_lft">
                    <table class="ui table table_cus_v bs">
                        <tbody>
                        <tr>
                            <h3 style="border-top: 1px solid #ddd; border-right: 1px solid #ddd; border-left: 1px solid #ddd">
                                <center>{{  trans('laralum.dosh_priksha') }}</center>
                            </h3>
                        </tr>
                        <tr>
                            <th><h5>{{  trans('laralum.vat_dosh') }}</h5></th>
                        </tr>
                        <tr>
                            @if($doshpariksha->vat_dosh_growth != null)
                                <th>{{  trans('laralum.vridhi') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $doshpariksha->getValue('vat_dosh_growth') }}</td>
                            @endif
                            @if($doshpariksha->vat_dosh_decay != null)
                                <th style="border-top: 1px solid #ddd">{{  trans('laralum.kshaya') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $doshpariksha->getValue('vat_dosh_decay') }}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table_head_lft">
                    <table class="ui table table_cus_v bs">
                        <tbody>

                        <tr>
                            <th><h5>{{  trans('laralum.pitt_dosh') }}</h5></th>
                        </tr>
                        <tr>
                            @if($doshpariksha->pitt_dosh_growth != null)
                                <th>{{  trans('laralum.vridhi') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $doshpariksha->getValue('pitt_dosh_growth') }}</td>
                            @endif
                            @if($doshpariksha->pitt_dosh_decay != null)
                                <th style="border-top: 1px solid #ddd">{{  trans('laralum.kshaya') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $doshpariksha->getValue('pitt_dosh_decay') }}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table_head_lft">
                    <table class="ui table table_cus_v bs">
                        <tbody>

                        <tr>
                            <th><h5>{{  trans('laralum.kaph_dosh') }}</h5></th>
                        </tr>
                        <tr>
                            @if($doshpariksha->kaph_dosh_growth != null)
                                <th>{{ trans('laralum.vridhi') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $doshpariksha->getValue('kaph_dosh_growth') }}</td>
                            @endif
                            @if($doshpariksha->kaph_dosh_decay != null)
                                <th style="border-top: 1px solid #ddd">{{  trans('laralum.kshaya') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $doshpariksha->getValue('kaph_dosh_decay') }}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
            @endif
            @if($dhatupariksha->id != null)
                <div class="table_head_lft">
                    <table class="ui table table_cus_v bs">
                        <tbody>
                        <tr>
                            <h3 style="border-top: 1px solid #ddd; border-right: 1px solid #ddd; border-left: 1px solid #ddd">
                                <center>{{  trans('laralum.dhatu_priksha') }}</center>
                            </h3>
                        </tr>
                        <tr>
                            <th><h5>{{  trans('laralum.ras') }}</h5></th>
                        </tr>
                        <tr>
                            @if($doshpariksha->ras_growth != null)
                                <th>{{  trans('laralum.vridhi') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->getValue('ras_growth') }}</td>
                            @endif
                            @if($doshpariksha->ras_decay != null)
                                <th style="border-top: 1px solid #ddd">{{  trans('laralum.kshaya') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->getValue('ras_decay') }}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table_head_lft">
                    <table class="ui table table_cus_v bs">
                        <tbody>

                        <tr>
                            <th><h5>{{  trans('laralum.rakt') }}</h5></th>
                        </tr>
                        <tr>
                            @if($doshpariksha->rakht_growth != null)
                                <th>{{  trans('laralum.vridhi') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->getValue('rakht_growth') }}</td>
                            @endif

                            @if($doshpariksha->rakht_decay != null)
                                <th style="border-top: 1px solid #ddd">{{  trans('laralum.kshaya') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->getValue('rakht_decay') }}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table_head_lft">
                    <table class="ui table table_cus_v bs">
                        <tbody>

                        <tr>
                            <th><h5>{{  trans('laralum.maans') }}</h5></th>
                        </tr>
                        <tr>
                            @if($doshpariksha->maans_growth != null)
                                <th>{{  trans('laralum.vridhi') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->getValue('maans_growth') }}</td>
                            @endif
                            @if($doshpariksha->maans_decay != null)
                                <th style="border-top: 1px solid #ddd">{{  trans('laralum.kshaya') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->getValue('maans_decay') }}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table_head_lft">
                    <table class="ui table table_cus_v bs">
                        <tbody>

                        <tr>
                            <th><h5>{{  trans('laralum.med') }}</h5></th>
                        </tr>
                        <tr>
                            @if($doshpariksha->med_growth != null)
                                <th>{{  trans('laralum.vridhi') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->getValue('med_growth') }}</td>

                            @endif
                            @if($doshpariksha->med_decay != null)
                                <th style="border-top: 1px solid #ddd">{{  trans('laralum.kshaya') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->getValue('med_decay') }}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table_head_lft">
                    <table class="ui table table_cus_v bs">
                        <tbody>

                        <tr>
                            <th><h5>{{  trans('laralum.asthi') }}</h5></th>
                        </tr>
                        <tr>
                            @if($doshpariksha->asthi_growth != null)
                                <th>{{  trans('laralum.vridhi') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->getValue('asthi_growth') }}</td>
                            @endif
                            @if($doshpariksha->asthi_decay != null)
                                <th style="border-top: 1px solid #ddd">{{  trans('laralum.kshaya') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->getValue('asthi_decay') }}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table_head_lft">
                    <table class="ui table table_cus_v bs">
                        <tbody>

                        <tr>
                            <th><h5>{{  trans('laralum.majja') }}</h5></th>
                        </tr>
                        <tr>
                            @if($doshpariksha->majja_growth != null)
                                <th>{{  trans('laralum.vridhi') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->getValue('majja_growth') }}</td>
                            @endif
                            @if($doshpariksha->majja_decay != null)
                                <th style="border-top: 1px solid #ddd">{{  trans('laralum.kshaya') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->getValue('majja_decay') }}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table_head_lft">
                    <table class="ui table table_cus_v bs">
                        <tbody>

                        <tr>
                            <th><h5>{{  trans('laralum.shukra') }}</h5></th>
                        </tr>
                        <tr>
                            shukra_growth
                            <th>{{  trans('laralum.vridhi') }}</th>
                            <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->getValue('shukra_growth') }}</td>
                            @endif
                            @if($doshpariksha->shukra_decay != null)
                                <th style="border-top: 1px solid #ddd">{{  trans('laralum.kshaya') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->getValue('shukra_decay') }}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table_head_lft">
                    <table class="ui table table_cus_v bs">
                        <tbody>
                        <tr>
                            @if($doshpariksha->rog_nidan != null)
                                <th>{{  trans('laralum.rog_nidan') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->rog_nidan }}</td>
                            @endif
                            @if($doshpariksha->vyadhi_ka_naam != null)
                                <th style="border-top: 1px solid #ddd">{{  trans('laralum.vydhi_ka_naam') }}</th>
                                <td style="border-top: 1px solid #ddd">{{ $dhatupariksha->vyadhi_ka_naam }}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        @if($booking->treatments->count() > 0)
        <div class="token-detail-box" id="">
            <p class="visit-dr">Treatments Details</p>

                <div class="row">
                    <div class="table-responsive">
                        <div class="content_BoxIN">
                            @foreach($booking->treatments as $treatment)
                                @foreach($treatment->treatments as $pat_treatment)
                                    @if($pat_treatment->status == \App\PatientTreatment::STATUS_COMPLETED)
                                        <p>
                                            <span style="margin:10px ;">Treatment: {{ $pat_treatment->treatment->title }}</span><span> Date: {{ $treatment->treatment_date }}</span><span> Price: {{ $pat_treatment->treatment->price }}</span>
                                        </p>
                                    @endif
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>

        </div>
        @endif
        @if($booking->labTests->count() > 0)
        <div class="token-detail-box" id="">
            <p class="visit-dr">Lab Tests Details</p>

                <div class="row">
                    <div class="table-responsive">
                        <table>
                            <tr>
                                <th>Lab Name</th>
                                <th>Date</th>
                                <th>Address</th>
                                <th>Tests</th>
                                <th>Note</th>
                                <th>Price</th>
                            </tr>
                            @foreach($booking->labTests as $lab_test)
                                <tr>
                                    <td> {{ $lab_test->lab_name }}</td>
                                    <td>{{ $lab_test->date }}</td>
                                    <td>{{ $lab_test->address }}</td>
                                    <td>{{ isset($lab_test->test->name) ? $lab_test->test->name : "" }}</td>
                                    <td>{{ $lab_test->note }}</td>
                                    <td>{{ $discharge == true  ? $lab_test->getPrice(true) :  $lab_test->getPrice() }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
        </div>
        @endif

        @if($booking->diets->count() > 0)
        <div class="token-detail-box" id="">
            <p class="visit-dr">Daily Diet Details</p>

                <div class="row">
                    <div class="table-responsive">
                        <table class="table ui">
                            <tr>
                                <th>Date</th>
                                <th>Price</th>
                                <th></th>
                                <th></th>


                            </tr>
                            @foreach($booking->diets as $diet)
                                @if(count($diet->dailyDiets) > 0)
                                    @foreach($diet->dailyDiets as $daily_diet)
                                        <tr>
                                            <td>{{ $daily_diet->date }}</td>
                                            <td>
                                                @if($loop->iteration == 1)
                                                    <input type="hidden"
                                                           id="daily_diet_id"
                                                           value="{{  $daily_diet->id }}"> @endif
                                                <span>{{ $daily_diet->getTotalAmount() }}</span>

                                            </td>
                                            <td>
                                                @php
                                                    $daily_diet = \App\DietDailyStatus::find($daily_diet->id);
    $html = "";
    if ($daily_diet != null) {
    $html = "<table>";
    foreach (\App\DietChartItems::getTypeOptions() as $type_id => $type) {
    if ($daily_diet->checkType($type_id)) {
    $items = \App\DietChartItems::where([
    'diet_id' => $daily_diet->diet_id,
    'type_id' => $type_id
    ])->get();
    $items_html = "";
    foreach ($items as $item) {
    $price = $item->item_price;
    if ($type_id == \App\DietChartItems::TYPE_BREAKFAST) {
    if ($daily_diet->is_breakfast == 0) {
    $price = 0;
    }
    } elseif ($type_id == \App\DietChartItems::TYPE_LUNCH) {
    if ($daily_diet->is_lunch == 0) {
    $price = 0;
    }
    } elseif ($type_id == \App\DietChartItems::TYPE_POST_LUNCH) {
    if ($daily_diet->is_post_lunch == 0) {
    $price = 0;
    }
    } elseif ($type_id == \App\DietChartItems::TYPE_DINNER) {
    if ($daily_diet->is_dinner == 0) {
    $price = 0;
    }
    } elseif ($type_id == \App\DietChartItems::TYPE_SPECIAL) {
    if ($daily_diet->is_special == 0) {
    $price = 0;
    }
    }

    $items_html .= $item->item->name . "  => " . $price . "<br/>";
    }

    $html .= "<tr><th>" . $type . "</th><td>" . $items_html . "</td></tr>";
    }
    }
    $html .= "</table>";
    }                                                                                                    @endphp
                                                {!! $html !!}

                                            </td>
                                        </tr>
                                    
                                    @endforeach
                                @endif
                            @endforeach
                        </table>
                    </div>
                </div>
         
        </div>
    </div>
    </div>

   </div>

        @endif

@endsection

@section('script')
    <script>
        $("#print").click(function () {
            $(this).hide();
            $("#back").hide();
            window.print();
            $(this).show();
            $("#back").show();

        });
        /* $("#mySelector").printThis({
             debug: false,               /!* show the iframe for debugging*!/
             importCSS: true,            /!* import page CSS*!/
             importStyle: true,       /!* import style tags*!/
             printContainer: true,      /!* grab outer container as well as the contents of the selector*!/
             loadCSS: [
                 "{{ asset('/css/style.css') }}",
            "{{ asset('js/bootstrap.min.js') }}",
            "{{ asset('/css/font-awesome.min.css')}}",
            "{{ asset('/css/animate.css') }}",
            "{{ asset('css/jquery.steps.css') }}"],  /!* path to additional css file - use an array [] for multiple*!/
        pageTitle: "",              /!* add title to print page*!/
        removeInline: false,       /!* remove all inline styles from print elements*!/
        printDelay: 333,           /!* variable print delay; depending on complexity a higher value may be necessary*!/
        header: null,              /!* prefix to html*!/
        footer: null,              /!* postfix to html*!/
        base: false     ,           /!* preserve the BASE tag, or accept a string for the URL*!/
        formValues: true,            /!* preserve input/form values*!/
        canvas: false ,             /!* copy canvas elements (experimental)*!/
        doctypeString: ""        /!* enter a different doctype for older markup*!/
    });*/
        function PrintElem(elem) {
            Popup($('<div/>').append($(elem).clone()).html());
        }

        function Popup(data) {
            var mywindow = window.open('', 'my div', 'height=400,width=600');
            mywindow.document.write('<html><head><title>my div</title>');
            mywindow.document.write('<link href="http://122.180.254.6:8082/Kayakalp/public/css/font-awesome.min.css" rel="stylesheet" type="text/css" /><link href="http://122.180.254.6:8082/Kayakalp/public/css/bootstrap.min.css" rel="stylesheet" type="text/css" /><link href="http://122.180.254.6:8082/Kayakalp/public/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" /><link href="http://122.180.254.6:8082/Kayakalp/public/css/animate.css " rel="stylesheet" type="text/css" /><link href="http://122.180.254.6:8082/Kayakalp/public/css/style.css" rel="stylesheet" type="text/css" /><link href="http://122.180.254.6:8082/Kayakalp/public/css/jquery.steps.css" rel="stylesheet" type="text/css" />');
            mywindow.document.write('</head><body >');
            mywindow.document.write(data);
            mywindow.document.write('</body></html>');

            mywindow.print();
            //  mywindow.close();

            return true;
        }
    </script>
@endsection

