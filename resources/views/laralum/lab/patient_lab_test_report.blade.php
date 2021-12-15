<div class="clearfix"></div>
    <p> <b> Patient Name : </b>  {{ $booking->getProfile('first_name').' '.$booking->getProfile('last_name')}}</p>
    <p> <b> Lab Test : </b> {{ $lab_test->getTestsName() }} </p>

<form action="{{ URL::route('Laralum::patient.lab_test_report') }}" method="post" enctype="multipart/form-data">
	{{ csrf_field() }}
	<input type="hidden" name="id" value="{{ $lab_test->id }}">
	<div class="field">
		<b> Upload Report: </b> <input type="file" name="lab_report" required>
	</div>
	<br> <br> 
    <button type="submit" class="ui blue submit button">Submit</button>                                     
</form>