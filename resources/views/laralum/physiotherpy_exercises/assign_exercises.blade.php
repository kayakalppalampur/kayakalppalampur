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
        <div class="active section">Discharge Patient</div>
    </div>
@endsection
@section('title', 'Attachments')
@section('icon', "check")
@section('subtitle', '')
@section('content')
    <link rel="stylesheet" type="text/css" media="screen"
          href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">

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
                        <li><a class="section"
                               href="{{ route('Laralum::patient.treatment', ['patient_id' => $booking->id]) }}">Allot
                                Treatments</a></li>
                        <li><a class="section"
                               href="{{ route('Laralum::patient.diet-chart', ['patient_id' => $booking->id]) }}">Diet
                                Chart</a></li>
                        <li>
                            <a class="section"
                               href="{{ route('Laralum::discharge.patient', ['token_id' => $booking->id]) }}">Discharge
                                Patient</a></li>


                        @php
                            $dept_model=\App\Department::where('title','like',"%Physiotherapy%")->first();
                        @endphp

                        @if(\Auth::user()->isAdmin()||(!empty($dept_model) && \Auth::user()->department->department_id==$dept_model->id))
                            <li>
                            <div class="active section">Attachments</div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <div class="admin_wrapper signup discharge_patient_con">
        <div class="main_wrapper">
            <div class="ui one column doubling stackable">
                <div class="column admin_basic_detail1">
                    <div class="ui very padded segment">

                        {{--<a href="#" class="btn btn-block pull-right">Complete</a>--}}
                        <table class="ui table_cus_v table " style="width: 100%">
                            <thead>
                            <tr>
                                <th>Sno</th>
                                <th>Category</th>
                                <th>Name of Exercise</th>
                                <th>Assigned To Patient</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php  $count=0;
                            @endphp
                            @foreach($exercises as $exercise)

                                @php  $count=$count+1;
                                @endphp
                                <tr>
                                    <td>{{ $count  }}</td>
                                    <td>{{ $exercise->getCategory()->title }}</td>
                                    <td>{{ $exercise->name_of_excercise }}</td>

                                    <td>
                                        @php
                                            $apply_model=\App\ApplyRecommendExcercise::where('physiotherpy_exercise_id',$exercise->id)->first();
                                        @endphp

                                        <input type="checkbox" name="apply-exercise" id="assign_to_{{$exercise->id}}"
                                               data-id="{{$exercise->id}}" {{!empty($apply_model->state_id)?'checked="true"':''}} >
                                    </td>
                                    <td>

                                        <button type="button" class="btn btn-info btn-lg value_{{$exercise->id}}"
                                                data-toggle="modal"
                                                data-target="#bookingModal_{{ $exercise->id }}" data-id="{{ $exercise->id }}">View
                                        </button>
                                        <a href="{{ route('Laralum::recommend-exercise.print',['exercise_id'=> $exercise->id]) }}" style="color:#444;">
                                        <button type="button" class="btn btn-info btn-lg value_{{$exercise->id}}">Print
                                        </button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach($exercises as $exercise)

    <div class="modal fade set-modal-for-exercise assign_modal" id="bookingModal_{{$exercise->id}}" role="dialog" data-backdrop="static">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="
    background-color: orange!important;
">
                    <button type="button" class="modal-close close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{ $exercise->name_of_excercise }}</h4>
                </div>
                <div class="modal-body">

                    @php
                        $images=\App\SystemFile::where('model_id',$exercise->id)->where('model_type',get_class($exercise))->get();

                    @endphp
                    @foreach($images as $image)
                        <img src="{{ asset('../storage/app/').'/'.$image->disk_name }}" class="set-image">
                    @endforeach
                    <p>{!! $exercise->description !!}  </p>


                </div>
                <div class="modal-footer">
                    <button type="button" class="ui blue btn btn-default modal-close" data-dismiss="modal">Close
                    </button>
                </div>
            </div>

        </div>
    </div>
    @endforeach
@endsection
@section("js")
    <script>

        $('#bookingModal').on('click', function () {

            console.log($(this).attr('data-id'));
        });


        $("input[id^=assign_to_]").click(function () {

            var id = $(this).data('id');

            if ($(this).is(':checked')) {
                assignedtoPatient(id, 1);
            } else {
                assignedtoPatient(id, 0);
            }
        });

        function assignedtoPatient(id, state_id) {

            $.ajax({
                url: '{{route('Laralum::recommend-exercise.assign.ajax')}}',
                type: "POST",
                data: {
                    id: id,
                    booking_id: '{{$booking->id}}',
                    patient_id: '{{$booking->user_id}}',
                    _token: '{{csrf_token()}}',
                    state_id: state_id
                },
                cache: false,
                success: function (data) {

                }
            });


        }

    </script>




@endsection