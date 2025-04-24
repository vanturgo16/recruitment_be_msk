<div class="btn-group" role="group">
    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        {{ __('messages.action') }} <i class="mdi mdi-chevron-down"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu2">
        <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#detail{{ $data->id }}"><span class="mdi mdi-information"></span> | {{ __('messages.detail') }}</a></li>
        <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#edit{{ $data->id }}"><span class="mdi mdi-file-edit"></span> | {{ __('messages.edit') }}</a></li>
    </ul>
</div>

{{-- MODAL --}}
<div class="left-align truncate-text">
    {{-- Modal Detail --}}
    <div class="modal fade" id="detail{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{ __('messages.detail') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">{{ __('messages.dept_name') }} :</span></div>
                                <span>
                                    <span>{{ $data->dept_name }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">{{ __('messages.position_name') }} :</span></div>
                                <span>
                                    <span>{{ $data->position_name }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">{{ __('messages.hie_level') }} :</span></div>
                                <span>
                                    <span>{{ $data->hie_level }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">{{ __('messages.notes') }} :</span></div>
                                <span>
                                    <span>{{ $data->notes ?? '-' }}</span>
                                </span>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div class="modal fade" id="edit{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{ __('messages.edit') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="formLoad" action="{{ route('position.update', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">{{ __('messages.dept_name') }}</label> <label class="text-danger">*</label>
                                <select class="form-control select2" name="id_dept" required>
                                    <option value="" disabled selected>- {{ __('messages.select') }} -</option>
                                    @foreach($listDepartments as $item)
                                        <option value="{{ $item->id }}" @if($data->id_dept == $item->id) selected="selected" @endif>{{ $item->dept_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">{{ __('messages.position_name') }}</label> <label class="text-danger">*</label>
                                <input class="form-control" name="position_name" type="text" value="{{ $data->position_name }}" placeholder="Input Position Name.." required>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">{{ __('messages.hie_level') }}</label> <label class="text-danger">*</label>
                                <input class="form-control" name="hie_level" type="text" value="{{ $data->hie_level }}" placeholder="Input Hierarchy Level.." required>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">{{ __('messages.notes') }}</label>
                                <textarea class="form-control" rows="3" type="text" class="form-control" name="notes" placeholder="(Input Note For This Department)">{{ $data->notes }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                        <button type="submit" class="btn btn-primary waves-effect btn-label waves-light">
                            <i class="mdi mdi-update label-icon"></i>{{ __('messages.update') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/formLoad.js') }}"></script>
