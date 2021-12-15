@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">{{ trans('laralum.kitchen_item_requirements_list') }}</div>
    </div>
@endsection
@section('title',  trans('laralum.kitchen_item_requirements'))
@section('icon', "pencil")
@section('subtitle', 'List of all Kitchen Item Requirements')
@section('content')

    <div class="ui one column doubling stackable">
        <div class="column">
            <div class="ui very padded segment table_header_row table-responsive" id="department_list">
                @include('laralum.kitchen_item.requirements_list')
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $(document).ready(function () {
            var date1 = new Date($('#date1').val());
            var date2 = new Date($('#date2').val());

            $('.daterange').daterangepicker({
                minDate: moment(),
                startDate:date1,
                endDate:date2,
                autoApply:true,
                /*locale: {
                    format:  'DD/MM/YYYY'
                }*/
            });

            $("#date").change(function () {
                var val = $(this).val();
                window.location = "{{ url('admin/kitchen-item/requirements') }}?daterange="+val;
            })
        })
    </script>
@endsection

