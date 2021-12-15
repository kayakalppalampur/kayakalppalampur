@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @if($booking->isEditable())
            <a class="section" href="{{ route('Laralum::patients') }}">{{ trans('laralum.patient_list') }}</a>
        @else
            <a class="section" href="{{ route('Laralum::archived.patients.list') }}">{{ trans('laralum.archived_patients') }}</a>
        @endif
        <i class="right angle icon divider"></i>
        <div class="active section">Patient's Diet Chart</div>
    </div>
@endsection
@section('title', 'Diet Chart of Patient')
@section('icon', "pencil")
@section('subtitle', '')
@section('content')
    <link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">
    <div class="ui one column doubling stackable">
        <div class="column admin_basic_detail1 admin_wrapper">
            <div class="segment main_wrapper">
                <div class="ui breadcrumb steps clearfix">
                    <ul>
                        <li><a class="section"
                               href="{{ route('Laralum::patient.show', ['token_id' => $booking->id]) }}">Personal
                                Details</a>
                        </li>
                        {{-- <a class="section" href="{{ route('Laralum::tokens') }}">Case History</a>
                         <i class="right angle icon divider"></i>--}}
                        <li><a class="section"
                               href="{{ route('Laralum::patient.vital_data', ['token_id' => $booking->id]) }}">Vital
                                Data</a>
                        </li>
                        <li><a class="section"
                               href="{{ route('Laralum::patient_lab_test.index', ['patient_id' => $booking->id]) }}">Lab
                                Tests</a></li>
                        <li><a class="section"
                               href="{{ route('Laralum::patient.diagnosis', ['patient_id' => $booking->id]) }}">Provisional Diagnosis</a>
                        </li>

                        <li>  <a class="section" href="{{ route('Laralum::patient.treatment', ['patient_id' => $booking->id]) }}">Allot Treatments</a></li>

                        <li>  <div class="active section">Diet Chart</div></li>
                        <li>
                            <a class="section"
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
                        @endif--}}
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="admin_wrapper signup">
        <div class="main_wrapper">
            <div class="ui one column doubling stackable">
                <div class="column admin_basic_detail1">
                    <div class="ui very padded segment">
                        {!! Form::open(array('route' => ['Laralum::add-patient-diet-chart-details', 'patient_id' => $booking->id], 'id' => 'dischargeForm','files'=>true,'method'=>'post')) !!}

                        <div  class="column2 table_top_btn">
                            <h2 class="pull-left">Diet Chart</h2>
                            <div class="btn-group pull-right">
                                <a class="btn btn-primary ui button {{ \App\Http\Controllers\Laralum\Laralum::settings()->button_color }}" href="{{ route('Laralum::patient.diet-chart', ['patient_id' => $booking->id]) }}">Diet Chart</a>

                            </div>
                        </div>


                        {{ csrf_field() }}

                        <div class="signup_bg diet_chart_title">
                            <h3 class="title_3">Diet Chart for: {{ $booking->getProfile('first_name').' '.$booking->getProfile('last_name')}}</h3>
                        </div>

                        <div class="about_sec white_bg  discharge_form">

                            <div class="ui stackable grid">
                                <div class="column">
                                     <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                                   <div class="row">
                                         <div class="col-md-6">
                                              <label>DATE</label>
                                              <input type="text" name="start_date" placeholder="Default Date" class="form-control datepicker" value="{{ date("d-m-Y", strtotime($diet->start_date)) }}" />
                                         </div>
                                        <div class="col-md-6">
                                            <label>REPEATS</label>
                                            <input type="text" name="repeats" class="form-control" placeHolder="Show repeat options"/>
                                        </div>
                                   </div>
                                </div>
                            </div>
                            <div class="ui stackable grid diet_chart_form_wrap">
                                <div class="column">
                                     <div class="scroll-tab-in">
                                         <table class="ui table table_cus_v last_row_bdr">
                                          <tr>
                                                <th>
                                                    <div class="col-md-3"><label> >>Breakfast(8:30 AM)</label></div>
                                                </th>
                                              <?php $i = 0; ?>
                                                @foreach(\App\KitchenItem::getItems(\App\DietChartItems::TYPE_BREAKFAST, 7) as $item)
                                                <td>
                                                    <div class="pull-left select-div">
                                                    <label>Item {{ $loop->iteration }}</label>
                                                    <select class="form-control item_list_{{ \App\DietChartItems::TYPE_BREAKFAST }}" name="item_{{ $item->id }}-type_{{ \App\DietChartItems::TYPE_BREAKFAST }}">
                                                        <option value="">Select Item</option>
                                                        @foreach(\App\KitchenItem::getItems(\App\DietChartItems::TYPE_BREAKFAST)  as $item)
                                                        <option value="{{ $item->id }}" {{ $diet->checkSelected($item->id, \App\DietChartItems::TYPE_BREAKFAST, $i) ? 'selected="selected"' : "" }}>
                                                            {{ $item->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    </div>
                                                </td>
                                                  <?php $i++; ?>
                                                @endforeach
                                              @for($j = $i; $j < 7; $j++)
                                                  <td></td>
                                              @endfor
                                            </tr>
                                            <tr>
                                                <th>
                                                    <div class="col-md-3"><label>>>Lunch(12:30 AM)</label></div>
                                                </th>
                                                <?php $i = 0; ?>
                                                @foreach(\App\KitchenItem::getItems(\App\DietChartItems::TYPE_LUNCH, 7) as $item)
                                                <td>
                                                <div class="pull-left select-div">
                                                    <label>Item {{ $loop->iteration }}</label>
                                                    <select class="form-control item_list_{{ \App\DietChartItems::TYPE_LUNCH }}" name="item_{{ $item->id }}-type_{{ \App\DietChartItems::TYPE_LUNCH }}">
                                                        <option value="">Select Item</option>
                                                        @foreach(\App\KitchenItem::getItems(\App\DietChartItems::TYPE_LUNCH) as $item)
                                                            <option value="{{ $item->id }}" {{ $diet->checkSelected($item->id, \App\DietChartItems::TYPE_LUNCH, $i) ? 'selected="selected"' : "" }}>
                                                                {{ $item->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                 </div>
                                                </td>
                                                    <?php $i++; ?>
                                                @endforeach
                                                @for($j = $i; $j < 7; $j++)
                                                    <td></td>
                                                @endfor
                                            </tr>
                                            <tr>
                                                <th>
                                                    <div class="col-md-3"><label>>>Post Lunch (4:00 PM)</label></div>
                                                </th>
                                                <?php $i = 0; ?>
                                                @foreach(\App\KitchenItem::getItems(\App\DietChartItems::TYPE_POST_LUNCH, 7) as $item)
                                                <td>
                                                    <div class="pull-left select-div">
                                                    <label>Item {{ $loop->iteration }}</label>
                                                    <select class="form-control item_list_{{ \App\DietChartItems::TYPE_POST_LUNCH }}" name="item_{{ $item->id }}-type_{{ \App\DietChartItems::TYPE_POST_LUNCH }}">
                                                        <option value="">Select Item</option>
                                                        @foreach(\App\KitchenItem::getItems(\App\DietChartItems::TYPE_POST_LUNCH) as $item)
                                                            <option value="{{ $item->id }}"  {{ $diet->checkSelected($item->id, \App\DietChartItems::TYPE_POST_LUNCH, $i) ? 'selected="selected"' : "" }}>
                                                                {{ $item->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                </td>
                                                    <?php $i++; ?>
                                                @endforeach
                                                @for($j = $i; $j < 7; $j++)
                                                    <td></td>
                                                @endfor
                                            </tr>

                                            <tr>
                                                <th>
                                                    <div class="col-md-3"><label>>>Dinner(7:00 AM)</label></div>
                                                </th>
                                                <?php $i = 0; ?>
                                                @foreach(\App\KitchenItem::getItems(\App\DietChartItems::TYPE_DINNER, 7) as $item)
                                                <td>
                                                <div class="pull-left select-div">
                                                    <label>Item {{ $loop->iteration }}</label>
                                                    <select class="form-control item_list_{{ \App\DietChartItems::TYPE_DINNER }}" name="item_{{ $item->id }}-type_{{ \App\DietChartItems::TYPE_DINNER }}">
                                                        <option value="">Select Item</option>
                                                        @foreach(\App\KitchenItem::getItems(\App\DietChartItems::TYPE_DINNER) as $item)
                                                            <option value="{{ $item->id }}"  {{ $diet->checkSelected($item->id, \App\DietChartItems::TYPE_DINNER, $i) ? 'selected="selected"' : "" }}>
                                                                {{ $item->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                </td>
                                                    <?php $i++; ?>
                                                @endforeach
                                                @for($j = $i; $j < 7; $j++)
                                                    <td></td>
                                                @endfor
                                            </tr>
                                            <tr class="last">
                                                <th class="no_bdr_btm">
                                                    <div class="col-md-3"><label> >>Special(8:30 PM)</label></div>
                                                </th>
                                                <?php $i = 0; ?>
                                                @foreach(\App\KitchenItem::getItems(\App\DietChartItems::TYPE_SPECIAL, 7) as $item)
                                                <td>
                                                    <div class="pull-left select-div">
                                                    <label>Item {{ $loop->iteration }}</label>
                                                    <select class="form-control item_list_{{ \App\DietChartItems::TYPE_SPECIAL }}" name="item_{{ $item->id }}-type_{{ \App\DietChartItems::TYPE_SPECIAL }}">
                                                        <option value="">Select Item</option>
                                                        @foreach(\App\KitchenItem::getItems(\App\DietChartItems::TYPE_SPECIAL) as $item)
                                                            <option value="{{ $item->id }}"  {{ $diet->checkSelected($item->id, \App\DietChartItems::TYPE_SPECIAL, $i) ? 'selected="selected"' : "" }}>
                                                                {{ $item->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    </div>
                                                </td>
                                                    <?php $i++; ?>
                                                @endforeach
                                                @for($j = $i; $j < 7; $j++)
                                                    <td></td>
                                                @endfor
                                            </tr>
                                        </table>

                                    </div>
                                    <div class="diet_notes">
                                        <label>Notes</label>
                                        <textarea name="notes" class="form-control">{!! $diet->notes !!}</textarea></div>
                                    </div>

                                    <div class="vital-btn1 discharge_btn_rw">
                                        <button id="submit" class="ui button no-disable orange">Submit</button>
                                    </div>

                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("js")
    <script>
    $( ".datepicker" ).datepicker({dateFormat: "dd-mm-yy", autoclose:true, minDate:0});

    $("select").each(function() {
        var id = $(this).attr('class').split('item_list_')[1];
        var val = $(this).val();
        console.log('val'+val+"id"+id);
        if (val != "") {
            $(".item_list_" + id).not(this).each(function () {
                console.log('+' + $(this).find('option[value="' + val + '"]'));
                $(this).find('option[value="' + val + '"]').remove();
            })
        }


    });
    $("select").change(function() {
        var id = $(this).attr('class').split('item_list_')[1];
        var val = $(this).val();
        console.log('val'+val+"id"+id);
        if (val != "") {
            $(".item_list_" + id).not(this).each(function () {
                console.log('+' + $(this).find('option[value="' + val + '"]'));
                $(this).find('option[value="' + val + '"]').remove();
            })
        }

    });
    </script>


@endsection