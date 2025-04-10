@extends('layouts.master')
@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center mt-3">
                            <div class="col-12">
                                <div class="text-center">
                                    <h5>{{ __('messages.welcome') }} {{ __('messages.app_name') }}</h5>
                                    <p class="text-muted">{{ __('messages.welcome_sub') }} {{ __('messages.company_name') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 text-center mt-4">
                <div class="maintenance-cog-icon text-primary pt-4">
                    <i class="mdi mdi-cog spin-right display-3"></i>
                    <i class="mdi mdi-cog spin-left display-4 cog-icon"></i>
                </div>
                <h3 class="mt-4">Site is Under Development</h3>
                <p>Please wait....</p>
            </div>
        </div>
    </div>
</div>

@endsection