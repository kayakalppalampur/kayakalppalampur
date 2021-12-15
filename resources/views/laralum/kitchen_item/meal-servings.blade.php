@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Daily Meal Serving List</div>
    </div>
@endsection
@section('title', 'Daily Meal Servings')
@section('icon', "pencil")
@section('content')
    <style>
        table {
            table-layout: fixed;
        }
    </style>
    <div class="ui one column doubling stackable grid container">
        <div class="column">
            <section class="booking_filter booking_search_patient ui padded segment">
                <div class="row">
                    <div class="col-md-12">
                        <div class="about_sec white_bg signup_bg">
                            <div class="patient_head2">
                                <h5 class="title_3 pull-right">SELECT MEAL TYPE</h5>
                            </div>
                            <form id="bookingFilter" method="POST">
                                {{ csrf_field() }}

                                <div class="form-group">
                                    <input type="text" name="start_date" placeholder="Default Date"
                                           class="form-control datepicker" value="{{ date("d-m-Y") }}"/>
                                </div>

                                <div class="form-group">
                                    <select class="form-control" name="filter_meal_type" id="filter_meal_type">
                                        <option value="">SELECT MEAL TYPE</option>
                                        @foreach(\App\DietChartItems::getTypeOptions() as $key => $type)
                                            <option {{ $meal_type == $key ? "selected" : "" }} value="{{ $key }}"> {{ $type }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-button_row">
                                    <button class="ui button no-disable blue">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        @if(!isset($print))
            <div class="column">
                <div class="btn-group pull-right meal_btns">
                    <div class="item no-disable">
                        <a style="color:white" onclick="refresh()">
                            <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                        class="refresh icon"></i><span class="text responsive-text">Refresh</span></div>
                        </a>
                         <a style="color:white" href="{{url('/admin/diet-chart')}}">
                            <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                        class="check icon"></i><span class="text responsive-text">Meal Served Status</span></div>
                        </a>
                        <a style="color:white" id="print_url" href="{{ $print_url }}">
                            <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                        class="print icon"></i><span class="text responsive-text">Print</span></div>
                        </a>
                        <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                            <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                            <div class="menu">
                                <a id="clicked" class="item no-disable export_csv_url" href="{{$export_csv_url}}">Export
                                    as CSV
                                </a>
                                <a id="clicked" class="item no-disable export_pdf_url" href="{{ $export_pdf_url }}">Export
                                    as PDF
                                </a>
                                <a id="clicked" class="item no-disable export_xsl_url" href="{{ $export_xsl_url }}">Export
                                    as Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="meal-servings">
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(".datepicker").datepicker({dateFormat: "dd-mm-yy", autoclose: true, minDate: 0});

        refresh();
         $("#filter_meal_type").change(function () {
            refresh();
        });
        function refresh() {
            var val = $("#filter_meal_type").val();
            var date = $(".datepicker").val();
            $.ajax({
                url: "{{ url('get-meal-servings-ajax') }}",
                type: "POST",
                data: {'filter_meal_type': val, '_token': "{{ csrf_token() }}", 'date' : date},
                success: function (response) {
                    enablePage();
                    $("#print_url").attr('href', response.print_url);
                    $(".export_pdf_url").attr('href', response.export_pdf_url);
                    $(".export_csv_url").attr('href', response.export_csv_url);
                    $(".export_xsl_url").attr('href', response.export_xsl_url);
                    $(".meal-servings").html(response.html);
                }
            })
        }

        $(document).delegate(".toggle-state-checkbox", 'change', function () {
            var form = $(this).parent().parent().find('form');
            console.log('action' + form);
            $.ajax({
                url: form.attr('action'),
                type: "POST",
                data: form.serialize(),
                success: function (response) {
                    refresh();
                    enablePage();
                }
            })
        })

      //  setInterval(function(){ refresh() }, 5000);


        $(document).on("click","input[id^=meal-served_]",function () {
            if ($(this).is(':checked')) {
                var id=$(this).attr('data-id');
                var meal_type=$(this).attr('data-meal-type');
                addtoServedList(id,meal_type);
            }
        });
        function addtoServedList(id,meal_type){
           var url='{{ url('admin/kitchen-item/meal-served') }}';
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    meal_type:meal_type,
                    id:id,
                    '_token': "{{ csrf_token() }}"
                },

                success: function (response) {

                }
            })
        }

    </script>
@endsection



