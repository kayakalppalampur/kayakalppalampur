<<<<<<< HEAD

@php
    $bill_amount = 0;
    $discount = 0;
    $consultation = 0;
    $physiotherapy = 0;
    $naturopathy_and_yoga = 0;
    $ayurveda = 0;
    $ayurveda = 0;
    $lab = 0;
    $room_rent = 0;
@endphp



=======
>>>>>>> 5ed0c76eb7c3f854e777a8aa4decfe2b2a810fb2
@if(!isset($print))
    <div class="column table_top_btn">
        <div class="btn-group pull-right">
            <div class="item no-disable">
                <a style="color:white"
                   href="{{ url("admin/bills/print/".'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                class="print icon"></i><span class="text responsive-text">Print</span></div>
                </a>
                <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                    <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                    <div class="menu">
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/bills/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as CSV
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/bills/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as PDF
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/bills/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<<<<<<< HEAD
    @if(!isset($print))
<div class="row">
<div class="col-md-3">
<input type="text" class="form-control" id="table_search_bill_from_date"
                           value="{{ @$search_data['bill_from_date'] }}"
                           name="bill_from_date"
                           placeholder="Search Bill From Date"/>
                           
</div>
<div class="col-md-3">
                           <input type="text" class="form-control" id="table_search_bill_to_date"
                           value="{{ @$search_data['bill_to_date'] }}"
                           name="bill_to_date"
                           placeholder="Search Bill To Date"/>
</div>
</div>

@endif

=======
@if(!isset($print))
    <div class="column table_top_btn">
        <div class="btn-group pull-right">
            <div class="item no-disable">
                <a style="color:white" href="{{ url("admin/bill/create") }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button">
                        <i class="plus icon"></i><span class="text responsive-text">Create Bill</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endif
>>>>>>> 5ed0c76eb7c3f854e777a8aa4decfe2b2a810fb2
@if(count($bills) > 0)
    {{csrf_field()}}
    @if(!isset($print))
    <div class="pagination_con paggination_top" role="toolbar">
        <div class="pull-right">
            {!!  \App\Settings::perPageOptions(\App\Bill::count())  !!}
        </div>
    </div>
    @endif
<<<<<<< HEAD
    
=======


>>>>>>> 5ed0c76eb7c3f854e777a8aa4decfe2b2a810fb2
    <div class="table-responsive table_sec_row">
        <table class="ui table table_cus_v last_row_bdr"
               data-action="{{ url('admin/bills') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
            <thead>
            <tr>
<<<<<<< HEAD
                <th>Bill Date</th>
                <th>Bill No.</th>
                <th>Name</th>
                <th>Bill Amount</th>
                <th>Discount</th>
                <th>Consultation</th>
                <th>Naturopathy</th>
                <th>Physiotherapy</th>
                <th>Ayurveda</th>
                <th>Lab</th>
                <th>Room Rent</th>
=======
                <th>Title</th>
                <th>Description</th>
>>>>>>> 5ed0c76eb7c3f854e777a8aa4decfe2b2a810fb2
                @if(!isset($print))
                    <th>Actions</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @if(!isset($print))
                <tr class="table_search">

<<<<<<< HEAD
                <td class="icons">
                    <input type="text" class="table_search" id="table_search_bill_date"
                           value="{{ @$search_data['bill_date'] }}"
                           name="bill_date"
                           placeholder="Search Bill Date"/> <i
                            class="fa fa-filter"></i>
                </td>
                    <td>
                        &nbsp;
                    </td>
                    <td>
                        &nbsp;
                    </td>
                    <td>
                        &nbsp;
                    </td>
                    <td>
                        &nbsp;
                    </td>
                    <td>
                        &nbsp;
                    </td>
                    <td>
                        &nbsp;
                    </td>
                    <td>
                        &nbsp;
                    </td>
                    <td>
                        &nbsp;
                    </td>
                    <td>
                        &nbsp;
=======
                    <td class="icons">
                        <input type="text" class="table_search" id="table_search_title"
                               value="{{ @$search_data['title'] }}"
                               name="slug"
                               placeholder="search title"/> <i
                                class="fa fa-filter"></i>
>>>>>>> 5ed0c76eb7c3f854e777a8aa4decfe2b2a810fb2
                    </td>
                    <td>
                        &nbsp;
                    </td>
                    <td>&nbsp;</td>
                </tr>
            @endif
            @foreach($bills as $bill)
<<<<<<< HEAD
            @php
                $bill_amount = $bill_amount + $bill->bill_amount;
                $discount = $discount + $bill->discount;
                $consultation = $consultation + $bill->consultation;
                $physiotherapy = $physiotherapy + $bill->physiotherapy;
                $naturopathy_and_yoga = $naturopathy_and_yoga + $bill->naturopathy_and_yoga;
                $ayurveda = $ayurveda + $bill->ayurveda;
                $lab = $lab + $bill->lab;
                $room_rent = $room_rent + $bill->room_rent;
            @endphp
                <tr>
                    <td style="white-space:nowrap;">{{ $bill->bill_date }}</td>
                    <td>{{ $bill->bill_no }}</td>
                    <td>{{ $bill->booking->getProfile('first_name').' '.$bill->booking->getProfile('last_name') }}</td>
                    <td>{{ $bill->bill_amount }}</td>
                    <td>{{ $bill->discount }}</td>
                    <td>{{ $bill->consultation }}</td>               
                    <td>{{ $bill->naturopathy_and_yoga }}</td>
                    <td>{{ $bill->physiotherapy }}</td>
                    <td>{{ $bill->ayurveda }}</td>
                    <td>{{ $bill->lab }}</td>
                    <td>{{ $bill->room_rent }}</td>
                    @if(!isset($print))
                        <td>
                              <div>
                                    @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.bills'))
                                         <a href="{{ route('Laralum::bills.bill_print', ['id' => $bill->id]) }}"
                                           class="item no-disable">
                                            <i class="print icon"></i>                                           
                                        </a>
                                    @endif
                                    @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.bills'))
                                        <a href="{{ route('Laralum::bills.delete', ['id' => $bill->id]) }}"
                                           class="item no-disable">
                                            <i class="trash bin icon"></i>
                                        </a>
                                    @endif
                                </div>
=======
                <tr>
                    <td>{{ $bill->title }}</td>
                    <td>{{ $bill->description }}</td>
                    @if(!isset($print))
                        <td>
                            <div id="book-table" class="ui  top icon blue left pointing dropdown button">
                                <i class="configure icon"></i>
                                <div class="menu">
                                    @if(Laralum::loggedInUser()->hasPermission('admin.doctor_bills.list'))
                                        <div class="header">{{ trans('laralum.editing_options') }}</div>
                                        <a href="{{ route('Laralum::bill_edit', ['id' => $bill->id]) }}"
                                           class="item no-disable">
                                            <i class="edit icon"></i>
                                            {{ trans('laralum.edit_bill') }}
                                        </a>
                                    @endif
                                    @if(Laralum::loggedInUser()->hasPermission('admin.doctor_bills.list'))
                                        <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                        <a href="{{ route('Laralum::bill_delete', ['id' => $bill->id]) }}"
                                           class="item no-disable">
                                            <i class="trash bin icon"></i>
                                            {{ trans('laralum.delete_bill') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                            {{--  @else
                                  <div class="ui disabled blue icon button">
                                      <i class="lock icon"></i>
                                  </div>
                              @endif--}}
>>>>>>> 5ed0c76eb7c3f854e777a8aa4decfe2b2a810fb2
                        </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
<<<<<<< HEAD
            <tfoot>
            <td class="icons">
                    <b>Grand Total</b>
                    </td>
                    <td>
                        &nbsp;
                    </td>
                    <td>
                    &nbsp;
                    </td>
                    <td>
                    <b> {{ $bill_amount }}</b>
                    </td>
                    <td>
                    <b> {{ $discount }}</b>
                    </td>
                    <td>
                    <b>  {{ $consultation }}</b>
                    </td>
                    <td>
                    <b>{{ $naturopathy_and_yoga }}</b>
                    </td>
                    <td>
                    <b>{{ $physiotherapy }}</b>
                    </td>
                    <td>                    
                    <b>{{ $ayurveda }}</b>
                    </td>
                    <td>
                    <b>{{ $lab }}</b>
                    </td>
                    <td>
                    <b>{{ $room_rent }}</b>
                    </td>
                    <td>&nbsp;</td>
            </tfoot>
=======
>>>>>>> 5ed0c76eb7c3f854e777a8aa4decfe2b2a810fb2
        </table>
    </div>
    @if(!isset($print))
        @if(method_exists($bills, "links"))
            <div class="pagination_con main_paggination" role="toolbar">
                {{ $bills->links() }}
            </div>
        @endif
    @endif
@else
    <div class="table-responsive table_sec_row">
        <table class="ui table table_cus_v"
               data-action="{{ url('admin/bills') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
            <thead>
            <tr>
<<<<<<< HEAD
            <th>Bill Date</th>
                <th>Bill No.</th>
                <th>Name</th>
                <th>Bill Amount</th>
                <th>Discount</th>
                <th>Consultation</th>
                <th>Naturopathy</th>
                <th>Physiotherapy</th>
                <th>Ayurveda</th>
                <th>Lab</th>
                <th>Room Rent</th>
=======
                <th>Title</th>
                <th>Description</th>
>>>>>>> 5ed0c76eb7c3f854e777a8aa4decfe2b2a810fb2
                @if(!isset($print))
                    <th>Actions</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @if(!isset($print))
<<<<<<< HEAD
            <td class="icons">
                    <input type="text" class="table_search" id="table_search_bill_date"
                           value="{{ @$search_data['bill_date'] }}"
                           name="bill_date"
                           placeholder="Search Bill Date"/> <i
                            class="fa fa-filter"></i>
                </td>
                    <td>
                        &nbsp;
=======
                <tr class="table_search">
                    <td class="icons">
                        <input type="text" class="table_search" id="table_search_title"
                               value="{{ @$search_data['title'] }}"
                               name="slug"
                               placeholder="search title"/> <i
                                class="fa fa-filter"></i>
>>>>>>> 5ed0c76eb7c3f854e777a8aa4decfe2b2a810fb2
                    </td>
                    <td>
                        &nbsp;
                    </td>
<<<<<<< HEAD
                    <td>
                        &nbsp;
                    </td>
                    <td>
                        &nbsp;
                    </td>
                    <td>
                        &nbsp;
                    </td>
                    <td>
                        &nbsp;
                    </td>
                    <td>
                        &nbsp;
                    </td>
                    <td>
                        &nbsp;
                    </td>
                    <td>
                        &nbsp;
                    </td>
                    <td>
                        &nbsp;
                    </td>
                    <td>&nbsp;</td>
=======
                    @if(!isset($print))
                        <td>&nbsp;</td>
                    @endif
>>>>>>> 5ed0c76eb7c3f854e777a8aa4decfe2b2a810fb2
                </tr>
            @endif
            <tr>
                <td colspan="3">
                    <div class="ui negative icon message">
                        <i class="frown icon"></i>
                        <div class="content">
                            <div class="header">
                                {{ trans('laralum.missing_title') }}
                            </div>
                            <p>{{ isset($error) ? $error : "No bills found."}}</p>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
@endif
