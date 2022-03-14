
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
    $opd_consultation = 0;
@endphp



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

@if(count($bills) > 0)
    {{csrf_field()}}
    @if(!isset($print))
    <div class="pagination_con paggination_top" role="toolbar">
        <div class="pull-right">
            {!!  \App\Settings::perPageOptions(\App\Bill::count())  !!}
        </div>
    </div>
    @endif
    
    <div class="table-responsive table_sec_row">
        <table class="ui table table_cus_v last_row_bdr"
               data-action="{{ url('admin/bills') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
            <thead>
            <tr>
            @if(!isset($print))
                    <th>Actions</th>
                @endif
                <th>UHID</th>
                <th>Bill Date</th>
                <th>Bill No.</th>
                <th>Name</th>
                <th>Bill Amount</th>
                <th>Discount</th>
                <th>Consultation</th>
                <th>OPD Consultation</th>
                <th>Naturopathy</th>
                <th>Physiotherapy</th>
                <th>Ayurveda</th>
                <th>Lab</th>
                <th>Room Rent</th>
               
            </tr>
            </thead>
            <tbody>
            @if(!isset($print))
                <tr class="table_search">
                <td>
                        &nbsp;
                    </td>
                    <td>
                        &nbsp;
                    </td>
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
                    </td>
                    <td>
                        &nbsp;
                    </td>
                    <td>&nbsp;</td>
                </tr>
            @endif
            @foreach($bills as $bill)
            @php
                $bill_amount = $bill_amount + $bill->bill_amount;
                $discount = $discount + $bill->discount;
                $consultation = $consultation + $bill->consultation;
                $opd_consultation = $opd_consultation + $bill->opd_consultation;
                $physiotherapy = $physiotherapy + $bill->physiotherapy;
                $naturopathy_and_yoga = $naturopathy_and_yoga + $bill->naturopathy_and_yoga;
                $ayurveda = $ayurveda + $bill->ayurveda;
                $lab = $lab + $bill->lab;
                $room_rent = $room_rent + $bill->room_rent;
            @endphp
                <tr>
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
                        </td>
                    @endif
                    <td>
                    @if($bill->booking)
                             {{ $bill->booking->getProfile('uhid') }}
                                    @endif
</td>
                    <td style="white-space:nowrap;">{{ $bill->bill_date }}</td>
                    <td>{{ $bill->bill_no }}</td>
                    @if($bill->opdToken)
                    <td>{{ $bill->opdToken->first_name.' '.$bill->opdToken->last_name }}</td>
                   
                 @else
                    <td>{{ $bill->booking->getProfile('first_name').' '.$bill->booking->getProfile('last_name') }}</td>
                   @endif
                    <td>{{ $bill->bill_amount }}</td>
                    <td>{{ $bill->discount }}</td>
                    <td>{{ $bill->consultation }}</td>  
                    <td>{{ $bill->opd_consultation }}</td>             
                    <td>{{ $bill->naturopathy_and_yoga }}</td>
                    <td>{{ $bill->physiotherapy }}</td>
                    <td>{{ $bill->ayurveda }}</td>
                    <td>{{ $bill->lab }}</td>
                    <td>{{ $bill->room_rent }}</td>
                    
                </tr>
            @endforeach
            </tbody>
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
                    <b>  {{ $opd_consultation }}</b>
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
            </tfoot>
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
            <th>UHID</th>
            <th>Bill Date</th>
                <th>Bill No.</th>
                <th>Name</th>
                <th>Bill Amount</th>
                <th>Discount</th>
                <th>Consultation</th>
                <th>OPD Consultation</th>
                <th>Naturopathy</th>
                <th>Physiotherapy</th>
                <th>Ayurveda</th>
                <th>Lab</th>
                <th>Room Rent</th>
                @if(!isset($print))
                    <th>Actions</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @if(!isset($print))
            <td>
                        &nbsp;
                    </td>
                    <td>
                        &nbsp;
                    </td>
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
                    </td>
                    <td>
                        &nbsp;
                    </td>
                    <td>&nbsp;</td>
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
