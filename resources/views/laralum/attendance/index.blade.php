    @extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Attendance List</div>
    </div>
@endsection
@section('title', 'Attendances')
@section('icon', "pencil")
@section('content')
    <link rel="stylesheet" href="{{ asset('css/daterangepicker.min.css') }}">
    <div class="ui one column doubling stackable grid">
        <div class="column">
            <form method="get" id="search-attendance">
                {{ csrf_field() }}
                <div class="row">
                    <div class="field  select_date_attand">
                        <label class="pull-left">Select Date: </label>

                        <div class="select_ddate">
                            <input type="text" required="" value="{{ $range }}" class="form-control pull-right" id="dom-id" name="dates">
                            <button type="submit" class="button ui pull-right">Go>></button>
                        </div>

                    </div>
                </div>
            </form>
            <div class="ui very padded segment table_header_row table-responsive bk_table" id="attendance_list">
                @include('laralum.attendance._list')
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script src="{{ asset('/js/jquery.daterangepicker.js') }}"></script>

    <script>
        $("#dom-id").change(function(){
            $("#search-attendance").submit();
        })
        var startdate = "{{ $from_date }}";
        var enddate = "{{ $to_date }}";
        var configObject = {
            format: 'YYYY-MM-DD',
            separator: ' to ',
            language: 'auto',
            startOfWeek: 'sunday',// or sunday
            getValue: function () {
                return this.value;
            },
            setValue: function (s) {
                this.value = s;
            },
            startDate: false,
            endDate: false,
            minDays: 0,
            maxDays: 7,
            showShortcuts: true,
            time: {
                enabled: false
            },
            shortcuts: {
                //'prev-days': [1,3,5,7],
                'next-days': [3, 5, 7],
                //'prev' : ['week','month','year'],
                'next': ['week', 'month', 'year']
            },
            customShortcuts: [],
            inline: false,
            container: 'body',
            alwaysOpen: false,
            singleDate: false,
            batchMode: false,
            stickyMonths: false,
            dateLimit: { days: 7 },
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

@endsection{{--
@section("js")
    <script>
        $( ".datepicker" ).datepicker({dateFormat: "dd-mm-yy", autoclose:true,});
    </script>
@endsection--}}



