@if (session('success') || session('fail') || session('info') || session('warning') || count($errors)>0)
<div class="position-fixed bottom-0 end-0 p-3 mb-4" style="z-index: 15;">
    @if (session('success'))
        <div class="toast fade show" role="alert" aria-live="assertive" data-bs-autohide="false" aria-atomic="true">
            <div class="toast-header bg-success text-white">
                <span class="badge bg-white text-primary"><i class="mdi mdi-check-all label-icon"></i></span>&nbsp;
                <strong class="me-auto">{{ __('messages.success') }}</strong><small>{{ __('messages.just_now') }}</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">{{ session('success') }}</div>
        </div>
    @endif
    @if (session('fail'))
        <div class="toast fade show" role="alert" aria-live="assertive" data-bs-autohide="false" aria-atomic="true">
            <div class="toast-header bg-danger text-white">
                <span class="badge bg-white text-primary"><i class="mdi mdi-check-all label-icon"></i></span>&nbsp;
                <strong class="me-auto">{{ __('messages.fail') }}</strong><small>{{ __('messages.just_now') }}</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">{{ session('fail') }}</div>
        </div>
    @endif
    @if (session('info'))
        <div class="toast fade show" role="alert" aria-live="assertive" data-bs-autohide="false" aria-atomic="true">
            <div class="toast-header bg-info text-white">
                <span class="badge bg-white text-primary"><i class="mdi mdi-check-all label-icon"></i></span>&nbsp;
                <strong class="me-auto">{{ __('messages.info') }}</strong><small>{{ __('messages.just_now') }}</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">{{ session('info') }}</div>
        </div>
    @endif
    @if (session('warning'))
        <div class="toast fade show" role="alert" aria-live="assertive" data-bs-autohide="false" aria-atomic="true">
            <div class="toast-header bg-warning text-white">
                <span class="badge bg-white text-primary"><i class="mdi mdi-check-all label-icon"></i></span>&nbsp;
                <strong class="me-auto">{{ __('messages.warning') }}</strong><small>{{ __('messages.just_now') }}</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">{{ session('warning') }}</div>
        </div>
    @endif
    @if (count($errors)>0)
        <div class="toast fade show" role="alert" aria-live="assertive" data-bs-autohide="false" aria-atomic="true">
            <div class="toast-header bg-danger text-white">
                <span class="badge bg-white text-primary"><i class="mdi mdi-check-all label-icon"></i></span>&nbsp;
                <strong class="me-auto">{{ __('messages.fail') }}</strong><small>{{ __('messages.just_now') }}</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
</div>
<!-- TOAST JS -->
<script src="{{ asset('assets/js/pages/bootstrap-toasts.init.js') }}"></script>
@endif