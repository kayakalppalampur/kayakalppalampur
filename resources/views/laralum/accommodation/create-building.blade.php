@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::buildings') }}">{{ trans('laralum.building_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.building_add') }}</div>
    </div>
@endsection
@section('title', trans('laralum.building_add'))
@section('icon', "plus")
@section('content')
    <div class="ui one column doubling stackable grid container">
        <div class="column">
            <div class="ui very padded segment">
                <div class="ui container">
                    <div class="ui stackable grid">
                        <div class="row">
                            <div class="ten wide column">
                                {{ csrf_field() }}
                                {!! Form::open(['route' => 'Laralum::building.store','class' => 'ui form']) !!}
                                <div class="field ">
                                    {!! Form::label('text', 'Building Name') !!}
                                    {!! Form::text('building_name',old('building_name'),['required']) !!}
                                </div>
                                <div class="field ">
                                    {!! Form::label('text', 'Number of floors') !!}
                                    {!! Form::number('number_of_floors',old('number_of_floors'),['required']) !!}
                                </div>
                                <div class="field ">
                                    {!! Form::label('text', 'Description') !!}
                                    {!! Form::textArea('description',old('description')) !!}
                                </div>
                                <div class="field ">
                                    {!! Form::label('text', 'Room Types') !!}
                                    @foreach(\App\Room_Type::all() as $room_type)
                                        <input type="checkbox" name='room_types[]' value="{{ $room_type->id }}" >{{ $room_type->name }}<br/>
                                    @endforeach
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
