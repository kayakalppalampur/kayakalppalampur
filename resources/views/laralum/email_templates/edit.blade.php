@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::document_types') }}">{{ trans('laralum.document_type_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{ trans('laralum.document_types_edit_title') }}</div>
    </div>
@endsection
@section('title', trans('laralum.document_types_edit_title'))
@section('icon', "edit")
@section('subtitle', trans('laralum.document_types_edit_title_name', ['name' => $row->title]))
@section('content')
<div class="ui doubling stackable grid container">
    <div class="three wide column"></div>
    <div class="ten wide column">
        <div class="ui very padded segment">
            <form class="ui form" method="POST">
                {{ csrf_field() }}
                @include('laralum/forms/master')

                <div class="field ">
                    <label>File To download</label>
                    <input type="file"  name="file" />
                </div>

                <div class="field ">
                    <label>User Type</label>
                    <input type="checkbox" name="status[]" {{ $row->isChecked(\App\DocumentType::STATUS_INDIAN_CLIENT) }} value="{{ \App\DocumentType::STATUS_INDIAN_CLIENT }}"> Indian Client<br>
                    <input type="checkbox" name="status[]" {{ $row->isChecked(\App\DocumentType::STATUS_FOREIGN_CLIENT) }} value="{{ \App\DocumentType::STATUS_FOREIGN_CLIENT }}"> Foreign Client
                </div>
                <br>
                <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
            </form>
        </div>
    </div>
    <div class="three wide column"></div>
</div>
@endsection
