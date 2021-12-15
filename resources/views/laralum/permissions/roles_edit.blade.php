@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::permissions') }}">{{ trans('laralum.permissions_title') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{ trans('laralum.permissions_edit_title') }}</div>
    </div>
@endsection
@section('title', trans('laralum.permissions_edit_title'))
@section('icon', "edit")
@section('subtitle', trans('laralum.permissions_edit_subtitle', ['slug' => $model->slug]))
@section('content')
<div class="ui doubling stackable">
    <div class="ten wide column">
        <div class="ui very padded segment">
            <form class="ui form" method="POST">

                <div class="permission_list">
                    {{ csrf_field() }}
                    @foreach(\App\Role::all() as $role)
                    <p class="checbox_ch"> <input type="checkbox" name="roles[]" value="{{ $role->id }}" {{ $model->isChecked($role->id) }}> <label>{{ $role->name }}</label> </p>
                    @endforeach
                </div>

                <div class="permission_con">
                    <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
