<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Ubah Password</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="{{ asset('assets/images/logo/logo.png') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/app.min.css') }}"/>
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}"/>
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/icons.min.css') }}"/>
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/preloader.min.css') }}"/>
    </head>
    <body>
        @include('layouts.loading')
        <div class="auth-page">
            <div class="container-fluid p-0">
                <div class="row g-0">
                    <div class="col-xxl-3 col-lg-4 col-md-5">
                        <div class="auth-full-page-content d-flex p-sm-5 p-4">
                            <div class="w-100">
                                <div class="d-flex flex-column h-100">
                                    <div class="mb-4 mb-md-5 text-center">
                                        <img src="{{ asset('assets/images/logo/logo.png') }}" alt="" height="40">
                                    </div>
                                    <div class="auth-content my-auto">
                                        <div class="text-center">
                                            <h5 class="mb-0">Ubah Password</h5>
                                            <p class="text-muted mt-2">Silakan masukkan password lama dan password baru Anda</p>
                                        </div>
                                        <div class="text-left">
                                            @include('layouts.alert')
                                        </div>
                                        <form class="formLoad" action="{{ route('password.update') }}" id="change-password" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="email" value="{{ old('email', $email ?? '') }}">
                                            <div class="mb-2">
                                                <label class="form-label">Password Lama</label>
                                                <div class="input-group auth-pass-inputgroup">
                                                    <input type="password" class="form-control" name="old_password" id="old_password" placeholder="Masukkan password lama" required>
                                                    <button class="btn btn-light shadow-none ms-0 toggle-password" type="button" data-target="old_password"><i class="mdi mdi-eye-outline"></i></button>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">Password Baru</label>
                                                <div class="input-group auth-pass-inputgroup">
                                                    <input type="password" class="form-control" name="new_password" id="new_password" placeholder="Masukkan password baru" required>
                                                    <button class="btn btn-light shadow-none ms-0 toggle-password" type="button" data-target="new_password"><i class="mdi mdi-eye-outline"></i></button>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <button class="btn btn-danger w-100 waves-effect waves-light" type="submit">Ubah Password</button>
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
                    <div class="col-xxl-9 col-lg-8 col-md-7">
                        <div class="auth-bg pt-md-5 p-4 d-flex" style="background-image: url('{{ asset('assets/images/background/MSK.png') }}');">
                            <div class="bg-overlay bg-secondary-subtle" style="opacity: 0.85"></div>
                            <!-- bubble effect -->
                            <ul class="bg-bubbles">
                                @foreach(range(1, 10) as $i)
                                    <li></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
        <script src="{{ asset('assets/js/formLoad.js') }}"></script>
        <script>
        document.querySelectorAll('.toggle-password').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var target = document.getElementById(this.getAttribute('data-target'));
                if (target.type === 'password') {
                    target.type = 'text';
                    this.innerHTML = '<i class="mdi mdi-eye-off-outline"></i>';
                } else {
                    target.type = 'password';
                    this.innerHTML = '<i class="mdi mdi-eye-outline"></i>';
                }
            });
        });
        </script>
    </body>
</html>
