@if(isset($bookings))
<div class="booking_info_page">
    <div class="content_Box">
        <div class="content_BoxIN">
           @if(!empty($bookings))
                @foreach($bookings as $booking)
                    <p>Patient Name: {{ $booking->getProfile('first_name').' '. $booking->getProfile('last_name') }}
                    <p>Room number: {{ $booking->room->room_number }}</p>
                    <p>Floor: {{ $booking->room->getFloorNumber($booking->room->floor_number) }}</p>
                    <!--1 => SingleBed, 2 => SingleOccupancy, 3=>SingleOccupancyWithExtraBed, 4- DoubleBedWithSharing-->
                  @php $booking_type = $booking->getBookingType($booking->booking_type); @endphp
                    <p>Booking type: {{ $booking_type }}</p>
                    <p>Staying: From: {{ date('d M, Y',strtotime($booking->check_in_date)) }} to: {{ date('d M, Y',strtotime($booking->check_out_date)) }}</p>
                    <p>Price: {{ $booking->daysPrice() }}</p>
                    @if(!empty($booking->services))
                        @foreach($booking->services as $service)
                            <p>Service Name: {{ $service->service->name }}</p>
                            <p>Service Price: {{ $service->service->price }}</p>
                        @endforeach
                    @endif

                @endforeach
            @endif
        </div>
    </div>
</div>
@else
    <div class="booking_info_page">
        <div class="content_Box">
            <div class="content_BoxIN">
                @if(!empty($wallets))
                    @foreach($wallets as $wallet)
                        @if($wallet->type == \App\Wallet::TYPE_PAID)
                            <div class="details">
                                <p>
                                    <b>Transaction Id:</b><span> {{ $wallet->txn_id }}</span>
                                    <b>Paid Amount:</b><span> {{ $wallet->amount }}</span>
                                    <b>Account Status:</b><span> {{ $wallet->getStatusOptions($wallet->status) }}</span>
                                 </p>
                                <button class="btn btn-success" id="show_details_{{ $wallet->id }}">Show Details</button> <button class="btn btn-success" style="display:none;" id="hide_details_{{ $wallet->id }}">Hide Details</button>
                                <table style="display:none;" class="wallet-table table" id="details_{{ $wallet->id }}">
                                    @if($wallet->model_type == \App\Transaction::class)
                                        @foreach($wallet->transaction->items as $item)
                                        <tr>
                                            <th>{{ $item->getType() }}</th>
                                            <td>{{ $item->amount }}</td>
                                        </tr>
                                        @endforeach

                                        @if($wallet->transaction->discount_id != null)
                                            <tr>
                                                <th>Discount</th>
                                                <td>{{ $wallet->transaction->discount_amount }}</td>
                                            </tr>
                                         @endif
                                        @else
                                        <tr>
                                            <th>{!! $wallet->getModelName() !!}</th></tr>
                                    @endif
                                        <tr>
                                            <th>Paid Amount:</th>
                                            <td>{{ $wallet->amount }}</td>
                                        </tr>

                                </table>
                            </div>
                        @endif
                    @endforeach
                        <p>  <b>Paid Amount:</b><span>{{ $user->getTotalAmount(\App\Wallet::TYPE_PAID, false) }}</span></p>
                        <p> <b>Refundable Amount:</b><span>{{ $user->getTotalAmount(\App\Wallet::TYPE_REFUND, false) }}</span></p>
                        <p><b>Refundable Status:</b><span>{{ $user->getRefundStatus() }}</span></p>

                        {{--<b>Refunded Amount:</b><span>{{ $user->getTotalAmount(\App\Wallet::TYPE_REFUNDED, false) }}</span>
                        <b>Due Amount:</b><span>{{ $user->getTotalAmount(\App\Wallet::TYPE_DUE, false)  }}</span>--}}
                @endif
            </div>
        </div>
    </div>
    <script>
        $("[id^=show_details_]").click(function () {
            var id = $(this).attr('id').split("show_details_")[1];
            $("#details_"+id).show();
            $("#hide_details_"+id).show();
            $(this).hide();
        })
        $("[id^=hide_details_]").click(function () {
            var id = $(this).attr('id').split("hide_details_")[1];
            $("#details_"+id).hide();
            $("#show_details_"+id).show();
            $(this).hide();
        })
    </script>
@endif
@section('js')
<script>
    $("[id^=show_details_]").click(function () {
        var id = $(this).split("show_details_")[1];
        $("#details_"+id).show();
    })
</script>
@endsection