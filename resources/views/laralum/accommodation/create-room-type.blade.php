@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::room_types') }}">{{ trans('laralum.room_types') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.room_type_add') }}</div>
    </div>
@endsection
@section('title', trans('laralum.room_type_add'))
@section('icon', "plus")
@section('content')
    <div class="ui one column doubling stackable grid container">
        <div class="column">
            <div class="ui very padded segment">
                <div class="ui container">
                    <div class="ui stackable grid">
                        <div class="row">
                            <div class="ten wide column">
                                {!! Form::open(['route' => 'Laralum::room_type.store','class' => 'ui form']) !!}
                                {{ csrf_field() }}
                                <div class="field ">
                                    {!! Form::label('text', 'Name') !!}
                                    {!! Form::text('room_type_name',old('room_type_name'),['required']) !!}
                                </div>
                              {{--  <div class="field ">
                                    {!! Form::label('text', 'Price') !!}
                                    {!! Form::number('room_type_price',old('room_type_price'),['required','step'=>'any','min' => '0']) !!}
                                </div>--}}
                                <div class="field ">
                                    {!! Form::label('text', 'Short Name') !!}
                                    {!! Form::text('short_name',old('short_name'),['required']) !!}
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
