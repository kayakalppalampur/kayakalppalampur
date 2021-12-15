@if(!isset($print))
    <div class="column table_top_btn">
        <div class="btn-group pull-right">
            <div class="item no-disable">
                <a style="color:white" href="{{ url("admin/bank-accounts/add") }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button">
                        <i class="plus icon"></i><span class="text responsive-text">Create Bank Account</span>
                    </div>
                </a>
                <a style="color:white" href="{{ url("admin/bank-accounts/print/".'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">
                    <div tabindex="0" class="ui secondary top labeled icon left  button responsive-button"><i
                                class="print icon"></i><span class="text responsive-text">Print</span></div>
                </a>
                <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                    <i class="file icon"></i> <span class="text responsive-text">  Export</span>
                    <div class="menu">
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/bank-accounts/export/'.\App\Settings::EXPORT_CSV.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as CSV
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/bank-accounts/export/'.\App\Settings::EXPORT_PDF.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as PDF
                        </a>
                        <a id="clicked" class="item no-disable"
                           href="{{ url('/admin/bank-accounts/export/'.\App\Settings::EXPORT_EXCEL.'?per_page='. @$_REQUEST['per_page'].'&page='. @$_REQUEST['page'].'&s='.@json_encode($search_data)) }}">Export
                            as Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if(count($models) > 0)
    @if(isset($count))
        {{csrf_field()}}
        @if(!isset($print))
        <div class="pagination_con paggination_top" role="toolbar">
            <div class="pull-right">
                {!!  \App\Settings::perPageOptions(\App\HospitalBankaccount::count())  !!}
            </div>
        </div>
            @endif
    @endif
    <table class="ui table table_cus_v last_row_bdr"
           data-action="{{ url('admin/bank-accounts') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
        <thead>
        <tr>
            <th>Bank Name</th>
            <th>Account No</th>
            <th>Date</th>
            <th>Opening Balance</th>
            <th>Account Type</th>
            <th>Branch</th>
            @if(!isset($print))
                <th>Actions</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @if(!isset($print))
        <tr class="table_search">
            <td class="icons">
                <input type="text" class="table_search" id="table_search_bank_name"
                       value="{{ @$search_data['bank_name'] }}"
                       name="bank_name"
                       placeholder="search Bank Name"/> <i
                        class="fa fa-filter"></i>
            </td>
            <td class="icons">
                <input type="text" class="table_search" id="table_search_account_no"
                       value="{{ @$search_data['account_no'] }}"
                       name="account_no"
                       placeholder="search Account no"/> <i
                        class="fa fa-filter"></i>
            </td>

            <td class="icons">
                <input type="text" class="table_search filter_datepicker" id="table_search_date" value="{{ @$search_data['date'] }}"
                       name="date"
                       placeholder="search Date"/> <i
                        class="fa fa-filter"></i>
            </td>

            <td class="icons">
                <input type="text" class="table_search" id="table_search_opening_balance"
                       value="{{ @$search_data['opening_balance'] }}"
                       name="opening_balance"
                       placeholder="search Opening Balance"/> <i
                        class="fa fa-filter"></i>
            </td>
            <td class="icons">

                <select class="table_search" id="table_search_account_type" name="account_type">
                    <option value="all">All Account type</option>
                    @foreach(\App\HospitalBankaccount::getTypeOptions() as $key => $type)
                        <option value="{{ $key }}" {{ @$search_data['account_type'] == $key ? "selected" : "" }}>{{$type}}</option>
                    @endforeach
                </select> <i
                        class="fa fa-filter"></i>
            </td>

            <td class="icons">
                <input type="text" class="table_search" id="table_search_branch"
                       value="{{ @$search_data['branch'] }}"
                       name="branch"
                       placeholder="search Branch"/> <i
                        class="fa fa-filter"></i>
            </td>
            <td>
                &nbsp;
            </td>
        </tr>
        @endif
        @foreach($models as $model)
            <tr>
                <td>{{ $model->bank_name }}</td>
                <td>{{ $model->account_no}}</td>
                <td>{{ date('d-m-Y',strtotime($model->date)) }}</td>
                <td>{{ $model->opening_balance}}</td>
                <td>{{ $model->getTypeOptions($model->account_type) }}</td>
                <td>{{ $model->branch }}</td>
                @if(!isset($print))
                    <td>
                        <div id="book-table"  class="ui  top icon blue left pointing dropdown button">
                            <i class="configure icon"></i>
                            <div class="menu">
                                @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.hospital_bank_account'))
                                    <div class="header">{{ trans('laralum.editing_options') }}</div>
                                    <a href="{{ route('Laralum::admin.hospital_bank_account.edit', ['account_id' => $model->id]) }}"
                                       class="item no-disable">
                                        <i class="edit icon"></i>
                                        Edit Bank Account
                                    </a>
                                @endif
                                @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.hospital_bank_account'))
                                    <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                    <a href="{{ route('Laralum::hospital_bankaccount.delete', ['id' => $model->id]) }}"
                                       class="item no-disable">
                                        <i class="trash bin icon"></i>
                                        Delete Account
                                    </a>
                                @endif
                            </div>
                        </div>
                        {{--  @else
                              <div class="ui disabled blue icon button">
                                  <i class="lock icon"></i>
                              </div>
                          @endif--}}
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
    @if(!isset($print))
    @if(method_exists($models, "links"))
        <div class="pagination_con main_paggination" role="toolbar">
            {{ $models->links() }}
        </div>
    @endif
        @endif
@else
    <table class="ui table table_cus_v last_row_bdr"
           data-action="{{ url('admin/bank-accounts') }}?page={{ @$_REQUEST['page'] }}&per_page={{ @$_REQUEST['per_page'] }}">
        <thead>
        <tr>
            <th>Bank Name</th>
            <th>Account No</th>
            <th>Date</th>
            <th>Opening Balance</th>
            <th>Account Type</th>
            <th>Branch</th>
            @if(!isset($print))
                <th>Actions</th>
            @endif
        </tr>
        </thead>
        <tbody>
        <tr class="table_search">
            <td class="icons">
                <input type="text" class="table_search" id="table_search_bank_name"
                       value="{{ @$search_data['bank_name'] }}"
                       name="bank_name"
                       placeholder="search Bank Name"/> <i
                        class="fa fa-filter"></i>
            </td>
            <td class="icons">
                <input type="text" class="table_search" id="table_search_account_no"
                       value="{{ @$search_data['account_no'] }}"
                       name="account_no"
                       placeholder="search Account no"/> <i
                        class="fa fa-filter"></i>
            </td>

            <td class="icons">
                <input type="text" class="table_search filter_datepicker" id="table_search_date" value="{{ @$search_data['date'] }}"
                       name="date"
                       placeholder="search Date"/> <i
                        class="fa fa-filter"></i>
            </td>

            <td class="icons">
                <input type="text" class="table_search" id="table_search_opening_balance"
                       value="{{ @$search_data['opening_balance'] }}"
                       name="opening_balance"
                       placeholder="search Opening Balance"/> <i
                        class="fa fa-filter"></i>
            </td>
            <td class="icons">

                <select class="table_search" id="table_search_account_type" name="account_type"
                        value="{{ @$search_data['account_type'] }}">
                    <option value="">All Account type</option>
                    @foreach(\App\HospitalBankaccount::getTypeOptions() as $key => $type)
                        <option value="{{ $key }}" {{ @$search_data['account_type'] == $key ? "selected" : "" }}>{{$type}}</option>
                    @endforeach
                </select> <i
                        class="fa fa-filter"></i>
            </td>

            <td class="icons">
                <input type="text" class="table_search" id="table_search_branch"
                       value="{{ @$search_data['branch'] }}"
                       name="branch"
                       placeholder="search Branch"/> <i
                        class="fa fa-filter"></i>
            </td>
            <td>
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7">
                <div class="ui negative icon message">
                    <i class="frown icon"></i>
                    <div class="content">
                        <div class="header">
                            {{ $search == true ? $error : "No account found" }}
                        </div>
                        <p>There are currently no Account</p>
                    </div>
                </div>
            </td>
        </tr>
        </tbody></table>
@endif


           