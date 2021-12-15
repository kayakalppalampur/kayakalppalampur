@if(count($models) > 0)
    @if(isset($count))
        {{csrf_field()}}
        <div class="pagination_con" role="toolbar">
            <div class="pull-right">
                {!!  \App\Settings::perPageOptions(\App\ConsultationCharge::count())  !!}
            </div>
        </div>
    @endif
    <table class="ui table table_cus_v last_row_bdr">
        <thead>
        <tr>
            <th>Charges</th>
            <th>Edit</th>
        </tr>
        </thead>
        <tbody>
        @foreach($models as $model)
            <tr>
                <td>{{ $model->charges }}</td>
                <td><a href="{{ url('admin/consultation-charges/add') }}"><i class="fa fa-edit"></i> </a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @if(method_exists($models, "links"))
        <div class="pagination_con main_paggination" role="toolbar">
            {{ $models->links() }}
        </div>
    @endif
@else
    <div class="ui negative icon message">
        <i class="frown icon"></i>
        <div class="content">
            <div class="header">
                {{ trans('laralum.missing_title') }}
            </div>
            <p>There are currently no Lab Tests added for the selected date</p>
        </div>
    </div>
@endif