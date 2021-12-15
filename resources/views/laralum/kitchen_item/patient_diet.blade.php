@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <!-- <a class="section" href="{{ route('Laralum::kitchen-items') }}">{{ trans('laralum.kitchen_item_list') }}</a>
        <i class="right angle icon divider"></i> -->
        <div class="active section">{{  trans('laralum.generate_token') }}</div>
    </div>
@endsection
@section('title', 'Patient Diet')
@section('icon', "pencil")
@section('subtitle', 'Patient Diet')
@section('content')
    <div class="ui one column doubling stackable">

        <div class="column">
            <section class="booking_filter booking_search_patient ui padded segment">
                <div class="row">
                    <div class="search_patient_con  signup_bg">

                        <h3 class="title_3">SEARCH PATIENT</h3>

                        <div class="form-wrap">
                            <div class="search-patient-wrap">
                                <div class="head-tag-search">
                                    <p>SEARCH PATIENT</p>
                                </div>
                                <form id="bookingFilter" action="{{ route('Laralum::patient-diet-chart') }}"
                                      method="POST">
                                    {{ csrf_field() }}
                                    {{--  <div class="form-group">
                                      <label>Barcode</label>
                                          <input class="user_namer form-control required" type="text" id="filter_bar_code" value="{{ @$_REQUEST['filter_bar_code'] }}" name="filter_bar_code" autofocus>
                                      </div>--}}
                                    <div class="form-group">
                                        <label>UHID</label>
                                        <input class="user_last form-control required" type="text"
                                               id="filter_uh_id" value="{{ @$_REQUEST['filter_uh_id'] }}"
                                               name="filter_uh_id">
                                    </div>
                                    <div class="form-group">
                                        <label>Registration ID</label>
                                        <input class="user_last form-control required" type="text"
                                               id="filter_patient_id" value="{{ @$_REQUEST['filter_patient_id'] }}"
                                               name="filter_patient_id">
                                    </div>

                                    <div class="form-group">
                                        <label>Email ID</label>
                                        <input class="user_email form-control required" type="email"
                                               id="filter_email" value="{{ @$_REQUEST['filter_email'] }}"
                                               name="filter_email">
                                    </div>
                                    <div class="form-group">
                                        <label>Mobile No.</label>
                                        <input class="user_password form-control required" type="text"
                                               name="filter_mobile" value="{{ @$_REQUEST['filter_mobile'] }}"
                                               id="filter_mobile">
                                    </div>
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input class="user_namee form-control required" type="text"
                                               name="filter_name" id="filter_name"
                                               value="{{ @$_REQUEST['filter_name'] }}">
                                    </div>
                                    <div class="form-button_row">
                                        <button class="ui button no-disable blue">Search</button>
                                    </div>
                                </form>
                            </div>
                            <div class="token-form-wrap">
                                @if(isset($diet->booking->userProfile->kid))
                                    <form class="token" method="POST"
                                          action="{{ url('/admin/diet-chart/toggle-state/'.$diet->id) }}">
                                        {!! csrf_field() !!}
                                        <div class="header">Searched
                                            Patient: {{ isset($diet->booking->userProfile->first_name) ? $diet->booking->userProfile->first_name.' '.$diet->booking->userProfile->last_name : "" }}
                                            ({{ $diet->booking->userProfile->kid }})
                                        </div>

                                        <div class="bg-head-diet">Today's diet</div>

                                        <div class="">Notes: {!! $diet->notes !!}</div>
                                        <div class="breakfast {{ \App\DietChart::getCurrentTimeClass(\App\DietChartItems::TYPE_BREAKFAST) ? "active-class" : "" }}">

                                            <div class="diet-today-detail">
                                                <table>
                                                    <thead>
                                                    <th colspan="3">
                                                        <div class="name-diet"> >>Breakfast
                                                            ({{ \App\DietChartItems::getTimes(\App\DietChartItems::TYPE_BREAKFAST) }}
                                                            )
                                                        </div>
                                                    </th>
                                                    </thead>
                                                    <tbody>
                                                    <tr>

                                                        <td width="90%">
                                                            <div class="breakfast-items diet-con">
                                                                @foreach($diet->getItems(\App\DietChartItems::TYPE_BREAKFAST) as $breakfast)
                                                                    <p class="diet-item-list">
                                                                        item-{{ $loop->iteration }}</p>
                                                                    <p class="diet-item-name">{{ isset($breakfast->item->name) ? $breakfast->item->name : $breakfast->item_id }}</p>
                                                                @endforeach

                                                            </div>
                                                        </td>
                                                        <td width="30" align="center">
                                                            <div class="breakfast-status diet-con">
                                                                <input {{ \App\DietChart::getCurrentTimeClass(\App\DietChartItems::TYPE_BREAKFAST) ? "" : "" }} type="checkbox"
                                                                       name="is_breakfast"
                                                                       @if($diet->getDietStatus(\App\DietChartItems::TYPE_BREAKFAST)) checked
                                                                       @endif value="{{ \App\DietDailyStatus::STATUS_DONE .'-'.$diet->getDietPrice(\App\DietChartItems::TYPE_BREAKFAST) }}">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>


                                        <div class="lunch {{ \App\DietChart::getCurrentTimeClass(\App\DietChartItems::TYPE_LUNCH) ? "active-class" : "" }}">
                                            <div class="diet-today-detail">
                                                <table>
                                                    <thead>
                                                    <th colspan="3">
                                                        <div class="name-diet"> >>Lunch
                                                            ({{ \App\DietChartItems::getTimes(\App\DietChartItems::TYPE_LUNCH)}}
                                                            )
                                                        </div>
                                                    </th>
                                                    </thead>
                                                    <tbody>
                                                    <tr>

                                                        <td width="90%">
                                                            <div class="lunch-items diet-con">
                                                                @foreach($diet->getItems(\App\DietChartItems::TYPE_LUNCH) as $lunch)
                                                                    <p class="diet-item-list">
                                                                        item-{{ $loop->iteration }}</p>
                                                                    <p class="diet-item-name">{{ isset($lunch->item->name) ? $lunch->item->name : $breakfast->item_id }}</p>
                                                                @endforeach

                                                            </div>
                                                        </td>
                                                        <td width="30" align="center">
                                                            <div class="lunch-status diet-con">
                                                                <input {{ \App\DietChart::getCurrentTimeClass(\App\DietChartItems::TYPE_LUNCH) ? "" : "" }} @if($diet->getDietStatus(\App\DietChartItems::TYPE_LUNCH)) checked
                                                                       @endif type="checkbox" name="is_lunch"
                                                                       value="{{ \App\DietDailyStatus::STATUS_DONE .'-'.$diet->getDietPrice(\App\DietChartItems::TYPE_LUNCH) }}">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>


                                        <div class="post-lunch {{ \App\DietChart::getCurrentTimeClass(\App\DietChartItems::TYPE_POST_LUNCH) ? "active-class" : "" }}">
                                            <div class="diet-today-detail">
                                                <table>
                                                    <thead>
                                                    <th colspan="3">
                                                        <div class="name-diet"> >>Post Lunch
                                                            ({{ \App\DietChartItems::getTimes(\App\DietChartItems::TYPE_POST_LUNCH) }}
                                                            )
                                                        </div>
                                                    </th>
                                                    </thead>
                                                    <tbody>
                                                    <tr>

                                                        <td width="90%">
                                                            <div class="post-lunch-items diet-con">
                                                                @foreach($diet->getItems(\App\DietChartItems::TYPE_POST_LUNCH) as $post_lunch)
                                                                    <p class="diet-item-list">
                                                                        item-{{ $loop->iteration }}</p>
                                                                    <p class="diet-item-name">{{ isset($post_lunch->item->name) ? $post_lunch->item->name : $post_lunch->item_id }}</p>
                                                                @endforeach
                                                            </div>
                                                        </td>
                                                        <td width="30" align="center">
                                                            <div class="post-lunch-status diet-con">
                                                                <input {{ \App\DietChart::getCurrentTimeClass(\App\DietChartItems::TYPE_POST_LUNCH) ? "" : "" }} @if($diet->getDietStatus(\App\DietChartItems::TYPE_POST_LUNCH)) checked
                                                                       @endif type="checkbox" name="is_post_lunch"
                                                                       value="{{ \App\DietDailyStatus::STATUS_DONE .'-'.$diet->getDietPrice(\App\DietChartItems::TYPE_POST_LUNCH)  }}">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="dinner {{ \App\DietChart::getCurrentTimeClass(\App\DietChartItems::TYPE_DINNER) ? "active-class" : "" }}">
                                            <div class="diet-today-detail">
                                                <table>
                                                    <thead>
                                                    <th colspan="3">
                                                        <div class="name-diet"> >>Dinner
                                                            ({{ \App\DietChartItems::getTimes(\App\DietChartItems::TYPE_DINNER) }}
                                                            )
                                                        </div>
                                                    </th>
                                                    </thead>
                                                    <tbody>
                                                    <tr>

                                                        <td width="90%">
                                                            <div class="dinner-items diet-con">
                                                                @foreach($diet->getItems(\App\DietChartItems::TYPE_DINNER) as $dinner)
                                                                    <p class="diet-item-list">
                                                                        item-{{ $loop->iteration }}</p>
                                                                    <p class="diet-item-name">{{ isset($dinner->item->name) ? $dinner->item->name : $dinner->item_id }}</p>
                                                                @endforeach

                                                            </div>
                                                        </td>
                                                        <td width="30" align="center" align="center">
                                                            <div class="dinner-status diet-con">
                                                                <input {{ \App\DietChart::getCurrentTimeClass(\App\DietChartItems::TYPE_DINNER) ? "" : "" }} @if($diet->getDietStatus(\App\DietChartItems::TYPE_DINNER)) checked
                                                                       @endif type="checkbox" name="is_dinner"
                                                                       value="{{ \App\DietDailyStatus::STATUS_DONE .'-'.$diet->getDietPrice(\App\DietChartItems::TYPE_DINNER) }}">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>


                                        <div class="special {{ \App\DietChart::getCurrentTimeClass(\App\DietChartItems::TYPE_SPECIAL) ? "active-class" : "" }}">

                                            <div class="diet-today-detail">

                                                <table>
                                                    <thead>
                                                    <th colspan="3">
                                                        <div class="name-diet"> >>Special
                                                            ({{ \App\DietChartItems::getTimes(\App\DietChartItems::TYPE_SPECIAL) }}
                                                            )
                                                        </div>
                                                    </th>
                                                    </thead>
                                                    <tbody>
                                                    <tr>

                                                        <td width="90%">
                                                            <div class="special-items diet-con">
                                                                @foreach($diet->getItems(\App\DietChartItems::TYPE_SPECIAL) as $special)
                                                                    <p class="diet-item-list">
                                                                        item-{{ $loop->iteration }}</p>
                                                                    <p class="diet-item-name">{{ isset($special->item->name) ? $special->item->name : $special->item_id }}</p>
                                                                @endforeach

                                                            </div>
                                                        </td>
                                                        <td width="30" align="center">
                                                            <div class="special-status diet-con">
                                                                <input {{ \App\DietChart::getCurrentTimeClass(\App\DietChartItems::TYPE_SPECIAL) ? "" : "" }} @if($diet->getDietStatus(\App\DietChartItems::TYPE_SPECIAL)) checked
                                                                       @endif type="checkbox" name="is_special"
                                                                       value="{{ \App\DietDailyStatus::STATUS_DONE .'-'.$diet->getDietPrice(\App\DietChartItems::TYPE_SPECIAL)  }}">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>


                                            </div>

                                        </div>


                                        @if($diet->id != null)
                                            <div class="field">
                                                <button type="submit"
                                                        class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                                            </div>
                                        @endif
                                    </form>
                                @elseif($search == true)
                                    <div class="ui negative icon message">
                                        <i class="frown icon"></i>
                                        <div class="content">
                                            <div class="header">
                                                {{ $error }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>


        <div class="column">

            <div class="ui very padded segment">
                @if(count($patients) > 0)
                    <div class="pagination_con" role="toolbar">
                        <div class="pull-right">
                            {!!  \App\Settings::perPageOptions($count)  !!}
                        </div>
                    </div>
                    {{csrf_field()}}
                    <table class="ui table table_cus_v last_row_bdr">
                        <thead>
                        <tr>
                            <th>Patient Id</th>
                            <th>Name of the Person</th>
                            <th>Email ID</th>
                            <th>Contact No.</th>
                            <th>City, State, Country</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($patients as $row)
                            <tr>
                                <td>{{ $row->userProfile->kid }}</td>
                                <td>{{ $row->userProfile->first_name.' '.$row->userProfile->last_name }}</td>
                                <td>{{ isset($row->userProfile->user->email) ? $row->userProfile->user->email : ""}}</td>
                                <td>{{ isset($row->userProfile->mobile) ?  $row->userProfile->mobile : ""}}</td>
                                <td>{{ $row->getAddress('city').','. $row->getAddress('state').','. $row->getAddress('country ') }} </td>
                                {{--<td>{{ ($row->patient->patient_type == 1)? 'IPD':'OPD' }}</td>--}}
                                <td>
                                    <form id="bookingFilter" action="{{ route('Laralum::patient-diet-chart') }}"
                                          method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" id="filter_patient_id" value="{{ $row->userProfile->kid }}"
                                               name="filter_patient_id">
                                        <button class="check_diet_status_btn" type="submit"> Check Diet Status</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @if(method_exists($patients, "links"))
                        <div class="pagination_con main_paggination" role="toolbar">
                            {{ $patients->links() }}
                        </div>
                    @endif
                @else
                    <div class="ui negative icon message">
                        <i class="frown icon"></i>
                        <div class="content">
                            <div class="header">
                                {{ trans('laralum.missing_title') }}
                            </div>
                            <p>There are currently no patients</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection


