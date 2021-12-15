@if(count($models) > 0)
    {{csrf_field()}}
    <div class="pagination_con" role="toolbar">
        <div class="pull-right">
            {!!  \App\Settings::perPageOptions(\App\DocumentType::count())  !!}
        </div>
    </div>
    <table class="ui five column table ">
        <thead>
        <tr>
            <th>Period</th>
            <th>Temolate Id</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($models as $model)
            <tr>
                <td>{{ $model->getPeriod() }}</td>
                <td>{{ $model->template->handle }}</td>
                <td>
                <div id="book-table"  class="ui  top icon blue left pointing dropdown button">
                    <i class="configure icon"></i>
                    <div class="menu">
                        @if(Laralum::loggedInUser()->hasPermission('followup_settings'))
                        <div class="header">{{ trans('laralum.editing_options') }}</div>
                            <a href="{{ url('admin/followup-settings/'.$model->id.'/edit') }}" class="item no-disable">
                            <i class="edit icon"></i>
                            {{ trans('laralum.edit_followup_setting') }}
                        </a>
                        @endif
                        @if(Laralum::loggedInUser()->hasPermission('followup_settings'))
                            <div class="header">{{ trans('laralum.advanced_options') }}</div>
                            <a href="{{ url('admin/followup-settings/'.$model->id.'/delete') }}" class="item no-disable">
                                <i class="trash bin icon"></i>
                                {{ trans('laralum.delete_followup_setting') }}
                            </a>
                        @endif
                    </div>
                </div>
                  {{--  @else
                        <div class="ui disabled blue icon button">
                            <i class="lock icon"></i>
                        </div>
                    @endif--}}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @if(method_exists($models, "links"))
    <div class="pagination_con" role="toolbar">
        <div class="pull-right">
            {{ $models->links() }}
        </div>
    </div>
        @endif
@else
    <div class="ui negative icon message">
        <i class="frown icon"></i>
        <div class="content">
            <div class="header">
                {{ trans('laralum.missing_title') }}
            </div>
            <p>There are currently no followup settings added for the selected date</p>
        </div>
    </div>
@endif