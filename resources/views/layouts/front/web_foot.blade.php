
<!-- Scripts -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/wow.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/printThis.js') }}"></script>
<script src='{{ asset(Laralum::publicPath() . '/js/moment.js') }}'></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


    <script>
        new WOW().init();

        jQuery('.header').click(function(){
            if( jQuery(this).parent('div').hasClass('openTooltip') ){
                jQuery(this).parent('div').removeClass('openTooltip');
            }else{
                jQuery(this).parent('div').addClass('openTooltip');
            }
        });
    </script>
@yield('script')
