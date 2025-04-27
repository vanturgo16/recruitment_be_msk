<button type="button" class="btn btn-sm btn-info waves-effect btn-label waves-light mb-2" data-bs-toggle="modal" data-bs-target="#detail{{ $data->id }}">
    <i class="mdi mdi-information label-icon"></i> {{ __('messages.detail') }}
</button>

{{-- MODAL --}}
<div class="left-align truncate-text">
    {{-- Modal Detail --}}
    <div class="modal fade" id="detail{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{ __('messages.edit') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                    <div class="row">
                        <div class="col-lg-12 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Email :</span></div>
                                <span>
                                    <span>{{ $data->email }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">{{ __('messages.reason') }} :</span></div>
                                <span>
                                    <span>{{ $data->reason }}</span>
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
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
