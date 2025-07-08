@extends('layouts.master')
@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 text-center mt-4">
                <div class="maintenance-cog-icon text-primary pt-4">
                    <i class="mdi mdi-cog spin-right display-3"></i>
                    <i class="mdi mdi-cog spin-left display-4 cog-icon"></i>
                </div>
                <h3 class="mt-4">{{ __('messages.ua') }}</h3>
                <p>{{ __('messages.sub_ua') }}</p>
                <div class="mt-5 text-center">
                    <a class="btn btn-primary waves-effect waves-light" href="{{ route('dashboard') }}">{{ __('messages.back_to_dashboard') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection