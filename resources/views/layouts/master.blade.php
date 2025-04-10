<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('messages.title_tab_main') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/logo.png') }}">
    <!-- APP CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/app.min.css') }}"/>
    <!-- BOOTSTRAP CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}"/>
    <!-- ICONS CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/icons.min.css') }}"/>
    <!-- PRELOADER CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/preloader.min.css') }}"/>
    <!-- DATATABLES CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"/>
    <!-- CHOICES SELECT CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/choices.js/public/assets/styles/choices.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/select2/css/select2.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/select2/css/select2.min.css') }}"/>

    <!-- CUSTOM CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom.css') }}"/>
    <!-- JQUERY SCRIPT -->
    <script type="text/javascript" src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
</head>

{{-- <body> --}}
<body @if(Auth::user()->is_darkmode) data-bs-theme="dark" data-topbar="dark" data-sidebar="dark" @endif>
    <!-- Loading -->
    @include('layouts.loading')
    @include('layouts.toast')

    <div id="layout-wrapper">
        <!-- HEADER -->
        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <div class="navbar-brand-box">
                        <a href="{{ route('dashboard') }}" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="{{ asset('assets/images/logo/logo_vertical_light.jpg') }}" alt="" height="50">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ asset('assets/images/logo/logo_horizontal_light.png') }}" alt="" height="50">
                            </span>
                        </a>
                        <a href="{{ route('dashboard') }}" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="{{ asset('assets/images/logo/logo_vertical_dark.jpg') }}" alt="" height="50">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ asset('assets/images/logo/logo_horizontal_light.png') }}" alt="" height="50">
                            </span>
                        </a>
                    </div>
                    <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn"><i class="fa fa-fw fa-bars"></i></button>
                    <form class="app-search d-none d-lg-block">
                        <div class="position-relative">
                            <h3 class="d-inline-block me-2"><span class="badge bg-info text-white">{{ Auth::user()->role }}</span></h3>
                        </div>
                    </form>
                    <!-- Role-->
                    @if(Auth::check() && Auth::user()->role == null)
                        {{-- Code to destroy authentication session --}}
                        <?php Auth::logout(); ?>
                    @endif
                </div>

                <div class="d-flex">
                    <div class="dropdown d-none d-sm-inline-block">
                        <button type="button" class="btn header-item" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img id="header-lang-img" src="{{ asset('assets/images/flags/' . (App::getLocale() == 'id' ? 'id.png' : (App::getLocale() == 'sd' ? 'sunda.jpg' : 'us.jpg'))) }}" alt="Header Language" height="16">
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- English -->
                            <a href="{{ route('change.language', 'en') }}" class="dropdown-item notify-item language">
                                <img src="{{ asset('assets/images/flags/us.jpg') }}" alt="user-image" class="me-1" height="12"> 
                                <span class="align-middle">English</span>
                            </a>
                            <!-- Indonesian -->
                            <a href="{{ route('change.language', 'id') }}" class="dropdown-item notify-item language">
                                <img src="{{ asset('assets/images/flags/id.png') }}" alt="user-image" class="me-1" height="12"> 
                                <span class="align-middle">Indonesia</span>
                            </a>
                            <!-- Sundanese -->
                            <a href="{{ route('change.language', 'sd') }}" class="dropdown-item notify-item language">
                                <img src="{{ asset('assets/images/flags/sunda.jpg') }}" alt="user-image" class="me-1" height="12"> 
                                <span class="align-middle">Sunda</span>
                            </a>
                        </div>                        
                    </div>                    
                    <div class="dropdown d-none d-sm-inline-block">
                        <button type="button" class="btn header-item" data-bs-toggle="modal" data-bs-target="#switchTheme">
                            <i data-feather="moon" class="icon-lg layout-mode-dark"></i>
                            <i data-feather="sun" class="icon-lg layout-mode-light"></i>
                        </button>
                    </div>
                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item right-bar-toggle me-2">
                            <i data-feather="settings" class="icon-lg"></i>
                        </button>
                    </div>
                    <div class="dropdown d-inline-block">
                        @php use Illuminate\Support\Facades\File; $defaultImagePath = public_path('assets/images/users/userDefault.png'); @endphp
                        <button type="button" class="btn header-item bg-light-subtle border-start border-end" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @if(Auth::user()->photo_path)
                                @php $userImagePath = public_path(Auth::user()->photo_path); @endphp
                                @if(File::exists($userImagePath))
                                    <img class="rounded-circle header-profile-user" src="{{ asset(Auth::user()->photo_path) }}" alt="Header Avatar">
                                @else
                                    @if(File::exists($defaultImagePath))
                                        <img class="rounded-circle header-profile-user" src="{{ asset('assets/images/users/userDefault.png') }}" alt="Header Avatar">
                                    @endif
                                @endif
                            @else 
                                @if(File::exists($defaultImagePath))
                                    <img class="rounded-circle header-profile-user" src="{{ asset('assets/images/users/userDefault.png') }}" alt="Header Avatar">
                                @endif
                            @endif
                            <span class="d-none d-xl-inline-block ms-1 fw-medium">{{ Auth::user()->name }}</span> <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="{{ route('profile.index') }}"><i class="mdi mdi mdi-face-man font-size-16 align-middle me-1"></i> Profile</a>
                            <a class="dropdown-item" href="" data-bs-toggle="modal" data-bs-target="#logout"><i class="mdi mdi-logout font-size-16 align-middle me-1"></i> Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- ========== Left Sidebar ========== -->
        <div class="vertical-menu">
            <div data-simplebar class="h-100">
                <div id="sidebar-menu">
                    <ul class="metismenu list-unstyled" id="side-menu">
                        <li>
                            <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard*') ? 'bg-light active' : '' }}">
                                <i class="mdi mdi-home"></i><span>Dashboard</span>
                            </a>
                        </li>

                        @if(in_array(auth()->user()->role, ['Super Admin', 'Admin']))
                            <li class="menu-title mt-2" data-key="t-menu">Configuration</li>
                            <li>
                                <a href="{{ route('user.index') }}" class="{{ request()->is('user*') ? 'bg-light active' : '' }}">
                                    <i class="mdi mdi-account-supervisor"></i><span>{{ __('messages.mng_user') }}</span>
                                </a>
                            </li>
                            @if(auth()->user()->role == 'Super Admin')
                            <li>
                                <a href="{{ route('rule.index') }}" class="{{ request()->is('rule*') ? 'bg-light active' : '' }}">
                                    <i class="mdi mdi-cog-box"></i><span>{{ __('messages.mng_rule') }}</span>
                                </a>
                            </li>
                            @endif
                            <li>
                                <a href="{{ route('dropdown.index') }}" class="{{ request()->is('dropdown*') ? 'bg-light active' : '' }}">
                                    <i class="mdi mdi-package-down"></i><span>{{ __('messages.mng_dropdown') }}</span>
                                </a>
                            </li>

                            <li class="menu-title mt-2" data-key="t-menu">Master</li>
                            <li>
                                <a href="#" class="{{ request()->is('master_1*') ? 'bg-light active' : '' }}">
                                    <i class="mdi mdi-list-status"></i><span>Master 1</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="{{ request()->is('master_2*') ? 'bg-light active' : '' }}">
                                    <i class="mdi mdi-list-status"></i><span>Master 2</span>
                                </a>
                            </li>
                        @endif

                        <li class="menu-title mt-2" data-key="t-menu">Recruitment</li>
                        <li>
                            <a href="#" class="{{ request()->is('applicants_list*') ? 'bg-light active' : '' }}">
                                <i class="mdi mdi-account-group"></i><span>{{ __('messages.applicants_list') }}</span>
                            </a>
                        </li>

                        @if(in_array(auth()->user()->role, ['Super Admin', 'Admin']))
                            <li class="menu-title mt-2" data-key="t-menu">{{ __('messages.other') }}</li>
                            <li>
                                <a href="{{ route('auditlog.index') }}" class="{{ request()->is('auditlog*') ? 'bg-light active' : '' }}">
                                    <i class="mdi mdi-chart-donut"></i><span>{{ __('messages.audit_log') }}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <!-- ========== Main Content ========== -->
        <div class="main-content">
            @yield('konten')
            <footer class="footer" style="position: fixed; z-index: 10;">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6 footer-text">
                            {{ __('messages.footer_copyright') }}
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- ========== Right Sidebar ========== -->
        <div class="right-bar">
            <div data-simplebar class="h-100">
                <div class="rightbar-title d-flex align-items-center p-3">
                    <h5 class="m-0 me-2">Theme Customizer</h5>
                    <a href="javascript:void(0);" class="right-bar-toggle ms-auto">
                        <i class="mdi mdi-close noti-icon"></i>
                    </a>
                </div>
                <hr class="m-0"/>
                <div class="p-4">
                    <h6 class="mb-3">Layout</h6>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="layout"
                            id="layout-vertical" value="vertical">
                        <label class="form-check-label" for="layout-vertical">Vertical</label>
                    </div>

                    <h6 class="mt-4 mb-3 pt-2">Layout Mode</h6>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="layout-mode"
                            id="layout-mode-light" value="light">
                        <label class="form-check-label" for="layout-mode-light">Light</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="layout-mode"
                            id="layout-mode-dark" value="dark">
                        <label class="form-check-label" for="layout-mode-dark">Dark</label>
                    </div>

                    <h6 class="mt-4 mb-3 pt-2">Layout Width</h6>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="layout-width"
                            id="layout-width-fuild" value="fuild" onchange="document.body.setAttribute('data-layout-size', 'fluid')">
                        <label class="form-check-label" for="layout-width-fuild">Fluid</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="layout-width"
                            id="layout-width-boxed" value="boxed" onchange="document.body.setAttribute('data-layout-size', 'boxed')">
                        <label class="form-check-label" for="layout-width-boxed">Boxed</label>
                    </div>

                    <h6 class="mt-4 mb-3 pt-2">Layout Position</h6>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="layout-position"
                            id="layout-position-fixed" value="fixed" onchange="document.body.setAttribute('data-layout-scrollable', 'false')">
                        <label class="form-check-label" for="layout-position-fixed">Fixed</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="layout-position"
                            id="layout-position-scrollable" value="scrollable" onchange="document.body.setAttribute('data-layout-scrollable', 'true')">
                        <label class="form-check-label" for="layout-position-scrollable">Scrollable</label>
                    </div>

                    <h6 class="mt-4 mb-3 pt-2">Topbar Color</h6>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="topbar-color"
                            id="topbar-color-light" value="light" onchange="document.body.setAttribute('data-topbar', 'light')">
                        <label class="form-check-label" for="topbar-color-light">Light</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="topbar-color"
                            id="topbar-color-dark" value="dark" onchange="document.body.setAttribute('data-topbar', 'dark')">
                        <label class="form-check-label" for="topbar-color-dark">Dark</label>
                    </div>

                    <h6 class="mt-4 mb-3 pt-2 sidebar-setting">Sidebar Size</h6>
                    <div class="form-check sidebar-setting">
                        <input class="form-check-input" type="radio" name="sidebar-size"
                            id="sidebar-size-default" value="default" onchange="document.body.setAttribute('data-sidebar-size', 'lg')">
                        <label class="form-check-label" for="sidebar-size-default">Default</label>
                    </div>
                    <div class="form-check sidebar-setting">
                        <input class="form-check-input" type="radio" name="sidebar-size"
                            id="sidebar-size-compact" value="compact" onchange="document.body.setAttribute('data-sidebar-size', 'md')">
                        <label class="form-check-label" for="sidebar-size-compact">Compact</label>
                    </div>
                    <div class="form-check sidebar-setting">
                        <input class="form-check-input" type="radio" name="sidebar-size"
                            id="sidebar-size-small" value="small" onchange="document.body.setAttribute('data-sidebar-size', 'sm')">
                        <label class="form-check-label" for="sidebar-size-small">Small (Icon View)</label>
                    </div>

                    <h6 class="mt-4 mb-3 pt-2 sidebar-setting">Sidebar Color</h6>
                    <div class="form-check sidebar-setting">
                        <input class="form-check-input" type="radio" name="sidebar-color"
                            id="sidebar-color-light" value="light" onchange="document.body.setAttribute('data-sidebar', 'light')">
                        <label class="form-check-label" for="sidebar-color-light">Light</label>
                    </div>
                    <div class="form-check sidebar-setting">
                        <input class="form-check-input" type="radio" name="sidebar-color"
                            id="sidebar-color-dark" value="dark" onchange="document.body.setAttribute('data-sidebar', 'dark')">
                        <label class="form-check-label" for="sidebar-color-dark">Dark</label>
                    </div>
                    <div class="form-check sidebar-setting">
                        <input class="form-check-input" type="radio" name="sidebar-color"
                            id="sidebar-color-brand" value="brand" onchange="document.body.setAttribute('data-sidebar', 'brand')">
                        <label class="form-check-label" for="sidebar-color-brand">Brand</label>
                    </div>

                    <h6 class="mt-4 mb-3 pt-2">Direction</h6>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="layout-direction"
                            id="layout-direction-ltr" value="ltr">
                        <label class="form-check-label" for="layout-direction-ltr">LTR</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="rightbar-overlay"></div>
    </div>

    <!-- Modal Switch Them -->
    <div class="modal fade" id="switchTheme" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{ __('messages.switch_theme') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <h5>{{ __('messages.switch_to') }} <b>@if(Auth::user()->is_darkmode) Light @else Dark @endif</b> Mode ?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                    <form class="formLoad" action="{{ route('switchTheme') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <button type="submit" class="btn btn-success waves-effect btn-label waves-light"><i class="mdi mdi-check label-icon"></i>{{ __('messages.apply') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Logout -->
    <div class="modal fade" id="logout" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('messages.logout_text') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                    <form class="formLoad" action="{{ route('logout') }}" id="formlogout" method="POST" enctype="multipart/form-data">
                        @csrf
                        <button type="submit" class="btn btn-danger waves-effect btn-label waves-light" name="sb"><i class="mdi mdi-logout label-icon"></i>Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <!-- DATATABLE -->
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- CHOICHES SELECT JS -->
    <script src="{{ asset('assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/js/select2.init.js') }}"></script>
    <!-- twitter-bootstrap-wizard js -->
    <script src="{{ asset('assets/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}"></script>
    <script src="{{ asset('assets/libs/twitter-bootstrap-wizard/prettify.js') }}"></script>
    <!-- form wizard init -->
    <script src="{{ asset('assets/js/pages/form-wizard.init.js') }}"></script>
    <!-- CKEDITOR JS -->
    <script src="{{ asset('assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-editor.init.js') }}"></script>
    <!-- FORM LOAD JS -->
    <script src="{{ asset('assets/js/formLoad.js') }}"></script>
    <!-- DASHBOARD INIT -->
    <script src="{{ asset('assets/js/pages/dashboard.init.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="{{ asset('assets/libs/dayjs@1/dayjs.min.js') }}"></script>

    <!-- DATATABLE CUSTOM SCROLL -->
    <script>
        $(function() {
            // Scroll Table
            $('#ssTable_wrapper .dataTables_scrollBody').css({
                'overflow-x': 'hidden', 'overflow-y': 'scroll',
                'max-height': '44vh', // Default
            });
            function setTableHeight() {
                const windowHeight = $(window).height();
                let maxHeight;
    
                if (windowHeight < 550) { maxHeight = '10vh'; } 
                else if (windowHeight < 600) { maxHeight = '19vh'; } 
                else if (windowHeight < 700) { maxHeight = '29vh'; } 
                else if (windowHeight < 800) { maxHeight = '36vh'; } 
                else if (windowHeight < 900) { maxHeight = '42vh'; } 
                else if (windowHeight < 1000) { maxHeight = '44vh'; }
                $('#ssTable_wrapper .dataTables_scrollBody').css('max-height', maxHeight);
            }
            setTableHeight();
            $(window).resize(function() { setTableHeight(); });
            $('<style>').prop('type', 'text/css').html(`
                #ssTable_wrapper .dataTables_scrollBody::-webkit-scrollbar { width: 4px; }
                #ssTable_wrapper .dataTables_scrollBody::-webkit-scrollbar-thumb { background-color: #888;border-radius: 4px; }
                #ssTable_wrapper .dataTables_scrollBody::-webkit-scrollbar-thumb:hover { background-color: #555; }
                #ssTable_wrapper .dataTables_scrollBody::-webkit-scrollbar-track { background: transparent; }
                /* Add margin between scroll body and pagination */
                #ssTable_wrapper .dataTables_scroll {
                    margin-bottom: 20px; /* Add space between table body and pagination */
                }
            `).appendTo('head');
        });
    </script>
</body>

</html>