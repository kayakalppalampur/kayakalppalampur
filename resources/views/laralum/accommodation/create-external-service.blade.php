@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <a class="section" href="{{ route('Laralum::buildings') }}">{{ trans('laralum.external_services_list') }}</a>
    <i class="right angle icon divider"></i>
    <div class="active section">{{  trans('laralum.external_services_add') }}</div>
</div>
@endsection
@section('title', trans('laralum.external_services_add'))
@section('icon', "plus")
@section('content')
<div class="ui one column doubling stackable grid container">
    <div class="column">
        <div class="ui very padded segment">
            <div class="ui container">
                <div class="ui stackable grid">
                    <div class="row">
                        <div class="ten wide column">
                            {!! Form::open(['route' => 'Laralum::external_service.store','class' => 'ui form']) !!}
                            {{ csrf_field() }}
                            <div class="field ">
                                {!! Form::label('text', 'External Service Name') !!}
                                {!! Form::text('name',old('ext_service_name'),['required']) !!}
                            </div>
                         {{--   <div class="field ">
                                {!! Form::label('text', 'room_id') !!}
                                {!! Form::select('room_id', \App\Room::getRoomNo(),old('room_id'), ['required']) !!}
                            </div>--}}
                            <div class="field ">
                                {!! Form::label('text', 'Price (per Day)') !!}
                                {!! Form::text('price', old('price'), ['required']) !!}
                            </div>
                            <div class="field ">
                                {!! Form::label('textarea', 'Description') !!}
                                {!! Form::textarea('desc',old('ext_service_desc')) !!}
                            </div>

                            <div class="field ">
                                {!! Form::submit('Submit') !!}
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
