@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::treatments') }}">{{ trans('laralum.treatment_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.treatment_create') }}</div>
    </div>
@endsection

@section('title', 'Add Treatment')
@section('icon', "plus")
@section('subtitle', 'Treatment')

@section('content')

    <div class="ui one column doubling stackable">
        <div class="about_sec hospital_info role_edt">
            <div class="ui very padded segment">
                <form method="POST" class="form form form_cond_lft">
                    {{ csrf_field() }}

                    @include('laralum.forms.master')

                    <div class="form-button_row">
                        <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@section('script')
<script>
    $( "#expiry_date" ).datepicker({format: "dd-mm-yy", autoclose:true});
</script>

<script>
    $( "#expiry_date" ).datepicker({format: "dd-mm-yy", autoclose:true});
</script>
@endsection