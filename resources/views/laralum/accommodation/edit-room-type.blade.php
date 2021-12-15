@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::room_types') }}">{{ trans('laralum.room_types') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{ trans('laralum.room_type_edit') }}</div>
    </div>
@endsection
@section('title', trans('laralum.room_type_edit'))
@section('icon', "edit")
@section('content')
    <div class="ui doubling stackable ">
        <div class="column about_sec hospital_info role_edt">
            @if(!empty($room_type_arr))
                @php
                    if(old('name')) {  $name = old('name'); }
                    elseif(isset($room_type_arr['name']) && !empty($room_type_arr['name'])){ $name = $room_type_arr['name']; }
                    else {   $name = '';    }

                    if(old('number_of_floors')) {  $price = old('number_of_floors'); }
                    elseif(isset($room_type_arr['price']) && !empty($room_type_arr['price'])){ $price = $room_type_arr['price']; }
                    else {   $price = '';    }

                    if(old('short_name')) {  $short_name = old('short_name'); }
                    elseif(isset($room_type_arr['short_name']) && !empty($room_type_arr['short_name'])){ $short_name = $room_type_arr['short_name']; }
                    else {   $short_name = '';    }
                @endphp
                <div class="ui very padded segment">
                    {{ csrf_field() }}
                    {!! Form::open(['route' => array('Laralum::room_type.update',$room_type_arr['id']),'class' => 'form form form_cond_lft']) !!}
                    {!! Form::hidden('room_type_id',$room_type_arr['id']) !!}
                    <div class="field ">
                        {!! Form::label('text', 'Name') !!}
                        {!! Form::text('name',$name, ['class' => 'form-control'] ,['required']) !!}
                    </div>
                   {{-- <div class="field ">
                        {!! Form::label('text', 'Price') !!}
                        {!! Form::number('price',$price,['required']) !!}
                    </div>--}}
                    <div class="field ">
                        {!! Form::label('text', 'Short Name') !!}
                        {!! Form::text('short_name',$short_name,['required' , 'class' => 'form-control']) !!}
                    </div>
                    <div class="form-button_row">
                        {!! Form::submit('Submit' , ['class' => 'ui blue submit button']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            @else
                <div class="ui very padded segment">No data Found</div>
           @endif
        </div>
    </div>
@endsection
