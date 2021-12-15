<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
    <style type="text/css" media="print">
        @page {
            size: auto;   /* auto is the initial value */
            margin: 0mm;  /* this affects the margin in the printer settings */
        }

        .date {
            background-color: #ccc;
            float: left;
            padding: 8px;
            width: 75%;
        }
        table{
            width: 100%;
            margin-top: 10px;
        }
        table th, table td{
            text-align: center;
        }
        table td{
    padding: 8px;
    line-height: 1.42857143;
    vertical-align: top;
    border-top: 1px solid #ddd;
}
.form_phone_detail span{
    display: block;
}
    </style>
    <title></title>
</head>
<body>

<div class="token-receipt" id="mySelector" style="width:1000px;max-width:1000px;">
    <div class="booking_info_page noc-pop">
        <div class="patient_form_wrap" style="text-align: center;display: inline-block;width: 100%;">
                <h2 style="text-transform: uppercase;font-size:16px;margin-top:0;font-weight:600;line-height:22px;margin-bottom:0;">
                    Vivekanand medical research trust, holta, palampur, (Regd.)<br> Distt. Kangra, Himachal pradesh -176062
                </h2>
                <div class="logo_kaya" style="position: relative;min-height: 95px;">
                    <div class="logo_form" style="float: left;width:150px;">
                        <img width="100px" src="{{asset('images/slip_left_logo.jpg')}}">
                    </div>
                    <div class="center_head" style="position: relative;left:0;transform: translateX(-50%);width:400px;float:left;">
                        <h3 style="text-transform: uppercase;margin: 0;font-weight: bold;font-size:30px;">Kayakalp</h3>
                        <p style="text-transform: uppercase;font-size:16px;line-height:20px;">Himalayan research
                            institute<br> for yoga and naturopathy</p>
                    </div>
                    <div class="form_phone_detail" style="float: right;text-align:right;width:200px;word-break: break-all;padding-right:15px;">
                        <img width="100px" src="{{ asset('images/slip_right_logo.jpg') }}" style="display:block;">
                        <div style="width:100%;display:block;">
                            <span style="display: block;font-size:16px;margin-top:10px;">Phone: (01894) 235676</span>
                            <span style="display: block;font-size:16px;">Tele Fax: (01894) 235666</span>
                            <span style="display: block;font-size:16px;">Mobile No: 7807310891</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="content_Box">
            @php $counter = 0; @endphp
            <table>
                @foreach($data as $value)
                    <tr>
                        @if($counter == 0)
                            @foreach($value as $field)
                                <th>{{$field}}</th>
                            @endforeach

                        @else
                            @foreach($value as $field)
                                <td>{{$field}}</td>
                            @endforeach
                        @endif
                    </tr>
                    @php $counter++; @endphp
                @endforeach
            </table>
        </div>
    </div>
</div>
</body>
</html>