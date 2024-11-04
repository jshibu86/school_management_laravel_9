<!--This is admin master page -->
<!-- author: jk -->
@php
    $theme = Configurations::getCurrentTheme();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ isset(Configurations::getConfig('site')->site_name) ? Configurations::getConfig('site')->site_name : '' }}|
        Administrator</title>



    <!-- Vector CSS -->
    {{-- {!! Cms::style('theme/vendors/plugins/vectormap/jquery-jvectormap-2.0.2.css') !!} --}}
    {{-- {!! Cms::style('theme/vendors/plugins/vectormap/jquery-jvectormap-2.0.2.css') !!}
    {!! Cms::style('theme/vendors/plugins/simplebar/css/simplebar.css') !!}
    {!! Cms::style('theme/vendors/plugins/perfect-scrollbar/css/perfect-scrollbar.css') !!}
    {!! Cms::style('theme/vendors/plugins/metismenu/css/metisMenu.min.css') !!}
    {!! Cms::style('theme/vendors/css/pace.min.css') !!}

    {!! Cms::script('theme/vendors/js/pace.min.js') !!}

    {!! Cms::style('theme/vendors/plugins/datetimepicker/css/classic.time.css') !!}
    {!! Cms::style('theme/vendors/plugins/datetimepicker/css/classic.css') !!} --}}
    <link rel="stylesheet" href="{{ mix('css/all.css') }}">

    
    {!! Cms::script('theme/vendors/js/pace.min.js') !!}
    
    {!! Cms::style('css/classic.time.css') !!}
    {!! Cms::style('css/classic.css') !!}
    


    <!-- loader-->

    {!! Cms::style('theme/vendors/css/bootstrap.min.css') !!}
    <link type="text/css"
        href="{{ asset('assets/backend/js/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}" />
    {!! Cms::style('theme/vendors/css/icons.css') !!}
    {!! Cms::style('theme/vendors/css/app.css') !!}
    {!! Cms::style('theme/vendors/css/dark-sidebar.css') !!}
    {!! Cms::style('theme/vendors/css/dark-theme.css') !!}
    <!-- Bootstrap CSS -->


    {!! Cms::style(
        'theme/vendors/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.min.css',
    ) !!}


    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&family=Roboto&display=swap" />


    {!! Cms::style('theme/vendors-old/pnotify/dist/pnotify.css') !!}
    {!! Cms::style('theme/vendors-old/pnotify/dist/pnotify.buttons.css') !!}
    {!! Cms::style('theme/vendors/plugins/select2/css/select2.min.css') !!}
    {!! Cms::style('theme/vendors/plugins/select2/css/select2-bootstrap4.css') !!}
    {!! Cms::style('theme/vendors/font-awesome/css/font-awesome.min.css') !!}

    <link rel="stylesheet" href="{{ asset('assets/backend/css/switch.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/js/SnackBar/snackbar.min.css') }}">
    <link href="{{ asset('assets/backend/css/theme2.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/backend/js/hrpicker/css/hr-timePicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/js/timepicker/timepicker.css') }}">


    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.3/jquery.datetimepicker.css" />


    <!-- Custom Theme Style -->



    @yield('style')


</head>

<body class="nav-md">
    <div class="wrapper" id="app">
        <!-- sidebar menu -->
        @include('layout::admin.sidemenu')
        <!-- /sidebar menu -->
        <!--top nav -->
        @include('layout::admin.top_nav')
        <!--- top nav end -->

        <div class="page-wrapper">
            <!--page-content-wrapper-->
            <div class="page-content-wrapper">
                <div class="page-content">
                    @if (session()->has('exception_error'))
                        <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
                            <div class="text-white">{{ session('exception_error') }} </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session()->has('invoice'))
                        <div class="alert alert-success border-0 bg-success alert-dismissible fade show">
                            <div class="text-white">{{ session('invoice') }} <a
                                    href="{{ asset(session('invoicelink')) }}" class="alert-link" style="color:white;"
                                    target="_blank">view Invoice</a>.</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session()->has('exception_error_link'))
                        <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
                            <div class="text-white">{{ session('exception_error_link') }} <a
                                    href="{{ session('link') }}" class="alert-link" style="color:white;"
                                    target="_blank">Click here</a>.</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session()->has('success_custom'))
                        <div class="alert alert-success border-0 bg-success alert-dismissible fade show">
                            <div class="text-white">{{ session('success_custom') }}
                                <br />
                                <p>User name : {{ session('username') }}</p>

                                <p>Password : {{ session('password') }}</p>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session()->has('success_default'))
                        <div class="alert alert-success border-0 bg-success alert-dismissible fade show">
                            <div class="text-white">{{ session('success_default') }}

                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session()->has('success_student'))
                        <div class="alert alert-success border-0 bg-success alert-dismissible fade show">
                            <div class="text-white">{{ session('success_student') }}
                                <br />
                                <p>For Studet</p>
                                <p>User name : {{ session('username') }}</p>

                                <p>Password : {{ session('password') }}</p>
                                <br />
                                <p>For Parent</p>
                                <p>User name : {{ session('parent_username') }}</p>

                                <p>Password : {{ session('parent_password') }}</p>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif


                    @yield('body')
                </div>


            </div>
        </div>

        <a href="javaScript:;" class="back-to-top"><i class="bx bxs-up-arrow-alt"></i></a>
        <div class="footer">
            <p class="mb-0">
                Developed By :
                <a href="#" target="_blank">Laravel</a>
            </p>
        </div>
    </div>




    <!-------------------------------------SCRIPT--------------------------------------------------->
    <!-- jQuery -->


    <script src="{{ asset('/js/app.js') }}"></script>
    {{-- <script src="{{ asset('/js/vue/app.js') }}"></script> --}}
    <script src="{{ asset('assets/backend/js/app.js') }}" type="module"></script>
    <script src="{{ asset('assets/backend/js/SnackBar/snackbar.min.js') }}"></script>

    {!! Cms::script('theme/vendors/js/jquery.min.js') !!}
    <!-- Bootstrap JS -->
    {!! Cms::script('theme/vendors/js/bootstrap.bundle.min.js') !!}
    <script src="{{ asset('assets/backend/js/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    {!! Cms::script('theme/vendors/plugins/simplebar/js/simplebar.min.js') !!}
    {!! Cms::script('theme/vendors/plugins/metismenu/js/metisMenu.min.js') !!}
    {!! Cms::script('theme/vendors/plugins/perfect-scrollbar/js/perfect-scrollbar.js') !!}
    {!! Cms::script('theme/vendors/plugins/vectormap/jquery-jvectormap-2.0.2.min.js') !!}
    {!! Cms::script('theme/vendors/plugins/vectormap/jquery-jvectormap-in-mill.js') !!}
    {!! Cms::script('theme/vendors/plugins/vectormap/jquery-jvectormap-us-aea-en.js') !!}
    {!! Cms::script('theme/vendors/plugins/vectormap/jquery-jvectormap-uk-mill-en.js') !!}
    {!! Cms::script('theme/vendors/plugins/vectormap/jquery-jvectormap-au-mill.js') !!}

    {{-- removed this apexchart error --}}
    {{-- {!! Cms::script('theme/vendors/js/index.js') !!} --}}
    <!-- NProgress -->
    {{-- {!! Cms::script('theme/vendors-old/nprogress/nprogress.js') !!} --}}
    {!! Cms::script('theme/vendors/js/app_.js') !!}
    {!! Cms::script('theme/vendors-old/bootstrap-progressbar/bootstrap-progressbar.min.js') !!}
    <!--plugins-->


    {{-- <script>
		new PerfectScrollbar('.dashboard-social-list');
		new PerfectScrollbar('.dashboard-top-countries');
	</script> --}}

    {!! Cms::script('theme/vendors-old/pnotify/dist/pnotify.js') !!}
    {!! Cms::script('theme/vendors-old/pnotify/dist/pnotify.buttons.js') !!}


    {!! Cms::script('theme/vendors/plugins/select2/js/select2.min.js') !!}



    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"> </script> --}}



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.3/build/jquery.datetimepicker.full.js">
    </script>

    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap-switch-button@1.1.0/dist/bootstrap-switch-button.min.js">
    </script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="{{ asset('assets/backend/js/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/backend/js/hrpicker/js/hr.timePicker.js') }}"></script>
    <script src="{{ asset('assets/backend/js/timepicker/timepicker.js') }}"></script>
    <script src="{{ asset('assets/backend/js/Iris-master/dist/iris.js') }}"></script>


    {!! Cms::script(
        'theme/vendors/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.min.js',
    ) !!}
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script src="https://unpkg.com/colortranslator@1.10.1/web/colortranslator.js"></script>
    {{-- <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script> --}}


    @yield('scripts')

    <script type="module">
        function notify_script(title, text, type, hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: 'fontawesome'
            })
        }
        //for running minicart 
        window.minicart = '{{ route('cart.minicart') }}'
        window.cartproductremove = '{{ route('cart.Productremove') }}'
        window.termurl = '{{ route('examterm.index') }}';
        GeneralConfig.generalinit(notify_script);

        // AcademicYearConfig.AcademicyearInit();
        Testing.testing();
        ProductConfig.Minicart();

        //Timepicker


        $('document').ready(function() {
            let user_form = document.querySelector('form');
            if (user_form) {
                user_form.addEventListener('keydown', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        return false;
                    }
                });
            }

            function notify(title, text, type, hide) {
                new PNotify({
                    title: title,
                    text: text,
                    type: type,
                    hide: hide,
                    styling: 'fontawesome'
                })
            }
            @if (Session::has('success'))
                notify('Success', '{{ Session::get('success') }}', 'success', true);
            @endif
            @if (Session::has('error'))
                notify('Error', '{{ Session::get('error') }}', 'error', true);
            @endif
            @if (Session::has('info'))
                notify('Info', '{{ Session::get('info') }}', 'info', true);
            @endif

            @if (count((array) $errors) > 0)
                @foreach ($errors->all() as $error)
                    notify('Error', '{{ $error }}', 'error', true);
                @endforeach
            @endif

        });
        $(document).ready(function() {


            $(".side-menu").find("li").each(function() { // loop through all li
                if ($(this).hasClass("active")) { // check if li has active class
                    console.log($(this));
                    var logo = $(this).find('a span.fa-chevron-left');

                    if (logo.hasClass('fa-chevron-left')) {
                        logo.removeClass('fa-chevron-left');
                        logo.addClass('fa-chevron-down');
                    } // get the value of data-interest attribute
                }
            })
        });
    </script>
    <script>
        // $('select').select2({
        //     theme: 'bootstrap4',
        //     width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        //     placeholder: $(this).data('placeholder'),
        //     allowClear: Boolean($(this).data('allow-clear')),
        // });
        $(".select2Input").select2({
            dropdownParent: $("#assigen__parent"),
        });
        $('.single-select').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });
        $('.multiple-select').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });
    </script>
    <script>
        var $SIDEBAR_MENU = $("#sidebar-menu");
        $SIDEBAR_MENU.find("a").on("click", function(ev) {
            console.log("clicked - sidebar_menu yes");
            var $li = $(this).parent();

            $(this).children('a span.fa').toggleClass('fa-chevron-left fa-chevron-down');

        });


        function myFunction(status, id) {
            var url = window.statuschange;

            $.ajax({
                type: "GET",
                dataType: "json",
                url: url,
                data: {
                    'status': status,
                    'id': id
                },
                success: function(data) {

                    function notify(title, text, type, hide) {
                        new PNotify({
                            title: title,
                            text: text,
                            type: type,
                            hide: hide,
                            styling: 'fontawesome'
                        })
                    }

                    if ($.isEmptyObject(data.error)) {
                        notify('Success', data.success, 'success', true);
                    } else {

                        notify('Error', data.error, 'error', true);
                    }
                },
                error: function(data) {
                    console.log(data.responseText);

                    function notify(title, text, type, hide) {
                        new PNotify({
                            title: title,
                            text: text,
                            type: type,
                            hide: hide,
                            styling: 'bootstrap3'
                        })
                    }
                    notify('Error', data.responseText, 'error', true);


                }

            });

        }
    </script>
    <script>
        $(document).ready(function() {

            $(".side-menu")
            // $('.select2').select2();


        });
    </script>
    <script>
        const divElement = document.getElementById('alert__box')
        setTimeout(function() {
            $("#alert__box").slideUp();

        }, 20000);
    </script>
    <script>
        function admissionFormStatus(status, id) {
            var url = window.statuschange;

            $.ajax({
                type: "GET",
                dataType: "json",
                url: url,
                data: {
                    'status': status,
                    'id': id
                },
                success: function(data) {

                    function notify(title, text, type, hide) {
                        new PNotify({
                            title: title,
                            text: text,
                            type: type,
                            hide: hide,
                            styling: 'fontawesome'
                        })
                    }

                    if ($.isEmptyObject(data.error)) {
                        notify('Success', data.success, 'success', true);
                    } else {

                        notify('Error', data.error, 'error', true);
                    }
                },
                error: function(data) {
                    console.log(data.responseText);

                    function notify(title, text, type, hide) {
                        new PNotify({
                            title: title,
                            text: text,
                            type: type,
                            hide: hide,
                            styling: 'bootstrap3'
                        })
                    }
                    notify('Error', data.responseText, 'error', true);


                }

            });

        }
    </script>

    @yield('script')
    @yield('script_link')



</body>

</html>
<!---------------widgets--------------------->
<!---------NOTIFICATIONS--------->
