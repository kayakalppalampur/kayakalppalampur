@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::groups') }}">{{ trans('laralum.groups_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.edit_group') }}</div>
    </div>
@endsection
@section('title', 'Edit Group')
@section('icon', "plus")
@section('subtitle', 'Group')
@section('content')
    <div class="ui one column doubling stackable">
        <div class="ui very padded segment">
            <div class="column about_sec hospital_info role_edt">
                <form method="POST" class="form form form_cond_lft" action="{{ url('/admin/group/edit/'.$model->id) }}">
                    {{ csrf_field() }}
                        <!-- STRING COLUMN -->
                        <div class="field ">
                            <label>Title</label>
                            <input type="text" value="{{ $model->title }}" class="form-control" placeholder="Title" name="title" id="title">
                        </div>
                        <div class="field ">
                            <label>Description</label>
                            <textarea value="" class="form-control" placeholder="Title" name="description"id="description">{{ $model->description }}</textarea>
                        </div>
                        <div class="form-button_row">
                            <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection