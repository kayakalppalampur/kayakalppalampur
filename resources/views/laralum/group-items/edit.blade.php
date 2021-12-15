@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::group-items') }}">{{ trans('laralum.group_items_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Edit Group Item</div>
    </div>
@endsection
@section('title', 'Edit Group Item')
@section('icon', "edit")
@section('content')
<div class="ui one column doubling stackable">
    <div class="ui very padded segment">
        <div class="column about_sec hospital_info role_edt">
            <form class="form form form_cond_lft" method="POST">
                {{ csrf_field() }}
                <div class="field ">
                    <label>Group</label>
                    <select name="group_id" class="form-control">
                        <option>Select All</option>
                        @foreach(\App\InventoryGroup::all() as $group)
                            <option {{ $row->group_id == $group->id ? "selected" : "" }} value="{{ $group->id }}" {{ $group->id == old('group_id') ? 'checked' : '' }}>{{ $group->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field ">
                    <label>Title</label>
                    <input type="text" value="{{ $row->title }}" class="form-control" placeholder="Title" name="title" id="title">
                </div>
                <div class="field ">
                    <label>Description</label>
                    <textarea value="" placeholder="Description" class="form-control" name="description" id="description">{!! $row->description !!}</textarea>
                </div>
                <div class="form-button_row">
                      <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
