<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
    <style type="text/css">
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
        table{ page-break-inside:auto }
        tr{ page-break-inside:avoid; page-break-after:auto }
        .segment table.ui.table td {
            font-size: 1em;
            min-width: 100px;
            word-break: break-all;
        }

    </style>
    <title></title>
</head>
<body>

<div class="token-receipt" id="mySelector" style="width:1000px;max-width:1000px;">
    <div class="booking_info_page noc-pop">
        <div class="patient_form_wrap" style="text-align: center;display: inline-block;width: 100%;">
            <h2 style="padding-top:15px; text-transform: uppercase;font-size:16px;margin-top:10px;font-weight:600;line-height:22px;margin-bottom:0;">
                Vivekanand medical research trust, holta, palampur, (Regd.)<br> Distt. Kangra, Himachal pradesh -176062
            </h2>
            <div class="logo_kaya" style="position: relative;min-height: 95px;">
                <div class="logo_form" style="float: left; width: 100px; padding: 10px;">
                    <img width="100px" src="{{asset('images/slip_left_logo.jpg')}}">
                </div>
                <div class="center_head" style="width: 300px; float: left; text-align: center; margin-left: 230px;">
                    <h3 style="text-transform: uppercase;margin: 0;font-weight: bold;font-size:30px;">Kayakalp</h3>
                    <p style="margin-top:0; text-transform: uppercase;font-size:16px;line-height:20px;">Himalayan research
                        institute<br> for yoga and naturopathy</p>
                </div>
                <div style="">
                <div style="width: 100px; float: right; padding:10px 10px 0; ">
                    <img style="display: block; margin:auto;" width="100px" src="{{ asset('images/slip_right_logo.jpg') }}">
                </div>
                <div class="form_phone_detail" style="float:right; width: 100%; text-align: right;
                padding-right: 20px;">
                    <div style="display: block;font-size:16px;margin-top:0px;">Phone: (01894) 235676</div>
                    <div style="display: block;font-size:16px;">Tele Fax: (01894) 235666</div>
                    <div style="display: block;font-size:16px;">Mobile No: 7807310891</div>
                </div>
            </div>
            </div>
        </div>
        <div class="content_Box">
            @include('laralum.token._summary_data_pdf')
        </div>
    </div>
</div>
</body>
</html>
