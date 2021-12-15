@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">{{ trans('laralum.admin_settings_list') }}</div>
    </div>
@endsection
@section('title',  trans('laralum.admin_settings'))
@section('icon', "pencil")
@section('subtitle', 'List of all Admin Settings')
@section('content')
    <div class="ui one column doubling stackable">
        <div class="column">
            <div class="ui very padded segment table_header_row table-responsive" id="department_list">
                @if(count($admin_settings) > 0)


                    {{csrf_field()}}
                    <table class="ui table table_cus_v last_row_bdr">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($admin_settings as $admin_setting)
                            <tr>
                                <td><span>{{ $admin_setting->setting_name }} </span> </td>
                                <td><span id="view_{{ $admin_setting->id }}">{{ $admin_setting->price }}</span>
                                    <span style="display:none;" id="edit_{{ $admin_setting->id }}">
                                        <form class="setting_form" method="post">
                                            {!! csrf_field() !!}
                                            <div class="feedback_input">
                                                <input type="text" class="form-control" name="price_{{ $admin_setting->id }}"
                                                   value="{{ $admin_setting->price }}">

                                                <input type="hidden" name="setting_id" value="{{ $admin_setting->id }}"/>
                                                <button class="btn ui blue">Save</button>
                                            </div>
                                        </form>

                                    </span>
                                </td>
                                <td>
                                    <div id="book-table"  class="ui  top icon blue left pointing dropdown button">
                                        <i class="configure icon"></i>
                                        <div class="menu">
                                                <div class="header">{{ trans('laralum.editing_options') }}</div>
                                                <button id="edit_setting_{{$admin_setting->id}}" class="item no-disable">
                                                    <i class="edit icon"></i>
                                                    {{ trans('laralum.edit_admin_setting') }}
                                                </button>                                            
                                        </div>
                                    </div>
                                    
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @if(method_exists($admin_settings, "links"))
                        <div class="pagination_con main_paggination" role="toolbar">
                             {{ $admin_settings->links() }}
                        </div>
                    @endif
                @else
                    <div class="ui negative icon message">
                        <i class="frown icon"></i>
                        <div class="content">
                            <div class="header">
                                {{ trans('laralum.missing_title') }}
                            </div>
                            <p>There are currently no admin setting added.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@section("js")
 <script>
        $(document).delegate("[id^=edit_setting_]", 'click', function () {
            var id = $(this).attr("id").split("edit_setting_")[1];
            console.log("id" + id);
            $("#edit_" + id).show();
            $("#view_" + id).hide();
        })
    </script>
@endsection


