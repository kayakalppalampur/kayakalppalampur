@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::group-items') }}">{{ trans('laralum.group_items_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.group_items_create') }}</div>
    </div>
@endsection
@section('title', 'Add Group Item')
@section('icon', "plus")
@section('subtitle', 'Group Items')
@section('content')
    <div class="ui one column doubling stackable">
        <div class="ui very padded segment">
            <div class="column about_sec hospital_info role_edt">
                <form method="POST" class="form form form_cond_lft">
                    {{ csrf_field() }}
                        <!-- STRING COLUMN -->
                        <div class="field ">
                            <label>Group</label>
                            <select name="group_id" class="form-control" required>
                                @foreach(\App\InventoryGroup::all() as $group)
                                    <option value="{{ $group->id }}" {{ $group->id == old('group_id') ? 'checked' : '' }}>{{ $group->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field ">
                            <label>Title</label>
                            <input type="text" value="" class="form-control" placeholder="Title" name="title" id="title" required>
                        </div>
                        <div class="field ">
                            <label>Description</label>
                            <textarea value="" placeholder="Description" class="form-control" name="description" id="description"></textarea>
                        </div>
                        <div class="form-button_row">
                            <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script>
    $( "#expiry_date" ).datepicker({format: "dd-mm-yy", autoclose:true});
</script>
@endsection