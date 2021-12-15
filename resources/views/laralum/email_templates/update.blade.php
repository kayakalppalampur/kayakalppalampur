@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ url("admin/email-templates") }}">{{ trans('laralum.email_templates_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.email_templates_update') }}</div>
    </div>
@endsection
@section('title', 'Update Email Template')
@section('icon', "plus")
@section('subtitle', 'Email Template')
@section('content')
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('/laralum_public/css/jquery.datetimepicker.css') }}">
    <div class="ui one column doubling stackable">
        <div class="ui very padded segment">
            <div class="column about_sec hospital_info role_edt">
                <form enctype="multipart/form-data"  id="demo-form2" data-parsley-validate class="form form form_cond_lft" method="post" action="{{ url('admin/email-templates/'.$model->id) }}">
                    <input type="hidden" name="_method" value="PUT">
                    {!! csrf_field() !!}
                    {{--<div class="field ">
                        <label class="" for="code">Email From <span class="required">*</span>
                        </label>
                        <input name="code" type="text" value="{{ old('code') }}" required="required" class="">
                    </div>--}}
                    <div class="field ">
                        <label class="" for="event_id">Event<span class="required">*</span> </label>
                        <select name="event_id" class="form-control" id="event_id">
                            @foreach(\App\EmailTemplate::getEventOptions(null,null) as $key => $val)
                                <option value="{{ $key }}" {{ $model->event_id == $key ? "selected" : "" }}>{{$val}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field" id="date-div">
                        <label class="" for="sent_date_time">Choose date and time<span class="required">*</span> </label>
                        <input name="sent_date_time" type="text" value="{{ old('sent_date_time', date('d-m-Y H:i', strtotime($model->sent_date_time))) }}" required="required" class="datetimepicker form-control">
                    </div>
                    <div class="field ">
                        <label class="" for="from_email">Email From <span class="required">*</span> </label>
                        <input name="from_email" type="text" value="{{ old('from_email', $model->from_email) }}" required="required" class="form-control">
                    </div>



                    <div class="field ">
                        <label class="" for="from_name">From Name <span class="required">*</span> </label>
                        <input name="from_name" type="text" value="{{ old('from_name', $model->from_name) }}" required="required" class="form-control">
                    </div>


                    <div class="field ">
                        <label class="" for="reply_to_email">Reply to Email <span class="required">*</span> </label>
                        <input name="reply_to_email" type="text" value="{{ old('reply_to_email', $model->reply_to_email) }}" required="required" class="form-control">
                    </div>

                    <div class="field ">
                        <label class="" for="name">Subject<span class="required">*</span> </label>
                        <input name="subject" type="text" value="{{ old('subject', $model->subject) }}" required="required" class="form-control">
                    </div>

                    <div class="field ">
                        <label class="" for="name">Send Email<span class="required">*</span> </label>

                        <div class="pymnt_opt_row">
                            <div class="pymt_inn">
                                <input type="radio" name="status" value="{{ \App\EmailTemplate::STATUS_DISABLE }}" {{ old('status', $model->status) == \App\EmailTemplate::STATUS_DISABLE ? 'checked' : '' }}>
                                <span> Disable </span>
                            </div>
                        </div>

                        <div class="pymnt_opt_row">
                            <div class="pymt_inn">
                                <input type="radio" name="status" value="{{ \App\EmailTemplate::STATUS_ENABLE }}" {{ old('status', $model->status) == \App\EmailTemplate::STATUS_ENABLE ? 'checked' : '' }}>
                                <span>Enable</span>
                            </div>
                        </div>

                        {{--<input type="radio" name="status" value="{{ \App\EmailTemplate::STATUS_DISABLE }}">Disable<br>
                        <input type="radio" name="status" value="{{ \App\EmailTemplate::STATUS_ENABLE }}">Enable--}}
                    </div>

                    <div class="field ">
                        <label class="" for="name">Content <span class="required">*</span> </label>
                        <b>Custom Text Fields</b><br/>
                        @foreach(\App\EmailTemplate::getCustomText() as $text)
                            <div class="tag"><span id="text_{{ '&#123;&#123;' . $text. '&#125;&#125;' }}"> {{ $text }}</span></div>
                        @endforeach
                        <div class="clearfix"></div>
                        <br/>

                        <textarea id="description" name="content" class="">{{ old('content', $model->content) }}</textarea>
                    </div>

                    <input type="hidden" name="layout_id" value="{{ @\JDT\LaravelEmailTemplates\Entities\EmailLayout::first()->id }}">
                    <div class="form-button_row">
                        <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    <script  src="{{ asset('/laralum_public/js/jquery.datetimepicker.js') }}"></script>
    <script>
        $("[id^=text_]").click(function () {
            console.log("sd");
            var text = $(this).attr('id').split('text_')[1];
            var val = $("#description").val();
            $("#description").val(val+text)
            CKEDITOR.instances.description.setData( val+text);
        })
        $(".datetimepicker").datetimepicker({
            format: 'd-m-Y H:i'
        });

        CKEDITOR.replace( 'description' );
        CKEDITOR.config.filebrowserBrowseUrl = '{{ url('/elfinder/ckeditor') }}';
        /*$('textarea').ckeditor({
         allowedContent:true,
         filebrowserBrowseUrl: '/elfinder/ckeditor'
         });*/

        $("#event_id").change(function () {
            if ($(this).val() == "{{ \App\EmailTemplate::EVENT_OTHER }}") {
                $("#date-div").show();
            }else{
                $("#date-div").hide();
                $(".datetimepicker").val("");
            }
        })

        $("#group_id").change(function () {
            var id = $(this).val();
            $.ajax({
                url:"{{ url('admin/email-template/group-events') }}",
                type:"POST",
                data:{'group_id' : id,'_token':"{{ csrf_token() }}"},
                success:function (data) {
                    var options = "";
                    for(key in data) {
                        options  += "<option value='"+key+"'>"+data[key]+"</option>";
                    }
                    $("#event_id").html(options);
                }
            })
        })
    </script>
@endsection