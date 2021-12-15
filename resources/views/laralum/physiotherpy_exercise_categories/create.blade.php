@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::physiotherpy_exercise_categories.index') }}">{{ trans('laralum.physiotherpy_exercise_categories_title') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{ trans('laralum.physiotherpy_exercise_categories_create') }}</div>
    </div>
@endsection
@section('title', trans('laralum.physiotherpy_exercise_categories_create_title'))
@section('icon', "plus")
@section('subtitle', trans('laralum.physiotherpy_exercise_categories_create_subtitle'))
@section('content')
    <div class="ui one column doubling stackable grid">
        <div class="column">
            <div class="ui very padded segment">

                {{ Form::open(array('url' => url('/admin/physiotherpy_exercise_categories/create') ,'method'=>'POST')) }}
                <div class="create_form_outer">
                    <div class="form-group">
                        {{ Form::label('title', 'Title') }}
                        {{ Form::text('title', \Illuminate\Support\Facades\Input::old('title'), array('class' => 'form-control')) }}
                    </div>

                   {{ Form::submit('Create the Category!', array('class' => 'btn btn-primary')) }}
               </div>
                {{ Form::close() }}

            </div>
        </div>
    </div>
@endsection
