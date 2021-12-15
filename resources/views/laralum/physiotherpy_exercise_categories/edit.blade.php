@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::physiotherpy_exercise_categories.index') }}">{{ trans('laralum.physiotherpy_exercise_categories_title') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{ trans('laralum.physiotherpy_exercise_categories_edit') }}</div>
    </div>
@endsection
@section('title', trans('laralum.physiotherpy_exercise_categories_edit_title'))
@section('icon', "pencil")
@section('subtitle', trans('laralum.physiotherpy_exercise_categories_create_subtitle'))
@section('content')
    <div class="ui one column doubling stackable grid">
        <div class="column">
            <div class="ui very padded segment">

                {{ Form::open(array('url' => route('Laralum::physiotherpy_exercise_category_update',$category->id), 'method'=>'POST')) }}
                <div class="create_form_outer">
                    <div class="form-group">
                        {{ Form::label('title', 'Title') }}
                        {{ Form::text('title', $category->title, array('class' => 'form-control',)) }}
                    </div>

                    {{ Form::submit('Update!', array('class' => 'btn btn-primary')) }}
                </div>

                {{ Form::close() }}

            </div>
        </div>
    </div>
@endsection
