<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <title>@yield('title') - {{ Laralum::settings()->website_title }}</title>
    <meta name="description" content="Kayakalp administration panel">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {!! Laralum::includeAssets('laralum_header') !!}

	{!! Laralum::includeAssets('charts') !!}

  @yield('css')
  <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>

    <![endif]-->
    {{-- <script type="text/javascript" >
         function preventBack(){window.history.forward();}
         setTimeout("preventBack()", 0);
         window.onunload=function(){null};
     </script>--}}
    <link rel="stylesheet" type="text/css" media="screen"
          href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
</head>

<body class="top-main-cls">
<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>

<div class="ui inverted dimmer">
    <div class="ui text loader">Loading</div>
</div>


@if(session('success'))
    <script>
        swal({
            title: "Nice!",
            text: "{!! session('success') !!}",
            type: "success",
            confirmButtonText: "Cool"
        });
    </script>
@endif
@if(session('error'))
    <script>
        swal({
            title: "Whops!",
            text: "{!! session('error') !!}",
            type: "error",
            confirmButtonText: "Okay"
        });
    </script>
@endif
@if(session('warning'))
    <script>
        swal({
            title: "Watch out!",
            text: "{!! session('warning') !!}",
            type: "warning",
            confirmButtonText: "Okay"
        });
    </script>
@endif
@if(session('info'))
    <script>
        swal({
            title: "Watch out!",
            text: "{!! session('info') !!}",
            type: "info",
            confirmButtonText: "Okay"
        });
    </script>
@endif
@if (count($errors) > 0)
    <script>
        swal({
            title: "Whops!",
            text: "<?php foreach ($errors->all() as $error) {
                echo "$error<br>";
            } ?>",
            type: "error",
            confirmButtonText: "Okay",
            html: true
        });
    </script>
@endif
<div class="ui sidebar left-menu ">
    <header>

        <div class="ui left fixed vertical menu sidebar_wrapper" id="vertical-menu">
            <div id="vertical-menu-height">
                <a href="{{ route('Laralum::dashboard') }}" class="item logo-box"
                   style="background-color: {{ \App\Http\Controllers\Laralum\Laralum::settings()->header_color }}">
                    <div class="logo-container">
                        {{--<img class="logo-image ui fluid small image" src="@if(Laralum::settings()->logo) {{ Laralum::settings()->logo }} @else '' --}}{{--{{ Laralum::laralumLogo() }}--}}{{-- @endif">--}}
                        KAYAKALP
                    </div>
                </a>
                @if(\Auth::user()->isPatient() && !\Auth::user()->isSuperAdmin())
                    @include('layouts.admin._patient_sidebar')
                @else
                    @include('layouts.admin._sidebar')
                @endif
            </div>
        </div>
    </header>
</div>


<div class="content-wrap12">

    <div class="ui top fixed menu" id="menu-div">
        <div class="item" id="menu">
            <div class="ui secondary button"><i class="bars icon"></i> {{ trans('laralum.menu') }}</div>
        </div>
        <div class="item" id="breadcrumb" {{--style="margin-left: 210px !important;"--}} >
            @yield('breadcrumb')
        </div>
        <div class="right menu">
            {{--<div class="item">
                <div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
                  <i class="globe icon"></i>
                  <span class="text responsive-text"> {{ trans('laralum.language') }}</span>
                  <div class="menu">
                    @foreach(Laralum::locales() as $locale => $locale_info)
                        @if($locale_info['enabled'])
                            <a href="{{ route('Laralum::locale', ['locale' => $locale]) }}" class="item">
                                @if($locale_info['type'] == 'image')
                                    <img class="ui image"  height="11" src="{{ $locale_info['type_data'] }}">
                                @elseif($locale_info['type'] == 'flag')
                                    <i class="{{ $locale_info['type_data'] }} flag"></i>
                                @endif
                                {{ $locale_info['name'] }}
                            </a>
                        @endif
                    @endforeach
                  </div>
                </div>
            </div>--}}
            <div class="item">
                <div class="ui {{ Laralum::settings()->button_color }} top labeled icon left dropdown button responsive-button">
                    <i class="user icon"></i>
                    <span class="text responsive-text">{{ Auth::user()->name }}</span>
                    <div class="menu">
                        <a href="{{ route('home') }}" class="item">
                            {{ trans('laralum.profile') }}
                        </a>
                        <a href="{{ url('/user/change-password') }}" class="item">
                            {{ trans('laralum.change_password') }}
                        </a>
                        <a href="{{ url('/logout') }}"
                           onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                           class="item">
                            {{ trans('laralum.logout') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="back">
        <div class="menu-margin">
            <div class="content-title" style="background-color: {{ Laralum::settings()->header_color }};">
                <div class="menu-pusher">
                    <div class="ui one column doubling stackable grid">
                        <div class="column">
                            <h3 class="ui header">
                                <i class="@yield('icon') icon white-text"></i>
                                <div class="content white-text">
                                    @yield('title')
                                    <div class="sub header">
                                        <span class="white-text">@yield('subtitle')</span>
                                    </div>
                                </div>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-content">
                <div class="menu-pusher">
                    @yield('content')
                </div>
            </div>
            <br><br>

        </div>
        <div class="page-footer">
            <div class="ui bottom fixed padded segment">
                <div class="menu-pusher">
                    <div class="ui container">
                        <a href="{{ url('/') }}" class="ui tiny header">
                            {{ Laralum::websiteTitle() }}
                        </a>
                        <?php
                        /*$locales = Laralum::locales();

                        if($locale = Laralum::loggedInUser()->locale) {
                            $locale = $locales[$locale];
                        } else {
                            $locale = $locales['en'];
                        }*/
                        ?>

                        {{--<a href="{{ $locale['website'] }}" class="ui tiny header">
                            {{ trans('laralum.translated_by', ['author' => $locale['author']]) }}
                        </a>--}}
                        <a class="ui tiny header right floated" href='{{ url('/') }}'>&copy;
                            Copyright Kayakalp</a>
                        {{--<a class="ui tiny header right floated" href="https://erik.cat">Author</a>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{!! Laralum::includeAssets('laralum_bottom') !!}
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@yield('js')

<script>


    setInterval(function () {
        var footer = $('.page-footer');
        footer.removeAttr("style");
        var footerPosition = footer.position();
        var docHeight = $(document).height();
        var winHeight = $(window).height();
        if (winHeight == docHeight) {
            if ((footerPosition.top + footer.height() + 3) < docHeight) {
                var topMargin = (docHeight - footer.height()) - footerPosition.top;
                footer.css({'margin-top': topMargin + 'px'});
            }
        }
    }, 10);

    $('#vertical-menu-height .header').click(function () {
        if ($(this).parent('div').hasClass('openTooltip')) {
            $(this).parent('div').removeClass('openTooltip');
        } else {
            $('#vertical-menu-height .header').parent('div').removeClass('openTooltip');
            $(this).parent('div').addClass('openTooltip');
        }
    });

    $("ul.pagination li.active span").css("background-color", "{{ \App\Http\Controllers\Laralum\Laralum::settings()->button_color }}");
    $("ul.pagination li.active").css("background-color", "{{ \App\Http\Controllers\Laralum\Laralum::settings()->button_color }}");

    $("ul.pagination li a").addClass('no-disable');

    $(".pagination_con").find("a").each(function () {
        var text = $(this).text();

        console.log("Text" + text);
        console.log("s" + "{{ @$_REQUEST['s'] }}");
        if (text == "{{ @$_REQUEST['per_page'] }}") {
            $(this).html("<b>" + text + "</b>").css("background-color", "#ddd").css("padding", "3px");
        }
    })
    $(document).ready(function () {
        $(document).delegate(".pagination_con ul li a", 'click', function (e) {
            // e.preventDefault();
            // $('.table_cus_v').attr('data-action', $(this).attr('href'));
            // refresh();
        })

        $(document).delegate("[id^=table_search_]", 'change', function () {
            refresh();
        })

        $('input').keypress(function (e) {
            console.log('sfddddddddd');
            // Enter pressed?
            if (e.which == 10 || e.which == 13) {
                refresh();
            }
        });

        function refresh() {
            var data = '_token=' + "{{ csrf_token() }}";
            $('[id^=table_search_]').each(function () {
                var field = $(this).attr('id').split('table_search_')[1];
                data = data + '&' + field + '=' + $(this).val();
            });
            var  url = $('.table_cus_v').attr('data-action');
            var newurl = updateQueryStringParameter(url, 'page' , 1 );

            /*
             console.log($("#select").val());
             var data = data  + '&role_id=' + $("#select").val();*/

            $.ajax({
                url: newurl,
                data: data,
                type: "POST",
                success: function (response) {
                    //console.log(response.html);
                    $(".table_header_row").html(response.html);
                }
            })
        }

        function updateQueryStringParameter(uri, key, value) {
            console.log('uri:::'+uri);
          var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
          //alert(uri);

          var separator = uri.indexOf('?') !== -1 ? "&" : "?";

          if (uri.match(re)) {
            return uri.replace(re, '$1' + key + "=" + value + '$2');
          }
          else {
            return uri + separator + key + "=" + value;
          }
        }

        $(document).delegate(".pointing.dropdown", 'click', function (evt) {

            if ($(evt.target).closest('.menu').length) {
                return;
            }

            $(".pointing.dropdown").find(".menu").removeClass('visible');
            $(".pointing.dropdown").removeClass('active');

            $(this).addClass('active visible');
            $(this).find('.menu').addClass('transition visible').show();
        });

        $(document).delegate('body', 'click', function (evt) {

            if (evt.target.class == "menu") {
                return;
            }

            if ($(evt.target).closest('.menu').length) {
                return;
            }

            if (evt.target.id == "book-table") {
                return;
            }

            if (evt.target.class == "configure icon") {
                return;
            }

            if ($(evt.target).closest('.configure').length) {
                return;
            }

            if ($(evt.target).closest('.pointing').length) {
                return;
            }

            $('.configure').parent().removeClass('active visible');
            $('.configure').parent().find('.menu').removeClass('transition visible').hide();


            $(".pointing.dropdown").find(".menu").removeClass('visible');
            $(".pointing.dropdown").removeClass('active');

        });


        /*$("body").not(".pointing").on('click', function (evt) {

            if (evt.target.id == "book-table") {
                return;
            }

            if (evt.target.class == "configure icon") {
                return;
            }

            if ($(evt.target).closest('.configure').length) {
                return;
            }

            console.log('class',evt.target.class);

            $(".pointing.dropdown").find(".menu").removeClass('visible');
            $(".pointing.dropdown").removeClass('active');

        })*/

        $(document).delegate(".pointing.dropdown.button .item", 'click', function () {
            var text = $(this).text();
            $(".pointing.dropdown").find('.text.responsive-text').text(text);

            $(".pointing.dropdown").parent().removeClass('active visible');
            $(".pointing.dropdown").find('.menu').removeClass('transition');
            $(".pointing.dropdown").find('.menu').removeClass('visible');

        });


        /*$(".age_ayrud_exam").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                //display error message
                $("#errmsg_age").html("Digits Only").show().fadeOut("slow");
                $('#errmsg_age').css('color','red');
                //$(this).val('');
                return false;
            }
        });*/


        $(".age_ayrud_exam").keyup(function (e) {

            var regExp = new RegExp('[a-zA-Z]');
            var age = $(this).val();
            if (regExp.test(age)) {
                e.preventDefault();
                $("#errmsg_age").html("Digits Only").show().fadeOut("slow");
                $('#errmsg_age').css('color', 'red');
                $(this).val('');
                return false;
            }

            var age = parseInt(age);

            $('.baal_age').attr({disabled: false});
            $('.madhyam_age').attr({disabled: false});
            $('.vridh_age').attr({disabled: false});

            if (age >= 0 && age <= 20) {
                //alert('bal');
                $('.baal_age').prop("checked", true).trigger("click");
                $('.baal_age').attr("checked", 'checked');
                $('.madhyam_age').attr('disabled', 'disabled');
                $('.vridh_age').attr('disabled', 'disabled');

            }
            else if (age >= 21 && age <= 60) {
                //alert('Madhyam');
                $('.baal_age').attr('disabled', 'disabled');
                $('.madhyam_age').prop("checked", true).trigger("click");
                $('.madhyam_age').attr("checked", 'checked');
                $('.vridh_age').attr('disabled', 'disabled');
            }
            else if (age >= 60) {
                $('.baal_age').attr('disabled', 'disabled');
                $('.madhyam_age').attr('disabled', 'disabled');
                $('.vridh_age').prop("checked", true).trigger("click");
                $('.vridh_age').attr("checked", 'checked');
            }
            else {
                $('.baal_age').attr({disabled: false});
                $('.madhyam_age').attr({disabled: false});
                $('.vridh_age').attr({disabled: false});
            }
        });

        var age = $('.age_ayrud_exam').val();

        if (age != '') {

            var age = parseInt(age);

            $('.baal_age').attr({disabled: false});
            $('.madhyam_age').attr({disabled: false});
            $('.vridh_age').attr({disabled: false});

            if (age >= 0 && age <= 20) {
                //alert('bal');
                $('.baal_age').prop("checked", true).trigger("click");
                $('.baal_age').attr("checked", 'checked');
                $('.madhyam_age').attr('disabled', 'disabled');
                $('.vridh_age').attr('disabled', 'disabled');
            }
            else if (age >= 21 && age <= 60) {
                //alert('Madhyam');
                $('.baal_age').attr('disabled', 'disabled');
                $('.madhyam_age').prop("checked", true).trigger("click");
                $('.madhyam_age').attr("checked", 'checked');
                $('.vridh_age').attr('disabled', 'disabled');
            }
            else if (age >= 60) {
                $('.baal_age').attr('disabled', 'disabled');
                $('.madhyam_age').attr('disabled', 'disabled');
                $('.vridh_age').prop("checked", true).trigger("click");
                $('.vridh_age').attr("checked", 'checked');
            }
            else {
                $('.baal_age').attr({disabled: false});
                $('.madhyam_age').attr({disabled: false});
                $('.vridh_age').attr({disabled: false});
            }
        }

    });

    function HandleBackFunctionality() {
        if (window.event) //Internet Explorer
        {
            alert("Browser back button is clicked on Internet Explorer...");
        }
        else //Other browsers e.g. Chrome
        {
            alert("Browser back button is clicked on other browser...");
        }
    }
</script>
<!-- <script>
    // $(document).ready(function () {
    //     $('div#book-table').click(function () {
    //         $('.table-responsive.table_sec_row').toggleClass('padding_slide-btm');
    //     });

    //     $(document).click(function (e) {
    //         console.log('hua');
    //         if (e.target.id == "book-table") {
    //             //alert('hoga');
    //             return;
    //         }
    //         $('.table-responsive.table_sec_row').removeClass('padding_slide-btm');
    //     });
    // });
</script> -->
</body>
</html>
