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
        <div class="active section">{{  trans('laralum.view_token') }}</div>
    </div>
@endsection
@section('title', 'Patient Details')
@section('icon', "pencil")
@section('subtitle', 'Patient Details')

@section('content')
    <br><br>
    <div class="ui one column doubling stackable grid container">
        <div class="column admin_basic_detail1 admin_wrapper">
            <div class="ui very padded segment  main_wrapper">
                <div class="ui breadcrumb steps clearfix">
                    <ul>
                        <li><a class="section"
                               href="{{ route('Laralum::patient.show', ['token_id' => $booking->id]) }}">Personal
                                Details</a>
                        </li>
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
                                Diagnosis</a>
                        </li>

                        <li><a class="section"
                               href="{{ route('Laralum::patient.treatment', ['patient_id' => $booking->id]) }}">Allot
                                Treatments</a></li>

                        <li><a class="section"
                               href="{{ route('Laralum::patient.diet-chart', ['patient_id' => $booking->id]) }}">Diet
                                Chart</a></li>
                        <li><a class="section"
                               href="{{ route('Laralum::discharge.patient', ['token_id' => $booking->id]) }}">Discharge
                                Patient</a></li>

                        <li><a class=" section"
                               href="{{ route('Laralum::attachments', ['booking_id' => $booking->id]) }}">Attachments
                            </a></li>
                        <li><a class="section"
                               href="{{ route('Laralum::summary', ['id' => $booking->id]) }}">Summary</a></li>

                        {{--@php
                            $dept_model=\App\Department::where('title','like',"%Physiotherapy%")->first();
                        @endphp

                        @if(\Auth::user()->isAdmin()||(!empty($dept_model) && \Auth::user()->department->department_id==$dept_model->id))
                            <li><a class=""
                                   href="{{ route('Laralum::recommend-exercise.assign', ['patient_id' => $booking->id]) }}">Attachments
                                </a></li>
                            <li>
                        @endif--}}
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="ui one column doubling stackable grid container">
            {{--  <div>
                  <button onclick="return window.history.go(-1);" class="btn btn-primary ui button blue">
                      Back
                  </button>
              </div>--}}
            <div class="column admin_basic_detail1">
                <div class="ui very padded segment">
                    <div class="page_title">
                        <h2 class="pull-left">Ayurvedic Examinations:
                            <div class="clearfix" style="font-size:13px;font-weight:normal;clear:both">
                                <span>( Only for Ayurvedic Doctor )</span></div>
                        </h2>

                            <div class="pull-right">
                                <a class="btn btn-primary ui button {{ \App\Http\Controllers\Laralum\Laralum::settings()->button_color }}"
                                   href="{{ route('Laralum::patient.vital_data', ['token_id' => $booking->id]) }}">{{--Vital
                                    Data/Common Examinations--}}Back</a>
                            </div>

                    </div>

                    @if(\Auth::user()->isAyurvedic())
                    @if($booking->isEditable())
                        <form method="post" action="{{ url('/admin/patient/'.$booking->id.'/ayurved_vital_data/') }}">
                            {!! csrf_field() !!}
                            <input type="hidden" value="{{ $patient->id }}" name="patient_id">
                            <input type="hidden" value="{{ $booking->id }}" name="booking_id">
                            @endif
                            <div class="vital-data-wrap">
                                <div class="vital-head">
                                    <h2>{{ $booking->getProfile('first_name').' '. $booking->getProfile('last_name') }}</h2>
                                </div>
                                <section class="aurv_section">
                                    <h3 class="section_title"> {{ trans('laralum.ashtvidh_pariksha') }} </h3>
                                    <div class="form_main">

                                        <div class="form-row">
                                            <label class="label"> {{ trans('laralum.pulse') }}  </label>
                                            <div class="form-rgt_sec">
                                                <div class="input_sm">
                                                    <input class="form_control" name="pulse"
                                                           value="{{ old('pulse', $ashtvidh->pulse) }}" type="text"/>
                                                    <label>{{ trans('laralum.speed_per_mins') }}</label>
                                                </div>
                                                <div class="input_sm">
                                                    <label>{{ trans('laralum.pulse_issue') }}  </label>
                                                    <input class="form_control" type="text" name="pulse_issue"
                                                           value="{{ old('pulse_issue', $ashtvidh->pulse_issue) }}"/>
                                                </div>
                                                <div class="input_lg">
                                                    <label>{{ trans('laralum.comment_data') }} </label>
                                                    <input class="form_control" name="pulse_comment"
                                                           value="{{ old('pulse_comment', $ashtvidh->pulse_comment) }}"
                                                           type="text"/>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <label class="label"> {{ trans('laralum.faecal_matter') }} </label>
                                            <div class="form-rgt_sec">

                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedaAshtvidhExamination::TYPE_CONSISTANT }}"
                                                           name="faecal_matter" {{ old('faecal_matter', $ashtvidh->faecal_matter) == \App\AyurvedaAshtvidhExamination::TYPE_CONSISTANT ? 'checked' : "" }} />
                                                    <label>{{ trans('laralum.consistent') }} </label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedaAshtvidhExamination::TYPE_INCONSISTANT }}"
                                                           name="faecal_matter" {{ old('faecal_matter', $ashtvidh->faecal_matter) == \App\AyurvedaAshtvidhExamination::TYPE_INCONSISTANT ? 'checked' : "" }}/>
                                                    <label>{{ trans('laralum.inconsistent') }}</label>
                                                </div>

                                                <div class="input_sm">
                                                    <label>{{ trans('laralum.speed_per_days') }}</label>
                                                    <input class="form_control" name="faecal_matter_speed_days"
                                                           value="{{ old('faecal_matter_speed_days', $ashtvidh->faecal_matter_speed_days) }}"
                                                           type="text"/>
                                                </div>
                                                <div class="input_lg">
                                                    <label>{{ trans('laralum.comment_data') }} </label>
                                                    <input class="form_control" name="faecal_matter_comment"
                                                           value="{{ old('faecal_matter_comment', $ashtvidh->faecal_matter_comment) }}"
                                                           type="text"/>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <label class="label"> {{ trans('laralum.faecal_matter_liquid') }}</label>
                                            <div class="form-rgt_sec">
                                                <div class="input_sm">
                                                    <label>{{ trans('laralum.speed_per_days') }}</label>
                                                    <input class="form_control" name="faecal_matter_liquid_speed_days"
                                                           value="{{ old('faecal_matter_liquid_speed_days', $ashtvidh->faecal_matter_liquid_speed_days) }}"
                                                           type="text"/>
                                                </div>
                                                <div class="input_sm">
                                                    <label> {{ trans('laralum.varna') }}</label>
                                                    <input class="form_control" type="text" name="faecal_matter_liquid"
                                                           value="{{ old('faecal_matter_liquid', $ashtvidh->faecal_matter_liquid) }}"/>
                                                </div>
                                                <div class="input_sm">
                                                    <label>{{ trans('laralum.speed_per_nights') }}</label>
                                                    <input class="form_control" type="text"
                                                           name="faecal_matter_liquid_speed_nights"
                                                           value="{{ old('faecal_matter_liquid', $ashtvidh->faecal_matter_liquid_speed_nights) }}"/>
                                                </div>
                                                <div class="input_lg">
                                                    <label>{{ trans('laralum.comment_data') }} </label>
                                                    <input class="form_control" type="text"
                                                           name="faecal_matter_liquid_comment"
                                                           value="{{ old('faecal_matter_liquid_comment', $ashtvidh->faecal_matter_liquid_comment) }}"/>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <label class="label"> {{ trans('laralum.tongue') }}</label>
                                            <div class="form-rgt_sec">

                                               <div class="form-radio">
                                                    <input class="radio" type="radio" name="tongue"
                                                           value="{{ \App\AyurvedaAshtvidhExamination::TYPE_SAAM }}" {{ old('tongue', $ashtvidh->tongue) == \App\AyurvedaAshtvidhExamination::TYPE_SAAM ? 'checked' : "" }} />
                                                    <label>{{ trans('laralum.saam') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedaAshtvidhExamination::TYPE_NIRAM }}"
                                                           name="tongue" {{ old('tongue', $ashtvidh->tongue) == \App\AyurvedaAshtvidhExamination::TYPE_NIRAM ? 'checked' : "" }}/>
                                                    <label>{{ trans('laralum.niraam') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio"
                                                           value="{{ \App\AyurvedaAshtvidhExamination::TYPE_SHUSHK }}"
                                                           type="radio"
                                                           name="tongue_2" {{ old('tongue_2', $ashtvidh->tongue_2) == \App\AyurvedaAshtvidhExamination::TYPE_SHUSHK ? 'checked' : "" }}/>
                                                    <label>{{ trans('laralum.shushk') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio"
                                                           value="{{ \App\AyurvedaAshtvidhExamination::TYPE_LIPT }}"
                                                           type="radio"
                                                           name="tongue_2" {{ old('tongue_2', $ashtvidh->tongue_2) == \App\AyurvedaAshtvidhExamination::TYPE_LIPT ? 'checked' : "" }}/>
                                                    <label>{{ trans('laralum.lipt') }}</label>
                                                </div>

                                                <div class="input_lg">
                                                    <label>{{ trans('laralum.comment_data') }}  </label>
                                                    <input class="form_control" type="text" name="tongue_comment"
                                                           value="{{ old('tongue_comment', $ashtvidh->tongue_comment) }}"/>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <label class="label"> {{ trans('laralum.speech') }}</label>
                                            <div class="form-rgt_sec">

                                                <div class="form-radio">
                                                    <input class="radio" type="radio" name="speech"
                                                           value="{{ \App\AyurvedaAshtvidhExamination::TYPE_NATURAL }}" {{ old('speech', $ashtvidh->speech) == \App\AyurvedaAshtvidhExamination::TYPE_NATURAL ? 'checked' : "" }} />
                                                    <label>{{ trans('laralum.prakrut') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio" name="speech"
                                                           value="{{ \App\AyurvedaAshtvidhExamination::TYPE_UNNATURAL }}" {{ old('speech', $ashtvidh->speech) == \App\AyurvedaAshtvidhExamination::TYPE_UNNATURAL ? 'checked' : "" }} />
                                                    <label>{{ trans('laralum.vikrut') }}</label>
                                                </div>

                                                <div class="input_lg">
                                                    <label>{{ trans('laralum.comment_data') }}  </label>
                                                    <input class="form_control" type="text" name="speech_comment"
                                                           value="{{ old('speech_comment', $ashtvidh->speech_comment) }}"/>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-row">
                                            <label class="label"> {{ trans('laralum.skin') }} </label>
                                            <div class="form-rgt_sec">

                                                <div class="form-radio">
                                                    <input class="radio" type="radio" name="skin"
                                                           value="{{ \App\AyurvedaAshtvidhExamination::TYPE_NATURAL }}" {{ old('skin', $ashtvidh->skin) == \App\AyurvedaAshtvidhExamination::TYPE_NATURAL ? 'checked' : "" }} />
                                                    <label>{{ trans('laralum.prakrut') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio" name="skin"
                                                           value="{{ \App\AyurvedaAshtvidhExamination::TYPE_UNNATURAL }}" {{ old('skin', $ashtvidh->skin) == \App\AyurvedaAshtvidhExamination::TYPE_UNNATURAL ? 'checked' : "" }} />
                                                    <label>{{ trans('laralum.vikrut') }}</label>
                                                </div>

                                                <div class="input_lg">
                                                    <label>{{ trans('laralum.comment_data') }}  </label>
                                                    <input class="form_control" type="text" name="skin_comment"
                                                           value="{{ old('skin_comment', $ashtvidh->skin_comment) }}"/>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <label class="label"> {{ trans('laralum.eyes') }}</label>
                                            <div class="form-rgt_sec">

                                                <div class="form-radio">
                                                    <input class="radio" type="radio" name="eyes"
                                                           value="{{ \App\AyurvedaAshtvidhExamination::TYPE_NATURAL }}" {{ old('eyes', $ashtvidh->eyes) == \App\AyurvedaAshtvidhExamination::TYPE_NATURAL ? 'checked' : "" }}/>
                                                    <label>{{ trans('laralum.prakrut') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio" name="eyes"
                                                           value="{{ \App\AyurvedaAshtvidhExamination::TYPE_UNNATURAL }}" {{ old('eyes', $ashtvidh->eyes) == \App\AyurvedaAshtvidhExamination::TYPE_UNNATURAL ? 'checked' : "" }} />
                                                    <label>{{ trans('laralum.vikrut') }}</label>
                                                </div>

                                                <div class="input_lg">
                                                    <label>{{ trans('laralum.comment_data') }} </label>
                                                    <input class="form_control" type="text" name="eyes_comment"
                                                           value="{{ old('eyes_comment', $ashtvidh->eyes_comment) }}"/>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-row">
                                            <label class="label"> {{ trans('laralum.body_build') }}</label>
                                            <div class="form-rgt_sec">

                                                <div class="form-radio">
                                                    <input class="radio" type="radio" name="body_build"
                                                           value="{{ \App\AyurvedaAshtvidhExamination::VAAT }}" {{ old('body_build', $ashtvidh->body_build) == \App\AyurvedaAshtvidhExamination::VAAT ? 'checked' : "" }} />
                                                    <label>{{ trans('laralum.vat') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio" name="body_build"
                                                           value="{{ \App\AyurvedaAshtvidhExamination::PITT }}" {{ old('body_build', $ashtvidh->body_build) == \App\AyurvedaAshtvidhExamination::PITT ? 'checked' : "" }} />
                                                    <label>{{ trans('laralum.pitt') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedaAshtvidhExamination::COUGH }}"
                                                           name="body_build" {{ old('body_build', $ashtvidh->body_build) == \App\AyurvedaAshtvidhExamination::COUGH ? 'checked' : "" }} />
                                                    <label>{{ trans('laralum.kaph') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedaAshtvidhExamination::VAATPITT }}"
                                                           name="body_build" {{ old('body_build', $ashtvidh->body_build) == \App\AyurvedaAshtvidhExamination::VAATPITT ? 'checked' : "" }} />
                                                    <label>{{ trans('laralum.vaatpitt') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedaAshtvidhExamination::PITTCOUGH }}"
                                                           name="body_build" {{ old('body_build', $ashtvidh->body_build) == \App\AyurvedaAshtvidhExamination::PITTCOUGH ? 'checked' : "" }} />
                                                    <label>{{ trans('laralum.pitt_kaph') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedaAshtvidhExamination::COUGHVAAT }}"
                                                           name="body_build" {{ old('body_build', $ashtvidh->body_build) == \App\AyurvedaAshtvidhExamination::COUGHVAAT ? 'checked' : "" }} />
                                                    <label>{{ trans('laralum.kaph_vaat') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedaAshtvidhExamination::SUM }}"
                                                           name="body_build" {{ old('body_build', $ashtvidh->body_build) == \App\AyurvedaAshtvidhExamination::SUM ? 'checked' : "" }} />
                                                    <label>{{ trans('laralum.sam') }}</label>
                                                </div>
                                                {{--<div class="input_lg">
                                                    <label>{{ trans('laralum.comment_data') }}  </label>
                                                    <input class="form_control" type="text" name="body_build_comment" value="{{ old('body_build_comment', $ashtvidh->body_build_comment) }}"/>
                                                </div>--}}
                                            </div>
                                        </div>

                                    </div>
                                </section>

                                <section class="aurv_section">
                                    <h3 class="section_title"> {{  trans('laralum.atur_pariksha') }} </h3>
                                    <div class="form_main">

                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.prakriti') }}</label>
                                            <div class="form-rgt_sec">

                                                <div class="form-radio">
                                                    <input class="radio" type="radio" name="prakriti"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_VAT }}" {{ old('prakriti', $aturpariksha->prakriti) == \App\AyurvedAturExamination::TYPE_VAT ? 'checked' : "" }} />
                                                    <label>{{  trans('laralum.vat') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio" name="prakriti"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_PITT }}" {{ old('prakriti', $aturpariksha->prakriti) == \App\AyurvedAturExamination::TYPE_PITT ? 'checked' : "" }} />
                                                    <label>{{  trans('laralum.pitt') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_KAPH }}"
                                                           type="radio"
                                                           name="prakriti" {{ old('prakriti', $aturpariksha->prakriti) == \App\AyurvedAturExamination::TYPE_KAPH ? 'checked' : "" }} />
                                                    <label>{{  trans('laralum.kaph') }}</label>
                                                </div>

                                                <div class="form-radio">
                                                    <input class="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_VATPITT }}"
                                                           type="radio"
                                                           name="prakriti" {{ old('prakriti', $aturpariksha->prakriti) == \App\AyurvedAturExamination::TYPE_VATPITT ? 'checked' : "" }} />
                                                    <label>{{  trans('laralum.vaatpitt') }}</label>
                                                </div>

                                                <div class="form-radio">
                                                    <input class="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_PITTKAPH }}"
                                                           type="radio"
                                                           name="prakriti" {{ old('prakriti', $aturpariksha->prakriti) == \App\AyurvedAturExamination::TYPE_PITTKAPH ? 'checked' : "" }} />
                                                    <label>{{  trans('laralum.pittkaph') }}</label>
                                                </div>

                                                <div class="form-radio">
                                                    <input class="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_KAPHVAT }}"
                                                           type="radio"
                                                           name="prakriti" {{ old('prakriti', $aturpariksha->prakriti) == \App\AyurvedAturExamination::TYPE_KAPHVAT ? 'checked' : "" }} />
                                                    <label>{{  trans('laralum.kaphvitt') }}</label>
                                                </div>

                                                <div class="form-radio">
                                                    <input class="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_SAM }}"
                                                           type="radio"
                                                           name="prakriti" {{ old('prakriti', $aturpariksha->prakriti) == \App\AyurvedAturExamination::TYPE_SAM ? 'checked' : "" }} />
                                                    <label>{{  trans('laralum.sam') }}</label>
                                                </div>
                                                {{--<div class="form-radio">
                                                    <input class="radio" type="radio" />
                                                    <label>text</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio" />
                                                    <label>text2</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio" />
                                                    <label>text2</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio" />
                                                    <label>text2</label>
                                                </div>--}}

                                                {{--<div class="input_lg">
                                                    <label>{{  trans('laralum.comment_data') }} </label>
                                                    <input class="form_control" type="text" name="prakriti_comment"
                                                           value="{{ old('prakriti_comment', $aturpariksha->prakriti_comment) }}"/>
                                                </div>--}}
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.saar') }}</label>
                                            <div class="form-rgt_sec">

                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox"  name="saar[]"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_RAKT }}"  {{ $aturpariksha->isChecked('saar', \App\AyurvedAturExamination::TYPE_RAKT)   ? 'checked' : "" }} />
                                                    <label>{{  trans('laralum.rakt') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox"  name="saar[]"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_RAS }}" {{ $aturpariksha->isChecked('saar', \App\AyurvedAturExamination::TYPE_RAS)  ? 'checked' : ""  }}/>
                                                    <label>{{  trans('laralum.ras') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox"  name="saar[]"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_MANS }}" {{ $aturpariksha->isChecked('saar', \App\AyurvedAturExamination::TYPE_MANS)  ? 'checked' : ""  }} />
                                                    <label>{{  trans('laralum.maans') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox"  name="saar[]"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_MED }}" {{ $aturpariksha->isChecked('saar', \App\AyurvedAturExamination::TYPE_MED)  ? 'checked' : ""  }}  />
                                                    <label>{{  trans('laralum.med') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_ASTHI }}"
                                                           name="saar[]" {{ $aturpariksha->isChecked('saar', \App\AyurvedAturExamination::TYPE_ASTHI)  ? 'checked' : ""  }} />
                                                    <label>{{  trans('laralum.asthi') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox"  name="saar[]"
                                                           {{ $aturpariksha->isChecked('saar', \App\AyurvedAturExamination::TYPE_MAJJ)  ? 'checked' : ""  }} value="{{ \App\AyurvedAturExamination::TYPE_MAJJ }}"/>
                                                    <label>{{  trans('laralum.majj') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="saar[]"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_SHUKRA }}" {{ $aturpariksha->isChecked('saar', \App\AyurvedAturExamination::TYPE_SHUKRA)  ? 'checked' : ""  }} />
                                                    <label>{{  trans('laralum.shukra') }}</label>
                                                </div>

                                                {{--<div class="input_lg">
                                                    <label>{{  trans('laralum.comment_data') }} </label>
                                                    <input class="form_control" type="text" name="saar_comment"
                                                           value="{{ old('saar_comment', $aturpariksha->saar_comment) }}"/>
                                                </div>--}}
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.sanhanan') }}</label>
                                            <div class="form-rgt_sec">

                                                <div class="form-radio">
                                                    <input class="radio" type="radio" name="sanhanan"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_UTTAM }}" {{ old('sanhanan', $aturpariksha->sanhanan) == \App\AyurvedAturExamination::TYPE_UTTAM ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.uttam') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio" name="sanhanan"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_MADHYAM }}" {{ old('sanhanan', $aturpariksha->sanhanan) == \App\AyurvedAturExamination::TYPE_MADHYAM ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.madhyam') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio" name="sanhanan"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_HEEN }}" {{ old('sanhanan', $aturpariksha->sanhanan) == \App\AyurvedAturExamination::TYPE_HEEN ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.heen') }}</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.praman') }}</label>
                                            <div class="form-rgt_sec">

                                                <div class="input_sm">
                                                    <label>{{  trans('laralum.lambai') }} </label>
                                                    <input type="text" class="form_control" disabled
                                                    @if($aturpariksha->praman)
                                                      value="{{ old('praman', $aturpariksha->praman) }}"   
                                                    @else
                                                      @if($patient_details != null)
                                                        value="{{ $patient_details->heigh }}"
                                                      @endif 
                                                    @endif 
                                                    >
                                                    <input type="hidden" class="form_control" name="praman"
                                                    @if($aturpariksha->praman)
                                                      value="{{ old('praman', $aturpariksha->praman) }}"   
                                                    @else
                                                      @if($patient_details != null)
                                                        value="{{ $patient_details->heigh }}"
                                                      @endif 
                                                    @endif 
                                                    >
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.satmyaya') }} </label>
                                            <div class="form-rgt_sec">

                                                <div class="form-radio">
                                                    <input class="radio" type="radio" name="satmyaya"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_PRAVER }}" {{ old('satmyaya', $aturpariksha->satmyaya) == \App\AyurvedAturExamination::TYPE_PRAVER ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.pravar') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_MADHYAM }}"
                                                           name="satmyaya" {{ old('satmyaya', $aturpariksha->satmyaya) == \App\AyurvedAturExamination::TYPE_MADHYAM ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.madhyam') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_HEEN }}"
                                                           name="satmyaya" {{ old('satmyaya', $aturpariksha->satmyaya) == \App\AyurvedAturExamination::TYPE_HEEN ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.heen') }}</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.satva') }}</label>
                                            <div class="form-rgt_sec">

                                                <div class="form-radio">
                                                    <input class="radio" type="radio" name="satva"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_PRAVER }}" {{ old('satva', $aturpariksha->satva) == \App\AyurvedAturExamination::TYPE_PRAVER ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.pravar') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_MADHYAM }}"
                                                           name="satva" {{ old('satva', $aturpariksha->satva) == \App\AyurvedAturExamination::TYPE_MADHYAM ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.madhyam') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_HEEN }}"
                                                           name="satva" {{ old('satva', $aturpariksha->satva) == \App\AyurvedAturExamination::TYPE_HEEN ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.heen') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.ahaar_shakti') }}</label>
                                            <div class="form-rgt_sec">

                                                <div class="form-radio">
                                                    <input class="radio" type="radio" name="ahaar_shakti"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_UTTAM }}" {{ old('ahaar_shakti', $aturpariksha->ahaar_shakti) == \App\AyurvedAturExamination::TYPE_UTTAM ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.uttam') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio" name="ahaar_shakti"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_MADHYAM }}" {{ old('ahaar_shakti', $aturpariksha->ahaar_shakti) == \App\AyurvedAturExamination::TYPE_MADHYAM ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.madhyam') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_HEEN }}"
                                                           name="ahaar_shakti" {{ old('ahaar_shakti', $aturpariksha->ahaar_shakti) == \App\AyurvedAturExamination::TYPE_HEEN ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.heen') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.vyayaam_shakti') }}</label>
                                            <div class="form-rgt_sec">

                                                <div class="form-radio">
                                                    <input class="radio" type="radio" name="vyayaam_shakti"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_PRAVER }}" {{ old('vyayaam_shakti', $aturpariksha->vyayaam_shakti) == \App\AyurvedAturExamination::TYPE_PRAVER ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.pravar') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_MADHYAM }}"
                                                           name="vyayaam_shakti" {{ old('vyayaam_shakti', $aturpariksha->vyayaam_shakti) == \App\AyurvedAturExamination::TYPE_MADHYAM ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.madhyam') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_HEEN }}"
                                                           type="radio"
                                                           name="vyayaam_shakti" {{ old('vyayaam_shakti', $aturpariksha->vyayaam_shakti) == \App\AyurvedAturExamination::TYPE_HEEN ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.heen') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.vaya') }}</label>
                                            <div class="form-rgt_sec">
                                                <div class="form-radio">
                                                    <input class="radio baal_age" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_BAAL }}"
                                                           name="vaya" {{ old('vaya', $aturpariksha->vaya) == \App\AyurvedAturExamination::TYPE_BAAL ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.baal') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio madhyam_age" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_MADHYAM }}"
                                                           name="vaya" {{ old('vaya', $aturpariksha->vaya) == \App\AyurvedAturExamination::TYPE_MADHYAM ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.madhyam') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio vridh_age" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_VRIDH }}"
                                                           name="vaya" {{ old('vaya', $aturpariksha->vaya) == \App\AyurvedAturExamination::TYPE_VRIDH ? 'checked' : "" }}/>

                                                    <label>{{  trans('laralum.vridh') }}</label>
                                                </div>


                                                <!-- <div class="form-radio">
                                                    <input class="radio baal_age" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_BAAL }}"
                                                           name="vaya" {{ old('vaya', $aturpariksha->vaya) == \App\AyurvedAturExamination::TYPE_BAAL ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.baal') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio yuva_age" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_YUVA }}"
                                                           name="vaya" {{ old('vaya', $aturpariksha->vaya) == \App\AyurvedAturExamination::TYPE_YUVA ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.yuva') }}</label>
                                                </div>

                                                <div class="form-radio">
                                                    <input class="radio praun_age" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_PRAUN }}"
                                                           name="vaya" {{ old('vaya', $aturpariksha->vaya) == \App\AyurvedAturExamination::TYPE_PRAUN ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.praun') }}</label>
                                                </div>

                                                <div class="form-radio">
                                                    <input class="radio vridh_age" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_JEERNYA }}"
                                                           name="vaya" {{ old('vaya', $aturpariksha->vaya) == \App\AyurvedAturExamination::TYPE_JEERNYA ? 'checked' : "" }}/>

                                                    <label>{{  trans('laralum.jeernya') }}</label>
                                                </div> -->

                                                <div class="input_lg">
                                                    <div class="row">
                                                    <label>{{  trans('laralum.varsh') }} </label>
                                                    <input type="text" class="form_control age_ayrud_exam" disabled 
                                                    @if($aturpariksha->varsh)
                                                        value="{{ old('varsh', $aturpariksha->varsh) }}"
                                                    @else
                                                        value="{{ $booking->getProfile('age') }}"
                                                     @endif > 
                                                     <input type="hidden" class="form_control age_ayrud_exam" name="varsh"
                                                    @if($aturpariksha->varsh)
                                                        value="{{ old('varsh', $aturpariksha->varsh) }}"
                                                    @else
                                                        value="{{ $booking->getProfile('age') }}"
                                                     @endif >    
                                                    </div>
                                                    <span id="errmsg_age"></span>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.bal') }}</label>
                                            <div class="form-rgt_sec">

                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_PRAVER }}"
                                                           name="bal" {{ old('bal', $aturpariksha->bal) == \App\AyurvedAturExamination::TYPE_PRAVER ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.pravar') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_MADHYAM }}"
                                                           name="bal" {{ old('bal', $aturpariksha->bal) == \App\AyurvedAturExamination::TYPE_MADHYAM ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.madhyam') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_HEEN }}"
                                                           name="bal" {{ old('bal', $aturpariksha->bal) == \App\AyurvedAturExamination::TYPE_HEEN ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.heen') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.drishya') }}</label>
                                            <div class="form-rgt_sec">

                                                <div class="input_sm">
                                                    <input type="text" class="form_control" name="drishya"
                                                           value="{{ old('drishya', $aturpariksha->drishya) }}">
                                                </div>

                                            </div>
                                        </div>
                                        <div class=""><h3>{{  trans('laralum.desh') }}</h3></div>
                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.utpatti_desh') }}</label>
                                            <div class="form-rgt_sec">

                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_ANOOP }}"
                                                           name="utpatti_desh" {{ old('utpatti_desh', $aturpariksha->uttpatti_desh) == \App\AyurvedAturExamination::TYPE_ANOOP ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.aanoop') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_JANGAL }}"
                                                           name="utpatti_desh" {{ old('utpatti_desh', $aturpariksha->uttpatti_desh) == \App\AyurvedAturExamination::TYPE_JANGAL ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.jangal') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_SADHARAN }}"
                                                           name="utpatti_desh" {{ old('utpatti_desh', $aturpariksha->uttpatti_desh) == \App\AyurvedAturExamination::TYPE_SADHARAN ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.sadharan') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.vyadhit_desh') }}</label>
                                            <div class="form-rgt_sec">

                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_ANOOP }}"
                                                           name="vyadhit_desh" {{ old('vyadhit_desh', $aturpariksha->vyadhit_desh) == \App\AyurvedAturExamination::TYPE_ANOOP ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.aanoop') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_JANGAL }}"
                                                           name="vyadhit_desh" {{ old('vyadhit_desh', $aturpariksha->vyadhit_desh) == \App\AyurvedAturExamination::TYPE_JANGAL ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.jangal') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_SADHARAN }}"
                                                           name="vyadhit_desh" {{ old('vyadhit_desh', $aturpariksha->vyadhit_desh) == \App\AyurvedAturExamination::TYPE_SADHARAN ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.sadharan') }}</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.chikitsa_desh') }}</label>
                                            <div class="form-rgt_sec">

                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_ANOOP }}"
                                                           name="chikitsa_desh" {{ old('chikitsa_desh', $aturpariksha->chikitsa_desh) == \App\AyurvedAturExamination::TYPE_ANOOP ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.aanoop') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_JANGAL }}"
                                                           name="chikitsa_desh" {{ old('chikitsa_desh', $aturpariksha->chikitsa_desh) == \App\AyurvedAturExamination::TYPE_JANGAL ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.jangal') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_SADHARAN }}"
                                                           name="chikitsa_desh" {{ old('chikitsa_desh', $aturpariksha->chikitsa_desh) == \App\AyurvedAturExamination::TYPE_SADHARAN ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.sadharan') }}</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.kaal_ritu') }}</label>
                                            <div class="form-rgt_sec">
                                                <h5>{{  trans('laralum.aadaan') }}
                                                </h5>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio" name="kaal"
                                                   value="{{ \App\AyurvedAturExamination::TYPE_SHISHIR }}"  {{ $aturpariksha->isChecked('kaal', \App\AyurvedAturExamination::TYPE_SHISHIR)  ? 'checked' : ""  }}/>
                                                    <label>{{  trans('laralum.shishir') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio" name="kaal" value="{{ \App\AyurvedAturExamination::TYPE_VASANT }}"  {{ $aturpariksha->isChecked('kaal', \App\AyurvedAturExamination::TYPE_VASANT)  ? 'checked' : ""  }}/>
                                                    <label>{{  trans('laralum.vasant') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio" name="kaal" value="{{ \App\AyurvedAturExamination::TYPE_GREESH }}"  {{ $aturpariksha->isChecked('kaal', \App\AyurvedAturExamination::TYPE_GREESH)  ? 'checked' : ""  }}/>
                                                    <label>{{  trans('laralum.greeshm') }}</label>
                                                </div>
                                                <div class="clearfix"></div>

                                                <h5>{{  trans('laralum.visarg') }}
                                                </h5>

                                                <div class="form-radio">
                                                    <input class="radio" type="radio" name="kaal" value="{{ \App\AyurvedAturExamination::TYPE_VARSHA }}"  {{ $aturpariksha->isChecked('kaal', \App\AyurvedAturExamination::TYPE_VARSHA)  ? 'checked' : ""  }}/>
                                                    <label>{{  trans('laralum.varsha') }}</label>
                                                </div>

                                                <div class="form-radio">
                                                    <input class="radio" type="radio" name="kaal" value="{{ \App\AyurvedAturExamination::TYPE_SHARAD }}" {{ $aturpariksha->isChecked('kaal', \App\AyurvedAturExamination::TYPE_SHARAD)  ? 'checked' : ""  }}/>
                                                    <label>{{  trans('laralum.sharad') }}</label>
                                                </div>

                                                <div class="form-radio">
                                                    <input class="radio" type="radio" name="kaal" value="{{ \App\AyurvedAturExamination::TYPE_HEMANT }}" {{ $aturpariksha->isChecked('kaal', \App\AyurvedAturExamination::TYPE_HEMANT)  ? 'checked' : ""  }}/>
                                                    <label>{{  trans('laralum.hemant') }}</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.anal') }}</label>
                                            <div class="form-rgt_sec">

                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_UTTAM }}"
                                                           name="anal" {{ old('anal', $aturpariksha->anal) == \App\AyurvedAturExamination::TYPE_UTTAM ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.sam') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_MADHYAM }}"
                                                           name="anal" {{ old('anal', $aturpariksha->anal) == \App\AyurvedAturExamination::TYPE_MADHYAM ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.visham') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_JEERNYA }}"
                                                           name="anal" {{ old('anal', $aturpariksha->anal) == \App\AyurvedAturExamination::TYPE_JEERNYA ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.teekshan') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class=""><h3>{{  trans('laralum.anal') }} </h3></div>
                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.rogi_awastha') }}</label>
                                            <div class="form-rgt_sec">

                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_UTTAM }}"
                                                           name="rogi_awastha" {{ old('rogi_awastha', $aturpariksha->rogi_awastha) == \App\AyurvedAturExamination::TYPE_UTTAM ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.baal') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_MADHYAM }}"
                                                           name="rogi_awastha" {{ old('rogi_awastha', $aturpariksha->rogi_awastha) == \App\AyurvedAturExamination::TYPE_MADHYAM ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.madhyam') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_JEERNYA }}"
                                                           name="rogi_awastha" {{ old('rogi_awastha', $aturpariksha->rogi_awastha) == \App\AyurvedAturExamination::TYPE_JEERNYA ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.jeernya') }}</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.rog_awastha') }}</label>
                                            <div class="form-rgt_sec">
                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_UTTAM }}"
                                                           name="rog_awastha" {{ old('rog_awastha', $aturpariksha->rog_awastha) == \App\AyurvedAturExamination::TYPE_UTTAM ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.nootan') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_MADHYAM }}"
                                                           name="rog_awastha" {{ old('rog_awastha', $aturpariksha->rog_awastha) == \App\AyurvedAturExamination::TYPE_MADHYAM ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.madhyam') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="radio" type="radio"
                                                           value="{{ \App\AyurvedAturExamination::TYPE_JEERNYA }}"
                                                           name="rog_awastha" {{ old('rog_awastha', $aturpariksha->rog_awastha) == \App\AyurvedAturExamination::TYPE_JEERNYA ? 'checked' : "" }}/>
                                                    <label>{{  trans('laralum.jeernya') }}</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="subtitle2"> {{  trans('laralum.dosh_priksha') }} </div>
                                        <div class=""><h3> {{  trans('laralum.vat_dosh') }} </h3></div>
                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.vridhi') }}</label>
                                            <div class="form-rgt_sec">

                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="vat_dosh_growth[]"
                                                           {{ $doshpariksha->isChecked('vat_dosh_growth', \App\AyurvedDoshExamination::TYPE_KASHRYA)   ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_KASHRYA }}"/>
                                                    <label>{{  trans('laralum.kashrya') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="vat_dosh_growth[]"
                                                           {{ $doshpariksha->isChecked('vat_dosh_growth', \App\AyurvedDoshExamination::TYPE_KRISHNTA)  ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_KRISHNTA }}"/>
                                                    <label>{{  trans('laralum.krishnta') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="vat_dosh_growth[]"
                                                           {{ $doshpariksha->isChecked('vat_dosh_growth', \App\AyurvedDoshExamination::TYPE_USHN_ICHHA)  ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_USHN_ICHHA }}"/>
                                                    <label>{{  trans('laralum.ushn_ichha') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="vat_dosh_growth[]"
                                                           {{ $doshpariksha->isChecked('vat_dosh_growth', \App\AyurvedDoshExamination::TYPE_KAMP)  ? 'checked' : "" }}  value="{{ \App\AyurvedDoshExamination::TYPE_KAMP }}"/>
                                                    <label>{{  trans('laralum.kamp') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="vat_dosh_growth[]"
                                                           {{ $doshpariksha->isChecked('vat_dosh_growth', \App\AyurvedDoshExamination::TYPE_SHAKRUT_GRIH)  ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_SHAKRUT_GRIH }}"/>
                                                    <label>{{  trans('laralum.shakrut_grih') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="vat_dosh_growth[]"
                                                           {{ $doshpariksha->isChecked('vat_dosh_growth', \App\AyurvedDoshExamination::TYPE_BAL_BHRANSH) ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_BAL_BHRANSH }}"/>
                                                    <label>{{  trans('laralum.bal_bhransh') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="vat_dosh_growth[]"
                                                           {{ $doshpariksha->isChecked('vat_dosh_growth', \App\AyurvedDoshExamination::TYPE_INDRIYE_BHRUNSH) ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_INDRIYE_BHRUNSH }}"/>
                                                    <label>{{  trans('laralum.indriye_bhrunsh') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="vat_dosh_growth[]"
                                                           {{ $doshpariksha->isChecked('vat_dosh_growth', \App\AyurvedDoshExamination::TYPE_NIDRA_BHRUNSH) ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_NIDRA_BHRUNSH }}"/>
                                                    <label>{{  trans('laralum.nidra_bhrunsh') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="vat_dosh_growth[]"
                                                           {{ $doshpariksha->isChecked('vat_dosh_growth', \App\AyurvedDoshExamination::TYPE_PRALAP) ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_PRALAP }}"/>
                                                    <label>{{  trans('laralum.pralap') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="vat_dosh_growth[]"
                                                           {{ $doshpariksha->isChecked('vat_dosh_growth', \App\AyurvedDoshExamination::TYPE_BHRAM) ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_BHRAM }}"/>
                                                    <label>{{  trans('laralum.bhram') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="vat_dosh_growth[]"
                                                           {{ $doshpariksha->isChecked('vat_dosh_growth', \App\AyurvedDoshExamination::TYPE_DEENTA)  ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_DEENTA }}"/>
                                                    <label>{{  trans('laralum.deenta') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.kashrya') }}</label>
                                            <div class="form-rgt_sec">

                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="vat_dosh_decay[]"
                                                           {{ $doshpariksha->isChecked('vat_dosh_decay', \App\AyurvedDoshExamination::TYPE_SAAD)  ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_SAAD }}"/>
                                                    <label>{{  trans('laralum.saad') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="vat_dosh_decay[]"
                                                           {{ $doshpariksha->isChecked('vat_dosh_decay', \App\AyurvedDoshExamination::TYPE_ALP_BHASHAN)  ? 'checked' : "" }}value="{{ \App\AyurvedDoshExamination::TYPE_ALP_BHASHAN }}"/>
                                                    <label>{{  trans('laralum.alp_bhashan') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="vat_dosh_decay[]"
                                                           {{ $doshpariksha->isChecked('vat_dosh_decay', \App\AyurvedDoshExamination::TYPE_SANGYA_MOH)  ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_SANGYA_MOH }}"/>
                                                    <label>{{  trans('laralum.sangya_moh') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="vat_dosh_decay[]"
                                                           {{ $doshpariksha->isChecked('vat_dosh_decay', \App\AyurvedDoshExamination::TYPE_KAPH_VRIDHI)  ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_KAPH_VRIDHI }}"/>
                                                    <label>{{  trans('laralum.kaph_vridhi_janya_vyadhi') }}</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class=""><h3> {{  trans('laralum.pitt_dosh') }}</h3></div>
                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.vridhi') }}</label>
                                            <div class="form-rgt_sec">
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="pitt_dosh_growth[]"
                                                           {{  $doshpariksha->isChecked('pitt_dosh_growth', \App\AyurvedDoshExamination::TYPE_PEET_VIT) ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_PEET_VIT }}"/>
                                                    <label>{{  trans('laralum.peet_vit') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="pitt_dosh_growth[]"
                                                           {{ $doshpariksha->isChecked('pitt_dosh_growth', \App\AyurvedDoshExamination::TYPE_PEET_MUTRA) ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_PEET_MUTRA }}"/>
                                                    <label>{{  trans('laralum.peet_mutra') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="pitt_dosh_growth[]"
                                                           {{ $doshpariksha->isChecked('pitt_dosh_growth', \App\AyurvedDoshExamination::TYPE_PEET_NETRA)   ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_PEET_NETRA }}"/>
                                                    <label>{{  trans('laralum.peet_netra') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="pitt_dosh_growth[]"
                                                           {{  $doshpariksha->isChecked('pitt_dosh_growth', \App\AyurvedDoshExamination::TYPE_PEET_TWAK)  ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_PEET_TWAK }}"/>
                                                    <label>{{  trans('laralum.peet_twak') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="pitt_dosh_growth[]"
                                                           {{  $doshpariksha->isChecked('pitt_dosh_growth', \App\AyurvedDoshExamination::TYPE_ATI_KSHUDA)   ? 'checked' : "" }}  value="{{ \App\AyurvedDoshExamination::TYPE_ATI_KSHUDA }}"/>
                                                    <label>{{  trans('laralum.ati_kshuda') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="pitt_dosh_growth[]"
                                                           {{  $doshpariksha->isChecked('pitt_dosh_growth', \App\AyurvedDoshExamination::TYPE_ATI_TRUSHNA)   ? 'checked' : "" }}  value="{{ \App\AyurvedDoshExamination::TYPE_ATI_TRUSHNA }}"/>
                                                    <label>{{  trans('laralum.ati_trushna') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="pitt_dosh_growth[]"
                                                           {{  $doshpariksha->isChecked('pitt_dosh_growth', \App\AyurvedDoshExamination::TYPE_DAAH)   ? 'checked' : "" }}  value="{{ \App\AyurvedDoshExamination::TYPE_DAAH }}"/>
                                                    <label>{{  trans('laralum.daah') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="pitt_dosh_growth[]"
                                                           {{ $doshpariksha->isChecked('pitt_dosh_growth', \App\AyurvedDoshExamination::TYPE_ALP_NIDRA)   ? 'checked' : "" }}  value="{{ \App\AyurvedDoshExamination::TYPE_ALP_NIDRA }}"/>
                                                    <label>{{  trans('laralum.alp_nidra') }}</label>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.kshaya') }}</label>
                                            <div class="form-rgt_sec">

                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="pitt_dosh_decay[]"
                                                           {{  $doshpariksha->isChecked('pitt_dosh_decay', \App\AyurvedDoshExamination::TYPE_MAND_AGNI)  ? 'checked' : "" }}  value="{{ \App\AyurvedDoshExamination::TYPE_MAND_AGNI }}"/>
                                                    <label>{{  trans('laralum.mand_agni') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="pitt_dosh_decay[]"
                                                           {{   $doshpariksha->isChecked('pitt_dosh_decay', \App\AyurvedDoshExamination::TYPE_SHEET_PRATITI) ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_SHEET_PRATITI }}"/>
                                                    <label>{{  trans('laralum.sheet_pratiti') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="pitt_dosh_decay[]"
                                                           {{  $doshpariksha->isChecked('pitt_dosh_decay', \App\AyurvedDoshExamination::TYPE_PRABHA_HANI) ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_PRABHA_HANI }}"/>
                                                    <label>{{  trans('laralum.prabha_hani') }}</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class=""><h3>{{  trans('laralum.kaph_dosh') }}</h3></div>
                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.vridhi') }}</label>
                                            <div class="form-rgt_sec">
                                                <div class="form-radio">

                                                    <input class="checkbox" type="checkbox" name="kaph_dosh_growth[]"
                                                           {{ $doshpariksha->isChecked('kaph_dosh_growth', \App\AyurvedDoshExamination::TYPE_AGNI_SAAD) ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_AGNI_SAAD }}"/>
                                                    <label>{{  trans('laralum.agni_saad') }}</label>
                                                </div>
                                                <div class="form-radio">

                                                    <input class="checkbox" type="checkbox" name="kaph_dosh_growth[]"
                                                           {{  $doshpariksha->isChecked('kaph_dosh_growth', \App\AyurvedDoshExamination::TYPE_PRASEK)  ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_PRASEK }}"/>
                                                    <label>{{  trans('laralum.prasek') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="kaph_dosh_growth[]"
                                                           {{ $doshpariksha->isChecked('kaph_dosh_growth', \App\AyurvedDoshExamination::TYPE_ALSYA) ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_ALSYA }}"/>
                                                    <label>{{  trans('laralum.alasya') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="kaph_dosh_growth[]"
                                                           {{ $doshpariksha->isChecked('kaph_dosh_growth', \App\AyurvedDoshExamination::TYPE_GAURAV) ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_GAURAV }}"/>
                                                    <label>{{  trans('laralum.gaurav') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="kaph_dosh_growth[]"
                                                           {{ $doshpariksha->isChecked('kaph_dosh_growth', \App\AyurvedDoshExamination::TYPE_KSHVATYA) ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_KSHVATYA }}"/>
                                                    <label>{{  trans('laralum.kshvatya') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox"
                                                           value="{{ \App\AyurvedDoshExamination::TYPE_SHAITYA }}"
                                                           type="checkbox"
                                                           name="kaph_dosh_growth[]" {{ $doshpariksha->isChecked('kaph_dosh_growth', \App\AyurvedDoshExamination::TYPE_SHAITYA) ? 'checked' : "" }} />
                                                    <label>{{  trans('laralum.shaitya') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="kaph_dosh_growth[]"
                                                           {{ $doshpariksha->isChecked('kaph_dosh_growth', \App\AyurvedDoshExamination::TYPE_ANG_SHISHITHILTA) ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_ANG_SHISHITHILTA }}"/>
                                                    <label>{{  trans('laralum.ang_shishithilta') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="kaph_dosh_growth[]"
                                                           {{  $doshpariksha->isChecked('kaph_dosh_growth', \App\AyurvedDoshExamination::TYPE_KSHWAAS) ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_KSHWAAS }}"/>
                                                    <label>{{  trans('laralum.kshwas') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="kaph_dosh_growth[]"
                                                           {{  $doshpariksha->isChecked('kaph_dosh_growth', \App\AyurvedDoshExamination::TYPE_KAAS) ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_KAAS }}"/>
                                                    <label>{{  trans('laralum.kaas') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="kaph_dosh_growth[]"
                                                           {{  $doshpariksha->isChecked('kaph_dosh_growth', \App\AyurvedDoshExamination::TYPE_ATI_NIDRA) ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_ATI_NIDRA }}"/>
                                                    <label>{{  trans('laralum.ati_nidra') }}</label>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.kshaya') }}</label>
                                            <div class="form-rgt_sec">

                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="kaph_dosh_decay[]"
                                                           {{  $doshpariksha->isChecked('kaph_dosh_decay', \App\AyurvedDoshExamination::TYPE_KAPH_DOSH_DECAY_BHRAM )  ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_KAPH_DOSH_DECAY_BHRAM }}"/>
                                                    <label>{{  trans('laralum.bhram') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="kaph_dosh_decay[]"
                                                           {{  $doshpariksha->isChecked('kaph_dosh_decay', \App\AyurvedDoshExamination::TYPE_KAPH_DOSH_DECAY_SHUNYATA )  ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_KAPH_DOSH_DECAY_SHUNYATA }}"/>
                                                    <label>{{  trans('laralum.shlesh_ashaye_shunyata') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="kaph_dosh_decay[]"
                                                           {{ $doshpariksha->isChecked('kaph_dosh_decay', \App\AyurvedDoshExamination::TYPE_HRITDRAV )  ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_HRITDRAV }}"/>
                                                    <label>{{  trans('laralum.hritdrav') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="kaph_dosh_decay[]"
                                                           {{ $doshpariksha->isChecked('kaph_dosh_decay', \App\AyurvedDoshExamination::TYPE_SANDHI_SHITHILTA )  ? 'checked' : "" }}  value="{{ \App\AyurvedDoshExamination::TYPE_SANDHI_SHITHILTA }}"/>
                                                    <label>{{  trans('laralum.sandhi_shithilta') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="kaph_dosh_decay[]"
                                                           {{ $doshpariksha->isChecked('kaph_dosh_decay', \App\AyurvedDoshExamination::TYPE_ANTRDAAH ) ? 'checked' : "" }} value="{{ \App\AyurvedDoshExamination::TYPE_ANTRDAAH }}"/>
                                                    <label>{{  trans('laralum.antardaah') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox"
                                                           value="{{ \App\AyurvedDoshExamination::TYPE_JAAGRAN }}"
                                                           name="kaph_dosh_decay[]" {{ $doshpariksha->isChecked('kaph_dosh_decay',\App\AyurvedDoshExamination::TYPE_JAAGRAN) ? 'checked' : "" }} />
                                                    <label>{{  trans('laralum.jaagran') }}</label>
                                                </div>

                                            </div>
                                        </div>


                                        <div class="subtitle2"> {{  trans('laralum.dhatu_priksha') }} </div>
                                        <div class=""><h3> {{  trans('laralum.ras') }} </h3></div>
                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.vridhi') }}</label>
                                            <div class="form-rgt_sec">
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="ras_growth[]"
                                                           {{ $dhatupariksha->isChecked('ras_growth', \App\AyurvedDhatuExamination::TYPE_AGNI_SAAD) ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_AGNI_SAAD }}"/>
                                                    <label>{{  trans('laralum.agni_saad') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="ras_growth[]"
                                                           {{  $dhatupariksha->isChecked('ras_growth', \App\AyurvedDhatuExamination::TYPE_PRASEK)  ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_PRASEK }}"/>
                                                    <label>{{  trans('laralum.prasek') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="ras_growth[]"
                                                           {{ $dhatupariksha->isChecked('ras_growth', \App\AyurvedDhatuExamination::TYPE_ALSYA) ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_ALSYA }}"/>
                                                    <label>{{  trans('laralum.alasya') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="ras_growth[]"
                                                           {{ $dhatupariksha->isChecked('ras_growth', \App\AyurvedDhatuExamination::TYPE_GAURAV) ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_GAURAV }}"/>
                                                    <label>{{  trans('laralum.gaurav') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="ras_growth[]"
                                                           {{ $dhatupariksha->isChecked('ras_growth', \App\AyurvedDhatuExamination::TYPE_KSHVATYA) ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_KSHVATYA }}"/>
                                                    <label>{{  trans('laralum.kshvatya') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox"
                                                           value="{{ \App\AyurvedDhatuExamination::TYPE_SHAITYA }}"
                                                           type="checkbox"
                                                           name="ras_growth[]" {{ $dhatupariksha->isChecked('ras_growth', \App\AyurvedDhatuExamination::TYPE_SHAITYA) ? 'checked' : "" }} />
                                                    <label>{{  trans('laralum.shaitya') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="ras_growth[]"
                                                           {{ $dhatupariksha->isChecked('ras_growth', \App\AyurvedDhatuExamination::TYPE_ANG_SHISHITHILTA) ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_ANG_SHISHITHILTA }}"/>
                                                    <label>{{  trans('laralum.ang_shishithilta') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="ras_growth[]"
                                                           {{  $dhatupariksha->isChecked('ras_growth', \App\AyurvedDhatuExamination::TYPE_KSHWAAS) ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_KSHWAAS }}"/>
                                                    <label>{{  trans('laralum.kshwas') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="ras_growth[]"
                                                           {{  $dhatupariksha->isChecked('ras_growth', \App\AyurvedDhatuExamination::TYPE_KAAS) ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_KAAS }}"/>
                                                    <label>{{  trans('laralum.kaas') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="ras_growth[]"
                                                           {{  $dhatupariksha->isChecked('ras_growth', \App\AyurvedDhatuExamination::TYPE_ATI_NIDRA) ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_ATI_NIDRA }}"/>
                                                    <label>{{  trans('laralum.ati_nidra') }}</label>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.kshaya') }}</label>
                                            <div class="form-rgt_sec">

                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="ras_decay[]"
                                                           {{ $dhatupariksha->isChecked('ras_decay', \App\AyurvedDhatuExamination::TYPE_ROKSH)  ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_ROKSH }}"/>
                                                    <label>{{  trans('laralum.roksh') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="ras_decay[]"
                                                           {{ $dhatupariksha->isChecked('ras_decay', \App\AyurvedDhatuExamination::TYPE_SARAM)  ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_SARAM }}"/>
                                                    <label>{{  trans('laralum.saram') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="ras_decay[]"
                                                           {{ $dhatupariksha->isChecked('ras_decay', \App\AyurvedDhatuExamination::TYPE_SOSH)  ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_SOSH }}"/>
                                                    <label>{{  trans('laralum.sosh') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="ras_decay[]"
                                                           {{ $dhatupariksha->isChecked('ras_decay', \App\AyurvedDhatuExamination::TYPE_GLANI)  ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_GLANI }}"/>
                                                    <label>{{  trans('laralum.glani') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="ras_decay[]"
                                                           {{ $dhatupariksha->isChecked('ras_decay', \App\AyurvedDhatuExamination::TYPE_SHABD_ASAHISHUNTA)  ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_SHABD_ASAHISHUNTA }}"/>
                                                    <label>{{  trans('laralum.shabd_asahishunta') }}</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class=""><h3> {{  trans('laralum.rakt') }}</h3></div>
                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.vridhi') }}</label>
                                            <div class="form-rgt_sec">
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="rakht_growth[]"
                                                           {{  $dhatupariksha->isChecked('rakht_growth', \App\AyurvedDhatuExamination::TYPE_VISARP) ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_VISARP }}"/>
                                                    <label>{{  trans('laralum.visarp') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="rakht_growth[]"
                                                           {{ $dhatupariksha->isChecked('rakht_growth', \App\AyurvedDhatuExamination::TYPE_PLEEH) ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_PLEEH }}"/>
                                                    <label>{{  trans('laralum.pleeh') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="rakht_growth[]"
                                                           {{ $dhatupariksha->isChecked('rakht_growth', \App\AyurvedDhatuExamination::TYPE_VIDHRATHI)   ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_VIDHRATHI }}"/>
                                                    <label>{{  trans('laralum.vidhrathi') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="rakht_growth[]"
                                                           {{ $dhatupariksha->isChecked('rakht_growth', \App\AyurvedDhatuExamination::TYPE_KUSTH)   ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_KUSTH }}"/>
                                                    <label>{{  trans('laralum.kusth') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="rakht_growth[]"
                                                           {{  $dhatupariksha->isChecked('rakht_growth', \App\AyurvedDhatuExamination::TYPE_VAATRAKHT)  ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_VAATRAKHT }}"/>
                                                    <label>{{  trans('laralum.vaatrakht') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="rakht_growth[]"
                                                           {{  $dhatupariksha->isChecked('rakht_growth', \App\AyurvedDhatuExamination::TYPE_RAKHT_PITT)   ? 'checked' : "" }}  value="{{ \App\AyurvedDhatuExamination::TYPE_RAKHT_PITT }}"/>
                                                    <label>{{  trans('laralum.rakht_pitt') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="rakht_growth[]"
                                                           {{  $dhatupariksha->isChecked('rakht_growth', \App\AyurvedDhatuExamination::TYPE_GULM)   ? 'checked' : "" }}  value="{{ \App\AyurvedDhatuExamination::TYPE_GULM }}"/>
                                                    <label>{{  trans('laralum.gulm') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="rakht_growth[]"
                                                           {{  $dhatupariksha->isChecked('rakht_growth', \App\AyurvedDhatuExamination::TYPE_KAAMLA)   ? 'checked' : "" }}  value="{{ \App\AyurvedDhatuExamination::TYPE_KAAMLA }}"/>
                                                    <label>{{  trans('laralum.kaamla') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="rakht_growth[]"
                                                           {{ $dhatupariksha->isChecked('rakht_growth', \App\AyurvedDhatuExamination::TYPE_VYANG)   ? 'checked' : "" }}  value="{{ \App\AyurvedDhatuExamination::TYPE_VYANG }}"/>
                                                    <label>{{  trans('laralum.vyang') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="rakht_growth[]"
                                                           {{ $dhatupariksha->isChecked('rakht_growth', \App\AyurvedDhatuExamination::TYPE_AGNI_NAASH)   ? 'checked' : "" }}  value="{{ \App\AyurvedDhatuExamination::TYPE_AGNI_NAASH }}"/>
                                                    <label>{{  trans('laralum.agni_naash') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="rakht_growth[]"
                                                           {{ $dhatupariksha->isChecked('rakht_growth', \App\AyurvedDhatuExamination::TYPE_SAMOOH)   ? 'checked' : "" }}  value="{{ \App\AyurvedDhatuExamination::TYPE_SAMOOH }}"/>
                                                    <label>{{  trans('laralum.samooh') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="rakht_growth[]"
                                                           {{ $dhatupariksha->isChecked('rakht_growth', \App\AyurvedDhatuExamination::TYPE_RAKHT_TWAK)   ? 'checked' : "" }}  value="{{ \App\AyurvedDhatuExamination::TYPE_RAKHT_TWAK }}"/>
                                                    <label>{{  trans('laralum.rakht_twak') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="rakht_growth[]"
                                                           {{ $dhatupariksha->isChecked('rakht_growth', \App\AyurvedDhatuExamination::TYPE_RAKHT_NETRA)   ? 'checked' : "" }}  value="{{ \App\AyurvedDhatuExamination::TYPE_RAKHT_NETRA }}"/>
                                                    <label>{{  trans('laralum.rakht_netra') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="rakht_growth[]"
                                                           {{ $dhatupariksha->isChecked('rakht_growth', \App\AyurvedDhatuExamination::TYPE_RAKHT_MOTRA)   ? 'checked' : "" }}  value="{{ \App\AyurvedDhatuExamination::TYPE_RAKHT_MOTRA }}"/>
                                                    <label>{{  trans('laralum.rakht_motra') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.kshaya') }}</label>
                                            <div class="form-rgt_sec">

                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="rakht_decay[]"
                                                           {{  $dhatupariksha->isChecked('rakht_decay', \App\AyurvedDhatuExamination::TYPE_AML_PRATI)  ? 'checked' : "" }}  value="{{ \App\AyurvedDhatuExamination::TYPE_AML_PRATI }}"/>
                                                    <label>{{  trans('laralum.aml_prati') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="rakht_decay[]"
                                                           {{   $dhatupariksha->isChecked('rakht_decay', \App\AyurvedDhatuExamination::TYPE_SHISHIR_PRATI) ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_SHISHIR_PRATI }}"/>
                                                    <label>{{  trans('laralum.shishir_prati') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="rakht_decay[]"
                                                           {{  $dhatupariksha->isChecked('rakht_decay', \App\AyurvedDhatuExamination::TYPE_SHEERA_SHETHILP) ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_SHEERA_SHETHILP }}"/>
                                                    <label>{{  trans('laralum.sheera_shethilp') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="rakht_decay[]"
                                                           {{  $dhatupariksha->isChecked('rakht_decay', \App\AyurvedDhatuExamination::TYPE_ROOKHTHA) ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_ROOKHTHA }}"/>
                                                    <label>{{  trans('laralum.rookhtha') }}</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class=""><h3>{{  trans('laralum.maans') }}</h3></div>
                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.vridhi') }}</label>
                                            <div class="form-rgt_sec">
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="maans_growth[]"
                                                           {{ $dhatupariksha->isChecked('maans_growth', \App\AyurvedDhatuExamination::TYPE_GAND) ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_GAND }}"/>
                                                    <label>{{  trans('laralum.gand') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="maans_growth[]"
                                                           {{  $dhatupariksha->isChecked('maans_growth', \App\AyurvedDhatuExamination::TYPE_ABURD)  ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_ABURD }}"/>
                                                    <label>{{  trans('laralum.aburd') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="maans_growth[]"
                                                           {{ $dhatupariksha->isChecked('maans_growth', \App\AyurvedDhatuExamination::TYPE_GRATHI) ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_GRATHI }}"/>
                                                    <label>{{  trans('laralum.grathi') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="maans_growth[]"
                                                           {{ $dhatupariksha->isChecked('maans_growth', \App\AyurvedDhatuExamination::TYPE_UTHER_VRIDHI) ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_UTHER_VRIDHI }}"/>
                                                    <label>{{  trans('laralum.uther_vridhi') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="maans_growth[]"
                                                           {{ $dhatupariksha->isChecked('maans_growth', \App\AyurvedDhatuExamination::TYPE_ADI_MAANS) ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_ADI_MAANS }}"/>
                                                    <label>{{  trans('laralum.adi_maans') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox"
                                                           value="{{ \App\AyurvedDhatuExamination::TYPE_MED_VRIDHI }}"
                                                           type="checkbox"
                                                           name="maans_growth[]" {{ $dhatupariksha->isChecked('maans_growth', \App\AyurvedDhatuExamination::TYPE_MED_VRIDHI) ? 'checked' : "" }} />
                                                    <label>{{  trans('laralum.med_vridhi') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="maans_growth[]"
                                                           {{ $dhatupariksha->isChecked('maans_growth', \App\AyurvedDhatuExamination::TYPE_SARAM_MAANS) ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_SARAM_MAANS }}"/>
                                                            <label>{{  trans('laralum.saram') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="maans_growth[]"
                                                           {{  $dhatupariksha->isChecked('maans_growth', \App\AyurvedDhatuExamination::TYPE_GAL_GAND) ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_GAL_GAND }}"/>
                                                    <label>{{  trans('laralum.gal_gand') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.kshaya') }}</label>
                                            <div class="form-rgt_sec">

                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="maans_decay[]"
                                                           {{  $dhatupariksha->isChecked('maans_decay', \App\AyurvedDhatuExamination::TYPE_GLANI_MAANS )  ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_GLANI_MAANS }}"/>
                                                    <label>{{  trans('laralum.glani') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="maans_decay[]"
                                                           {{  $dhatupariksha->isChecked('maans_decay', \App\AyurvedDhatuExamination::TYPE_GAND_SHUSKTA )  ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_GAND_SHUSKTA }}"/>
                                                    <label>{{  trans('laralum.gand_shuskta') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="maans_decay[]"
                                                           {{ $dhatupariksha->isChecked('maans_decay', \App\AyurvedDhatuExamination::TYPE_SIFK_SHUSKTA )  ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_SIFK_SHUSKTA }}"/>
                                                    <label>{{  trans('laralum.sifk_shuskta') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="maans_decay[]"
                                                           {{ $dhatupariksha->isChecked('maans_decay', \App\AyurvedDhatuExamination::TYPE_SANDHI_VEDNA )  ? 'checked' : "" }}  value="{{ \App\AyurvedDhatuExamination::TYPE_SANDHI_VEDNA }}"/>
                                                    <label>{{  trans('laralum.sandhi_vedna') }}</label>
                                                </div>
                                            </div>
                                        </div>


                                        <div class=""><h3>{{  trans('laralum.med') }}</h3></div>
                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.vridhi') }}</label>
                                            <div class="form-rgt_sec">
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="med_growth[]"
                                                           {{ $dhatupariksha->isChecked('med_growth', \App\AyurvedDhatuExamination::TYPE_ALP_CHESHTHA_SHWAS) ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_ALP_CHESHTHA_SHWAS }}"/>
                                                    <label>{{  trans('laralum.alp_cheshtha_shwas') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="med_growth[]"
                                                           {{  $dhatupariksha->isChecked('med_growth', \App\AyurvedDhatuExamination::TYPE_SIFAK_LAMBAN)  ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_SIFAK_LAMBAN }}"/>
                                                    <label>{{  trans('laralum.sifak_lamban') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="med_growth[]"
                                                           {{ $dhatupariksha->isChecked('med_growth', \App\AyurvedDhatuExamination::TYPE_STAN_LAMBAN) ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_STAN_LAMBAN }}"/>
                                                    <label>{{  trans('laralum.stan_lamban') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="med_growth[]"
                                                           {{ $dhatupariksha->isChecked('med_growth', \App\AyurvedDhatuExamination::TYPE_UTHER_LAMBAN) ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_UTHER_LAMBAN }}"/>
                                                    <label>{{  trans('laralum.uther_lamban') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.kshaya') }}</label>
                                            <div class="form-rgt_sec">

                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="med_decay[]"
                                                           {{  $dhatupariksha->isChecked('med_decay', \App\AyurvedDhatuExamination::TYPE_GLANI_MED )  ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_GLANI_MED }}"/>
                                                    <label>{{  trans('laralum.glani') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="med_decay[]"
                                                           {{  $dhatupariksha->isChecked('med_decay', \App\AyurvedDhatuExamination::TYPE_GAND_SHUSKTA_MED )  ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_GAND_SHUSKTA_MED }}"/>
                                                    <label>{{  trans('laralum.gand_shuskta') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="med_decay[]"
                                                           {{ $dhatupariksha->isChecked('med_decay', \App\AyurvedDhatuExamination::TYPE_SIFK_SHUSKTA_MED )  ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_SIFK_SHUSKTA_MED }}"/>
                                                    <label>{{  trans('laralum.sifk_shuskta') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="med_decay[]"
                                                           {{ $dhatupariksha->isChecked('med_decay', \App\AyurvedDhatuExamination::TYPE_SANDHI_VEDNA_MED )  ? 'checked' : "" }}  value="{{ \App\AyurvedDhatuExamination::TYPE_SANDHI_VEDNA_MED }}"/>
                                                    <label>{{  trans('laralum.sandhi_vedna') }}</label>
                                                </div>
                                            </div>
                                        </div>


                                        <div class=""><h3>{{  trans('laralum.asthi') }}</h3></div>
                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.vridhi') }}</label>
                                            <div class="form-rgt_sec">
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="asthi_growth[]"
                                                           {{ $dhatupariksha->isChecked('asthi_growth', \App\AyurvedDhatuExamination::TYPE_ADHI_ASTHI) ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_ADHI_ASTHI }}"/>
                                                    <label>{{  trans('laralum.adhi_asthi') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="asthi_growth[]"
                                                           {{  $dhatupariksha->isChecked('asthi_growth', \App\AyurvedDhatuExamination::TYPE_ADHI_DANT)  ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_ADHI_DANT }}"/>
                                                    <label>{{  trans('laralum.adhi_dant') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.kshaya') }}</label>
                                            <div class="form-rgt_sec">

                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="asthi_decay[]"
                                                           {{  $dhatupariksha->isChecked('asthi_decay', \App\AyurvedDhatuExamination::TYPE_ASTHI_TOD )  ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_ASTHI_TOD }}"/>
                                                    <label>{{  trans('laralum.asthi_tod') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="asthi_decay[]"
                                                           {{  $dhatupariksha->isChecked('asthi_decay', \App\AyurvedDhatuExamination::TYPE_DANT_SADAN )  ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_DANT_SADAN }}"/>
                                                    <label>{{  trans('laralum.dant_sadan') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="asthi_decay[]"
                                                           {{ $dhatupariksha->isChecked('asthi_decay', \App\AyurvedDhatuExamination::TYPE_KASH_SADAN )  ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_KASH_SADAN }}"/>
                                                    <label>{{  trans('laralum.kash_sadan') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="asthi_decay[]"
                                                           {{ $dhatupariksha->isChecked('asthi_decay', \App\AyurvedDhatuExamination::TYPE_NAKH_SADAN )  ? 'checked' : "" }}  value="{{ \App\AyurvedDhatuExamination::TYPE_NAKH_SADAN }}"/>
                                                    <label>{{  trans('laralum.nakh_sadan') }}</label>
                                                </div>
                                            </div>
                                        </div>


                                        <div class=""><h3>{{  trans('laralum.majja') }}</h3></div>
                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.vridhi') }}</label>
                                            <div class="form-rgt_sec">
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="majja_growth[]"
                                                           {{ $dhatupariksha->isChecked('majja_growth', \App\AyurvedDhatuExamination::TYPE_NETRA_GAURAV) ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_NETRA_GAURAV }}"/>
                                                    <label>{{  trans('laralum.netra_gaurav') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="majja_growth[]"
                                                           {{  $dhatupariksha->isChecked('majja_growth', \App\AyurvedDhatuExamination::TYPE_ANG_GAURAV)  ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_ANG_GAURAV }}"/>
                                                    <label>{{  trans('laralum.ang_gaurav') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="majja_growth[]"
                                                           {{  $dhatupariksha->isChecked('majja_growth', \App\AyurvedDhatuExamination::TYPE_PURV_SATHULTA)  ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_PURV_SATHULTA }}"/>
                                                    <label>{{  trans('laralum.purv_sathulta') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="majja_growth[]"
                                                           {{  $dhatupariksha->isChecked('majja_growth', \App\AyurvedDhatuExamination::TYPE_ARUNSHI)  ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_ARUNSHI }}"/>
                                                    <label>{{  trans('laralum.arunshi') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.kshaya') }}</label>
                                            <div class="form-rgt_sec">

                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="majja_decay[]"
                                                           {{  $dhatupariksha->isChecked('majja_decay', \App\AyurvedDhatuExamination::TYPE_ASTHI_SAUSHIRYA )  ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_ASTHI_SAUSHIRYA }}"/>
                                                    <label>{{  trans('laralum.asthi_saushirya') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="majja_decay[]"
                                                           {{  $dhatupariksha->isChecked('majja_decay', \App\AyurvedDhatuExamination::TYPE_BHERAM )  ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_BHERAM }}"/>
                                                    <label>{{  trans('laralum.bheram') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="majja_decay[]"
                                                           {{ $dhatupariksha->isChecked('majja_decay', \App\AyurvedDhatuExamination::TYPE_TIMIR_DERSHAN )  ? 'checked' : "" }}  value="{{ \App\AyurvedDhatuExamination::TYPE_TIMIR_DERSHAN }}"/>
                                                    <label>{{  trans('laralum.timir_dershan') }}</label>
                                                </div>
                                            </div>
                                        </div>


                                        <div class=""><h3>{{  trans('laralum.shukra') }}</h3></div>
                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.vridhi') }}</label>
                                            <div class="form-rgt_sec">
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="shukra_growth[]"
                                                           {{ $dhatupariksha->isChecked('shukra_growth', \App\AyurvedDhatuExamination::TYPE_STRI_KAMTA_VRIDHI) ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_STRI_KAMTA_VRIDHI }}"/>
                                                    <label>{{  trans('laralum.stri_kamta_vridhi') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="shukra_growth[]"
                                                           {{  $dhatupariksha->isChecked('shukra_growth', \App\AyurvedDhatuExamination::TYPE_SHUKRA_VRIDHI)  ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_SHUKRA_VRIDHI }}"/>
                                                    <label>{{  trans('laralum.shukra_vridhi') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="shukra_growth[]"
                                                           {{  $dhatupariksha->isChecked('shukra_growth', \App\AyurvedDhatuExamination::TYPE_SHUKRA_ASHMARI)  ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_SHUKRA_ASHMARI }}"/>
                                                    <label>{{  trans('laralum.shukra_ashmari') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label class="label"> {{  trans('laralum.kshaya') }}</label>
                                            <div class="form-rgt_sec">
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="shukra_decay[]"
                                                           {{  $dhatupariksha->isChecked('shukra_decay', \App\AyurvedDhatuExamination::TYPE_VRUSHN_TOD )  ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_VRUSHN_TOD }}"/>
                                                    <label>{{  trans('laralum.vrushn_tod') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="shukra_decay[]"
                                                           {{  $dhatupariksha->isChecked('shukra_decay', \App\AyurvedDhatuExamination::TYPE_MEDAR_TOD )  ? 'checked' : "" }} value="{{ \App\AyurvedDhatuExamination::TYPE_MEDAR_TOD }}"/>
                                                    <label>{{  trans('laralum.medar_tod') }}</label>
                                                </div>
                                                <div class="form-radio">
                                                    <input class="checkbox" type="checkbox" name="shukra_decay[]"
                                                           {{ $dhatupariksha->isChecked('shukra_decay', \App\AyurvedDhatuExamination::TYPE_GHUMTA )  ? 'checked' : "" }}  value="{{ \App\AyurvedDhatuExamination::TYPE_GHUMTA }}"/>
                                                    <label>{{  trans('laralum.ghumta') }}</label>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-row">
                                            <label class="label"><h3> {{  trans('laralum.rog_nidan') }}</h3></label>
                                            <div class="form-rgt_sec">
                                                <textarea class="form_control" name="rog_nidan" placeholder=" {{  trans('laralum.rog_nidan') }}">{!! old('rog_nidan', $dhatupariksha->rog_nidan) !!}</textarea>
                                            </div>
                                        </div>


                                        <div class="form-row">
                                            <label class="label"><h3> {{  trans('laralum.vydhi_ka_naam') }}</h3></label>
                                            <div class="form-rgt_sec">
                                                <textarea class="form_control" name="vydhi_ka_naam" placeholder=" {{  trans('laralum.vydhi_ka_naam') }}">{!! old('vydhi_ka_naam', $dhatupariksha->vyadhi_ka_naam) !!}</textarea>
                                            </div>
                                        </div>

                                    </div>
                                </section>
                                @if($booking->isEditable())
                                    <button class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                            </div>
                        </form>
                    @endif
                    @else
                        @include('laralum.token.ayurved_vital_data_summary')
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection
