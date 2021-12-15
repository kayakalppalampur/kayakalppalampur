{{--
@if(Session::has('status') && Session::get('message') != "")
    <div class="alert alert-{{ Session::get('status') }} alert-dismissible text-center" role="alert">
        <button type="button" class="close" data-dismiss="alert">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">Close</span>
        </button>
        <p>{!! Session::get('message') !!}</p>
    </div>
@endif--}}
