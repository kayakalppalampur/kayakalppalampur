@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @if(!\Auth::user()->isUser())
            <a class="section" href="{{ route('Laralum::bookings') }}">{{ trans('laralum.booking_list') }}</a>
            <i class="right angle icon divider"></i>
        @endif
        @if($booking->booking_id != null)
            <a class="section"
               href="{{ route('Laralum::booking.show', ['booking_id' => $booking->id]) }}">{{ trans('laralum.booking_details') }}</a>
            <i class="right angle icon divider"></i>
        @endif
        <div class="active section">Booking</div>
    </div>
@endsection
@section('title', 'Booking')
@section('icon', "pencil")
@section('subtitle', 'Booking')
@section('content')
    <div class="ui one column doubling stackable">
        <div class="admin_wrapper signup">
            <div class="main_wrapper">
                @include('laralum.booking.topbar')
                <div class="ui one column doubling stackable">
                    <div class="column admin_basic_detail1">
                        <div class="segment form_spacing_inn">
                            <div class="about_sec signup_bg">
                                <h3 class="title_3">Payment Details</h3>
                                {!! Form::open(array('route' => ['Laralum::booking.payment.store', 'booking_id'=> $booking->id], 'id' => 'bookingProcessForm','files'=>true,'method'=>'post')) !!}
                                {{--<form id="bookingProcessForm" action="{{ route('guest.booking') }}" method="post">--}}
                                {{ csrf_field() }}
                                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <input type="hidden" name="amount" value="{{ $booking->getConsultFees()  }}">
                                <input type="text" class="form-control" disabled id="amount"
                                       value="{{  \App\AdminSetting::getSettingPrice('advance_payment') }}"
                                       placeHolder="Amount to be paid"
                                       max="{{  \App\AdminSetting::getSettingPrice('advance_payment') }}" style="width:50%"
                                       name="amount"/>
                                <input type="hidden" checked name="payment_method"
                                       value="{{ \App\Wallet::TYPE_PAID }}"/>

                                <div class="form-group payment_optn">
                                    <p>PAYMENT OPTIONS (CCAVENUE or Better Payment Gateway) </p>
                                    <div class="pymnt_opt_row">
                                        <div class="pymt_inn">
                                            <input type="radio" disabled name="payment_method"
                                                   value="{{ \App\Transaction::PAYMENT_METHOD_CREDIT }}"/>
                                            <span>Credit Card</span>
                                        </div>
                                    </div>
                                    <div class="pymnt_opt_row">
                                        <div class="pymt_inn">
                                            <input type="radio" disabled name="payment_method"
                                                   value="{{ \App\Transaction::PAYMENT_METHOD_DEBIT }}"/>
                                            <span>Debit Card</span>
                                        </div>
                                    </div>
                                    <div class="pymnt_opt_row">
                                        <div class="pymt_inn">
                                            <input type="radio"
                                                   value="{{ \App\Transaction::PAYMENT_METHOD_NET_BANKING }}"
                                                   name="payment_method" disabled/>
                                            <span>Net Banking</span>
                                        </div>
                                    </div>
                                    <div class="pymnt_opt_row">
                                        <div class="pymt_inn">
                                            <input value="{{ \App\Transaction::PAYMENT_METHOD_MOBILE_PAYMENTS }}"
                                                   type="radio" disabled name="payment_method"/>
                                            <span>Mobile Payments</span>
                                        </div>
                                    </div>
                                    <div class="pymnt_opt_row">
                                        <div class="pymt_inn">
                                            <input type="radio" value="{{ \App\Transaction::PAYMENT_METHOD_WALLET }}"
                                                   name="payment_method" checked/>
                                            <span>Cash</span>
                                        </div>
                                    </div>
                                </div>

                                <p class="form-group btn_signup_con">
                                    <button class="ui blue submit button" type="submit"> AGREE & GO TO NEXT »</button>
                                </p>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection