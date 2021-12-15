@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::attendances') }}">{{ trans('laralum.attendance_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.attendance_create') }}</div>
    </div>
@endsection
@section('title', 'Mark Attendance')
@section('icon', "plus")
@section('subtitle', 'Attendance')
@section('content')
    <link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">


    <div class="ui one column doubling stackable">
        <div class="ui very padded segment">
            <div class="column about_sec hospital_info role_edt">
                <form id="attendance_form" class="form form_listing_table" method="POST" action="{{ route('Laralum::attendance.create') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="created_by" value="{{ Auth::id() }}">
                    {{-- <div class="field">
                        <label>Date</label>

                       <div class="ui fluid search selection dropdown" id="user_dropdown">
                            <input type="hidden" name="user_id" id="user_id">
                            <div class="default text">User</div>
                            <div class="menu">
                                --}}{{--<select name="user_id">--}}{{--
                                    @foreach(\App\User::all() as $user)
                                        --}}{{--<option value="{{ $user->id }}">{{ $user->name }}</option>--}}{{--
                                        <div class="item no-disable" data-value="{{ $user->id }}">{{ $user->name }}</div>
                                    @endforeach
                                --}}{{--</select>--}}{{--
                            </div>
                        </div>
                        </div>--}}
                    <div class="column">
                            <table class="ui table_cus_v table last_row_bdr">
                                <thead>
                                <tr>
                                    <th  style="width:30%;">Date
                                        <form method="get">
                                            {{ csrf_field() }}
                                            <input type="text" class="datepicker form-control" name="date_in" id="date_in" value="{{ $date }}">
                                        </form>
                                    </th>
                                    <th  style="width:30%;">Employee<br/>
                                        <input type="text" name="user_id" id="search" class="search form-control">
                                        {{--<div style="margin-top:9px;" class="ui fluid search selection dropdown" id="user_dropdown">
                                            <input type="hidden" name="user_id" id="user_id">
                                            <div class="default text">User</div>
                                            <div class="menu">
                                                --}}{{--<select name="user_id">--}}{{--
                                                @foreach(\App\User::all() as $user)
                                                    --}}{{--<option value="{{ $user->id }}">{{ $user->name }}</option>--}}{{--
                                                    <div class="item no-disable" data-value="{{ $user->id }}">{{ $user->name }}</div>
                                                @endforeach
                                                --}}{{--</select>--}}{{--
                                            </div>
                                        </div>--}}
                                    </th>
                                    <th style="width: 21%;text-align: center">Department<br/>
                                        <select name="department_id" class="search form-control" id="department_search" >
                                            <option value="">All</option>
                                            @foreach(\App\StaffDepartment::all() as $department)
                                            <option value="{{ trim($department->title) }}">{{$department->title}}</option>
                                            @endforeach
                                        </select>
                                        </th>
                                    <th>Mark Attendance</th>
                                    <!--th>Leave Dates</th>
                                    <th>Actions</th-->
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td id="selected_date">{{ $date }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->staffDepartment->title}}</td>
                                        <td id="status_{{ $user->id }}" style="width: 21%;text-align: center">
                                            @if($user->attendance($date))
                                                {!! $user->attendance($date) !!}
                                            @else
                                                @if($date == date("Y-m-d"))
                                                    <input type="hidden" id="same_date" value="true">

                                                    <div class="pymnt_opt_row row_inline">
                                                        <div class="pymt_inn">
                                                            <input type="radio" name="status_{{ $user->id }}" id="statuspresent_{{ $user->id }}" value="{{ \App\Attendance::STATUS_PRESENT }}">
                                                            <span> P&nbsp;</span>
                                                        </div>
                                                    &nbsp;</div>

                                                    <div class="pymnt_opt_row row_inline">
                                                        <div class="pymt_inn">
                                                            <input type="radio" name="status_{{ $user->id }}" id="statusabsent_{{ $user->id }}" value="{{ \App\Attendance::STATUS_ABSENT }}">
                                                            <span>A</span>
                                                        </div>
                                                    </div>&nbsp;&nbsp;
                                                    &nbsp;
                                                    <div class="pymnt_opt_row row_inline">
                                                        <div class="pymt_inn">
                                                            <input type="radio" name="status_{{ $user->id }}" id="statusleave_{{ $user->id }}" value="{{ \App\Attendance::STATUS_LEAVE }}">
                                                            <span>L</span>
                                                        </div>
                                                    </div>

                                                @endif
                                                <span id="leave_comment_div_{{ $user->id }}" style="display: none;">
                                                    <input placeHolder="Leave Comment" id="leave_comment_{{ $user->id }}" type="text" name="comment" id="comment">
                                                        <span style="cursor: pointer;" onClick="submit({{ $user->id }})" id="save_{{ $user->id }}">
                                                            <i class="fa fa-check"></i>Save
                                                        </span>
                                                </span>
                                                <span class="time_input_div" id="present_time_div_{{ $user->id }}" style="display: none;">
                                                    <input class="timepicker" placeHolder="Time In" id="time_in_{{ $user->id }}" type="text" name="time_in" value="09:00"> <input class="timepicker" placeHolder="Time Out" id="time_out_{{ $user->id }}" type="text" name="Time In" value="17:00">
                                                    <span style="cursor: pointer;" onClick="submit('{{ $user->id }}')">
                                                        <i class="fa fa-check"></i>Save
                                                    </span>
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <br>
                    {{--<div class="field @if($error) error @endif">
                        <label>User</label>
                        <input type="{{ $input_type }}"  id="{{ $field }}" name="{{ $field }}" placeholder="{{ $show_field }}" value="{{ $value }}">
                        @if($error)
                            <div class="ui pointing red basic label">
                                {{ $error }}
                            </div>
                        @endif
                    </div>
                    <div class="field @if($error) error @endif">
                        <label>{{ $show_field }}</label>
                        <input type="{{ $input_type }}"  id="{{ $field }}" name="{{ $field }}" placeholder="{{ $show_field }}" value="{{ $value }}">
                        @if($error)
                            <div class="ui pointing red basic label">
                                {{ $error }}
                            </div>
                        @endif
                    </div>--}}
                    {{--<button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>--}}
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script>
    {{--<script  src="{{ asset('/laralum_public/js/bootstrap.datetimepicker.js') }}"></script>--}}
    <script src="{{ asset('/js/jquery.timepicker.js') }}"></script>
    <script>

        $(function () {
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': $('[name="_token"]').val()}
            });
        });


        $(".datepicker").change(function () {
            console.log('ds');
            var date = {'date': $(this).val()};
            var url = '{{ url('admin/attendance/create') }}';
            location.replace(url + '/date/' + date);
        });

        function submit(id) {
            var val = $("[name=status_" + id + "]").val();
            if ($("#statuspresent_" + id).is(":checked")) {
                var val = "{{ \App\Attendance::STATUS_PRESENT }}"
            }

            if ($("#statusabsent_" + id).is(":checked")) {
                var val = "{{ \App\Attendance::STATUS_ABSENT }}"
            }

            if ($("#statusleave_" + id).is(":checked")) {
                var val = "{{ \App\Attendance::STATUS_LEAVE }}"
            }

            var comment = $("#leave_comment_" + id).val();
            var time_in = $("#time_in_" + id).val();
            var time_out = $("#time_out_" + id).val();
            var date = '{{ $date }}';
            var post_data = {
                'user_id': id,
                'status': val,
                'comment': comment,
                'time_in': time_in,
                'time_out': time_out,
                'date_in': date
            };
            //console.log(post_data); return false;
            $.ajax({
                url: $("#attendance_form").attr('action'),
                type: "POST",
                data: post_data,
                success: function (response) {
                    $("#status_" + id).html(response.status);
                    $('.timepicker').each(function () {
                        $(this).timePicker({
                                    startTime: new Date(0, 0, 0, 08, 0, 0), // 3:00:00 PM - noon
                                    endTime: new Date(0, 0, 0, 20, 0, 0), // 3:00:00 PM - noon
                                    interval: 5
                                }
                        );
                    });

                }
            })
        }
        $(document).ready(function () {
            $(document).delegate('[name^=status_]', 'click', function (e) {
                var id = $(this).attr('name').split('status_')[1];
                var val = $(this).val();

                if (val == '{{ \App\Attendance::STATUS_LEAVE}}') {
                    $("#leave_comment_div_" + id).show();
                } else if (val == '{{ \App\Attendance::STATUS_PRESENT }}') {
                    $("#present_time_div_" + id).show();
                    $("#leave_comment_div_" + id).hide();
                    $("#leave_comment_" + id).val("");
                    $('.timepicker').each(function () {
                        $(this).timePicker({
                                    startTime: new Date(0, 0, 0, 08, 0, 0), // 3:00:00 PM - noon
                                    endTime: new Date(0, 0, 0, 20, 0, 0), // 3:00:00 PM - noon
                                    interval: 5 // 15 minutes
                                    /*  show24Hours: true,
                                     separator: ':',
                                     step: 5*/
                                }
                        );
                    });
                } else {
                    $("#present_time_div_" + id).hide();
                    $("#leave_comment_div_" + id).hide();
                    $("#leave_comment_" + id).val("");
                    submit(id);
                }
                console.log(val + '--' + id);
            });

            $(document).delegate('[id^=save_]', 'click', function (e) {
                var id = $(this).attr('id').split('save_')[1];
                submit(id);
            });
            $(".datepicker").datepicker({
                dateFormat: "dd-mm-yy", autoclose: true, changeMonth: true,
                changeYear: true,
                onClose: function () {
                    var date = $('.datepicker').val();
                    var url = '{{ url('admin/attendance/create') }}';
                    location.replace(url + '/date/' + date);
                }
            });

            /* $(".datepicker").datetimepicker({
             format: 'YYYY-MM-DD',
             icons: {
             time: "fa fa-clock-o",
             date: "fa fa-calendar",
             up: "fa fa-arrow-up",
             down: "fa fa-arrow-down",
             next: "fa fa-arrow-right",
             previous: "fa fa-arrow-left"
             }
             }).on('dp.change',function(e){
             var date = $('.datepicker').val();
             var url =  '{{ url('admin/attendance/create') }}';
             location.replace(url+'/date/'+ date);
             });*/
        });

        $.expr[":"].contains = $.expr.createPseudo(function (arg) {
            return function (elem) {
                return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
            };
        });

        
        $(".search").keyup(function () {
console.log("jhh");
            //split the current value of searchInput

            var data = $(this).val().split(" ");
            //create a jquery object of the rows
            var jo = $("tbody").find("tr");
            if ($(this).val() == "") {
                jo.show();
                return;
            }
            //hide all the rows
            jo.hide();

            //Recusively filter the jquery object to get results.
            jo.filter(function (i, v) {
                var $t = $(this);
                for (var d = 0; d < data.length; ++d) {
                    if ($t.is(":contains('" + data[d] + "')")) {
                        return true;
                    }
                }
                return false;
            })
            //show the rows that match.
                    .show();
        }).focus(function () {
            $(this).val("");
            $(this).css({
                "color": "black"
            });
            $(this).unbind('focus');
        }).css({
            "color": "#C0C0C0"
        });

        $(".search").change(function () {
            console.log("jhh");
            //split the current value of searchInput

            var data = $(this).val().split(" ");
            //create a jquery object of the rows
            var jo = $("tbody").find("tr");
            if ($(this).val() == "") {
                jo.show();
                return;
            }
            //hide all the rows
            jo.hide();

            //Recusively filter the jquery object to get results.
            jo.filter(function (i, v) {
                var $t = $(this);
                for (var d = 0; d < data.length; ++d) {
                    if ($t.is(":contains('" + data[d] + "')")) {
                        return true;
                    }
                }
                return false;
            })
            //show the rows that match.
                    .show();
        }).focus(function () {
            $(this).val("");
            $(this).css({
                "color": "black"
            });
            $(this).unbind('focus');
        }).css({
            "color": "#C0C0C0"
        });

        $(document).delegate("[id^=edit_]", 'click', function () {
            var id = $(this).attr('id').split('edit_')[1];
            var p_checked = $("#selected_state_" + id).val() == '{{ \App\Attendance::STATUS_PRESENT }}' ? 'checked' : '';
            var a_checked = $("#selected_state_" + id).val() == '{{ \App\Attendance::STATUS_ABSENT }}' ? 'checked' : '';
            var l_checked = $("#selected_state_" + id).val() == '{{ \App\Attendance::STATUS_LEAVE }}' ? 'checked' : '';

            var p_checked = $("#selected_state_" + id).val() == '{{ \App\Attendance::STATUS_PRESENT }}' ? 'checked' : '';

            var display = l_checked ? 'block' : 'none';
            var display_p = p_checked ? 'block' : 'none';
            var comment = $("#comment_" + id).attr('title');
            var time_in = $("#time_in_val_" + id).val();
            var time_out = $("#time_out_val_" + id).val();

            $("#status_" + id).html('<input type="radio" name="status_' + id + '" id="statuspresent_' + id + '" value="{{ \App\Attendance::STATUS_PRESENT }}" ' + p_checked + '>P&nbsp;&nbsp;&nbsp;<input type="radio" name="status_' + id + '" id="statusabsent_' + id + '" value="{{ \App\Attendance::STATUS_ABSENT }}" ' + a_checked + '>A&nbsp;&nbsp;&nbsp;<input type="radio" name="status_' + id + '" id="statusleave_' + id + '"  value="{{ \App\Attendance::STATUS_LEAVE }}" ' + l_checked + '>L<span id="leave_comment_div_' + id + '" style="display: ' + display + ';"> <input placeHolder="Leave Comment" id="leave_comment_' + id + '" type="text" name="comment" id="comment" value="' + comment + '"> <span style="cursor: pointer;" onClick="submit(' + id + ')" id="save_' + id + '"><i class="fa fa-check"></i>Save</span></span><span class="time_input_div" id="present_time_div_' + id + '" style="display: ' + display_p + ';"> <input placeHolder="Time In" id="time_in_' + id + '" type="text" class="timepicker" name="time_in" value="' + time_in + '"><input placeHolder="Time Out" id="time_out_' + id + '" type="text" name="time_out" class="timepicker" value="' + time_out + '"> <span style="cursor: pointer;" onClick="submit(' + id + ')" id="save_' + id + '"><i class="fa fa-check"></i>Save</span></span>');

            $('.timepicker').each(function () {
                $(this).timePicker({
                            startTime: new Date(0, 0, 0, 08, 0, 0), // 3:00:00 PM - noon
                            endTime: new Date(0, 0, 0, 20, 0, 0), // 3:00:00 PM - noon
                            interval: 5
                        }
                );
            });


        });


    </script>

@endsection