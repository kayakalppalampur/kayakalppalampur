@extends('layouts.admin.panel')
@section('breadcrumb')
	<div class="ui breadcrumb">
		<div class="active section">{{ $title }}</div>
	</div>
@endsection
@section('title', $title)
@section('icon', "pencil")
{{--@section('subtitle', $title)--}}
@section('content')
<div class="main_content_area">
	<div class="change-pass-outer">
	<div class="main_title icon_password white_bg">
		<h2>Please fill the required fields</h2>
	</div>
	<div class="pro_main_content">
		<div class="row">
			<div class="col-md-12 text-center">
				<div class="change-password_wrapper text-left">
					@if ($errors->any())
						<div class="alert alert-danger">
							<ul>
								{!! implode('', $errors->all('
								<li class="error">:message</li>
								')) !!}
							</ul>
						</div>
					@endif

					@if (session('status'))
						<div class="alert alert-success">
							{{ session('status') }}
						</div>
					@endif
					{!! Form::open([$user, 'route' => 'Laralum::user.post.change.password', 'class' => 'form profile_form','autocomplete' => 'off']) !!}
						<input type="hidden" name="email" value="{{  $user->email }}">
					<div class="form-group">
						<div class="row">

							<label class="col-sm-12">Old Password*</label>
							<div class="col-sm-12">
								{!! Form::password('old_password',['class'=>'form-control','required'=>'required', 'placeholder'=> 'Password','value'=>'']) !!}
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							{!! Form::label('password', 'New Password*', ['class'=>'col-sm-12 control-label']) !!}
							<div class="col-sm-12">
								{!! Form::password('password', ['class'=>'form-control','required'=>'required', 'placeholder'=> 'Password']) !!}
							</div>
						</div>
					</div>

					<div class="form-group">
						<div class="row">
							{!! Form::label('password_confirmation', 'Confirm password*', ['class'=>'col-sm-12 control-label']) !!}
							<div class="col-sm-12">
								{!! Form::password('password_confirmation', ['class'=>'form-control', 'required'=>'required','placeholder'=> 'Confirm password']) !!}
							</div>
						</div>
					</div>
					<input type="hidden" value="{{ $user->id }}" name="user_id">

					<div class="form-group">
						{!! Form::submit('Change Password', ['class' => 'btn btn-save-edit']) !!}
					</div>
					<div class="clearfix"></div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
	</div>
</div>
@endsection