@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::groups') }}">{{ trans('laralum.groups_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.create_group') }}</div>
    </div>
@endsection
@section('title', 'Create Group')
@section('icon', "plus")
@section('subtitle', 'Group')
@section('content')
    <div class="ui one column doubling stackable">
        <div class="ui very padded segment">
            <div class="column about_sec hospital_info role_edt">
                <form method="POST" class="form form form_cond_lft" action="{{ url('/admin/group/add') }}">
                    {{ csrf_field() }}
                        <!-- STRING COLUMN -->
                        <div class="field ">
                            <label>Title</label>
                            <input type="text" value="" class="form-control" placeholder="Title" name="title" id="title">
                        </div>
                        <div class="field ">
                            <label>Description</label>
                            <textarea value="" placeholder="Title" class="form-control" name="description" id="description"></textarea>
                        </div>
                        <br>
                        <div class="form-button_row">
                            <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection