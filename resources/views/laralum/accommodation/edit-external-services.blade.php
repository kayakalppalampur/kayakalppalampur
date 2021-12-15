@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::buildings') }}">{{ trans('laralum.external_services_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.external_services_edit') }}</div>
    </div>
@endsection
@section('title', trans('laralum.external_services_edit'))
@section('icon', "edit")
@section('content')
    <div class="ui one column doubling stackable grid">
        <div class="column">
            <div class="ui very padded segment">
                <div class="about_sec">
                    <div class="ui stackable grid">
                        <div class="row">
                            <div class="ten wide column">
                                @if(!empty($service_arr))
                                    @php
                                        if(old('name')) {  $name = old('name'); }
                                        elseif(isset($service_arr['name']) && !empty($service_arr['name'])){
                                            $name = $service_arr['name'];
                                        }
                                        else {   $name = '';    }
                                        if(old('desc')) {  $name = old('desc'); }
                                        elseif(isset($service_arr['desc']) && !empty($service_arr['desc'])){
                                            $desc = $service_arr['desc'];
                                        }
                                        else {   $desc = '';    }

                                        if(old('price')) {  $price = old('price'); }
                                        elseif(isset($service_arr['price']) && !empty($service_arr['price'])){
                                            $price = $service_arr['price'];
                                        }
                                        else {   $price = '';    }
                                    @endphp
                                    {{ csrf_field() }}
                                    {!! Form::open(['route' => array('Laralum::external_service.update',$service_arr['id']),'class' => 'form']) !!}
                                    <div class="form-group">
                                        {!! Form::label('text', 'External Service Name') !!}
                                        {!! Form::text('name', $name, ['required', 'class' => 'form-control']) !!}
                                    </div>
                                    {{--<div class="field ">
                                        {!! Form::label('text', 'room_id') !!}
                                        {!! Form::select('room_id', \App\Room::getRoomNo(),old('room_id', $service_arr['room_id']), ['required']) !!}
                                    </div>--}}
                                    <div class="form-group">
                                        {!! Form::label('text', 'Price (per Day)') !!}
                                        {!! Form::text('price', $price, ['required' , 'class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group ">
                                        {!! Form::label('textarea', 'Description') !!}
                                        {!! Form::textarea('desc', $desc , ['class' => 'form-control form-testimonial']) !!}
                                    </div>
                                    <div class="form-group btn_signup_con">
                                        {!! Form::submit('Submit' , ['class' => 'ui blue submit button']) !!}
                                    </div>
                                    {!! Form::close() !!}
                                @else
                                    Invalid service
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
