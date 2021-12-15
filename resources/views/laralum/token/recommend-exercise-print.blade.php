@extends('layouts.front.web_layout')
@section('content')
    <style type="text/css" media="print">
        @page
        {
            size: auto;   /* auto is the initial value */
            margin: 0mm;  /* this affects the margin in the printer settings */
        }
        .date{
            background-color: #ccc;
            float: left;
            padding: 8px;
            width: 75%;
        }
    </style>

    <div class="ui one column doubling stackable grid container">

        <div class="column">

        </div>
    </div>

    <div class="token-receipt" id="mySelector">
        <div class="receipt-for">
            <p>@if(!isset($print)) <a id="print" class="btn btn-primary ui button blue">Print</a> <a id="back" class="btn btn-primary ui button blue" href="{{ \Illuminate\Support\Facades\URL::previous() }}">Back</a>  @endif</p>
        </div>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                {{--<button type="button" class="modal-close close" data-dismiss="modal">&times;</button>--}}
                <h4 class="modal-title">{{ $exercise->name_of_excercise }}</h4>
            </div>
            <div class="modal-body">

                @php
                    $images=\App\SystemFile::where('model_id',$exercise->id)->where('model_type',get_class($exercise))->get();

                @endphp
                @foreach($images as $image)
                    <img src="{{ asset('../storage/app/').'/'.$image->disk_name }}" class="set-image">
                @endforeach
                <p>{!! $exercise->description !!}  </p>


            </div>
            {{--<div class="modal-footer">
                <button type="button" class="ui blue btn btn-default modal-close" data-dismiss="modal">Close
                </button>
            </div>--}}
        </div>
    </div>
@endsection
@section('script')
    <script>
        $("#print").click(function () {
            $(this).hide();
            $("#back").hide();
            window.print();
            $(this).show();
            $("#back").show();

        });
        /* $("#mySelector").printThis({
         debug: false,               /!* show the iframe for debugging*!/
         importCSS: true,            /!* import page CSS*!/
         importStyle: true,       /!* import style tags*!/
         printContainer: true,      /!* grab outer container as well as the contents of the selector*!/
         loadCSS: [
         "{{ asset('/css/style.css') }}",
         "{{ asset('js/bootstrap.min.js') }}",
         "{{ asset('/css/font-awesome.min.css')}}",
         "{{ asset('/css/animate.css') }}",
         "{{ asset('css/jquery.steps.css') }}"],  /!* path to additional css file - use an array [] for multiple*!/
         pageTitle: "",              /!* add title to print page*!/
         removeInline: false,       /!* remove all inline styles from print elements*!/
         printDelay: 333,           /!* variable print delay; depending on complexity a higher value may be necessary*!/
         header: null,              /!* prefix to html*!/
         footer: null,              /!* postfix to html*!/
         base: false     ,           /!* preserve the BASE tag, or accept a string for the URL*!/
         formValues: true,            /!* preserve input/form values*!/
         canvas: false ,             /!* copy canvas elements (experimental)*!/
         doctypeString: ""        /!* enter a different doctype for older markup*!/
         });*/
        function PrintElem(elem)
        {
            Popup($('<div/>').append($(elem).clone()).html());
        }

        function Popup(data)
        {
            var mywindow = window.open('', 'my div', 'height=400,width=600');
            mywindow.document.write('<html><head><title>my div</title>');
            mywindow.document.write('<link href="http://122.180.254.6:8082/Kayakalp/public/css/font-awesome.min.css" rel="stylesheet" type="text/css" /><link href="http://122.180.254.6:8082/Kayakalp/public/css/bootstrap.min.css" rel="stylesheet" type="text/css" /><link href="http://122.180.254.6:8082/Kayakalp/public/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" /><link href="http://122.180.254.6:8082/Kayakalp/public/css/animate.css " rel="stylesheet" type="text/css" /><link href="http://122.180.254.6:8082/Kayakalp/public/css/style.css" rel="stylesheet" type="text/css" /><link href="http://122.180.254.6:8082/Kayakalp/public/css/jquery.steps.css" rel="stylesheet" type="text/css" />');
            mywindow.document.write('</head><body >');
            mywindow.document.write(data);
            mywindow.document.write('</body></html>');

            mywindow.print();
            //  mywindow.close();

            return true;
        }
    </script>
@endsection