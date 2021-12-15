@extends('layouts.front.web_layout')
@section('content')
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
    </style>
<style>
#mySelector.token-receipt {
    max-width: 800px;
}
.token-detail-box {
    display: inline-block;
    width: 100%;
    margin: 0px 0px 10px;
}

.discharge_form .form-group.col-4 {
    float: left;
    margin-left: 0;
    margin-right: 0;
    padding: 0 15px;
    width: 33.33% !important;
}
.discharge_form .col-5 {
    display: inline-block;
    padding: 0 15px;
    width: 50%;
    margin: 0px -1px;
}
.form-dod {
    float: left;
    width: 100%;
}
.token-detail-box {
    background-color: #fff;
    border: 2px solid;
    padding: 10px 15px 10px;
}
.token-receipt {
    margin: 0 auto;
    max-width: 400px;
    padding: 40px 0;
    text-align: center;
    width: 100%;
    max-width: 1000px !important;
}
.discharge_form .col-2 {
    display: inline-block;
    padding: 0 15px;
    width: 25%;
    vertical-align: middle;
}
.discharge_form .col-10 {
    padding: 0 15px;
    width: 75%;
    display: inline-block;
    vertical-align: middle;
    margin: 0px -2px;
}
.discharge_patient_con textarea {
    height: 70px;
    padding: 7px 10px;
}
.discharge_form textarea {
    width: 100%;
    border: 1px solid #ccc;
    padding: 5px;
}
.discharge-form-row [class*="section-"] {
    background-color: #fdfdfd;
    float: left;
    padding: 30px 0 0;
    width: 100%;
    border-top: 1px dashed #cdcdcd;
    margin-top: 20px;
}
.vital-head {
    display: inline-block;
    width: 100%;
    background-color: #f5f5f5;
    padding: 11px 15px;
    color: #555;
    margin-bottom: 15px;
}
.vital-wrap .form-group {
    float: left;
    margin-left: 0;
    margin-right: 0;
    padding: 0 15px;
    width: 25% !important;
}
.vital-head h2 {
    color: #000;
    font-size: 14px;
    text-align: center;
    margin: 0;
    font-weight: 600;
}
.form-new1 .section-4 label {
    float: left;
    width: 25%;
    padding: 0 15px;
}
.ui.red.button, .ui.red.buttons .button {
    background-color: #DB2828;
    box-shadow: 0 0 0 0 rgba(34, 36, 38, .15) inset;
    color: #FFF;
    text-shadow: none;
    background-image: none;
    height: 48px;
    width: 100%;
    border: none;
}
.vital-btn1 button.ui.button {
    max-width: 180px;
}
.form-new1 {
    text-align: left;
}
.about_sec.signup_bg h4 {
    font-weight: 700;
    text-align: center;
}
.dis-next {
    margin-top: 35px;
    margin-bottom: 35px;
}
.discharge_form input {
    border: 1px solid #ccc;
    min-height: 34px;
    padding: 5px;
}
</style>

    <div class="ui one column doubling stackable grid container">
        <div class="column">

        </div>
    </div>


    <div class="token-receipt" id="mySelector" style="width:1000px;max-width:1000px;">
        <div class="receipt-for">
            <p>@if(!isset($print)) <a id="print" class="btn btn-primary ui button blue">Print</a> 
            <a id="back" class="btn btn-primary ui button blue" href="{{ isset($back_url) ? $back_url : url('/admin/patient/discharge/'.$booking->id) }}">Back</a>  @endif
            </p>
        </div>

<div class="token-detail-box">
        <div class="patient_form_wrap" style="text-align: center;display: inline-block;width: 100%;">
            <h2 style="text-transform: uppercase;font-size:16px;margin-top:0;font-weight:600;line-height:22px;margin-bottom:0;">
                Vivekanand medical research trust, holta, palampur, (Regd.)<br> Distt. Kangra, Himachal pradesh -176062
            </h2>
            <div class="logo_kaya" style="position: relative;min-height: 95px;">
                <div class="logo_form" style="float: left;">
                    <img width="100px" src="{{asset('images/slip_left_logo.jpg')}}">
                </div>
                <div class="center_head" style="position: absolute;left: 50%;transform: translateX(-50%)">
                    <h3 style="text-transform: uppercase;margin: 0;font-weight: bold;font-size:30px;">Kayakalp</h3>
                    <p style="text-transform: uppercase;font-size:16px;line-height:20px;">Himalayan research
                        institute<br> for yoga and naturopathy</p>
                </div>
                <div class="form_phone_detail" style="float: right;text-align:right;">
                    <img width="100px" src="{{ asset('images/slip_right_logo.jpg') }}">
                    <span style="display: block;font-size:16px;margin-top:10px;">Phone: (01894) 235676</span>
                    <span style="display: block;font-size:16px;">Tele Fax: (01894) 235666</span>
                    <span style="display: block;font-size:16px;">Mobile No: 7807310891</span>
                </div>
            </div>
        </div>

        <div class="about_sec signup_bg discharge_form">
            <!--  <h3 class="title_3"></h3> -->
            <div class="discharge-form-row form-new1">

                <div class="section-1">
                    <div class="form-dod">
                        
                        <div class="form-group col-4">
                            <div class="discharge-row">
                                <div class="col-5">
                                    <label>DOA*</label>
                                </div>
                                <div class="col-5">{{ date('d-m-Y', strtotime($booking->getDate('check_in_date'))) }}</div>
                            </div>
                            <input type="hidden"
                                   value="{{ date('d-m-Y', strtotime($booking->getDate('check_in_date'))) }}"
                                   name="date_of_arrival"/>
                            <input type="hidden" value="{{ date('d-m-Y') }}" name="date_of_discharge"/>
                            <input type="hidden" value="{{ $patient->id }}" name="patient_id"/>
                            <input type="hidden" value="{{ $patient->id }}" name="token_id"/>
                            <input type="hidden" value="{{ $booking->id }}" name="booking_id"/>

                        </div>
                        <div class="form-group col-4">
                            <div class="discharge-row">
                                <div class="col-5"><label>DOD</label></div>

                                <div class="col-5">  {{ $discharge_patient->date_of_discharge != null && $discharge_patient->date_of_discharge != "0000-00-00" ? date("d-m-Y", strtotime($discharge_patient->date_of_discharge)) : date("d-m-Y") }} </div>

                                <input type="hidden"
                                       value="{{ $discharge_patient->date_of_discharge != null && $discharge_patient->date_of_discharge != "0000-00-00" ? date("d-m-Y", strtotime($discharge_patient->date_of_discharge)) : date("d-m-Y") }}"
                                       name="date_of_discharge" disabled/>
                            </div>
                        </div>
                    </div>
                    <div class="form-dod">
                        <div class="form-group col-4">
                            <div class="discharge-row">
                                <div class="col-5">
                                    <label>UHID</label>
                                </div>
                                <div class="col-5">{{ $patient->uhid }}</div>
                            </div>
                            

                        </div>
                        <div class="form-group col-4">
                            <div class="discharge-row">
                                <div class="col-5">
                                    <label>Patient Id</label>
                                </div>
                                <div class="col-5">{{ $patient_profile->kid }}</div>
                            </div>

                        </div>
                        <div class="form-group col-4">
                            <div class="discharge-row">
                                <div class="col-5">
                                    <label>Booking Id</label>
                                </div>
                                <div class="col-5">{{ $booking->booking_id }}</div>
                            </div>

                        </div>
                    </div>
                    <div class="form-dod">
                        <div class="form-group col-4">
                            <div class="discharge-row">
                                <div class="col-5"><label>Name of Patient</label></div>
                                <div class="col-5">{{ $booking->getProfile('first_name').' '.$booking->getProfile('last_name')}}</div>
                            </div>
                        </div>
                        <div class="form-group col-4">
                            <div class="discharge-row">
                                <div class="col-5"><label>Sex</label></div>
                                <div class="col-5"> {{ \App\UserProfile::getGenderOptions($booking->getProfile('gender')) }}</div>
                            </div>
                        </div>
                        <div class="form-group col-4">
                            <div class="discharge-row">
                                <div class="col-5"><label>Age</label></div>
                                <div class="col-5"> {{ $booking->getProfile('age')}}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-2">
                    <div class="form-group">
                        <div class="col-2"><label>Diagnosis</label></div>
                        <div class="col-10">

                                <p>
                                    {!! isset($diagnosis) ? $diagnosis->description != "" ? $diagnosis->description : $booking->getComplaints() : $booking->getComplaints()  !!}
                                </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-2"><label>Discharge Summary</label></div>
                        <div class="col-10">

                                <p>
                                    {!! $discharge_patient->discharge_summary !!}
                                </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-2"><label>Investigation Report (if any)</label></div>
                        <div class="col-10">

                                <p>
                                    {!! $discharge_patient->investigation_report !!}
                                </p>
                        </div>
                    </div>
                </div>

                <div class="section-3">
                    <div class="vital-wrap">

                        <div class="vital-head">
                            <h2>Vital Report*</h2>
                        </div>
                        <div class="form-group">
                            <label>On Admission</label>
                        </div>
                        <div class="form-group">
                            <div class="discharge-form-row">
                                <div class="col-2"><label>BP</label></div>
                                <div class="col-10">  {{ $vital_data->bp }} </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="discharge-form-row">
                                <div class="col-2"><label>PR</label></div>
                                <div class="col-10"> {{ $vital_data->pulse }} </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="discharge-form-row">
                                <div class="col-2"><label>WT</label></div>
                                <div class="col-10"> {{ $vital_data->weight }} </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>On Discharge</label>
                        </div>
                        <div class="form-group">
                            <div class="discharge-form-row">
                                <div class="col-2"><label>BP</label></div>
                                <div class="col-10">
                                      {!! $discharge_vital->bp !!}

                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="discharge-form-row">
                                <div class="col-2"><label>PR</label></div>
                                <div class="col-10">
                                        {!! $discharge_vital->pulse !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="discharge-form-row">
                                <div class="col-2"><label>WT</label></div>
                                <div class="col-10">

                                        {!! $discharge_vital->weight !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="section-4">
                    @foreach(\App\Department::all() as $department)
                        <h4>{{ $department->title }}</h4>
                        @php $department_discharge = \App\DepartmentDischargeBooking::where('booking_id', $booking->id)->where('department_id', $department->id)->first();
                                            if ($department_discharge == null) {
                                                $department_discharge = new \App\DepartmentDischargeBooking();
                                            }
                        @endphp
                        <div class="dis-summary">
                            <div class="form-group">
                                <label>Summary*</label>
                                <div class="col-10">
                                                       <p>
                                                           {!! $department_discharge->summary !!}
                                                       </p>
                                </div>
                            </div>
                        </div>
                        <div class="dis-summary">
                            <div class="form-group">
                                <label>Things To Avoid*</label>
                                <div class="col-10">
                                    <p>
                                        {!! $department_discharge->things_to_avoid !!}
                                    </p>

                                </div>
                            </div>
                        </div>

                        <div class="dis-summary">
                            <div class="form-group">
                                <label>Follow up Advice*</label>
                                <div class="col-10">
                                    <p>
                                        {!! $department_discharge->follow_up_advice !!}
                                    </p>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="section-5">
                <div class="dis-diet">
                    <div class="form-group">
                        <div class="vital-head"><h2>Diet Plan*</h2></div>
                        <div class="col-12"> For Days
                            {{ $discharge_patient->diet_plan_duration }}

                        </div>
                    </div>
                </div>
            </div>

            <div class="section-5">
                <div class="dis-next">
                    <div class="form-group">
                        <div class="vital-head"><h2>Next Follow Up Plan*</h2></div>
                        @if($discharge_patient->isEditable())
                            <div class="col-12"> Patient Should visit again after
                               {{ $discharge_patient->getFollowupDays() }}
                                days
                                <span class="sep-row">Or</span>
                                Select any future date
                                {{$discharge_patient->getFollowupDate() }}
                                <br><br> 
                                <b>Incase of urgent care:</b><br><br>
                                Contact on 01894-235666/235676 Between 9 a.m. to 5 p.m.<br><br>
                                Or may walk into OPD Between 9 a.m. to 5 p.m. (on all week days)
                            </div>
                        @else

                            <div class="col-12"> Patient Should visit again
                                after {{ $discharge_patient->getFollowupDays() }}
                                days <br> <br>
                                <br><br> 
                                <b>Incase of urgent care:</b><br><br>
                                Contact on 01894-235666/235676 Between 9 a.m. to 5 p.m.<br><br>
                                Or may walk into OPD Between 9 a.m. to 5 p.m. (on all week days)
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            {{--<div class="section-5">
                <div class="dis-diet">
                    <div class="form-group">
                        <div class="vital-head"><h2>Recommended Exercise*</h2></div>
                        <div class="col-12">
                            @foreach($booking->recommened_exercises as $exercise)
                                <div class="row">
                                    <div class="col-md-4">
                                        {{ $exercise->physiotherpy_exercise->name_of_excercise }}
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('Laralum::recommend-exercise.print',['exercise_id'=> $exercise->physiotherpy_exercise_id]) }}"
                                           style="color:#444;">
                                            <button type="button"
                                                    class="btn btn-info btn-lg value_{{$exercise->physiotherpy_exercise_id}}">
                                                Print
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>--}}


        </div>
</div>

    </div>
@endsection

@section('script')
    <script>
        $("#print").click(function () {
            $(this).hide();
            $("#back").hide();
            window.print();
            $(this).show();
            $("#back").show();

        });
        /* $("#mySelector").printThis({
         debug: false,               /!* show the iframe for debugging*!/
         importCSS: true,            /!* import page CSS*!/
         importStyle: true,       /!* import style tags*!/
         printContainer: true,      /!* grab outer container as well as the contents of the selector*!/
         loadCSS: [
         "{{ asset('/css/style.css') }}",
         "{{ asset('js/bootstrap.min.js') }}",
         "{{ asset('/css/font-awesome.min.css')}}",
         "{{ asset('/css/animate.css') }}",
         "{{ asset('css/jquery.steps.css') }}"],  /!* path to additional css file - use an array [] for multiple*!/
         pageTitle: "",              /!* add title to print page*!/
         removeInline: false,       /!* remove all inline styles from print elements*!/
         printDelay: 333,           /!* variable print delay; depending on complexity a higher value may be necessary*!/
         header: null,              /!* prefix to html*!/
         footer: null,              /!* postfix to html*!/
         base: false     ,           /!* preserve the BASE tag, or accept a string for the URL*!/
         formValues: true,            /!* preserve input/form values*!/
         canvas: false ,             /!* copy canvas elements (experimental)*!/
         doctypeString: ""        /!* enter a different doctype for older markup*!/
         });*/
        function PrintElem(elem) {
            Popup($('<div/>').append($(elem).clone()).html());
        }

        function Popup(data) {
            var mywindow = window.open('', 'my div', 'height=400,width=600');
            mywindow.document.write('<html><head><title>my div</title>');
            mywindow.document.write('<link href="http://122.180.254.6:8082/Kayakalp/public/css/font-awesome.min.css" rel="stylesheet" type="text/css" /><link href="http://122.180.254.6:8082/Kayakalp/public/css/bootstrap.min.css" rel="stylesheet" type="text/css" /><link href="http://122.180.254.6:8082/Kayakalp/public/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" /><link href="http://122.180.254.6:8082/Kayakalp/public/css/animate.css " rel="stylesheet" type="text/css" /><link href="http://122.180.254.6:8082/Kayakalp/public/css/style.css" rel="stylesheet" type="text/css" /><link href="http://122.180.254.6:8082/Kayakalp/public/css/jquery.steps.css" rel="stylesheet" type="text/css" />');
            mywindow.document.write('</head><body >');
            mywindow.document.write(data);
            mywindow.document.write('</body></html>');

            mywindow.print();
            //  mywindow.close();

            return true;
        }
    </script>
@endsection