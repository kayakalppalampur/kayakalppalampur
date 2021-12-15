@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">{{ trans('laralum.dashboard') }}</div>
    </div>
@endsection
@section('title', 'Daily Building Status')
@section('icon', "pencil")
@section('subtitle', 'Daily Building Status')
@section('content')
    <div class="ui one column doubling stackable grid">

        <div class="column">
            <section class="booking_filter booking_search_patient ui padded segment">
                <div class="row">
                    <div class="col-md-12">
                        <div class="about_sec white_bg signup_bg">
                            <form id="bookingFilter" method="post">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <select name="building_id" class="form-control">
                                        <option value="">Select Builidng</option>
                                        @foreach(\App\Building::all() as $building_model)
                                            <option value="{{ $building_model->id }}" {{ $building_model->id  == $building_id ? 'selected' : ''}} >{{ $building_model->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="date" placeholder="Default Date"
                                           class="form-control datepicker" value="{{ $date}}"/>
                                    <button class="ui button no-disable blue">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="column ">
            <div class="ui very padded segment table_sec2">
                <div class="page_title table_top_btn">
                    <h2 class="pull-left">Date- {{ $date }}</h2>

                    <div class="pull-right btn-group">
                        <a class="btn btn-primary ui button no-disable"
                           href="{{ url('admin/daily-building-status-print', ['date' => $date, 'building_id' => $building_id]) }}">Print</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 table_head_lft">
                        <table class="ui table table_cus_v bs">

                            <tbody>
                            @php $male_count = 0; $female_count = 0;$total_count = 0; @endphp
                            {{--@foreach(\App\Building::all() as $building)--}}
                                @php
                                    $males = $building->getMaleCount($date);
                                    $females = $building->getFemaleCount($date);
                                    $total = $males + $females;
                                    $male_count = $male_count+$males; $female_count = $female_count+$females;$total_count = $total_count + $total;
                                 $bookings = $building->getBookings($date);@endphp
                                <thead>
                                <tr>
                                    <th>Building Name: {{ $building->name }}</th>
                                    <th>Male: {{ $males }}</th>
                                    <th>Female: {{ $females }}</th>
                                    <th>Total:{{ $total }}</th>
                                </tr>
                                </thead>
                                @if ($bookings->count() > 0)
                                    <tr>
                                        <td colspan="4">
                                            <table class="ui table table_cus_v bs">
                                                <thead>
                                                <th>Floor</th>
                                                <th>Room No.</th>
                                                <th>Booked By</th>
                                                <th>Check In Date</th>
                                                <th>Check Out Date</th>
                                                </thead>
                                                <tbody>
                                                @foreach($bookings as $booking)
                                                    <tr>
                                                        <td>{{ $booking->room->floor_number }}</td>
                                                        <td>{{ $booking->room->room_number  }}</td>
                                                        <td>{{ $booking->alloted_to }}</td>
                                                        <td>{{ $booking->check_in_date }}</td>
                                                        <td>{{ $booking->check_out_date }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>

                                        </td>
                                    </tr>
                                    @else
                                            <tr>
                                                <td colspan="4">No Bookings in this building</td>
                                            </tr>
                                    @endif


                                    {{--@endforeach--}}
                                    {{--<tr>
                                        <td>Total</td>
                                        <td>{{ $male_count }}</td>
                                        <td>{{ $female_count }}</td>
                                        <td>{{ $total_count }}</td>
                                    </tr>--}}
                                    </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $(".datepicker").datepicker({dateFormat: "dd-mm-yy", autoclose: true});
    </script>
@endsection