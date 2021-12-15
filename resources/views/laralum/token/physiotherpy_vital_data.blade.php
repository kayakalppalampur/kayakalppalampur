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

    <style>
        .aurv_section h3.section_title {

            position: inherit !important;
        }


    </style>
    <br><br>
    <div class="ui one column doubling stackable grid container">
        <div class="column admin_basic_detail1 admin_wrapper">
            <div class="ui very padded segment  main_wrapper">
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
                            <li><a class=" section"
                                   href="{{ route('Laralum::recommend-exercise.assign', ['patient_id' => $booking->id]) }}">Attachments
                                </a></li>
                            <li>
                        @endif
--}}

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
            <div class="ui very padded segment">


                <div class="page_title">
                    <h2 class="pull-left">Physiotherapy Examinations:
                        <div class="clearfix" style="font-size:13px;font-weight:normal;clear:both">
                            <span>( Only for Physiotherapist )</span></div>
                    </h2>
                    {{--@if((!empty($dept_model) && \Auth::user()->department->department_id==$dept_model->id))--}}
                    <div class="pull-right b_btn">
                        <a style="display: inline;"
                           class="btn btn-primary ui button {{ \App\Http\Controllers\Laralum\Laralum::settings()->button_color }}"
                           href="{{ route('Laralum::patient.vital_data', ['token_id' => $booking->id]) }}">Back</a>
                    </div>
                    {{--@endif--}}
                </div>



                @if((!empty($dept_model) && \Auth::user()->department->department_id==$dept_model->id))
                @if($booking->isEditable())


                    <div class="vital-data-wrap">
                        <div class="vital-head">
                            <h2>{{ $booking->getProfile('first_name').' '. $booking->getProfile('last_name') }}</h2>
                        </div>
                    </div>


                    <section class="aurv_section physiotherpy_vital">
                        <h3 class="section_title"> Neurological System </h3>

                        <div class="form_main">
                            <form method="post"
                                  action="{{ route('Laralum::patient.physiotherpy_vital_data_store',$booking->id) }}">


                                {!! csrf_field() !!}
                                <input type="hidden" value="{{ $patient->id }}" name="patient_id">
                                <input type="hidden" value="{{ $booking->id }}" name="booking_id">
                                <input type="hidden" name="save_section" value="neurological_examination"/>

                                <div class="form-row">
                                    <label class="label">Headache
                                    </label>
                                    <div class="form-rgt_sec">


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
                                        <div class="pymnt_opt_row block_inline">
                                            <div class="pymt_inn">
                                                <input type="text" name="headache_doctor" class="form-control"
                                                       value="{{ $neuro->headache_doctor }}">

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <label class="label">Problem with vision/ hearing etc.
                                    </label>
                                    <div class="form-rgt_sec">


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

                                        <div class="pymnt_opt_row block_inline">
                                            <div class="pymt_inn">
                                                <input type="text" name="vision_hearing_doctor" class="form-control"
                                                       value="{{ $neuro->vision_hearing_doctor }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-row">
                                    <label class="label">Pain
                                    </label>
                                    <div class="form-rgt_sec">


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
                                        <div class="pymnt_opt_row block_inline">
                                            <div class="pymt_inn">
                                                <input type="text" name="pain_doctor" class="form-control"
                                                       value="{{ $neuro->pain_doctor }}">
                                            </div>
                                        </div>
                                    </div>


                                </div>


                                <div class="form-row">
                                    <label class="label">Numbness/ Pins& Needles
                                    </label>
                                    <div class="form-rgt_sec">


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

                                        <div class="pymnt_opt_row block_inline">
                                            <div class="pymt_inn">
                                                <input type="text" name="numbness_doctor" class="form-control"
                                                       value="{{ $neuro->numbness_doctor }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-row">
                                    <label class="label">Weakness or balance problem
                                    </label>
                                    <div class="form-rgt_sec">


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


                                        <div class="pymnt_opt_row block_inline">
                                            <div class="pymt_inn">
                                                <input type="text" name="weakness_doctor" class="form-control"
                                                       value="{{ $neuro->weakness_doctor }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-row">
                                    <label class="label">Abnormal/ involuntary movements
                                    </label>
                                    <div class="form-rgt_sec">


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


                                        <div class="pymnt_opt_row block_inline">
                                            <div class="pymt_inn">
                                                <input type="text" name="abnormal_movements_doctor" class="form-control"
                                                       value="{{ $neuro->abnormal_movements_doctor }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-row">
                                    <label class="label">Fits/ faints
                                    </label>
                                    <div class="form-rgt_sec">


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


                                        <div class="pymnt_opt_row block_inline">
                                            <div class="pymt_inn">
                                                <input type="text" name="fits_doctor" class="form-control"
                                                       value="{{ $neuro->fits_doctor }}">
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <button type="submit"
                                        class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                            </form>
                        </div>


                        <h3 class="section_title"> On examination</h3>

                        <div class="form_main">
                            <form method="post"
                                  action="{{ route('Laralum::patient.physiotherpy_vital_data_store',$booking->id) }}">


                                {!! csrf_field() !!}
                                <input type="hidden" value="{{ $patient->id }}" name="patient_id">
                                <input type="hidden" value="{{ $booking->id }}" name="booking_id">
                                <input type="hidden" name="save_section" value="systematic_examination"/>

                                <div class="vital-inner">
                                    {{--    <div class="vital-col-1">
                                            <label>On examination</label>
                                        </div>
                                        <div class="vital-col-3">
                                            <input type="text" name="neuro_doctor_details" class="form-control"
                                                   value="{{ $neuro->doctor_details }}">
                                        </div>
                                    </div>--}}


                                    <div class="form-row">
                                        <label class="label">Body Built</label>
                                        <div class="form-rgt_sec">


                                            @php

                                                $lists = $systemic->getBodyBuild();

                                            @endphp




                                            @foreach($lists as $key=>$list)
                                                @php $lists2 = explode(",",$systemic->body_built); @endphp
                                                <div class="input_sm">

                                                    <input class="form-control" name="body_built[]"
                                                           value="{{ $key  }}"
                                                           type="checkbox" {{ in_array($key , $lists2)?'checked':''}} />{{$list}}

                                                </div>

                                            @endforeach


                                        </div>

                                    </div>


                                    <div class="form-row">
                                        <label class="label">Gait</label>
                                        <div class="form-rgt_sec">


                                            @php

                                                $lists=$systemic->getGait();

                                            @endphp


                                            @foreach($lists as $key=>$list)
                                            @php $lists3 = explode(",",$systemic->gait); @endphp
                                                <div class="input_sm">

                                                <input class="form-control" name="gait[]"
                                                           value="{{ $key  }}"
                                                           type="checkbox" {{ in_array($key , $lists3)?'checked':''}} />{{$list}}

                                                </div>

                                            @endforeach


                                        </div>

                                    </div>
                                    <div class="form-row">
                                        <label class="label">Posture</label>
                                        <div class="form-rgt_sec">
                                            @php

                                                $lists=$systemic->getGait();

                                            @endphp


                                            @foreach($lists as $key=>$list)
                                            @php $lists4 = explode(",",$systemic->posture); @endphp
                                                <div class="input_sm">
                                                    <input class="form-control" name="posture[]"
                                                           value="{{ $key  }}"
                                                           type="checkbox" {{ in_array($key , $lists4)?'checked':''}} />{{$list}}

                                                </div>

                                            @endforeach


                                            <div class="col-md-8">
                                                <input class="form_control add-width" name="posture_comment"
                                                       value="{{ old('posture_comment', $systemic->posture_comment) }}"
                                                       type="text" placeholder="{{ trans('laralum.comment') }}"/>
                                            </div>


                                        </div>

                                    </div>

                                    <div class="form-row">
                                        <label class="label">Deformity</label>
                                        <div class="form-rgt_sec">


                                            @php

                                                $lists=$systemic->getState();

                                            @endphp
                                            <div class="input_sm">

                                                <select class="form-control" name="deformity">


                                                    @foreach($lists as $key=>$list)

                                                        <option value="{{$key}}" {{ ($systemic->deformity==$key )?'selected':''}}>{{$list}}</option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <div class="col-md-8">
                                                <input class="form_control add-width" name="deformity_comment"
                                                       value="{{ old('deformity_comment', $systemic->deformity_comment) }}"
                                                       type="text" placeholder="{{ trans('laralum.comment') }}"/>
                                            </div>


                                        </div>
                                    </div>


                                    <div class="form-row">
                                        <label class="label">Tenderness</label>
                                        <div class="form-rgt_sec">


                                            @php

                                                $lists=$systemic->getState();

                                            @endphp
                                            <div class="input_sm">

                                                <select class="form-control" name="tenderness">


                                                    @foreach($lists as $key=>$list)

                                                        <option value="{{$key}}" {{ ($systemic->tenderness==$key )?'selected':''}}>{{$list}}</option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <div class="col-md-8">
                                                <input class="form_control add-width" name="tenderness_comment"
                                                       value="{{ old('tenderness_comment', $systemic->tenderness_comment) }}"
                                                       type="text" placeholder="{{ trans('laralum.comment') }}"/>
                                            </div>


                                        </div>
                                    </div>


                                    <div class="form-row">
                                        <label class="label">Warmth</label>
                                        <div class="form-rgt_sec">


                                            @php

                                                $lists=$systemic->getState();

                                            @endphp
                                            <div class="input_sm">

                                                <select class="form-control" name="warmth">


                                                    @foreach($lists as $key=>$list)

                                                        <option value="{{$key}}" {{ ($systemic->warmth==$key )?'selected':''}}>{{$list}}</option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <div class="col-md-8">
                                                <input class="form_control add-width" name="warmth_comment"
                                                       value="{{ old('warmth_comment', $systemic->warmth_comment) }}"
                                                       type="text" placeholder="{{ trans('laralum.comment') }}"/>
                                            </div>


                                        </div>


                                        <div class="form-row">
                                            <label class="label">Swelling</label>
                                            <div class="form-rgt_sec">


                                                @php

                                                    $lists=$systemic->getState();

                                                @endphp
                                                <div class="input_sm">

                                                    <select class="form-control" name="swelling">


                                                        @foreach($lists as $key=>$list)

                                                            <option value="{{$key}}" {{ ($systemic->swelling==$key )?'selected':''}} >{{$list}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>


                                                <div class="col-md-8">
                                                    <input class="form_control add-width" name="swelling_comment"
                                                           value="{{ old('swelling_comment', $systemic->swelling_comment) }}"
                                                           type="text" placeholder="{{ trans('laralum.comment') }}"/>
                                                </div>


                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-row">
                                        <label class="label">Crepitus</label>
                                        <div class="form-rgt_sec">


                                            @php

                                                $lists=$systemic->getState();

                                            @endphp
                                            <div class="input_sm">

                                                <select class="form-control" name="creiptus">


                                                    @foreach($lists as $key=>$list)

                                                        <option value="{{$key}}" {{ ($systemic->creiptus==$key )?'selected':''}}
                                                        >{{$list}}</option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <div class="col-md-8">
                                                <input class="form_control add-width" name="creiptus_comment"
                                                       value="{{ old('creiptus_comment', $systemic->creiptus_comment) }}"
                                                       type="text" placeholder="{{ trans('laralum.comment') }}"/>
                                            </div>


                                        </div>
                                    </div>


                                    <div class="form-row">
                                        <label class="label">Muscle Spasm</label>
                                        <div class="form-rgt_sec">


                                            @php

                                                $lists=$systemic->getState();

                                            @endphp
                                            <div class="input_sm">

                                                <select class="form-control" name="muscle_spasm">


                                                    @foreach($lists as $key=>$list)

                                                        <option value="{{$key}}" {{ ($systemic->muscle_spasm==$key )?'selected':''}} >{{$list}}</option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <div class="col-md-8">
                                                <input class="form_control add-width" name="muscle_spasm_comment"
                                                       value="{{ old('muscle_spasm_comment', $systemic->muscle_spasm_comment) }}"
                                                       type="text" placeholder="{{ trans('laralum.comment') }}"/>
                                            </div>


                                        </div>
                                    </div>


                                    <div class="form-row">
                                        <label class="label">Muscle Tightness</label>
                                        <div class="form-rgt_sec">


                                            @php

                                                $lists=$systemic->getState();

                                            @endphp
                                            <div class="input_sm">

                                                <select class="form-control" name="muscle_tightness">


                                                    @foreach($lists as $key=>$list)

                                                        <option value="{{$key}}" {{ ($systemic->muscle_tightness==$key )?'selected':''}} >{{$list}}</option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <div class="col-md-8">
                                                <input class="form_control add-width" name="muscle_tightness_comment"
                                                       value="{{ old('muscle_tightness_comment', $systemic->muscle_tightness_comment) }}"
                                                       type="text" placeholder="{{ trans('laralum.comment') }}"/>
                                            </div>


                                        </div>
                                    </div>


                                    <div class="form-row">
                                        <label class="label">Edema</label>
                                        <div class="form-rgt_sec">


                                            @php

                                                $lists=$systemic->getEdema();

                                            @endphp


                                       


                                            @foreach($lists as $key=>$list)
                                            @php $listsss = explode(",",$systemic->edema); @endphp
                                                <div class="input_sm">

                                                    <input class="form-control" name="edema[]"
                                                           value="{{ $key  }}"
                                                           type="checkbox" {{ in_array($key , $listsss)?'checked':'' }} />{{$list}}

                                                </div>

                                            @endforeach


                                        </div>

                                    </div>


                                </div>
                                <button type="submit"
                                        class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                            </form>
                        </div>

                    </section>


                    <section class="aurv_section physiotherpy_vital">
                        <h3 class="section_title"> Sensory Examination </h3>
                        <div class="form_main">
                            <form method="post"
                                  action="{{ route('Laralum::patient.physiotherpy_vital_data_store',$booking->id) }}">


                                {!! csrf_field() !!}
                                <input type="hidden" value="{{ $patient->id }}" name="patient_id">
                                <input type="hidden" value="{{ $booking->id }}" name="booking_id">
                                <input type="hidden" name="save_section" value="sensory_examination"/>

                                <div class="form-row">
                                    <label class="label"> Superficial Sensation </label>
                                    <div class="form-rgt_sec">


                                        @php

                                            $lists=$sensory->getSensationType();

                                        @endphp


                                    


                                        @foreach($lists as $key=>$list)
                                         @php $lists5 = explode(",",$sensory->superficial_sensation); @endphp
                                            <div class="input_sm">

                                                <input class="form-control" name="superficial_sensation[]"
                                                       value="{{$key}}"
                                                       type="checkbox" {{ in_array($key , $lists5)?'checked':'' }} />{{$list}}

                                            </div>

                                        @endforeach


                                        <div class="col-md-8">
                                            <input class="form_control add-width" name="superficial_sensation_comment"
                                                   value="{{ old('superficial_sensation_comment', $sensory->superficial_sensation_comment) }}"
                                                   type="text" placeholder="{{ trans('laralum.comment') }}"/>
                                        </div>


                                    </div>

                                    <div class="form-row">
                                        <label class="label">Deep Sensation</label>
                                        <div class="form-rgt_sec">


                                            @php

                                                $lists=$sensory->getSensationType();

                                            @endphp


                                            @foreach($lists as $key=>$list)
                                             @php $lists6 = explode(",",$sensory->deep_sensation); @endphp
                                                <div class="input_sm">

                                                    <input class="form-control" name="deep_sensation[]"
                                                           value="{{ $key }}"
                                                           type="checkbox" {{ in_array($key , $lists6)?'checked':'' }} />{{$list}}

                                                </div>

                                            @endforeach


                                            <div class="col-md-8">
                                                <input class="form_control add-width" name="deep_sensation_comment"
                                                       value="{{ old('pulse_comment', $sensory->deep_sensation_comment) }}"
                                                       type="text" placeholder="{{ trans('laralum.comment') }}"/>
                                            </div>


                                        </div>

                                    </div>


                                    <div class="form-row">
                                        <label class="label">Hot/Cold Sensation</label>
                                        <div class="form-rgt_sec">


                                            @php

                                                $lists=$sensory->getSensationType();

                                            @endphp


                                            @foreach($lists as $key=>$list)
                                            @php $lists7 = explode(",",$sensory->hot_or_cold_sensation); @endphp
                                                <div class="input_sm">

                                                    <input class="form-control" name="hot_or_cold_sensation[]"
                                                           value="{{$key}}"
                                                           type="checkbox" {{ in_array($key , $lists7)?'checked':'' }} />{{$list}}

                                                </div>

                                            @endforeach


                                            <div class="col-md-8">
                                                <input class="form_control add-width"
                                                       name="hot_or_cold_sensation_comment"
                                                       value="{{ old('hot_or_cold_sensation_comment', $sensory->hot_or_cold_sensation_comment) }}"
                                                       type="text" placeholder="{{ trans('laralum.comment') }}"/>
                                            </div>


                                        </div>

                                    </div>
                                </div>
                                <button type="submit"
                                        class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                            </form>
                        </div>

                    </section>


                    <section class="aurv_section physiotherpy_vital">
                        <h3 class="section_title"> Motor Examination </h3>
                        <div class="form_main">
                            <form method="post"
                                  action="{{ route('Laralum::patient.physiotherpy_vital_data_store',$booking->id) }}">


                                {!! csrf_field() !!}
                                <input type="hidden" value="{{ $patient->id }}" name="patient_id">
                                <input type="hidden" value="{{ $booking->id }}" name="booking_id">
                                <input type="hidden" name="save_section" value="motor_examination"/>


                                <div class="form-row">
                                    <label class="label"> ROM Of Joint </label>
                                    <div class="form-rgt_sec form-rgt_sec_rom">


                                        @php

                                            $lists=$motor->getState();

                                        @endphp


                                        @foreach($lists as $key=>$list)
                                         @php $lists8 = explode(",",$motor->rom_of_joint); @endphp
                                            <div class="input_sm">

                                                <input class="form-control" name="rom_of_joint[]"
                                                       value="{{ $key }}"
                                                       type="checkbox" {{ in_array($key , $lists8)?'checked':'' }} />{{$list}}

                                            </div>

                                        @endforeach


                                        {{--<div class="col-md-8">--}}
                                        {{--<input class="form_control add-width" name="superficial_sensation_comment" value="{{ old('pulse_comment', $sensory->superficial_sensation_comment) }}" type="text" placeholder="{{ trans('laralum.comment') }}" />--}}
                                        {{--</div>--}}
                                    <!-- <div class="input_sm">
                                        
                                            <select>
                                                <option>1</option>
                                                <option>2</option>
                                                <option>3</option>
                                            </select>
                                        </div> -->
                                        @php
                                            $i = 0;
                                        @endphp
                                        @if($motor->joint_id != "")
                                            @php
                                                $joints = array_filter(explode(',', $motor->joint_id));
                                                $subcat = array_filter(explode(',', $motor->joint_sub_category_id));
                                                $left = array_filter(explode(',', $motor->joint_right_side));
                                                $right = array_filter(explode(',', $motor->joint_left_side));
                                            $i = 0;
                                            @endphp
                                            {{--dd($joints)--}}

                                            @foreach($joints as $romCount => $joint_id)
                                                @if(isset($subcat[$romCount]))
                                                    <div class="joint_row">
                                                        <div class="input_sm1">
                                                            <label>Joint</label>
                                                            <select name="joint[]" class="select-joint"
                                                                    data-id="{{ $i }}">
                                                                <option value="5">Select Joint</option>
                                                                @foreach($romjoint as $joint)
                                                                    <option value="{{$joint->id}}"
                                                                            @if($joint->id == $joint_id) selected @endif>{{$joint->joint_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="input_sm1">
                                                            <label>Sub-category</label>
                                                            {{--dd(App\RomSubCategory::getSubCatByJointID($joint_id))--}}
                                                            @php
                                                                $all_subCat = App\RomSubCategory::getSubCatByJointID($joint_id);
                                                            @endphp
                                                            <select name="subcat[]"
                                                                    class="insert-option insert-option-{{ $i }}"
                                                                    data-id="{{ $i }}">

                                                                <option value="">Select Sub Category</option>
                                                                @foreach($all_subCat as $subCat)
                                                                    <option value="{{$subCat->id}}"
                                                                            @if($subCat->id == $subcat[$romCount]) selected @endif>{{$subCat->sub_category}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="joint_row">
                                                        <h4 class="normal-rom normal-rom-0">Normal
                                                            ROM:({{App\RomSubCategory::getNormalByID($subcat[$romCount])}}
                                                            )</h4>
                                                        <div class="input_sm1">
                                                            <label>Right Side</label>
                                                            <input type="text" class="form_control add-width"
                                                                   name="right[]"
                                                                   value="{{ isset($left[$romCount]) ? $left[$romCount] : ""}}">
                                                        </div>
                                                        <div class="input_sm1">
                                                            <label>Left Side</label>
                                                            <input type="text" class="form_control add-width"
                                                                   name="left[]"
                                                                   value="{{isset($right[$romCount]) ? $right[$romCount] : ""}}">
                                                        </div>
                                                    </div>
                                                    @php $i++ @endphp
                                                @endif
                                            @endforeach

                                        @else
                                            @php $i = 1; @endphp

                                            <div class="joint_row">
                                                <div class="input_sm1">
                                                    <label>Joint</label>
                                                    <select name="joint[]" class="select-joint" data-id="0">
                                                        <option value="5">Select Joint</option>
                                                        @foreach($romjoint as $joint)
                                                            <option value="{{$joint->id}}">{{$joint->joint_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="input_sm1">
                                                    <label>Sub-category</label>
                                                    <select name="subcat[]" class="insert-option insert-option-0"
                                                            data-id="0">
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="joint_row">
                                                <h4 class="normal-rom normal-rom-0">Normal ROM:</h4>
                                                <div class="input_sm1">
                                                    <label>Right Side</label>
                                                    <input type="text" class="form_control add-width" name="right[]">
                                                </div>
                                                <div class="input_sm1">
                                                    <label>Left Side</label>
                                                    <input type="text" class="form_control add-width" name="left[]">
                                                </div>
                                            </div>
                                        @endif
                                        <div class="row_clone_data form-rgt_sec"></div>
                                    </div>
                                    <div class="joint_row btn-joint_row">
                                        <button class="btn btn-danger add-new-rom" data-id="{{ $i }}">+Add</button>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="label">Muscle Power/Grade</label>
                                    <div class="form-rgt_sec">


                                        @php

                                            $lists=$motor->getMuscleType();

                                        @endphp


                                        @foreach($lists as $key=>$list)
                                        @php $lists9 = explode(",",$motor->muscle_power_grade); @endphp
                                            <div class="input_sm">

                                                <input class="form-control check-class" name="muscle_power_grade[]"
                                                       id="muscle_power_grade"
                                                       value="{{ $key }}"
                                                       type="checkbox" {{ in_array($key , $lists9)?'checked':'' }} />{{$list}}

                                            </div>

                                        @endforeach


                                        <div class="col-md-8">
                                            <input class="form_control add-width" name="muscle_power_grade_comment"
                                                   value="{{ old('muscle_power_grade_comment', $motor->muscle_power_grade_comment) }}"
                                                   type="text" placeholder="{{ trans('laralum.comment') }}"/>
                                        </div>


                                    </div>

                                </div>


                                <div class="form-row">
                                    <label class="label">Muscle Tone</label>
                                    <div class="form-rgt_sec">


                                        @php

                                            $lists=$motor->getMuscleTone();

                                        @endphp


                                        @foreach($lists as $key=>$list)
                                        @php $lists10 = explode(",",$motor->muscle_power_tone); @endphp
                                            <div class="input_sm">

                                                <input class="form-control" name="muscle_power_tone[]"
                                                       value="{{ $key }}"
                                                       type="checkbox" {{ in_array($key , $lists10)?'checked':'' }} />{{$list}}

                                            </div>

                                        @endforeach


                                        <div class="col-md-8">
                                            <input class="form_control add-width" name="muscle_power_tone_comment"
                                                   value="{{ old('muscle_power_tone_comment', $motor->muscle_power_tone_comment) }}"
                                                   type="text" placeholder="{{ trans('laralum.comment') }}"/>
                                        </div>


                                    </div>

                                </div>
                                <h3 class="section_title"> Reflexes </h3>

                                <div class="form-row">
                                    <label class="label">Deep Reflexes</label>
                                    <div class="form-rgt_sec">


                                        @php

                                            $lists=$motor->getReflex();

                                        @endphp


                                        @foreach($lists as $key=>$list)
                                        @php $lists11 = explode(",",$motor->deep_reflexes); @endphp
                                            <div class="input_sm">

                                                <input class="form-control" name="deep_reflexes[]"
                                                       value="{{ $key }}"
                                                       type="checkbox" {{ in_array($key , $lists11)?'checked':'' }} />{{$list}}

                                            </div>

                                        @endforeach


                                        <div class="col-md-8">
                                            <input class="form_control add-width" name="deep_reflexes_comment"
                                                   value="{{ old('deep_reflexes_comment', $motor->deep_reflexes_comment) }}"
                                                   type="text" placeholder="{{ trans('laralum.comment') }}"/>
                                        </div>


                                    </div>

                                </div>


                                <div class="form-row">
                                    <label class="label">Superficial Reflexes</label>
                                    <div class="form-rgt_sec">


                                        @php

                                            $lists=$motor->getReflex();

                                        @endphp


                                        @foreach($lists as $key=>$list)
                                        @php $lists12 = explode(",",$motor->superficial_reflexes); @endphp
                                            <div class="input_sm">

                                                <input class="form-control" name="superficial_reflexes[]"
                                                       value="{{ $key }}"
                                                       type="checkbox" {{ in_array($key , $lists12)?'checked':'' }} />{{$list}}

                                            </div>

                                        @endforeach


                                        <div class="col-md-8">
                                            <input class="form_control add-width"
                                                   name="superficial_reflexes_comment"
                                                   value="{{ old('deep_reflexes_comment', $motor->superficial_reflexes_comment) }}"
                                                   type="text" placeholder="{{ trans('laralum.comment') }}"/>
                                        </div>


                                    </div>

                                </div>


                                <div class="form-row">
                                    <label class="label">Bowel & Bladder</label>
                                    <div class="form-rgt_sec">


                                        @php

                                            $lists=$motor->getSensationType();

                                        @endphp
                                        @foreach($lists as $key=>$list)
                                        @php $lists13 = explode(",",$motor->bower_and_bladder); @endphp
                                            <div class="input_sm">
                                                <input class="form-control" name="bower_and_bladder[]"
                                                       value="{{ $key }}"
                                                       type="checkbox" {{ in_array($key , $lists13)?'checked':'' }} />{{$list}}
                                            </div>

                                        @endforeach


                                        <div class="col-md-8">
                                            <input class="form_control add-width" name="bower_and_bladder_comment"
                                                   value="{{ old('bower_and_bladder_comment', $motor->bower_and_bladder_comment) }}"
                                                   type="text" placeholder="{{ trans('laralum.comment') }}"/>
                                        </div>


                                    </div>

                                </div>


                                <div class="form-row">
                                    <label class="label">Specific Test, if Any</label>
                                    <div class="form-rgt_sec">

                                        <div class="input_sm textarea">
                            <textarea class="form-control" name="specific_test">{{old('specific_test',$motor->specific_test)}}</textarea>
                                        </div>


                                    </div>
                                    <button type="submit"
                                            class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>


                                </div>
                            </form>


                            <h3 class="section_title"> Musculo Skeletal </h3>

                            <form method="post"
                                  action="{{ route('Laralum::patient.physiotherpy_vital_data_store',$booking->id) }}">


                                {!! csrf_field() !!}
                                <input type="hidden" value="{{ $patient->id }}" name="patient_id">
                                <input type="hidden" value="{{ $booking->id }}" name="booking_id">
                                <input type="hidden" name="save_section" value="pain_examination"/>
                                <div class="form-row">
                                    <label class="label">Muscle Pain</label>
                                    <div class="form-rgt_sec">


                                        @php

                                            $lists=$pain->getState();

                                        @endphp
                                        <div class="input_sm">

                                            <select class="form-control" name="muscle_pain">


                                                @foreach($lists as $key=>$list)

                                                    <option value="{{$key}}" {{ ($pain->muscle_pain == $key ) ? 'selected' : ''}} >{{$list}}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="col-md-8">
                                            <input class="form_control add-width" name="muscle_pain_comment"
                                                   value="{{ old('muscle_pain_comment', $pain->muscle_pain_comment) }}"
                                                   type="text" placeholder="{{ trans('laralum.comment') }}"/>
                                        </div>


                                    </div>

                                </div>


                                <div class="form-row">
                                    <label class="label">Back Pain</label>
                                    <div class="form-rgt_sec">


                                        @php

                                            $lists=$pain->getState();

                                        @endphp
                                        <div class="input_sm">

                                            <select class="form-control" name="back_pain">


                                                @foreach($lists as $key=>$list)

                                                    <option value="{{$key}}" {{ ($pain->back_pain==$key )?'selected':''}}>{{$list}}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="col-md-8">
                                            <input class="form_control add-width" name="back_pain_comment"
                                                   value="{{ old('back_pain_comment', $pain->back_pain_comment) }}"
                                                   type="text" placeholder="{{ trans('laralum.comment') }}"/>
                                        </div>


                                    </div>

                                </div>


                                <div class="form-row">
                                    <label class="label">Knee Pain</label>
                                    <div class="form-rgt_sec">


                                        @php

                                            $lists=$pain->getState();

                                        @endphp
                                        <div class="input_sm">

                                            <select class="form-control" name="knee_pain">


                                                @foreach($lists as $key=>$list)

                                                    <option value="{{$key}}" {{ ($pain->knee_pain==$key )?'selected':''}}>{{$list}}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="col-md-8">
                                            <input class="form_control add-width" name="knee_pain_comment"
                                                   value="{{ old('knee_pain_comment', $pain->knee_pain_comment) }}"
                                                   type="text" placeholder="{{ trans('laralum.comment') }}"/>
                                        </div>


                                    </div>

                                </div>

                                <div class="form-row">
                                    <label class="label">Joint Pain</label>
                                    <div class="form-rgt_sec">


                                        @php

                                            $lists=$pain->getState();

                                        @endphp
                                        <div class="input_sm">

                                            <select class="form-control" name="joint_pain">


                                                @foreach($lists as $key=>$list)

                                                    <option value="{{$key}}" {{ ($pain->joint_pain==$key )?'selected':''}}>{{$list}}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="col-md-8">
                                            <input class="form_control add-width" name="joint_pain_comment"
                                                   value="{{ old('muscle_pain_comment', $pain->joint_pain_comment) }}"
                                                   type="text" placeholder="{{ trans('laralum.comment') }}"/>
                                        </div>


                                    </div>

                                </div>

                                <div class="form-row">
                                    <label class="label">Spinal Injuries</label>
                                    <div class="form-rgt_sec">


                                        @php

                                            $lists=$pain->getState();

                                        @endphp
                                        <div class="input_sm">

                                            <select class="form-control" name="spinal_injuries">


                                                @foreach($lists as $key=>$list)

                                                    <option value="{{$key}}" {{ ($pain->spinal_injuries==$key )?'selected':''}} >{{$list}}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="col-md-8">
                                            <input class="form_control add-width" name="spinal_injuries_comment"
                                                   value="{{ old('spinal_injuries_comment', $pain->spinal_injuries_comment) }}"
                                                   type="text" placeholder="{{ trans('laralum.comment') }}"/>
                                        </div>


                                    </div>

                                </div>


                                <div class="form-row">
                                    <label class="label">Side</label>
                                    <div class="form-rgt_sec">


                                        @php

                                            $lists=$pain->getSide();

                                        @endphp


                                        @foreach($lists as $key=>$list)
                                        @php $listssss = explode(",",$pain->side); @endphp
                                            <div class="input_sm">

                                                <input class="form-control" name="side[]"
                                                       value="{{ $key  }}"
                                                       type="radio" {{ in_array($key , $listssss)?'checked':'' }} />{{$list}}

                                            </div>

                                        @endforeach


                                    </div>

                                </div>


                                <div class="form-row">
                                    <label class="label">Onset of Symptoms</label>
                                    <div class="form-rgt_sec">


                                        <input class="form_control add-width" name="onset_of_symptoms"
                                               value="{{ old('onset_of_symptoms	', $pain->onset_of_symptoms) }}"
                                               type="text" placeholder="{{ trans('laralum.comment') }}"/>


                                    </div>

                                </div>

                                <div class="form-row">
                                    <label class="label">Prior injury To Affected Area</label>
                                    <div class="form-rgt_sec">


                                        @php

                                            $lists=$pain->getState();

                                        @endphp
                                        <div class="input_sm">

                                            <select class="form-control" name="priorities_injuries_to_affected_area">


                                                @foreach($lists as $key=>$list)

                                                    <option value="{{$key}}" {{ ($pain->priorities_injuries_to_affected_area == $key )? 'selected' : ''}} >{{$list}}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="col-md-8">
                                            <input class="form_control add-width"
                                                   name="priorities_injuries_to_affected_area_comment"
                                                   value="{{ old('priorities_injuries_to_affected_area_comment', $pain->priorities_injuries_to_affected_area_comment	) }}"
                                                   type="text" placeholder="{{ trans('laralum.comment') }}"/>
                                        </div>


                                    </div>

                                </div>
                                <button type="submit"
                                        class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                            </form>
                        </div>
                    </section>


                    <section class="aurv_section physiotherpy_vital">
                        <h3 class="section_title"> Pain Assessments </h3>
                        <form method="post"
                              action="{{ route('Laralum::patient.physiotherpy_vital_data_store',$booking->id) }}">


                            {!! csrf_field() !!}
                            <input type="hidden" value="{{ $patient->id }}" name="patient_id">
                            <input type="hidden" value="{{ $booking->id }}" name="booking_id">
                            <input type="hidden" name="save_section" value="pain_assessment_examination"/>
                            <div class="form-row">

                                <label class="label">Pain At Rest</label>
                                <div class="form-rgt_sec">
                                    <div class="input_sm">

                                        <div class="range-slider">

                                            <input class="range-slider__range" type="range" id="slider1"
                                                   value="{{old('pain_at_rest',$pain_assesment->pain_at_rest)}}" min="0"
                                                   max="10" name="pain_at_rest">

                                            <span class="range-slider__value">0</span>

                                        </div>
                                    </div>
                                </div>

                            </div>


                            <div class="form-row">

                                <label class="label">Pain With Activity</label>
                                <div class="form-rgt_sec">
                                    <div class="input_sm">

                                        <div class="range-slider">

                                            <input class="range-slider__range" type="range"
                                                   value="{{old('pain_with_activity',$pain_assesment->pain_with_activity	)}}"
                                                   min="0" max="10" name="pain_with_activity">

                                            <span class="range-slider__value">0</span>

                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-row">

                                <label class="label">Pain At Night</label>
                                <div class="form-rgt_sec">
                                    <div class="input_sm">

                                        <div class="range-slider">

                                            <input class="range-slider__range" type="range"
                                                   value="{{old('pain_at_night',$pain_assesment->pain_at_night	)}}"
                                                   min="0" max="10" name="pain_at_night">

                                            <span class="range-slider__value">0</span>

                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-row">

                                <label class="label">Aggravating Factor</label>
                                <div class="form-rgt_sec">
                                    <input class="form_control add-width"
                                           name="aggregation_factor"
                                           value="{{ old('aggregation_factor', $pain_assesment->aggregation_factor) }}"
                                           type="text" placeholder="{{ trans('laralum.comment') }}"/>

                                </div>
                            </div>


                            <div class="form-row">

                                <label class="label">Relieving Factor</label>
                                <div class="form-rgt_sec">
                                    <input class="form_control add-width"
                                           name="relieving_factor"
                                           value="{{ old('relieving_factor', $pain_assesment->relieving_factor) }}"
                                           type="text" placeholder="{{ trans('laralum.comment') }}"/>

                                </div>
                            </div>


                            <div class="form-row">
                                <label class="label">Type of Pain</label>
                                <div class="form-rgt_sec">


                                    @php

                                        $lists=$pain_assesment->getTypeOfPain();

                                    @endphp


                                    @foreach($lists as $key=>$list)
                                    @php $lists14 = explode(",",$pain_assesment->type_of_pain); @endphp
                                        <div class="input_sm">

                                            <input class="form-control" name="type_of_pain[]"
                                                   value="{{ $key }}"
                                                   type="checkbox" {{ in_array($key , $lists14)?'checked':'' }} />{{$list}}

                                        </div>

                                    @endforeach


                                </div>


                                <div class="form-row">
                                    <label class="label">Nature of Pain</label>
                                    <div class="form-rgt_sec">


                                        @php

                                            $lists=$pain_assesment->getNature();

                                        @endphp


                                        @foreach($lists as $key=>$list)
                                        @php $lists15 = explode(",",$pain_assesment->nature_of_pain); @endphp
                                            <div class="input_sm">

                                                <input class="form-control" name="nature_of_pain[]"
                                                       value="{{ $key }}"
                                                       type="checkbox" {{ in_array($key , $lists15)?'checked':'' }} />{{$list}}

                                            </div>

                                        @endforeach


                                    </div>


                                    <div class="form-row">
                                        <label class="label">Symptoms Are Worse</label>
                                        <div class="form-rgt_sec">


                                            @php

                                                $lists=$pain_assesment->getSymptoms();

                                            @endphp


                                            @foreach($lists as $key=>$list)
                                            @php $lists16 = explode(",",$pain_assesment->symptoms_are_worse); @endphp
                                                <div class="input_sm">

                                                    <input class="form-control" name="symptoms_are_worse[]"
                                                           value="{{ $key}}"
                                                           type="checkbox" {{ in_array($key , $lists16)?'checked':'' }} />{{$list}}

                                                </div>

                                            @endforeach


                                        </div>
                                    </div>
                                </div>
                                <div class="form-button_row">
                                    <button type="submit"
                                            class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                                </div>
                            </div>
                        </form>
                    </section>


                @endif
                    @else

                    @include('laralum.token.physiotherpy_vital_data_summary')
               @endif

            </div>
        </div>
    </div>
@endsection
@section("js")



    <script>
        $(document).ready(function () {
            $('.add-new-rom').click(function (e) {
                e.preventDefault();
                // data_id=$(this).attr('data-id');
                // new_id=parseInt(data_id)+1;
                // $('.colne-html-container .select-joint').attr('data-id',new_id);
                // $('.colne-html-container .insert-option-'+data_id).addClass('insert-option-'+new_id);
                // $('.colne-html-container .insert-option-'+data_id).removeClass('insert-option-'+data_id);
                // $(this).attr('data-id', new_id)
                // $('.row_clone_data').html($('.colne-html').clone());
                url = "{{url('/admin/get/joint/getHtml')}}";
                data_id = $(this).attr('data-id');
                $this = $(this);
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {'count': data_id},
                    success: function (response) {
                        $('.row_clone_data').append(response);
                        $this.attr('data-id', parseInt(data_id) + 1);
                    }, error: function (error) {
                        console.log(error);
                    }
                });

            })

            $('.form-rgt_sec_rom').delegate('.select-joint', 'change', function (e) {
                joint_id = $(this).val();
                url = "{{url('/admin/get/joint/subcat')}}";
                data_id = $(this).attr('data-id');
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {'joint_id': joint_id},
                    success: function (response) {
                        $('.insert-option-' + data_id).html(response);
                    }, error: function (error) {
                        console.log(error);
                    }
                });
            });
            $('.form-rgt_sec_rom').delegate('.insert-option', 'change', function (e) {
                normal = $(this).children('option:selected').first().attr('data-normal-rom');
                data_id = $(this).attr('data-id');
                $('.normal-rom-' + data_id).text('Normal ROM:(' + normal + ')');

            })
        });

        var rangeSlider1 = function () {
            var slider = $('.range-slider'),
                range = $('.range-slider__range'),
                value = $('.range-slider__value');

            slider.each(function () {

                value.each(function () {
                    var value = $(this).prev().attr('value');
                    $(this).html(value);


                });

                range.on('input', function () {
                    $(this).next(value).html(this.value);
                });
            });
        };


        rangeSlider1();


    </script>


    <div class="colne-html-container" style="display: none;">
        <div class="colne-html">
            <div class="joint_row">
                <div class="input_sm1">
                    <label>Joint</label>
                    <select name="joint" class="select-joint" data-id="1">
                        <option>Select Joint</option>
                        @foreach($romjoint as $joint)
                            <option value="{{$joint->id}}">{{$joint->joint_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input_sm1">
                    <label>Sub-category</label>
                    <select name="subcat" class="insert-option insert-option-1">
                    </select>
                </div>
            </div>

            <div class="joint_row">
                <h4>Normal ROM:</h4>
                <div class="input_sm1">
                    <label>Right Side</label>
                    <input type="text" class="form_control add-width" name="right">
                </div>
                <div class="input_sm1">
                    <label>Left Side</label>
                    <input type="text" class="form_control add-width" name="left">
                </div>
            </div>
        </div>
    </div>

    <!--
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
                integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
                crossorigin="anonymous">
        </script> -->
@endsection
