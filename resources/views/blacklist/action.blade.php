<button type="button" class="btn btn-sm btn-primary waves-effect btn-label waves-light mb-2" data-bs-toggle="modal" data-bs-target="#edit{{ $data->id }}">
    <i class="mdi mdi-file-edit label-icon"></i> {{ __('messages.edit') }}
</button>
<button type="button" class="btn btn-sm btn-danger waves-effect btn-label waves-light mb-2" data-bs-toggle="modal" data-bs-target="#delete{{ $data->id }}">
    <i class="mdi mdi-trash-can label-icon"></i> {{ __('messages.delete') }}
</button>

{{-- MODAL --}}
<div class="left-align truncate-text">
    {{-- Modal Update --}}
    <div class="modal fade" id="edit{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{ __('messages.edit') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="formLoad" action="{{ route('blacklist.update', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_emp" value="{{ $data->id_emp }}">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">Employee</label><label style="color: darkred">*</label>
                                <input class="form-control" type="text" value="{{ $data->email }}" placeholder="Input Employee.." readonly>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">Reason</label><label style="color: darkred">*</label>
                                <input class="form-control" name="reason" type="text" value="{{ $data->reason }}" placeholder="Input Reason.." required>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="5" placeholder="Input Notes...">{{ $data->notes }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                        <button type="submit" class="btn btn-primary waves-effect btn-label waves-light"><i class="mdi mdi-update label-icon"></i>{{ __('messages.update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Modal Delete --}}
    <div class="modal fade" id="delete{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{ __('messages.delete') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="formLoad" action="{{ route('blacklist.delete', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center">
                            {{ __('messages.are_u_sure') }} <b>{{ __('messages.delete') }}</b>
                            <p class="text-center"><b>{{ $data->email }}</b></p>?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                        <button type="submit" class="btn btn-danger waves-effect btn-label waves-light">
                            <i class="mdi mdi-trash-can label-icon"></i>{{ __('messages.delete') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/formLoad.js') }}"></script>
