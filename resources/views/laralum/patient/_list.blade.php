@if(count($patients) > 0)
    <div class="pagination_con" role="toolbar">
        <div class="pull-right">
            {!!  \App\Settings::perPageOptions()  !!}
        </div>
    </div>
    {{csrf_field()}}
    <table class="ui five column table ">
        <thead>
        <tr>
            <th>Name</th>
            <th>Patient Id</th>
            {{--<th>Description</th>--}}
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($patients as $patient)
            <tr>
                <td>{{ $patient->name }}</td>
                <td>{{ $patient->userProfile->kid }}</td>
               {{-- <td>{{ $patient->description }}</td>--}}
                <td>
                <div id="book-table"  class="ui  top icon blue left pointing dropdown button">
                    <i class="configure icon"></i>
                    <div class="menu">
                        @if(Laralum::loggedInUser()->hasPermission('patients'))
                        <div class="header">{{ trans('laralum.editing_options') }}</div>
                        <a href="{{ route('Laralum::patient_details', ['id' => $patient->id]) }}" class="item no-disable">
                            <i class="edit icon"></i>
                            {{ trans('laralum.patient_details') }}
                        </a>
                        @endif
                       {{-- @if(Laralum::loggedInUser()->hasPermission('patients'))
                            <div class="header">{{ trans('laralum.advanced_options') }}</div>
                            <a href="{{ route('Laralum::kitchen-item.delete', ['id' => $patient->id]) }}" class="item no-disable">
                                <i class="trash bin icon"></i>
                                {{ trans('laralum.delete_patient') }}
                            </a>
                        @endif--}}
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
    <div class="pagination_con" role="toolbar">
        <div class="pull-right">
            {{ $patients->links() }}
        </div>
    </div>
@else
    <div class="ui negative icon message">
        <i class="frown icon"></i>
        <div class="content">
            <div class="header">
                {{ trans('laralum.missing_title') }}
            </div>
            <p>There are currently no feedback questions added.</p>
        </div>
    </div>
@endif