@extends('layouts.front.web_layout')
@section('content')
    <style type="text/css" media="print">
        @page
        {
            size: auto;   /* auto is the initial value */
            margin: 0mm;  /* this affects the margin in the printer settings */
        }
        .date{
            background-color: #ccc;
            float: left;
            padding: 8px;
            width: 75%;
        }
    </style>
    <div class="ui one column doubling stackable grid container">
		<div class="receipt-for">
			<p class="text-center">@if(!isset($print)) <a id="print" class="btn btn-primary ui button blue">Print</a> <a id="back" class="btn btn-primary ui button blue" href="{{ isset($back_url) ? $back_url : url('/admin/patient/'.$booking->id.'/treatment_history') }}">Back</a>  @endif</p>
		</div>
		<div class="token_treatment_con">
			<div class="column">
				<div class="heading"><h3>TOKEN FOR TREATMENTS</h3>
				@if($token->is_special == \App\TreatmentToken::IS_SPECIAL) <h5>(Special Treatment)</h5> @endif
				</div>
				<div class="patient-details">
					
					<dl class="dl-horizontal">
						<dt>Registration ID</dt>
						<dd><span>{{ $booking->getProfile('kid') }}</span></dd>
					</dl>
					
						<dl class="dl-horizontal">
						<dt>Token Date and Time</dt>
						<dd><span>{{ $token->treatment_date_date." ".date("h:i a", strtotime($token->created_at)) }}</span></dd>
					</dl>
					<dl class="dl-horizontal">
						<dt>Patient Type</dt>
						<dd><span>{{$booking->getPatientType($booking->patient_type)}}</span></dd>
					</dl>
					
						<dl class="dl-horizontal">
						<dt>Full Name</dt>
						<dd><span>{{ $booking->getProfile('first_name').' '.$booking->getProfile('last_name') }}</span></dd>
					</dl>
					
						<dl class="dl-horizontal">
						<dt>Age</dt>
						<dd><span>{{ $booking->getProfile('age') }}</span></dd>
					</dl>
					
					<dl class="dl-horizontal">
						<dt>Gender</dt>
						<dd><span>{{ \App\UserProfile::getGenderOptions($booking->getProfile('gender')) }}</span></dd>
					</dl>
					
						<dl class="dl-horizontal">
						<dt>Doctor's Note</dt>
						<dd><span>{{ $token->note }}</span></dd>
					</dl>
				
				
				</div>
				
				<div class="tok-num">
				<span>Token No</span>
				</div>
				
				<div class="token_no">
					
					<div class="p-token">
						<span>{{ $token->token_no }}</span>
						<span>Valid on date<br> {{ date('d-m-Y',strtotime($token->expiry_date))}}</span>
					</div>
				</div>
				<div class="department">
					<span>{{ $token->department->title }} ({{ \App\UserProfile::getGenderOptions($booking->getProfile('gender'))}})</span>
				</div>
				<div class="treatment-details">
					<h4>Treatment Details</h4>
					
					<div class="treat-in">
					@foreach($token->treatments as $treatment)
					<span>{{ $treatment->treatment->title." (".$treatment->treatment->getDuration().')' }}</span><br/>
					@endforeach
					<b><span>Total Duration: </span>
					<span>{{ $token->getTotalDuration() }}</span></b>
					</div>
				</div>
				<div class="treatment">
					<div class="treat-id">
						<h3>Treatment Id</h3>
						<span>{{ $token->id }}</span>
					</div>
					<div class="bar-code-token">
					<img src="data:image/png;base64, {!! $barcode !!}" alt="barcode" />
					</div>
				</div>
			</div>
        </div>
        
    </div>

    
    <div class="token-receipt" id="mySelector">
        <div class="receipt-for">


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
    function PrintElem(elem)
    {
        Popup($('<div/>').append($(elem).clone()).html());
    }

    function Popup(data)
    {
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

