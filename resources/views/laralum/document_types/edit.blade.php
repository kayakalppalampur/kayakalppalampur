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
<div class="ui one column doubling stackabler">
    <div class="ui very padded segment">
        <div class="column about_sec hospital_info role_edt">
            <form class="form form form_cond_lft" method="POST"  enctype="multipart/form-data">
                {{ csrf_field() }}
                @include('laralum/forms/master')

                <div class="field ">
                    <label>File To download</label>
                    <input type="file"  name="file" />
                    @if($row->is_downloadable == \App\DocumentType::IS_DOWNLOADABLE)   <a class="no-disable" href="{{ \App\Settings::getDownloadUrl($row->file, $row->file_name) }}">Download {{ $row->title }}</a>
                    @endif
                </div>

                <div class="field permission_list">
                    <label>User Type</label>
                    <p class="checbox_ch">
                        <input type="checkbox" name="status[]" {{ $row->isChecked(\App\DocumentType::STATUS_INDIAN_CLIENT) }} value="{{ \App\DocumentType::STATUS_INDIAN_CLIENT }}">
                        <label>Indian Client</label>
                    </p>

                    <p class="checbox_ch">
                        <input type="checkbox" name="status[]" {{ $row->isChecked(\App\DocumentType::STATUS_FOREIGN_CLIENT) }} value="{{ \App\DocumentType::STATUS_FOREIGN_CLIENT }}">
                        <label>Foreign Client</label>
                    </p>
                </div>

                <div class="form-button_row">
                    <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
