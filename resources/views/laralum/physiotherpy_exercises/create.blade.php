@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::physiotherpy_exercise_categories.index') }}">{{ trans('laralum.physiotherpy_exercise_categories_title') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{ trans('laralum.physiotherpy_exercise_categories_create') }}</div>
    </div>
@endsection
@section('title', trans('laralum.physiotherpy_exercises_create_title'))
@section('icon', "plus")
@section('subtitle', trans('laralum.physiotherpy_exercises_create_subtitle'))
@section('content')
    <div class="ui one column doubling stackable grid">
        <div class="column">
            <div class="ui very padded segment">
                {{ Form::open(array('url' => url('/admin/physiotherpy_exercises/create') ,'method'=>'POST', 'files'=>'true')) }}
                <div class="row">
                    <div clss="col-md-3">
                        {{ Form::label('category_id', 'Category') }}
                        {{ Form::select('category_id', $category, 'Select Category',['class'=>'form-control'])  }}
                    </div>
                    {{ Form::label('name_of_excercise', 'Name of Exercise') }}
                    {{ Form::text('name_of_excercise', \Illuminate\Support\Facades\Input::old('name_of_excercise'), array('class' => 'form-control')) }}
                    {{ Form::label('description', 'Description') }}
                    {{ Form::textarea('description', \Illuminate\Support\Facades\Input::old('description'), array('class' => 'form-control','id'=>'messageArea')) }}

                    <div class="form-group">
                        <input class="multifile" type="file" name="image[]" multiple="multiple">
                    </div>

                    {{ Form::submit('Create!', array('class' => 'btn btn-primary')) }}
                </div>

                {{ Form::close() }}
            </div>
        </div>
    </div>



    <script type="text/javascript">


        $('.multifile').multifile();


        CKEDITOR.replace('messageArea',
            {
                customConfig: 'config.js',
                toolbar: 'simple'
            })
    </script>
@endsection
