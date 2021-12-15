@if(count($models) > 0)
    @if(isset($count))
    {{csrf_field()}}
    <div class="pagination_con" role="toolbar">
        <div class="pull-right">
            {!!  \App\Settings::perPageOptions(\App\TreatmentPackage::count())  !!}
        </div>
    </div>
    @endif
    <table class="ui five column table ">
        <thead>
        <tr>
            <th>Package Name</th>
            <th>Department</th>
            <th>Treatments</th>
            <th>Duration</th>
            <th>Price (in Rs.)</th>
            @if(!isset($print))
            <th>Actions</th>
                @endif
        </tr>
        </thead>
        <tbody>
        @foreach($models as $model)
            <tr>
                <td>{{ $model->package_name }}</td>
                <td>{{ $model->department->title }}</td>
                <td>{!! $model->getTreatmentsList()  !!}</td>
                <td>{{ $model->getDuration() }}</td>
                <td>{{ $model->price }}</td>
                @if(!isset($print))
                <td>
                <div id="book-table"  class="ui  top icon blue left pointing dropdown button">
                    <i class="configure icon"></i>
                    <div class="menu">
                        @if(Laralum::loggedInUser()->hasPermission('treatments'))
                        <div class="header">{{ trans('laralum.editing_options') }}</div>
                        <a href="{{ route('Laralum::treatment_packages.edit', ['id' => $model->id]) }}" class="item no-disable">
                            <i class="edit icon"></i>
                         Edit
                        </a>
                        @endif
                        @if(Laralum::loggedInUser()->hasPermission('treatments'))
                            <div class="header">{{ trans('laralum.advanced_options') }}</div>
                            <a href="{{ route('Laralum::treatment_packages.delete', ['id' => $model->id]) }}" class="item no-disable">
                                <i class="trash bin icon"></i>
                                Delete
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
                    @endif
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
                {{ $search == true ? $error : "No Package found" }}
            </div>
            <p>There are currently no Packages</p>
        </div>
    </div>
@endif