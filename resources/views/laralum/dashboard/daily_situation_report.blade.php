@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">{{ trans('laralum.dashboard') }}</div>
    </div>
@endsection
@section('title', 'Daily Situation Report')
@section('icon', "pencil")
@section('subtitle', 'Daily Situation Report')
@section('content')
    <div class="ui one column doubling stackable grid">
        <div class="column ">
            <div class="ui very padded segment table_sec2 daily_report">
                <div class="page_title table_top_btn">
                    <h2 class="pull-left">Date- {{ date("d-m-Y") }}</h2>

                    <div class="pull-right btn-group">
                        <a class="btn btn-primary ui button no-disable"
                           href="{{ url('admin/daily-situation-report-print') }}">Print</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <p>1.Number of Cases received for consultation:</p>
                        </div>
                        <div class="col-md-6">
                            <p>General: {{ \App\OpdTokens::whereDate('date', date("Y-m-d"))->count() }}</p>
                            <p>Antyodaya:</p>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <hr>
                    <div class="col-md-12">
                        <div class="col-md-6 ">
                            <p>2.Number of Opds:</p>
                        </div>
                        <div class="col-md-6">
                                <p>General: {{ \App\Booking::where('patient_type', \App\Booking::PATIENT_TYPE_OPD)->whereDate('created_at', date("Y-m-d"))->count() }}</p>
                                <p>Antyodaya:</p>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <hr>
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <p>3.Number of Yoga participants for morning and evening batches separatly:</p>
                        </div>
                        <div class="col-md-6">
                            <p>Male:</p>
                            <p>Female:</p>
                            <p>Total:</p>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <hr>
                    <div class="col-md-12">
                        4.Number of Indoor Patients as on: {{ date("d-m-Y") }}<br>
                        both Male and Females
                    </div>
                    <div class="col-md-12">
                        <div class="table_head_lft clearfix">
                            <table class="ui table table_cus_v bs">
                                <thead>
                                <tr>
                                    <th>Building Name</th>
                                    <th>Male</th>
                                    <th>Female</th>
                                    <th>Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $male_count = 0; $female_count = 0;$total_count = 0; @endphp
                                @foreach(\App\Building::all() as $building)
                                    @php
                                        $males = $building->getMaleCount();
                                        $females = $building->getFemaleCount();
                                        $total = $males + $females;
                                        $male_count = $male_count+$males; $female_count = $female_count+$females;$total_count = $total_count + $total; @endphp
                                    <tr>
                                        <td>{{ $building->name }}</td>
                                        <td>{{ $males }}</td>
                                        <td>{{ $females }}</td>
                                        <td>{{ $total }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>Total</td>
                                    <td>{{ $male_count }}</td>
                                    <td>{{ $female_count }}</td>
                                    <td>{{ $total_count }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <hr>
                    <div class="col-md-6">
                        5.Advance booking cases with duration of stay
                    </div>
                    <div class="col-md-6">
                    </div>

                    @for($i = 0; $i < 10; $i ++ )
                        @php $date = date("Y-m-d", strtotime("+".$i." days")); @endphp
                        <div class="col-md-12">
                            <div class="data_col clearfix">
                                {{ date("d-m-Y", strtotime($date)) }}
                            </div>
                            <div class="table_head_lft clearfix">
                                <table class="ui table table_cus_v bs">
                                    <thead>
                                    <tr>
                                        <th>OPDs:</th>
                                        <th>
                                            {{ \App\OpdTokens::whereDate('date', date("Y-m-d"))->count() }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Building Name</th>
                                        <th>Male</th>
                                        <th>Female</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $male_count = 0; $female_count = 0;$total_count = 0; @endphp
                                    @foreach(\App\Building::all() as $building)
                                        @php
                                            $males = $building->getMaleCount($date);
                                            $females = $building->getFemaleCount($date);
                                            $total = $males + $females;
                                            $male_count = $male_count+$males; $female_count = $female_count+$females;$total_count = $total_count + $total; @endphp
                                        <tr>
                                            <td>{{ $building->name }}</td>
                                            <td>{{ $males }}</td>
                                            <td>{{ $females }}</td>
                                            <td>{{ $total }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>Total</td>
                                        <td>{{ $male_count }}</td>
                                        <td>{{ $female_count }}</td>
                                        <td>{{ $total_count }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
@endsection
