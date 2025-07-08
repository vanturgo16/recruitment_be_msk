<div class="btn-group" role="group">
    <button id="btnGroupDrop{{ $data->id }}" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown"
        aria-expanded="false">
        {{ __('messages.action') }} <i class="mdi mdi-chevron-down"></i>
    </button>
    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop{{ $data->id }}">
        <li><a class="dropdown-item drpdwn" href="{{ route('user.edit', encrypt($data->id)) }}"><span class="mdi mdi-file-edit"></span> | {{ __('messages.edit') }}</a></li>
        <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#reset{{ $data->id }}"><span class="mdi mdi-update"></span> | {{ __('messages.reset') }} Password</a></li>
        @if($data->is_active == 0)
            <li><a class="dropdown-item drpdwn-scs" href="#" data-bs-toggle="modal" data-bs-target="#activate{{ $data->id }}"><span class="mdi mdi-check-circle"></span> | {{ __('messages.activate') }}</a></li>
        @else
            <li><a class="dropdown-item drpdwn-dgr" href="#" data-bs-toggle="modal" data-bs-target="#deactivate{{ $data->id }}"><span class="mdi mdi-close-circle"></span> | {{ __('messages.deactivate') }}</a></li>
        @endif
        @if(auth()->user()->role == 'Super Admin')
            <li><a class="dropdown-item drpdwn-dgr" href="#" data-bs-toggle="modal" data-bs-target="#delete-user{{ $data->id }}"><span class="mdi mdi-delete-alert"></span> | {{ __('messages.delete') }}</a></li>
        @endif
    </ul>
</div>

{{-- MODAL --}}
<div class="left-align truncate-text">
    {{-- Modal Reset --}}
    <div class="modal fade" id="reset{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{ __('messages.reset') }} Password User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="formLoad" action="{{ route('user.reset', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center">
                            {{ __('messages.are_u_sure') }} <b>Reset Password</b> {{ __('messages.this_user') }}?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                        <button type="submit" class="btn btn-primary waves-effect btn-label waves-light">
                            <i class="mdi mdi-update label-icon"></i>{{ __('messages.reset') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Activate --}}
    <div class="modal fade" id="activate{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{ __('messages.activate') }} User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="formLoad" action="{{ route('user.activate', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center">
                            {{ __('messages.are_u_sure') }} <b>{{ __('messages.activate') }}</b> {{ __('messages.this_user') }}?
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
                    <h5 class="modal-title" id="staticBackdropLabel">{{ __('messages.deactivate') }} User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="formLoad" action="{{ route('user.deactivate', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center">
                            {{ __('messages.are_u_sure') }} <b>{{ __('messages.deactivate') }}</b> {{ __('messages.this_user') }}?
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

    {{-- Modal Delete User --}}
    @if(auth()->user()->role == 'Super Admin')
    <div class="modal fade" id="delete-user{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{ __('messages.delete') }} User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="formLoad" action="{{ route('user.delete', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <p class="text-center">{{ __('messages.are_u_sure') }} <b>{{ __('messages.delete') }}</b> {{ __('messages.this_user') }}?</p>
                            <p class="text-center"><b>{{ $data->name }}</b></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                        <button type="submit" class="btn btn-danger waves-effect btn-label waves-light">
                            <i class="mdi mdi-delete label-icon"></i>{{ __('messages.delete') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

<script src="{{ asset('assets/js/formLoad.js') }}"></script>
