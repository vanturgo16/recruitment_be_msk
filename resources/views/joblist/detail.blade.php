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
    
    @if($data->is_active == 1)
        {{-- Modal Update --}}
        <div class="modal fade" id="update" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{{ __('messages.update') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="formLoad" action="{{ route('joblist.update', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">{{ __('messages.position') }}</label> <label class="text-danger">*</label>
                                    <select class="form-select select2" style="width: 100%" name="id_position" required>
                                        <option value="" selected>-- {{ __('messages.select') }} --</option>
                                        <option disabled>──────────</option>
                                        @foreach($positions as $item)
                                            <option value="{{ $item->id }}" {{ $data->id_position == $item->id ? 'selected' : '' }}>
                                                {{ strtoupper($item->dept_name) }} - {{ $item->position_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">{{ __('messages.request_by') }}</label> <label class="text-danger">*</label>
                                    <select class="form-select select2" style="width: 100%" name="position_req_user" required>
                                        <option value="" selected>-- {{ __('messages.select') }} --</option>
                                        <option disabled>──────────</option>
                                        @foreach($listEmployee as $item)
                                            <option value="{{ $item->id }}" {{ $data->position_req_user == $item->id ? 'selected' : '' }}>
                                                {{ $item->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">{{ __('messages.rec_date_start') }}</label> <label class="text-danger">*</label>
                                    <input class="form-control" type="date" value="{{ $data->rec_date_start }}" name="rec_date_start" required>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">{{ __('messages.rec_date_end') }}</label>
                                    <input class="form-control" type="date" value="{{ $data->rec_date_end }}" name="rec_date_end">
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label">{{ __('messages.jobdesc') }}</label> <label class="text-danger">*</label>
                                    <textarea class="summernote-editor" name="jobdesc" placeholder="Input Job Description..." required>{!! $data->jobdesc !!}</textarea>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label">{{ __('messages.requirement') }}</label> <label class="text-danger">*</label>
                                    <textarea class="summernote-editor" name="requirement" placeholder="Input Requirement..." required>{!! $data->requirement !!}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label class="form-label">{{ __('messages.min_education') }}</label>
                                    <select class="form-select select2" style="width: 100%" name="min_education" required>
                                        <option value="" selected>-- {{ __('messages.select') }} --</option>
                                        <option disabled>──────────</option>
                                        @foreach($educations as $item)
                                            <option value="{{ $item->name_value }}" {{ $data->min_education == $item->name_value ? 'selected' : '' }}> {{ $item->name_value }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label class="form-label">{{ __('messages.min_yoe') }}</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" placeholder=".." value="{{ $data->min_yoe }}" name="min_yoe">
                                        <span class="input-group-text">{{ __('messages.years') }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <label class="form-label">{{ __('messages.min_age') }}</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" placeholder=".." value="{{ $data->min_age }}" name="min_age">
                                        <span class="input-group-text">{{ __('messages.years') }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <label class="form-label">{{ __('messages.max_candidate') }}</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" placeholder=".." value="{{ $data->max_candidate }}" name="max_candidate">
                                        <span class="input-group-text">{{ __('messages.applicants') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                            <button type="submit" class="btn btn-info waves-effect btn-label waves-light">
                                <i class="mdi mdi-update label-icon"></i>{{ __('messages.update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- MAIN CARD --}}
    <div class="card">
        <div class="card-header bg-light">
            <div class="row">
                <div class="col-lg-6">
                    <h4 class="text-bold">{{ __('messages.detail') }}</h4>
                </div>
                @if($data->is_active == 1)
                    <div class="col-lg-6">
                        <div class="text-end">
                            <button type="button" class="btn btn-info waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#update">
                                <i class="mdi mdi-update label-icon"></i> {{ __('messages.update') }}
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3 mb-3">
                                    <div class="form-group">
                                        <div><span class="fw-bold">{{ __('messages.position') }} :</span></div>
                                        <span>
                                            <span>{{ $data->position_name }}</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-3 mb-3">
                                    <div class="form-group">
                                        <div><span class="fw-bold">{{ __('messages.dept_name') }} :</span></div>
                                        <span>
                                            <span>{{ $data->dept_name }}</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-3 mb-3">
                                    <div class="form-group">
                                        <div><span class="fw-bold">{{ __('messages.request_by') }} :</span></div>
                                        <span>
                                            <span>{{ $data->email }}</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 mb-3">
                                    <div class="form-group">
                                        <div><span class="fw-bold">{{ __('messages.rec_date_start') }} :</span></div>
                                        <span>
                                            <span>{{ $data->rec_date_start }}</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-3 mb-3">
                                    <div class="form-group">
                                        <div><span class="fw-bold">{{ __('messages.rec_date_end') }} :</span></div>
                                        <span>
                                            <span>{{ $data->rec_date_end ?? '-' }}</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-3 mb-3">
                                    <div class="form-group">
                                        <div><span class="fw-bold">Status :</span></div>
                                        <span>
                                            @if($data->is_active == 0)
                                                <span class="badge bg-danger text-white"><i class="fas fa-window-close"></i> Innactive</span>
                                            @else
                                                <span class="badge bg-success text-white"><i class="fas fa-check"></i> Active</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 mb-3">
                                    <div class="form-group">
                                        <div><span class="fw-bold">{{ __('messages.min_education') }} :</span></div>
                                        <span>
                                            <span>{{ $data->min_education ?? '-' }}</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-3 mb-3">
                                    <div class="form-group">
                                        <div><span class="fw-bold">{{ __('messages.min_yoe') }} :</span></div>
                                        <span>
                                            <span>{{ $data->min_yoe ? $data->min_yoe . ' '. __('messages.years') : '-' }}</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-3 mb-3">
                                    <div class="form-group">
                                        <div><span class="fw-bold">{{ __('messages.min_age') }} :</span></div>
                                        <span>
                                            <span>{{ $data->min_age ? $data->min_age . ' '. __('messages.years') : '-' }}</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-3 mb-3">
                                    <div class="form-group">
                                        <div><span class="fw-bold">{{ __('messages.max_candidate') }} :</span></div>
                                        <span>
                                            <span>{{ $data->max_candidate ?? '-' }}</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <span class="fw-bold">{{ __('messages.jobdesc') }} :</span>
                        </div>
                        <div class="card-body">
                            <span>
                                <span>{!! $data->jobdesc !!}</span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <span class="fw-bold">{{ __('messages.requirement') }} :</span>
                        </div>
                        <div class="card-body">
                            <span>
                                <span>{!! $data->requirement !!}</span>
                            </span>
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