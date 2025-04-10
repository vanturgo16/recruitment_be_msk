<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>{{ __('messages.title_tab_login') }}</title>
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
    </head>

    <body>
        <!-- Loading -->
        @include('layouts.loading')
        
        <div class="auth-page">
            <div class="container-fluid p-0">
                <div class="row g-0">
                    <!-- Form Login -->
                    <div class="col-xxl-3 col-lg-4 col-md-5">
                        <div class="auth-full-page-content d-flex p-sm-5 p-4">
                            <div class="w-100">
                                <div class="d-flex flex-column h-100">
                                    <div class="mb-4 mb-md-5 text-center">
                                        <img src="{{ asset('assets/images/logo/logo.png') }}" alt="" height="40">
                                    </div>
                                    <div class="auth-content my-auto">
                                        <div class="text-center">
                                            <h5 class="mb-0">Welcome Back !</h5><p class="text-muted mt-2">Sign in to continue</p>
                                        </div>
                                        <div class="text-left">
                                            @include('layouts.alert')
                                        </div>
                                        <form class="formLoad" action="{{ route('postlogin') }}" id="login" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label">Email / Username</label>
                                                <input type="text" class="form-control" name="email" id="username" placeholder="Enter email / username">
                                            </div>
                                            <div class="mb-3">
                                                <div class="d-flex align-items-start">
                                                    <div class="flex-grow-1">
                                                        <label class="form-label">Password</label>
                                                    </div>
                                                </div>
                                                
                                                <div class="input-group auth-pass-inputgroup">
                                                    <input type="password" class="form-control" placeholder="Enter password" aria-label="Password" aria-describedby="password-addon" name="password">
                                                    <button class="btn btn-light shadow-none ms-0" type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="remember-check">
                                                        <label class="form-check-label" for="remember-check">
                                                            Remember me
                                                        </label>
                                                    </div>  
                                                </div>
                                                
                                            </div>
                                            <div class="mb-3">
                                                <button class="btn btn-primary w-100 waves-effect waves-light" type="submit" name="sb">Log In</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="mt-4 mt-md-5 text-center">
                                        <p class="mb-0">
                                            {{ __('messages.footer_copyright') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Background Login -->
                    <div class="col-xxl-9 col-lg-8 col-md-7">
                        <div class="auth-bg pt-md-5 p-4 d-flex" style="background-image: url('{{ asset('assets/images/loginPage/auth-bg.jpg') }}');">
                            <div class="bg-overlay bg-primary"></div>
                            <ul class="bg-bubbles">
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                            </ul>
                            <!-- end bubble effect -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- JAVASCRIPT -->
        <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
        <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
        <!-- FORM LOAD JS -->
        <script src="{{ asset('assets/js/formLoad.js') }}"></script>
        <!-- PW ADDON INIT -->
        <script src="{{ asset('assets/js/pages/pass-addon.init.js') }}"></script>
    </body>
</html>