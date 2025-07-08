<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Page Not Found</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/logo.png') }}">
    <!-- BOOTSTRAP CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}"/>
</head>

<body>
    <div class="my-5 pt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mb-5">
                        <h1 class="display-1 fw-semibold">4<span class="text-primary mx-2">0</span>4</h1>
                        <h4 class="text-uppercase">{{ __('messages.pnf') }}</h4>
                        <div class="mt-5 text-center">
                            <a class="btn btn-primary waves-effect waves-light" href="{{ route('dashboard') }}">{{ __('messages.back_to_dashboard') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-10 col-xl-8">
                    <div>
                        <img src="{{ asset('assets/images/error-img.png') }}" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>