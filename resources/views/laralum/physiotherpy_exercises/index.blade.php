@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">{{ trans('laralum.roles_title') }}</div>
    </div>
@endsection
@section('title', trans('laralum.physiotherpy_exercises_title'))
@section('icon', "star")
@section('subtitle', trans('laralum.physiotherpy_exercises_title_subtitle'))
@section('content')
    <div class="ui one column doubling stackable grid">
        <div class="column">
            <div class="ui very padded segment">
                @include('laralum.physiotherpy_exercises._index')

                @if(method_exists($exercises, "links"))
                    <div class="pagination_con main_paggination" role="toolbar">
                        {{ $exercises->links() }}
                    </div>
                @endif
            </div>
            <br>
        </div>
    </div>
@endsection
