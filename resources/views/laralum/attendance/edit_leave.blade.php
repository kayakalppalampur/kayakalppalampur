@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::attendance.leaves') }}">Leaves</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.add_leave') }}</div>
    </div>
@endsection
@section('title', 'Add Leave')
@section('icon', "plus")
@section('subtitle', 'Attendance')
@section('content')

    {{--<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">--}}
    <link rel="stylesheet" href="{{ asset('css/daterangepicker.min.css') }}">
    {{--<link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">--}}
    {{--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.16.0/moment.min.js"></script>--}}
    {{--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>--}}


<div class="ui one column doubling stackable">
    <div class="ui very padded segment">
        <div class="column about_sec hospital_info role_edt">
            <form id="attendance_form" class="form form form_cond_lft" method="POST" action="{{ route('Laralum::leave_edit_store', ['id' => $leave->id ]) }}">
                {{ csrf_field() }}
                    <input type="hidden" name="created_by" value="{{ \Auth::id() }}">
                    <div class="field">
                        <select name="user_id" class="form-control">
                            @foreach(\App\Staff::all() as $all_user)
                                <option value="{{ $all_user->id }}" {{ $leave->user_id == $all_user->id ? 'selected' : "" }}>{{ $all_user->name }}</option>
                                    <div class="item no-disable" data-value="{{ $all_user->id }}">{{ $all_user->name }}</div>
                                @endforeach
                        </select>
                    </div>
                {{--<input type="hidden" name="user_id" value="{{ $user->id }}">--}}
                    <div class="field">
                        <textarea type="text" name="comment" id="comment" class="form-control" placeHolder="Add Comment">{!! $leave->comment !!}</textarea>
                    </div>
                    <div class="field">
                        <input type="text" name="dates" id="dom-id" class="form-control" value="{{ $leave->date_start_date.' to '.$leave->date_end_date }}" required>
                    </div>
                    <div class="form-button_row">
                        <button type="submit" class="ui blue submit button">Add Leave</button>
                    </div>
                </form>
        </div>
</div>
</div>
@endsection
@section('js')
    <script  src="{{ asset('/js/jquery.daterangepicker.js') }}"></script>

    <script>
        var configObject = {
            format: 'DD-MM-YYYY',
                    separator: ' to ',
                language: 'auto',
                startOfWeek: 'sunday',// or sunday
                getValue: function()
        {
            return this.value;
        },
            setValue: function(s)
            {
                this.value = s;
            },
            startDate: false,
                    endDate: false,
                minDays: 0,
                maxDays: 0,
                showShortcuts: true,
                time: {
            enabled: false
        },
            shortcuts:
            {
                //'prev-days': [1,3,5,7],
                'next-days': [3,5,7],
                    //'prev' : ['week','month','year'],
                    'next' : ['week','month','year']
            },
            customShortcuts : [],
                    inline:false,
                container: 'body',
                alwaysOpen:false,
                singleDate:false,
                batchMode:false,
                stickyMonths: false
        };
        $('#dom-id').dateRangePicker(configObject);
       /* $('.input-daterange input').each(function() {
            $(this).datepicker('clearDates');
        });
        $(document).ready(function () {
            $("#datepicker").datetimepicker({
                format: 'YYYY-MM-DD',
                minDate: moment(),
                icons: {
                    time: "fa fa-clock-o",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down",
                    next: "fa fa-arrow-right",
                    previous: "fa fa-arrow-left"
                }
            });
        });*/
    </script>

@endsection