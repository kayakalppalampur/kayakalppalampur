@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::treatment_packages') }}">Treatment Packages</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Create Treatment Package</div>
    </div>
@endsection
@section('title', 'Add Treatment Package')
@section('icon', "plus")
@section('content')
    <div class="ui one column doubling stackable grid container">
        <div class="column">
            <div class="ui very padded segment">
                <form method="POST" class="ui form">
                    {{ csrf_field() }}
                    <div class="ui stackable grid">
                        <div class="three wide column"></div>
                        <div class="ten wide column">
                            <div class="field ">
                                <label class="" for="package_name">Package Name<span class="required">*</span>
                                </label>
                                <input name="package_name" type="text" value="{{ old('package_name', $model->package_name) }}" required="required" class="">
                            </div>

                            <div class="field ">
                                <label class="" for="department_id">Department<span class="required">*</span>
                                </label>
                               <select class="form-control" name="department_id" id="department_id">
                                   <option> Select Department</option>
                                   @foreach(\App\Department::all() as $dept)
                                   <option value="{{ $dept->id }}" {{ old('department_id', $model->department_id) == $dept->id ? "selected" : ""}}>{{ $dept->title }}</option>
                                   @endforeach
                               </select>
                            </div>

                            <div class="field ">
                                <label class="" for="treatment_id">Add Treaments<span class="required">*</span>
                                </label>
                                <select class="form-control" name="treatment_id[]" id="treatment_select" multiple>
                                </select>
                            </div>

                            <div class="field ">
                                <label class="" for="price">Price<span class="required">*</span>
                                </label>
                                <input name="price" type="text" id="price" value="{{ old('price', $model->price) }}" required="required" class="">
                            </div>


                            <div class="field ">
                                <label class="" for="duration">Duration<span class="required">*</span>
                                </label>
                                <input name="duration" type="text" value="{{ old('duration', $model->duration) }}" required="required" class="">
                                <select class="form-control" name="type">
                                    <option value="{{ \App\TreatmentPackage::TYPE_MINUTES}}">Minutes</option>
                                    <option value="{{ \App\TreatmentPackage::TYPE_HOURS }}">Hours</option>
                                </select>
                            </div>
                        </div>
                        <div class="three wide column"></div>
                    </div>
                    <br><br>
                    <br>
                    <div class="field">
                        <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
<script>
    var val = $("#department_id").val();
    updateDropdown(val);
    function updateDropdown(val) {
        var old_val = "{{ implode(',', old('treatment_id', [])) }}";
        $.ajax({
            url:"{{ url('admin/get-treatments') }}/"+val,
            type:"POST",
            data:{'_token':"{{ csrf_token() }}", 'old_val':old_val},
            success:function (result) {
                $("#treatment_select").html(result);
            }
        })
    }

    $("#department_id").change(function () {
        var val =  $(this).val();
        updateDropdown(val);
    })

    function updatePrice() {
        var price = 0;
        $("#treatment_select option:selected").each(function(){
            price = parseInt(price) + parseInt($(this).attr("data-price"));
        })
        $("#price").val(price);
    }

    $("#treatment_select").change(function () {
        updatePrice();
    });
</script>
@endsection