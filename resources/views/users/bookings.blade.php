@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Booking List</div>
    </div>
@endsection
@section('title', 'Bookings')
@section('icon', "pencil")
@section('subtitle', 'List of all bookings')
@section('content')
  <div class="ui one column doubling stackable grid container">

      <div class="column">

           <div class="ui very padded segment">
              {{-- <div class="">
                   <a href="{{ route('Laralum::booking.create') }}">Create New booking</a>
               </div>--}}
               @if(count($models) > 0)
                   <div class="pagination_con" role="toolbar">
                       <div class="pull-right">
                           {!!  \App\Settings::perPageOptions($count)  !!}
                       </div>
                   </div>
                   {{csrf_field()}}
                   <table class="ui table ">
                       <thead>
                       <tr>
                           <th>UHID</th>
                           <th>Patient Id</th>
                           <th>Booking Id</th>
                           <th>Created date</th>
                           <th>Status</th>
                           <th>Actions</th>
                       </tr>
                       </thead>
                       <tbody>
                       @foreach($models as $row)
                           <tr>
                               <td>{{ $row->getProfile('uhid') }}</td>
                               <td>{{ $row->getProfile('kid') }}</td>
                               <td>{{ $row->booking_id }}</td>
                               <td>{{ date_format(date_create($row->created_at),'d-m-Y') }}</td>
                               <td>{{ !empty($row->status) ? $row->getStatusOptions($row->status) : "Pending" }}</td>
                               <td>
                                   <div id="book-table"  class="ui  top icon blue left pointing dropdown button">
                                           <i class="configure icon"></i>
                                           <div class="menu">
                                               <div class="header">{{ trans('laralum.editing_options') }}</div>
                                               <a href="{{ route('Laralum::user.booking-detail', ['booking_id' => $row->id]) }}" class="item">
                                                   <i class="fa fa-eye"></i>
                                                   Booking Details
                                               </a>
                                                   {{--<div class="header">{{ trans('laralum.advanced_options') }}</div>
                                                   <a href="{{ route('Laralum::user.bookings.delete', ['id' => $row->id]) }}" class="item">
                                                       <i class="trash bin icon"></i>
                                                       Delete Booking
                                                   </a>--}}
                                           </div>
                                       </div>
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
                           </div>
                           <p>There are currently no bookings</p>
                       </div>
                   </div>
               @endif
          </div>
      </div>
  </div>
@endsection




