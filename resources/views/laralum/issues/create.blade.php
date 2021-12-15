@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::issues') }}">{{ trans('laralum.issue_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.issue_create') }}</div>
    </div>
@endsection
@section('title', 'Add Issue')
@section('icon', "plus")
@section('subtitle', 'Issue'))
@section('content')
    <div class="ui one column doubling stackable grid container">
        <div class="column">
            <div class="ui very padded segment">
                <form method="POST" class="ui form">
                    {{ csrf_field() }}
                    <div class="ui stackable grid">
                        <div class="three wide column"></div>
                        <div class="ten wide column">
                            @include('laralum.forms.master')
                        </div>
                        <div class="three wide column"></div>
                    </div>
                    <br><br>
                    <br>
                    <div class="field">
                        <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection