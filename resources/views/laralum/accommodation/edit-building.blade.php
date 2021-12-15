@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::buildings') }}">{{ trans('laralum.building_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{ trans('laralum.building_edit') }}</div>
    </div>
@endsection
@section('title', trans('laralum.building_edit'))
@section('icon', "edit")
@section('content')
    <div class="ui doubling stackable">
         <div class="column about_sec hospital_info role_edt">
                @php
                    if(old('name')) {  $building_name = old('name'); }
                    elseif(isset($building_arr['name']) && !empty($building_arr['name'])){ $building_name = $building_arr['name']; }
                    else {   $building_name = '';    }

                    if(old('number_of_floors')) {  $number_of_floors = old('number_of_floors'); }
                    elseif(isset($building_arr['number_of_floors']) && !empty($building_arr['number_of_floors'])){ $number_of_floors = $building_arr['number_of_floors']; }
                    else {   $number_of_floors = '';    }
                @endphp

                <div class="ui very padded segment">
                    {{ csrf_field() }}
                    {!! Form::open(['route' => array('Laralum::building.update',$building_arr['id']),'class' => 'form form form_cond_lft']) !!}
                    {!! Form::hidden('building_id',$building_arr['id']) !!}
                    <div class="field ">
                        {!! Form::label('text', 'Building Name') !!}
                        {!! Form::text('building_name',$building_name,['required' , 'class' => 'form-control']) !!}
                    </div>
                    <div class="field ">
                        {!! Form::label('text', 'Number of floors') !!}
                        {!! Form::number('number_of_floors',$number_of_floors,['required' , 'class' => 'form-control' ]) !!}
                    </div>
                    <div class="field ">
                        {!! Form::label('text', 'Description') !!}
                        {!! Form::textArea('description',$building->description , ['class' => 'form-control']) !!}
                    </div>
                    <div class="field ">
                        {!! Form::label('text', 'Room Types') !!}
                        @foreach(\App\Room_Type::all() as $room_type)
                        <p class="checbox_ch line-block">
                            <input type="checkbox" name='room_types[]' value="{{ $room_type->id }}" {{ $building->isRoomTypeChecked($room_type->id) }}>
                            <label>{{ $room_type->name }}</label>
                        </p>
                            @endforeach
                    </div>
                    <div class="form-button_row">
                        {!! Form::submit('Submit' , ['class' => 'ui blue submit button'] ) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
    </div>
@endsection
