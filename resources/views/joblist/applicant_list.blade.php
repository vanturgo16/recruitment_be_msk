@extends('layouts.master')
@section('konten')

<div class="page-content">
    <div class="row custom-margin">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div class="page-title-left">
                    <a href="{{ route('joblist.index') }}" class="btn btn-light waves-effect btn-label waves-light">
                        <i class="mdi mdi-arrow-left label-icon"></i> {{ __('messages.back_to_list') }}
                    </a>
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('joblist.index') }}">{{ __('messages.job_list') }}</a></li>
                        <li class="breadcrumb-item active"> {{ __('messages.applicants_list') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CARD --}}
    <div class="card">
        <div class="card-header bg-light">
            <h4 class="text-bold">{{ __('messages.applicants_list') }}</h4>
        </div>
        <div class="card-body">
            
        </div>
    </div>
</div>

@endsection