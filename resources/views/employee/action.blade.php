<div class="btn-group" role="group">
    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        {{ __('messages.action') }} <i class="mdi mdi-chevron-down"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu2">
        <li><a class="dropdown-item drpdwn" href="{{ route('employee.detail', encrypt($data->id)) }}"><span class="mdi mdi-information"></span> | {{ __('messages.detail') }}</a></li>
        @if($data->is_active == 1)
            <li><a class="dropdown-item drpdwn-dgr" href="#" data-bs-toggle="modal" data-bs-target="#deactivate{{ $data->id }}"><span class="mdi mdi-close-circle"></span> | {{ __('messages.deactivate') }}</a></li>
        @else
            <li><a class="dropdown-item drpdwn-scs" href="#" data-bs-toggle="modal" data-bs-target="#activate{{ $data->id }}"><span class="mdi mdi-check-circle"></span> | {{ __('messages.activate') }}</a></li>
        @endif
    </ul>
</div>

{{-- MODAL --}}
<div class="left-align truncate-text">
    {{-- Modal Activate --}}
    <div class="modal fade" id="activate{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{ __('messages.activate') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="formLoad" action="{{ route('employee.activate', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center">
                            {{ __('messages.are_u_sure') }} <b>{{ __('messages.activate') }}</b>
                            <p class="text-center"><b>{{ $data->email }}</b>?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                        <button type="submit" class="btn btn-success waves-effect btn-label waves-light">
                            <i class="mdi mdi-check-circle label-icon"></i>{{ __('messages.activate') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Deactivate --}}
    <div class="modal fade" id="deactivate{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{ __('messages.deactivate') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="formLoad" action="{{ route('employee.deactivate', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                        <div class="text-center">
                            {{ __('messages.are_u_sure') }} <b>{{ __('messages.deactivate') }}</b>
                            <p class="text-center"><b>{{ $data->email }}</b>?</p>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">{{ __('messages.inactive_date') }}</label> <label class="text-danger">*</label>
                                <input type="date" name="inactive_date" class="form-control" required>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">{{ __('messages.reason') }}</label> <label class="text-danger">*</label>
                                <select class="form-control select2" name="reason" required>
                                    <option value="" disabled selected>- {{ __('messages.select') }} -</option>
                                    @foreach($listReasons as $item)
                                        <option value="{{ $item->name_value }}" @if( old('reason') == $item->name_value) selected="selected" @endif>{{ $item->name_value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">{{ __('messages.notes') }}</label>
                                <textarea name="notes" class="form-control" rows="5" placeholder="Input Notes..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                        <button type="submit" class="btn btn-danger waves-effect btn-label waves-light">
                            <i class="mdi mdi-close-circle label-icon"></i>{{ __('messages.deactivate') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/formLoad.js') }}"></script>
<script src="{{ asset('assets/libs/select2/js/select2.init.js') }}"></script>