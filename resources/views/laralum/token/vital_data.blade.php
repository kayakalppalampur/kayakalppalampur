@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @if($booking->isEditable())
            <a class="section" href="{{ route('Laralum::patients') }}">{{ trans('laralum.patient_list') }}</a>
        @else
            <a class="section"
               href="{{ route('Laralum::archived.patients.list') }}">{{ trans('laralum.archived_patients') }}</a>
        @endif
        <i class="right angle icon divider"></i>
        <div class="active section">Vital Data</div>
    </div>
@endsection
@section('title', 'Patient Details')
@section('icon', "pencil")
@section('subtitle', 'Patient Details')

@section('content')
    <link rel="stylesheet" type="text/css" media="screen"
          href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">
    <div class="ui one column doubling stackable">
        <div class="column admin_basic_detail1 admin_wrapper">
            <div class="segment  main_wrapper">
                <div class="ui breadcrumb steps clearfix">
                    <ul>
                        <li><a class="section"
                               href="{{ route('Laralum::patient.show', ['token_id' => $booking->id]) }}">Personal
                                Details</a></li>
                        {{-- <a class="section" href="{{ route('Laralum::tokens') }}">Case History</a>
                         <i class="right angle icon divider"></i>--}}
                        <li>
                            <div class="active section">Vital Data</div>
                        </li>
                        <li><a class="section"
                               href="{{ route('Laralum::patient_lab_test.index', ['patient_id' => $booking->id]) }}">Lab
                                Tests</a></li>
                        <li><a class="section"
                               href="{{ route('Laralum::patient.diagnosis', ['patient_id' => $booking->id]) }}">Provisional
                                Diagnosis</a></li>

                        <li><a class="section"
                               href="{{ route('Laralum::patient.treatment', ['patient_id' => $booking->id]) }}">Allot
                                Treatments</a></li>
                        <li><a class="section"
                               href="{{ route('Laralum::patient.diet-chart', ['patient_id' => $booking->id]) }}">Diet
                                Chart</a></li>
                        <li><a class="section"
                               href="{{ route('Laralum::discharge.patient', ['token_id' => $booking->id]) }}">Discharge
                                Patient</a></li>

                        @php
                            $dept_model=\App\Department::where('title','like',"%Physiotherapy%")->first();
                        @endphp

                        {{--@if(\Auth::user()->isAdmin()||(!empty($dept_model) && \Auth::user()->department->department_id==$dept_model->id))--}}
                        <li><a class=" section"
                               href="{{ route('Laralum::attachments', ['booking_id' => $booking->id]) }}">Attachments
                            </a></li>

                        {{--@endif--}}
                        <li><a class="section"
                               href="{{ route('Laralum::summary', ['id' => $booking->id]) }}">Summary</a></li>


                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="ui one column doubling stackable vitual_data_wrapper">
        {{--  <div>
              <button onclick="return window.history.go(-1);" class="btn btn-primary ui button blue">
                  Back
              </button>
          </div>--}}
        <div class="column admin_basic_detail1">
            <div class="ui very padded segment about_sec1">
                <div class="page_title">
                    <h2 class="pull-left">Vital Data</h2>
                    @if(!\Auth::user()->isAdmin())
                        {{--@if(\Auth::user()->isAyurvedic())--}}
                            <div class="pull-right">
                                <a class="btn btn-primary ui button no-disable {{ \App\Http\Controllers\Laralum\Laralum::settings()->button_color }}"
                                   href="{{ route('Laralum::patient.ayurvedic_vital_data', ['token_id' => $booking->id]) }}">Ayurveda
                                    Examinations</a>
                            </div>
                        {{--@endif--}}


                        {{--@if((!empty($dept_model) && \Auth::user()->department->department_id==$dept_model->id))--}}
                            <div class="pull-right">
                                <a class="btn btn-primary ui button no-disable {{ \App\Http\Controllers\Laralum\Laralum::settings()->button_color }}"
                                   href="{{ route('Laralum::patient.physiotherpy_vital_data', ['token_id' => $booking->id]) }}">Physiotherapy
                                    Examinations</a>
                            </div>
                        {{--@endif--}}
                    @endif
                </div>
                @if($booking->isEditable())
                    <form method="POST" class="">
                        @endif
                        {{ csrf_field() }}
                        <input type="hidden" name="token_id" value="{{ $token->id }}"/>
                        <input type="hidden" name="patient_id" value="{{ $patient->id }}"/>
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}"/>

                        <input type="hidden" name="save_section" value="vital_data"/>
                        <div class="vital-data-wrap">

                            <div class="vital-head">
                                <h2>{{ $booking->getProfile('first_name').' '.$booking->getProfile('last_name') }}</h2>
                            </div>

                            <div class="vr_row_con">
                                <div class="vital-row">

                                    <div class="vital-inner">
                                        <div class="vital-col-1">
                                            <label>Present Complaints/Illness</label>
                                        </div>
                                        <div class="vital-col-2">
                                        <textarea cols=60 rows=5 name="present_complaints" class="form-control"
                                                  type="text" class="form-control"
                                                  value="{{ isset($vitalData->present_complaints) ? $vitalData->present_complaints : "" }}">{{ isset($vitalData->present_complaints) ? $vitalData->present_complaints : "" }}</textarea>

                                        </div>
                                    </div>


                                    <div class="vital-inner">
                                        <div class="vital-col-1">
                                            <label>Past Illness</label>
                                        </div>
                                        <div class="vital-col-2">
                                        <textarea cols=60 rows=5 name="past_illness" class="form-control" type="text"
                                                  class="form-control"
                                                  value="{{ isset($vitalData->past_illness) ? $vitalData->past_illness : "" }}">{{ isset($vitalData->past_illness) ? $vitalData->past_illness : "" }}</textarea>

                                        </div>
                                    </div>
                                    <div class="vital-inner">
                                        <div class="vital-col-1">
                                            <label>Family History</label>
                                        </div>
                                        <div class="vital-col-2">
                                        <textarea cols=60 rows=5 name="family_history" class="form-control" type="text"
                                                  class="form-control"
                                                  value="{{ isset($vitalData->family_history) ? $vitalData->family_history : "" }}">{{ isset($vitalData->family_history) ? $vitalData->family_history : "" }}</textarea>

                                        </div>
                                    </div>

                                    <div class="vital-inner">
                                        <div class="vital-col-1">
                                            <label>Gynecological & OBS. History</label>
                                        </div>
                                        <div class="vital-col-2">
                                        <textarea cols=60 rows=5 name="gynecological_obs_history" class="form-control"
                                                  type="text"
                                                  class="form-control"
                                                  value="{{ isset($vitalData->gynecological_obs_history) ? $vitalData->gynecological_obs_history : "" }}">{{ isset($vitalData->gynecological_obs_history) ? $vitalData->gynecological_obs_history : "" }}</textarea>

                                        </div>
                                    </div>

                                    <div class="vital-inner">
                                        <div class="vital-col-1">
                                            <label>Personal History</label>
                                        </div>
                                        <div class="vital-col-2">
                                        <textarea cols=60 rows=5 name="personal_history" class="form-control"
                                                  type="text"
                                                  class="form-control"
                                                  value="{{ isset($vitalData->personal_history) ? $vitalData->personal_history : "" }}">{{ isset($vitalData->personal_history) ? $vitalData->personal_history : "" }}</textarea>

                                        </div>
                                    </div>

                                    <div class="vital-inner">
                                        <div class="vital-col-1">
                                            <label>Diet</label>
                                            <input type="text" name="diet" class="form-control"
                                                   value="{{ $vitalData->diet }}">
                                        </div>
                                        <div class="vital-col-1">
                                            <label>Sleep</label>
                                            <input type="text" name="sleep" class="form-control"
                                                   value="{{ $vitalData->sleep }}">
                                        </div>
                                        <div class="vital-col-1">
                                            <label>Appetite</label>
                                            <input type="text" name="appetite" class="form-control"
                                                   value="{{ $vitalData->appetite }}">
                                        </div>
                                        <div class="vital-col-1">
                                            <label>Bowel</label>
                                            <input type="text" name="bowel" class="form-control"
                                                   value="{{ $vitalData->bowel }}">
                                        </div>
                                    </div>

                                    <div class="vital-inner">
                                        <div class="vital-col-1">
                                            <label>Exercise</label>
                                            <input type="text" name="exercise" class="form-control"
                                                   value="{{ $vitalData->exercise }}">
                                        </div>

                                        <div class="vital-col-1">
                                            <label>Digestion</label>
                                            <input type="text" name="digestion" class="form-control"
                                                   value="{{ $vitalData->digestion }}">
                                        </div>

                                        <div class="vital-col-1">
                                            <label>Habits</label>
                                            <input type="text" name="habits" class="form-control"
                                                   value="{{ $vitalData->habits }}">
                                        </div>
                                        <div class="vital-col-1">
                                            <label>Urine</label>
                                            <input type="text" name="urine" class="form-control"
                                                   value="{{ $vitalData->urine }}">
                                        </div>
                                    </div>

                                    <div class="vital-inner">
                                        <div class="vital-col-1">
                                            <label>Addiction</label>
                                            <input type="text" name="addiction" class="form-control"
                                                   value="{{ $vitalData->addiction }}">
                                        </div>

                                        <div class="vital-col-1">
                                            <label>Tongue</label>
                                            <input type="text" name="tongue" class="form-control"
                                                   value="{{ $vitalData->tongue }}">
                                        </div>

                                        <div class="vital-col-1">
                                            <label>Water Intake</label>
                                            <input type="text" name="water_intake" class="form-control"
                                                   value="{{ $vitalData->water_intake }}">
                                        </div>
                                    </div>


                                    <div class="vital-inner">
                                        <div class="vital-col-1">
                                            <label>Treatment & Medication</label>
                                        </div>
                                        <div class="vital-col-2">
                                        <textarea cols=60 rows=5 name="treatment_details" class="form-control"
                                                  type="text" class="form-control"
                                                  value="{{ isset($vitalData->treatment_details) ? $vitalData->treatment_details : "" }}">{{ isset($vitalData->treatment_details) ? $vitalData->treatment_details : "" }}</textarea>

                                        </div>
                                    </div>
                                    <div class="vital-inner">
                                        <div class="vital-col-1">
                                            <label>Past Investigation (if any)</label>
                                        </div>
                                        <div class="vital-col-2">
                                        <textarea cols=60 rows=5 name="past_investigation" class="form-control"
                                                  type="text" class="form-control"
                                                  value="{{ isset($vitalData->past_investigation) ? $vitalData->past_investigation : "" }}">{{ isset($vitalData->past_investigation) ? $vitalData->past_investigation : "" }}</textarea>

                                        </div>
                                    </div>

                                </div>
                            </div>
                            @if($booking->isEditable())
                                <button id="save-vital"
                                        class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                        </div>
                    </form>
                @endif


                <div class="vital-data-wrap">
                    <h3> Examinations</h3>
                    <hr/>
                    <h4 class="vr_title_2">General Physical Examinations</h4>

                    @if($booking->isEditable())
                        <form method="POST" class="">
                            @endif
                            {{ csrf_field() }}
                            <input type="hidden" name="token_id" value="{{ $token->id }}"/>
                            <input type="hidden" name="patient_id" value="{{ $patient->id }}"/>
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}"/>
                            <input type="hidden" name="save_section" value="general_examination"/>

                            <div class="">
                                <div class="vital-inner">
                                    <div class="vital-col-1">
                                        <label>Built</label>
                                    </div>
                                    <div class="vital-col-2">
                                        <input type="text" name="built" class="form-control"
                                               value="{{ $physical->built }}">
                                    </div>
                                </div>

                                <div class="vital-inner">
                                    <div class="vital-col-1">
                                        <label>Heart Rate</label>
                                    </div>
                                    <div class="vital-col-2">
                                        <input type="text" name="heart_rate" class="form-control"
                                               value="{{ $physical->heart_rate }}">
                                    </div>
                                </div>

                                <div class="vital-inner">
                                    <div class="vital-col-1">
                                        <label>Anaemia</label>
                                    </div>
                                    <div class="vital-col-2">
                                        <input type="text" name="anaemia" class="form-control"
                                               value="{{ $physical->anaemia }}">
                                    </div>
                                </div>

                                <div class="vital-inner">
                                    <div class="vital-col-1">
                                        <label>Nourishment</label>
                                    </div>
                                    <div class="vital-col-2">
                                        <input type="text" name="nourishment" class="form-control"
                                               value="{{ $physical->nourishment }}">
                                    </div>
                                </div>
                                <div class="vital-inner">
                                    <div class="vital-col-1">
                                        <label>Temperature</label>
                                    </div>
                                    <div class="vital-col-2">
                                        <input type="text" name="temperature" class="form-control"
                                               value="{{ $physical->temperature }}">
                                    </div>
                                </div>
                                <div class="vital-inner">
                                    <div class="vital-col-1">
                                        <label>Respiratory Rate</label>
                                    </div>
                                    <div class="vital-col-2">
                                        <input type="text" name="respiratory_rate" class="form-control"
                                               value="{{ $physical->respiratory_rate }}">
                                    </div>
                                </div>

                                <div class="vital-inner">
                                    <div class="vital-col-1">
                                        <label>Icterus</label>
                                    </div>
                                    <div class="vital-col-2">
                                        <input type="text" name="icterus" class="form-control"
                                               value="{{ $physical->icterus }}">
                                    </div>
                                </div>

                                <div class="vital-inner">
                                    <div class="vital-col-1">
                                        <label>Cyanosis</label>
                                    </div>
                                    <div class="vital-col-2">
                                        <input type="text" name="cyanosis" class="form-control"
                                               value="{{ $physical->cyanosis }}">
                                    </div>
                                </div>

                                <div class="vital-inner">
                                    <div class="vital-col-1">
                                        <label>Nails</label>
                                    </div>
                                    <div class="vital-col-2">
                                        <input type="text" name="nails" class="form-control"
                                               value="{{ $physical->nails }}">
                                    </div>
                                </div>


                                <div class="vital-inner">
                                    <div class="vital-col-1">
                                        <label>Clubbing</label>
                                    </div>
                                    <div class="vital-col-2">
                                        <input type="text" name="clubbing" class="form-control"
                                               value="{{ $physical->clubbing }}">
                                    </div>
                                </div>

                                <div class="vital-inner">
                                    <div class="vital-col-1">
                                        <label>Lymph Nodes Enlargement</label>
                                    </div>
                                    <div class="vital-col-2">
                                        <input type="text" name="lymph_nodes_enlargement" class="form-control"
                                               value="{{ $physical->lymph_nodes_enlargement }}">
                                    </div>
                                </div>

                                <div class="vital-inner">
                                    <div class="vital-col-1">
                                        <label>Oedema</label>
                                    </div>
                                    <div class="vital-col-2">
                                        <input type="text" name="oedema" class="form-control"
                                               value="{{ $physical->oedema }}">
                                    </div>
                                </div>

                                <div class="vital-inner">
                                    <div class="vital-col-1">
                                        <label>Tongue</label>
                                    </div>
                                    <div class="vital-col-2">
                                        <input type="text" name="tongue" class="form-control"
                                               value="{{ $physical->tongue }}">
                                    </div>
                                </div>
                                @if($booking->isEditable())
                                    <button id="save-vital"
                                            class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>


            <div class="vital-data-wrap">
                <h3> Systematic Examinations</h3>
                <hr/>
                <h4 class="vr_title_2">Cardiovascular System</h4>
                <div class="">
                    @if($booking->isEditable())
                        <form method="POST" class="">
                            @endif
                            {{ csrf_field() }}
                            <input type="hidden" name="token_id" value="{{ $token->id }}"/>
                            <input type="hidden" name="patient_id" value="{{ $patient->id }}"/>
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}"/>
                            <input type="hidden" name="save_section" value="cardio_examination"/>


                            <div class="vital-inner">

                                <div class="vital-col-1">
                                    <label>Chest Pain</label>
                                </div>
                                <div class="vital-col-1">

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="cardio_chest_pain"
                                                   {{ $cardio->chest_pain ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="cardio_chest_pain"
                                                   {{ $cardio->chest_pain ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="cardio_chest_pain"
                                                   {{ $cardio->chest_pain ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>


                                </div>
                                <div class="vital-col-2">
                                    <input type="text" class="form-control" name="cardio_chest_pain_doctor"
                                           value="{{ $cardio->chest_pain_doctor }}">
                                </div>
                            </div>

                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Dyspnoea</label>
                                </div>
                                <div class="vital-col-1">

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="dyspnoea"
                                                   {{ $cardio->dyspnoea ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="dyspnoea"
                                                   {{ $cardio->dyspnoea ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>
                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="dyspnoea"
                                                   {{ $cardio->dyspnoea ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>


                                </div>
                                <div class="vital-col-2">
                                    <input type="text" class="form-control" name="dyspnoea_doctor"
                                           value="{{ $cardio->dyspnoea_doctor }}">
                                </div>
                            </div>

                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Palpitations</label>
                                </div>
                                <div class="vital-col-1">

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="palpitations"
                                                   {{ $cardio->palpitations ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="palpitations"
                                                   {{ $cardio->palpitations ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="palpitations"
                                                   {{ $cardio->palpitations ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>


                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="palpitations_doctor" class="form-control"
                                           value="{{ $cardio->palpitations_doctor }}">
                                </div>
                            </div>
                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Dizziness</label>
                                </div>
                                <div class="vital-col-1">

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="dizziness"
                                                   {{ $cardio->dizziness ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="dizziness"
                                                   {{ $cardio->dizziness ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="dizziness"
                                                   {{ $cardio->dizziness ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="dizziness_doctor" class="form-control"
                                           value="{{ $cardio->dizziness_doctor }}">
                                </div>
                            </div>

                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>On examination</label>
                                </div>
                                <div class="vital-col-3">
                                    <input type="text" class="form-control" name="cardio_doctor_details"
                                           value="{{ $cardio->doctor_details }}">
                                </div>
                            </div>
                            @if($booking->isEditable())
                                <button id="save-vital"
                                        class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>

                        </form>
                    @endif
                </div>

                <h4 class="vr_title_2">Respiratory System</h4>

                <div class="">
                    @if($booking->isEditable())
                        <form method="POST" class="">
                            @endif
                            {{ csrf_field() }}
                            <input type="hidden" name="token_id" value="{{ $token->id }}"/>
                            <input type="hidden" name="patient_id" value="{{ $patient->id }}"/>
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}"/>
                            <input type="hidden" name="save_section" value="respiratory_examination"/>
                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Cough/ Sputum</label>
                                </div>
                                <div class="vital-col-1">

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="cough"
                                                   {{ $respiratory->cough ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="cough"
                                                   {{ $respiratory->cough ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="cough"
                                                   {{ $respiratory->cough ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>


                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="cough_doctor" class="form-control"
                                           value="{{ $respiratory->cough_doctor }}">
                                </div>
                            </div>

                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Fever/ Sweat</label>
                                </div>
                                <div class="vital-col-1">

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="respiratory_fever"
                                                   {{$respiratory->fever ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="respiratory_fever"
                                                   {{ $respiratory->fever ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="respiratory_fever"
                                                   {{ $respiratory->fever ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="vital-col-2">
                                    <input type="text" class="form-control" name="respiratory_fever_doctor"
                                           value="{{ $respiratory->fever_doctor }}">
                                </div>
                            </div>

                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Sinusitis</label>
                                </div>
                                <div class="vital-col-1">

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="sinusitis"
                                                   {{ $respiratory->sinusitis ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="sinusitis"
                                                   {{ $respiratory->sinusitis ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="sinusitis"
                                                   {{ $respiratory->sinusitis ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>


                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="sinusitis_doctor" class="form-control"
                                           value="{{ $respiratory->sinusitis_doctor }}">
                                </div>
                            </div>

                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Chest Pain</label>
                                </div>
                                <div class="vital-col-1">


                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="chest_pain"
                                                   {{ $respiratory->chest_pain ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="chest_pain"
                                                   {{ $respiratory->chest_pain ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="chest_pain"
                                                   {{ $respiratory->chest_pain ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="chest_pain_doctor" class="form-control"
                                           value="{{ $respiratory->chest_pain_doctor }}">
                                </div>
                            </div>
                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Wheeze</label>
                                </div>
                                <div class="vital-col-1">


                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="wheeze"
                                                   {{ $respiratory->wheeze ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="wheeze"
                                                   {{ $respiratory->wheeze ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="wheeze"
                                                   {{ $respiratory->wheeze ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>


                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="wheeze_doctor" class="form-control"
                                           value="{{ $respiratory->wheeze_doctor }}">
                                </div>
                            </div>
                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Hoarsness</label>
                                </div>
                                <div class="vital-col-1">


                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="hoarsness"
                                                   {{ $respiratory->hoarsness ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="hoarsness"
                                                   {{ $respiratory->hoarsness ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="hoarsness"
                                                   {{ $respiratory->hoarsness ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>


                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="hoarsness_doctor" class="form-control"
                                           value="{{ $respiratory->hoarsness_doctor }}">
                                </div>
                            </div>
                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>On examination</label>
                                </div>
                                <div class="vital-col-3">
                                    <input type="text" name="respiratory_doctor_details" class="form-control"
                                           value="{{ $respiratory->doctor_details }}">
                                </div>
                            </div>
                            @if($booking->isEditable())
                                <button id="save-vital"
                                        class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>

                        </form>
                    @endif
                </div>

                <h4 class="vr_title_2">Genitourinary examinations</h4>

                <div class="">
                    @if($booking->isEditable())
                        <form method="POST" class="">
                            @endif
                            {{ csrf_field() }}
                            <input type="hidden" name="token_id" value="{{ $token->id }}"/>
                            <input type="hidden" name="patient_id" value="{{ $patient->id }}"/>
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}"/>
                            <input type="hidden" name="save_section" value="genitourinary_examination"/>
                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Fever</label>
                                </div>
                                <div class="vital-col-1">


                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="fever"
                                                   {{ $genitorinary->fever ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="fever"
                                                   {{ $genitorinary->fever ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="fever"
                                                   {{ $genitorinary->fever ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="fever_doctor" class="form-control"
                                           value="{{ $genitorinary->fever_doctor }}">
                                </div>
                            </div>

                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Loin Pain</label>
                                </div>
                                <div class="vital-col-1">

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="loin_pain"
                                                   {{ $genitorinary->loin_pain ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="loin_pain"
                                                   {{ $genitorinary->loin_pain ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="loin_pain"
                                                   {{ $genitorinary->loin_pain ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="loin_pain_doctor" class="form-control"
                                           value="{{ $genitorinary->loin_pain_doctor }}">
                                </div>
                            </div>
                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Dysuria</label>
                                </div>
                                <div class="vital-col-1">

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="dysuria"
                                                   {{ $genitorinary->dysuria ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="dysuria"
                                                   {{ $genitorinary->dysuria ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="dysuria"
                                                   {{ $genitorinary->dysuria ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="vital-col-2">

                                    <input type="text" name="dysuria_doctor" class="form-control"
                                           value="{{ $genitorinary->dysuria_doctor }}">
                                </div>
                            </div>

                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Urethral/ Vaginal discharge</label>
                                </div>
                                <div class="vital-col-1">

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="urethral_discharge"
                                                   {{ $genitorinary->urethral_discharge ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="urethral_discharge"
                                                   {{ $genitorinary->urethral_discharge ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="urethral_discharge"
                                                   {{ $genitorinary->urethral_discharge ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>


                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="urethral_discharge_doctor" class="form-control"
                                           value="{{ $genitorinary->urethral_discharge_doctor }}">
                                </div>
                            </div>

                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Painful sexual intercourse</label>
                                </div>
                                <div class="vital-col-1">


                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="painful_sexual_intercourse"
                                                   {{ $genitorinary->painful_sexual_intercourse ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="painful_sexual_intercourse"
                                                   {{ $genitorinary->painful_sexual_intercourse ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="painful_sexual_intercourse"
                                                   {{ $genitorinary->painful_sexual_intercourse ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>


                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="painful_sexual_intercourse_doctor" class="form-control"
                                           value="{{ $genitorinary->painful_sexual_intercourse_doctor }}">
                                </div>
                            </div>
                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Menarche</label>
                                </div>
                                <div class="vital-col-1">


                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="menarche"
                                                   {{ $genitorinary->menarche ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="menarche"
                                                   {{ $genitorinary->menarche ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="menarche"
                                                   {{ $genitorinary->menarche ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="menarche_doctor" class="form-control"
                                           value="{{ $genitorinary->menarche_doctor }}">
                                </div>
                            </div>
                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Menopause</label>
                                </div>
                                <div class="vital-col-1">


                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="menopause"
                                                   {{ $genitorinary->menopause ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="menopause"
                                                   {{ $genitorinary->menopause ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="menopause"
                                                   {{ $genitorinary->menopause ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="menopause_doctor" class="form-control"
                                           value="{{ $genitorinary->menopause_doctor }}">
                                </div>
                            </div>

                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Length of periods</label>
                                </div>
                                <div class="vital-col-1">

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="length_of_periods"
                                                   {{ $genitorinary->length_of_periods ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="length_of_periods"
                                                   {{ $genitorinary->length_of_periods ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="length_of_periods"
                                                   {{ $genitorinary->length_of_periods ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="vital-col-2">
                                    <input type="text" class="form-control" name="length_of_periods_doctor"
                                           value="{{ $genitorinary->length_of_periods_doctor }}">
                                </div>
                            </div>

                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Amount/ Pain</label>
                                </div>
                                <div class="vital-col-1">


                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="amount_pain"
                                                   {{ $genitorinary->amount_pain ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="amount_pain"
                                                   {{ $genitorinary->amount_pain ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="amount_pain"
                                                   {{ $genitorinary->amount_pain ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="amount_pain_doctor" class="form-control"
                                           value="{{ $genitorinary->amount_pain_doctor }}">
                                </div>
                            </div>

                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>LMP</label>
                                </div>
                                <div class="vital-col-1">
                                    <input type="text" placeholder="Date" name="LMP" class="form-control datepicker"
                                           value="{{ $genitorinary->LMP != '0000-00-00' && !empty($genitorinary->LMP) ? date("d-m-Y", strtotime($genitorinary->LMP)) : '' }}">
                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="LMP_doctor" class="form-control"
                                           value="{{ $genitorinary->LMP_doctor }}">
                                </div>
                            </div>
                            @if($booking->isEditable())
                                <button id="save-vital"
                                        class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>

                        </form>
                    @endif
                </div>


                <div class="">
                    <div class="">
                        <h4>Gastrointestinal examination</h4>
                    </div>
                    @if($booking->isEditable())
                        <form method="POST" class="">
                            @endif
                            {{ csrf_field() }}
                            <input type="hidden" name="token_id" value="{{ $token->id }}"/>
                            <input type="hidden" name="patient_id" value="{{ $patient->id }}"/>
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}"/>
                            <input type="hidden" name="save_section" value="gastrointestinal_examination"/>
                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Abdominal pain</label>
                                </div>
                                <div class="vital-col-1">

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="abdominal_pain"
                                                   {{ $gastro->abdominal_pain ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="abdominal_pain"
                                                   {{ $gastro->abdominal_pain ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="abdominal_pain"
                                                   {{ $gastro->abdominal_pain ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>


                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="abdominal_pain_doctor" class="form-control"
                                           value="{{ $gastro->abdominal_pain_doctor }}">
                                </div>
                            </div>

                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Nausea/ vomiting/haematemesis</label>
                                </div>
                                <div class="vital-col-1">

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="nausea"
                                                   {{ $gastro->nausea ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="nausea"
                                                   {{ $gastro->nausea ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="nausea"
                                                   {{ $gastro->nausea ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="nausea_doctor" class="form-control"
                                           value="{{ $gastro->nausea_doctor }}">
                                </div>
                            </div>

                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Dysphagia</label>
                                </div>
                                <div class="vital-col-1">


                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="dysphagia"
                                                   {{ $gastro->dysphagia ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="dysphagia"
                                                   {{ $gastro->dysphagia ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="dysphagia"
                                                   {{ $gastro->dysphagia ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="dysphagia_doctor" class="form-control"
                                           value="{{ $gastro->dysphagia_doctor }}">
                                </div>
                            </div>
                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Indigestion</label>
                                </div>
                                <div class="vital-col-1">

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="indigestion"
                                                   {{ $gastro->indigestion ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="indigestion"
                                                   {{ $gastro->indigestion ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="indigestion"
                                                   {{ $gastro->indigestion ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="indigestion_doctor" class="form-control"
                                           value="{{ $gastro->indigestion_doctor }}">
                                </div>
                            </div>

                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Change in Bowel habits</label>
                                </div>
                                <div class="vital-col-1">

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="change_in_bowel_habits"
                                                   {{ $gastro->change_in_bowel_habits ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="change_in_bowel_habits"
                                                   {{ $gastro->change_in_bowel_habits ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="change_in_bowel_habits"
                                                   {{ $gastro->change_in_bowel_habits ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="change_in_bowel_habits_doctor" class="form-control"
                                           value="{{ $gastro->change_in_bowel_habits_doctor }}">
                                </div>
                            </div>


                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Diarrhoea/ constipation</label>
                                </div>
                                <div class="vital-col-1">


                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="diarrhoea_constipation"
                                                   {{ $gastro->diarrhoea_constipation ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="diarrhoea_constipation"
                                                   {{ $gastro->diarrhoea_constipation ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="diarrhoea_constipation"
                                                   {{ $gastro->diarrhoea_constipation ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>


                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="diarrhoea_constipation_doctor" class="form-control"
                                           value="{{ $gastro->diarrhoea_constipation_doctor }}">
                                </div>
                            </div>

                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Rectal Bleeding </label>
                                </div>
                                <div class="vital-col-1">

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="rectal_bleeding"
                                                   {{ $gastro->rectal_bleeding ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="rectal_bleeding"
                                                   {{ $gastro->rectal_bleeding ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="rectal_bleeding"
                                                   {{ $gastro->rectal_bleeding ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="rectal_bleeding_doctor" class="form-control"
                                           value="{{ $gastro->rectal_bleeding_doctor }}">
                                </div>
                            </div>

                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Appetite/ weight change </label>
                                </div>
                                <div class="vital-col-1">

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="weight_change"
                                                   {{ $gastro->weight_change ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="weight_change"
                                                   {{ $gastro->weight_change ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="weight_change"
                                                   {{ $gastro->weight_change ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>


                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="weight_change_doctor" class="form-control"
                                           value="{{ $gastro->weight_change_doctor }}">
                                </div>
                            </div>

                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Dark Urine or pale stools </label>
                                </div>
                                <div class="vital-col-1">


                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="dark_urine"
                                                   {{ $gastro->dark_urine ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="dark_urine"
                                                   {{ $gastro->dark_urine ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="dark_urine"
                                                   {{ $gastro->dark_urine ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="dark_urine_doctor" class="form-control"
                                           value="{{ $gastro->dark_urine_doctor }}">
                                </div>
                            </div>
                            @if($booking->isEditable())
                                <button id="save-vital"
                                        class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>

                        </form>
                    @endif
                </div>

                <div class="">
                    <div class="">
                        <h4>Neurological System</h4>
                    </div>
                    @if($booking->isEditable())
                        <form method="POST" class="">
                            @endif
                            {{ csrf_field() }}
                            <input type="hidden" name="token_id" value="{{ $token->id }}"/>
                            <input type="hidden" name="patient_id" value="{{ $patient->id }}"/>
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}"/>
                            <input type="hidden" name="save_section" value="neurological_examination"/>

                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Headache</label>
                                </div>
                                <div class="vital-col-1">


                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="headache"
                                                   {{ $neuro->headache ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="headache"
                                                   {{ $neuro->headache ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="headache"
                                                   {{ $neuro->headache ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="headache_doctor" class="form-control"
                                           value="{{ $neuro->headache_doctor }}">
                                </div>
                            </div>

                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Problem with vision/ hearing etc.</label>
                                </div>
                                <div class="vital-col-1">

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="vision_hearing"
                                                   {{ $neuro->vision_hearing ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="vision_hearing"
                                                   {{ $neuro->vision_hearing ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="vision_hearing"
                                                   {{ $neuro->vision_hearing ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>


                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="vision_hearing_doctor" class="form-control"
                                           value="{{ $neuro->vision_hearing_doctor }}">
                                </div>
                            </div>

                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Pain.</label>
                                </div>
                                <div class="vital-col-1">


                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="pain"
                                                   {{ $neuro->pain ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="pain"
                                                   {{ $neuro->pain ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="pain"
                                                   {{ $neuro->pain ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="pain_doctor" class="form-control"
                                           value="{{ $neuro->pain_doctor }}">
                                </div>
                            </div>

                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Numbness/ Pins& Needles</label>
                                </div>
                                <div class="vital-col-1">


                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="numbness"
                                                   {{ $neuro->numbness ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="numbness"
                                                   {{ $neuro->numbness ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="numbness"
                                                   {{ $neuro->numbness ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="numbness_doctor" class="form-control"
                                           value="{{ $neuro->numbness_doctor }}">
                                </div>
                            </div>

                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Weakness or balance problem</label>
                                </div>
                                <div class="vital-col-1">


                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="weakness"
                                                   {{ $neuro->weakness ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="weakness"
                                                   {{ $neuro->weakness ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="weakness"
                                                   {{ $neuro->weakness ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="weakness_doctor" class="form-control"
                                           value="{{ $neuro->weakness_doctor }}">
                                </div>
                            </div>
                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Abnormal/ involuntary movements</label>
                                </div>
                                <div class="vital-col-1">

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="abnormal_movements"
                                                   {{ $neuro->abnormal_movements ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="abnormal_movements"
                                                   {{ $neuro->abnormal_movements ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="abnormal_movements"
                                                   {{ $neuro->abnormal_movements ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>


                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="abnormal_movements_doctor" class="form-control"
                                           value="{{ $neuro->abnormal_movements_doctor }}">
                                </div>
                            </div>

                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Fits/ faints</label>
                                </div>
                                <div class="vital-col-1">


                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="fits"
                                                   {{ $neuro->fits ==  \App\PatientDetails::TYPE_YES  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_YES }}">
                                            <span>Yes</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="fits"
                                                   {{ $neuro->fits ==  \App\PatientDetails::TYPE_NO  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_NO }}">
                                            <span>No</span>
                                        </div>
                                    </div>

                                    <div class="pymnt_opt_row block_inline">
                                        <div class="pymt_inn">
                                            <input type="radio" name="fits"
                                                   {{ $neuro->fits ==  \App\PatientDetails::TYPE_DONTKNOW  ? 'checked' : ''}} value="{{ \App\PatientDetails::TYPE_DONTKNOW }}">
                                            <span>Don't Know</span>
                                        </div>
                                    </div>


                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="fits_doctor" class="form-control"
                                           value="{{ $neuro->fits_doctor }}">
                                </div>
                            </div>
                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>On examination</label>
                                </div>
                                <div class="vital-col-3">
                                    <input type="text" name="neuro_doctor_details" class="form-control"
                                           value="{{ $neuro->doctor_details }}">
                                </div>
                            </div>
                            @if($booking->isEditable())
                                <button id="save-vital"
                                        class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                            @endif

                        </form>
                </div>

                <div class="">
                <br>
                    <div class="">
                        <h4>Skin Examination</h4>
                    </div>
                    @if($booking->isEditable())
                        <form method="POST" class="">
                    @endif
                            {{ csrf_field() }}
                            <input type="hidden" name="token_id" value="{{ $token->id }}"/>
                            <input type="hidden" name="patient_id" value="{{ $patient->id }}"/>
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}"/>
                            <input type="hidden" name="save_section" value="skin_examination"/>

                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Skin</label>
                                </div>
                                <div class="vital-col-2">
                                    <input type="text" name="skin" class="form-control"
                                           value="{{ $skin->skin }}">
                                </div>
                            </div>
                            @if($booking->isEditable())
                                <button id="save-vital"
                                        class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                            @endif
                        </form>
                </div>



                <div class="">
                <br>
                    <div class="">
                        <h4>Eye Examination</h4>
                    </div>
                    @if($booking->isEditable())
                        <form method="POST" class="">
                    @endif
                            {{ csrf_field() }}
                            <input type="hidden" name="token_id" value="{{ $token->id }}"/>
                            <input type="hidden" name="patient_id" value="{{ $patient->id }}"/>
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}"/>
                            <input type="hidden" name="save_section" value="eye_examination"/>


                            <div class="vital-inner">
                                <div class="vital-col-1">
                                    <label>Eye / Ent</label>
                                </div>
                                <div class="vital-col-1">
                                    <input type="text" name="eye_ent" class="form-control"
                                           value="{{ $eye->eye_ent }}">
                                </div>
                            </div>
                            @if($booking->isEditable())
                                <button id="save-vital"
                                        class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                            @endif
                        </form>
                </div>


                {{-- <div class="vital-btn">
                     @if($booking->isEditable())
                         <button id="save-vital"
                                 class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                     @endif
                 </div>--}}
            </div>
            <input type="hidden" value="{{ $booking->isEditable() }}" id="is_editable">
            @if($booking->isEditable())
            </form>
            @endif
        </div>
    </div>
    </div>
@endsection

@section("js")
    <script>
        $(".datepicker").datepicker({dateFormat: "dd-mm-yy", autoclose: true,});
    </script>
@endsection

@section('js')
    <script>
        $("#copy").click(function () {
            $.ajax({
                url: "{{ url('admin/token/get-patient-details/'.$token->id) }}",
                type: "POST",
                data: {'_token': "{{ csrf_token() }}"},
                success: function (data) {
                    if (data.status == 'OK') {
                        $("#pulse").val(data.pulse);
                        $("#bp").val(data.bp);
                        $("#height").val(data.height);
                        $("#weight").val(data.weight);
                        $("#blood_group").val(data.blood_group);
                        $("#bmi").val(data.bmi);
                    }
                }

            });
        });
        $("#save-vital").click(function () {
            $.ajax({
                url: "{{ url('admin/token/post-patient-details/'.$token->id) }}",
                type: "POST",
                data: $("#patient-detail-form").serialize(),
                success: function (data) {
                    if (data.status == 'OK') {
                        $("#pulse").val(data.pulse);
                        $("#bp").val(data.bp);
                        $("#height").val(data.height);
                        $("#weight").val(data.weight);
                        $("#blood_group").val(data.blood_group);
                        $("#bmi").val(data.bmi);
                    }
                }

            });
        });
    </script>
@endsection
