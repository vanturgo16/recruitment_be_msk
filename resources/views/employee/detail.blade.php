@extends('layouts.master')
@section('konten')

<style>
    .emp-hierarchy-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        position: relative;
        padding-bottom: 25px;
    }

    .emp-hierarchy-item:last-child {
        padding-bottom: 0;
    }

    .emp-hierarchy-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background-color: #4A90E2;
        flex-shrink: 0;
        margin-top: 4px;
        position: relative;
    }

    .emp-hierarchy-item:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 18px;
        left: 6px;
        width: 2px;
        height: calc(100% - 18px);
        background: #d1d1d1;
    }

    .emp-hierarchy-content {
        background: #f5f8ff;
        padding: 12px 16px;
        border-radius: 8px;
        flex-grow: 1;
        transition: background 0.2s;
    }

    .emp-hierarchy-content:hover {
        background: #e9f1ff;
    }

    .emp-hierarchy-level {
        font-weight: 600;
        font-size: 14px;
        color: #4A4A4A;
        margin-bottom: 4px;
        text-transform: capitalize;
    }

    .emp-hierarchy-email {
        font-size: 13px;
        color: #2c3e50;
        word-break: break-word;
    }
</style>

@php
    $reportLines = collect([
        'reportline_5' => $data->reportline_5,
        'reportline_4' => $data->reportline_4,
        'reportline_3' => $data->reportline_3,
        'reportline_2' => $data->reportline_2,
        'reportline_1' => $data->reportline_1,
    ])->filter();
@endphp

<div class="page-content">
    <div class="row custom-margin">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div class="page-title-left">
                    <a href="{{ route('employee.index') }}" class="btn btn-light waves-effect btn-label waves-light">
                        <i class="mdi mdi-arrow-left label-icon"></i> {{ __('messages.back_to_list') }}
                    </a>
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('employee.index') }}">{{ __('messages.emp_list') }}</a></li>
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
                <div class="col-lg-7">
                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <div class="form-group">
                                <div><span class="fw-bold">{{ __('messages.emp_no') }} :</span></div>
                                <span>
                                    <span>{{ $data->emp_no }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <div class="form-group">
                                <div><span class="fw-bold">E-mail :</span></div>
                                <span>
                                    <span>{{ $data->email }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <div class="form-group">
                                <div><span class="fw-bold">{{ __('messages.placement') }} :</span></div>
                                <span>
                                    <span>{{ $data->office_name }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <div class="form-group">
                                <div><span class="fw-bold">{{ __('messages.div_name') }} :</span></div>
                                <span>
                                    <span>{{ $data->div_name }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <div class="form-group">
                                <div><span class="fw-bold">{{ __('messages.dept_name') }} :</span></div>
                                <span>
                                    <span>{{ $data->dept_name }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <div class="form-group">
                                <div><span class="fw-bold">{{ __('messages.position') }} :</span></div>
                                <span>
                                    <span>{{ $data->position_name }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-primary">
                                    <h5 class="text-white">Reporting Hierarchy</h5>
                                </div>
                                <div class="card-body">
                                    @foreach($reportLines->reverse() as $level => $email)
                                        <div class="emp-hierarchy-item">
                                            <div class="emp-hierarchy-dot"></div>
                                            <div class="emp-hierarchy-content">
                                                <div class="emp-hierarchy-level">{{ ucfirst(str_replace('_', ' ', $level)) }}</div>
                                                <div class="emp-hierarchy-email">{{ $email }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                
                                    <div class="emp-hierarchy-item">
                                        <div class="emp-hierarchy-dot"></div>
                                        <div class="emp-hierarchy-content">
                                            <div class="emp-hierarchy-level">Employee</div>
                                            <div class="emp-hierarchy-email">{{ $data->email }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-6 mb-2">
                    <div class="form-group">
                        <div><span class="fw-bold">{{ __('messages.created_at') }} :</span></div>
                        <span>
                            <span>{{ $data->created_at }}</span>
                        </span>
                    </div>
                </div>
                <div class="col-lg-6 mb-2">
                    <div class="form-group">
                        <div><span class="fw-bold">{{ __('messages.last_updated') }} :</span></div>
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