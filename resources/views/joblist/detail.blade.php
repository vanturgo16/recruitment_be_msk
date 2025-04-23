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
                        <li class="breadcrumb-item active"> {{ __('messages.detail') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CARD --}}
    <div class="card">
        <div class="card-header bg-light">
            <h4 class="text-bold">{{ __('messages.detail') }}</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <div class="form-group">
                        <div><span class="fw-bold">Position :</span></div>
                        <span>
                            <span>{{ $data->position_name }}</span>
                        </span>
                    </div>
                </div>
                <div class="col-lg-4 mb-3">
                    <div class="form-group">
                        <div><span class="fw-bold">Department :</span></div>
                        <span>
                            <span>{{ $data->dept_name }}</span>
                        </span>
                    </div>
                </div>
                <div class="col-lg-4 mb-3">
                    <div class="form-group">
                        <div><span class="fw-bold">Request By :</span></div>
                        <span>
                            <span>{{ $data->email }}</span>
                        </span>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-6 mb-2">
                    <div class="form-group">
                        <div><span class="fw-bold">Created At :</span></div>
                        <span>
                            <span>{{ $data->created_at }}</span>
                        </span>
                    </div>
                </div>
                <div class="col-lg-6 mb-2">
                    <div class="form-group">
                        <div><span class="fw-bold">Last Updated At :</span></div>
                        <span>
                            <span>{{ $data->updated_at }}</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection