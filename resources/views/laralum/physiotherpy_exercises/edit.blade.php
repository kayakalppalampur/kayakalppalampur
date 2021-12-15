@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::physiotherpy_exercise_categories.index') }}">{{ trans('laralum.physiotherpy_exercise_categories_title') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{ trans('laralum.physiotherpy_exercise_categories_edit') }}</div>
    </div>
@endsection
@section('title', trans('laralum.physiotherpy_exercises_create_title'))
@section('icon', "plus")
@section('subtitle', trans('laralum.physiotherpy_exercises_create_subtitle'))
@section('content')
    <div class="ui one column doubling stackable grid">
        <div class="column">
            <div class="ui very padded segment">


                {{ Form::open(array('url' => route('Laralum::physiotherpy_exercise_update',$exercise->id) ,'method'=>'POST','files'=>'true')) }}
                <div class="row">
                    <div clss="col-md-3">

                        {{ Form::label('category_id', 'Category') }}
                        {{ Form::select('category_id', $list, $exercise->category_id,['class'=>'form-control'])  }}

                    </div>


                    {{ Form::label('name_of_excercise', 'Name of Exercise') }}
                    {{ Form::text('name_of_excercise', $exercise->name_of_excercise, array('class' => 'form-control')) }}
                    {{ Form::label('description', 'Description') }}
                    {{ Form::textarea('description',$exercise->description , array('class' => 'form-control','id'=>'messageArea')) }}

                    @php
                        $images=\App\SystemFile::where(['model_id'=>$exercise->id,'model_type'=>get_class($exercise)])->get();

                    @endphp
                    @if(!empty($images))
                        <div class="row">
                            @foreach($images as $image)



                                <img src="{{ asset('../storage/app/').'/'.$image->disk_name }}" class="set-image">
                                <a href="javascript:" id="delete_image_{{$image->id}}" data-id="{{$image->id}}">x</a>

                            @endforeach

                        </div>
                    @endif
                    
                        <div class="form-group">
                            <input class="multifile" type="file" name="image[]" multiple="multiple">
                        </div>



                        {{ Form::submit('Create!', array('class' => 'btn btn-primary')) }}
                    

                </div>

                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection

@section("js")

    )

    <script type="text/javascript">


        $('.multifile').multifile();
        CKEDITOR.replace('messageArea',
            {
                customConfig: 'config.js',
                toolbar: 'simple'
            })

        $("a[id^=delete_image_]").click(function () {

            var id = $(this).attr('data-id');

            deleteImages(id);

        });


        function deleteImages(id) {


            $.ajax({
                url: '{{route('Laralum::delete-exercise-images')}}',
                type: "POST",
                data: {
                    id: id,
                    _token: '{{csrf_token()}}',

                },
                cache: false,
                success: function (data) {

                    if (data.status == "OK") {

                        location.reload();

                    } else {
                        alert('Something Wrong');
                    }

                }
            });


        }


    </script>
@endsection
