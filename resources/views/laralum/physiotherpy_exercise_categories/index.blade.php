@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">{{ trans('laralum.physiotherpy_exercise_categories_title') }}</div>
    </div>
@endsection
@section('title', trans('laralum.physiotherpy_exercise_categories_title'))
@section('icon', "star")
@section('subtitle', trans('laralum.physiotherpy_exercise_categories_title_subtitle'))
@section('content')
    <div class="ui one column doubling stackable grid">
        <div class="column">
            <div class="ui very padded segment">

                @include('laralum.physiotherpy_exercise_categories._list')

                @if(method_exists($categories, "links"))
                    <div class="pagination_con main_paggination" role="toolbar">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>
            <br>
        </div>
    </div>
@endsection
