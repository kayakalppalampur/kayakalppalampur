@if(!empty($diet_chart))
    <div class="column">
        <div class="ui very padded segment">
            <div class="col-md-12 table-responsive">
                <table class="ui table table_cus_v last_row_bdr">
                {{--<table class="ui table_cus_v table " style="width: 100%">--}}
                    <thead>
                    <tr>
                        <th>Sno</th>
                        <th>Patient Id</th>
                        <th>Patient Name</th> @for($i = 1; $i <= 7; $i++)
                            <th>Item {{ $i }}</th> @endfor
                        <th>Notes</th>
                        <th>Meal Served</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($diet_chart as $diet)
                        <tr>
                            <td>{{ $diet['sno'] }}</td>
                            <td>{{ $diet['kid'] }}</td>
                            <td>{{ $diet['patient_name'] }}</td>
                            @for($i = 1; $i <= 7; $i++)
                                <td>{{ $diet['item_'.$i] }}</td>
                            @endfor
                            <td>{{ $diet['notes'] }}</td>
                            <td>{{ $diet['is_served'] }}

                                {{--@if(isset($diet['id']))
                                    @if(!isset($print))
                                        <form class="togle-state" method="POST"
                                              action="{{ url('/admin/diet-chart/toggle-state/'.$diet['id']) }}">
                                            {!! csrf_field() !!} <input
                                                    name="{{ \App\DietDailyStatus::getDietAttribute($meal_type) }}"
                                                    {{ \App\DietChart::getCurrentTimeClass($meal_type) ? "" : "" }} type="checkbox"
                                                    class="toggle-state-checkbox"
                                                    @if(\App\DietChart::getDietStatusStatic($diet['id'], $meal_type)) checked
                                                    @endif
                                                    value="{{ \App\DietDailyStatus::STATUS_DONE .'-'.\App\DietChart::getDietPriceStatic($diet['id'], $meal_type) }}">
                                        </form>
                                    @else
                                        @if(\App\DietChart::getDietStatusStatic($diet['id'], $meal_type))
                                            Yes
                                        @else
                                            No
                                        @endif
                                    @endif
                                @else
                                    @if(isset($print)) No @else -- @endif
                                @endif--}}
                            </td>
                            <td>

                                <input type="checkbox" name="meal-served" id="meal-served_{{$diet['booking_id']}}"
                                       data-id="{{$diet['diet_id']}}" data-meal-type="{{$meal_type}}">


                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
@endif

