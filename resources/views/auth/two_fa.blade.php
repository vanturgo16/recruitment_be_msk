<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Two-Factor Authentication | NOS Honda Banten</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}">
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
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
                                    <div class="mb-2 text-center">
                                        <a href="#" class="d-block auth-logo">
                                            <img src="{{ asset('assets/images/logo/logo.png') }}" alt="" height="40">
                                        </a>
                                    </div>

                                    <div class="auth-content my-auto">
                                        <div class="text-center">
                                            <h5 class="mb-0">Two-Factor Authentication</h5>
                                            <p class="text-muted mt-2">
                                                Enter the 6-digit code sent to your email to continue.
                                            </p>

                                            <div class="text-start">
                                                @include('layouts.alert')
                                            </div>
                                        </div>

                                        <form class="formLoad" action="{{ route('verify.2fa.post') }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label">Authentication Code</label>
                                                <input type="text" name="two_fa_code" maxlength="6" class="form-control text-center" placeholder="Enter 6-digit code" required>
                                            </div>

                                            <div class="mb-3 text-center">
                                                <button type="submit" class="btn btn-danger w-100">Verify Code</button>
                                            </div>
                                        </form>
                                    </div>


                                    <div class="text-center mt-4">
                                        <form action="{{ route('resend.2fa') }}" method="POST" id="resend-form" class="d-inline">
                                            @csrf
                                            <button type="submit" id="resend-btn" class="btn btn-outline-danger waves-effect" disabled>
                                                <i class="mdi mdi-refresh"></i> <span id="resend-text">Resend Code (10s)</span>
                                            </button>
                                        </form>
                                    </div>

                                    <script>
                                        document.addEventListener("DOMContentLoaded", function () {
                                            let countdown = 10; // seconds
                                            const button = document.getElementById("resend-btn");
                                            const text = document.getElementById("resend-text");

                                            // Countdown timer
                                            const timer = setInterval(() => {
                                                countdown--;
                                                text.textContent = `Resend Code (${countdown}s)`;

                                                if (countdown <= 0) {
                                                    clearInterval(timer);
                                                    button.disabled = false;
                                                    button.classList.remove('btn-outline-danger');
                                                    button.classList.add('btn-danger');
                                                    text.textContent = "Resend Code";
                                                }
                                            }, 1000);

                                            // On click: reset cooldown after resend
                                            document.getElementById('resend-form').addEventListener('submit', function () {
                                                button.disabled = true;
                                                button.classList.remove('btn-danger');
                                                button.classList.add('btn-outline-danger');
                                                countdown = 10;
                                                text.textContent = `Resend Code (${countdown}s)`;

                                                const newTimer = setInterval(() => {
                                                    countdown--;
                                                    text.textContent = `Resend Code (${countdown}s)`;

                                                    if (countdown <= 0) {
                                                        clearInterval(newTimer);
                                                        button.disabled = false;
                                                        button.classList.remove('btn-outline-danger');
                                                        button.classList.add('btn-danger');
                                                        text.textContent = "Resend Code";
                                                    }
                                                }, 1000);
                                            });
                                        });
                                    </script>

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
        <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
        <script src="{{ asset('assets/libs/pace-js/pace.min.js') }}"></script>
        <script src="{{ asset('assets/js/formLoad.js') }}"></script>
    </body>
</html>
