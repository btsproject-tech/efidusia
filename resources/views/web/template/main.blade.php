<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Notaris APP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta content="Notaris APP" name="description" />
    <meta content="Notaris APP" name="author" />
    <style>
        .wd-column {
            width: 200px !important;
        }
    </style>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">


    <link href="{{ asset('skote/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('skote/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="{{ asset('skote/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />


    <link href="{{ asset('skote/assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('skote/assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}"
        rel="stylesheet">
    <link href="{{ asset('skote/assets/libs/spectrum-colorpicker2/spectrum.min.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('skote/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css') }}"
        rel="stylesheet">
    <link href="{{ asset('skote/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Bootstrap Css -->
    <link href="{{ asset('skote/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet"
        type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('skote/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('skote/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/template.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/libs/toastr/build/toastr.min.css') }}">
    {{-- loader --}}
    <link rel="stylesheet" href="{{ asset('assets/css/loader/loader.css') }}">

    @if (isset($header_data))
        @php
            $version = str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz');
        @endphp
        @foreach ($header_data as $key => $v_head)
            @php
                $data_key = explode('-', $key);
            @endphp
            @if ($data_key[0] == 'css')
                <link rel="stylesheet" href="{{ $v_head }}?v={{ $version }}">
            @endif
        @endforeach
    @endif
</head>

<body data-sidebar="dark">
    <div class="loader"></div>
    <!-- Loader -->
    <div id="preloader">
        <div id="status">
            <div class="spinner-chase">
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
            </div>
        </div>
    </div>

    <!-- Begin page -->
    <div id="layout-wrapper">
        <div class="toast-content"></div>
        @include('web.template.header')

        @include('web.template.leftmenu')

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">{{ isset($title) ? $title : '' }}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0 font-size-18">{{ isset($title_top) ? $title_top : '' }}</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item active">{{ isset($title_top) ? $title_top : '' }}
                                        </li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    {!! $view_file !!}
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            @include('web.template.footer')
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->
    {{-- @include('web.template.rightmenu') --}}

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('skote/assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('skote/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('skote/assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('skote/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('skote/assets/libs/node-waves/waves.min.js') }}"></script>
    <!-- apexcharts -->
    <script src="{{ asset('skote/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('skote/assets/libs/chart.js/Chart.bundle.min.js') }}"></script>

    <!-- Required datatable js -->
    <script src="{{ asset('skote/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('skote/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ asset('skote/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('skote/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('skote/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('skote/assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('skote/assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('skote/assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('skote/assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('skote/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

    <script src="{{ asset('assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-select/js/dataTables.select.min.js') }}"></script>

    <!-- Responsive examples -->
    <script src="{{ asset('skote/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('skote/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('skote/assets/js/pages/datatables.init.js') }}"></script>

    <script src="{{ asset('skote/assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('skote/assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('skote/assets/libs/spectrum-colorpicker2/spectrum.min.js') }}"></script>
    <script src="{{ asset('skote/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js') }}"></script>
    <script src="{{ asset('assets/libs/admin-resources/bootstrap-filestyle/bootstrap-filestyle.min.js') }}"></script>
    <script src="{{ asset('skote/assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('skote/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('skote/assets/libs/jquery-steps/build/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('skote/assets/js/pages/form-wizard.init.js') }}"></script>


    <!-- dashboard init -->
    {{-- <script src="{{ asset('skote/assets/js/pages/dashboard.init.js') }}"></script> --}}

    <!-- App js -->
    <script src="{{ asset('skote/assets/js/app.js') }}"></script>

    <!-- Other JS -->
    <script src="{{ asset('skote/assets/libs/bootbox/bootbox.js') }}"></script>
    <script src="{{ asset('assets/utils/url.js') }}"></script>
    <script src="{{ asset('assets/utils/message.js') }}"></script>
    <script src="{{ asset('assets/utils/validation.js') }}"></script>
    <script src="{{ asset('assets/js/lib/html2pdf.js') }}"></script>
    <!-- Other JS -->
    <script src="{{ asset('assets/libs/toastr/build/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/xlsx/xlsx.min.js') }}"></script>


    @if (isset($header_data))
        @php
            $version = str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz');
        @endphp
        @foreach ($header_data as $key => $v_head)
            @php
                $data_key = explode('-', $key);
            @endphp
            @if ($data_key[0] == 'js')
                <script src="{{ $v_head }}?v={{ $version }}"></script>
            @endif
        @endforeach
    @endif
</body>

</html>
