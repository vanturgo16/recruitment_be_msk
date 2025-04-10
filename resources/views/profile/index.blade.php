@extends('layouts.master')
@section('konten')

<div class="page-content">
    <div class="row">
        <div class="col-lg-4 order-lg-2">
            <div class="card mb-4">
                <div class="text-center align-middle">
                    <button type="button" class="btn btn-sm btn-info waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#updProfile">
                        <i class="mdi mdi-update label-icon"></i> {{ __('messages.update') }} {{ __('messages.photo_profile') }}
                    </button>
                </div>
                {{-- Modal Update --}}
                <div class="modal fade" id="updProfile" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md modal-dialog-top" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">{{ __('messages.update_pp') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form class="formLoad" action="{{ route('profile.updatePhoto') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body text-start">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label class="text-danger">*(<b>JPG, JPEG, PNG</b> | Max: 5 Mbps)</label>
                                                <input type="file" name="photoProfile" class="form-control" required accept=".jpg, .jpeg, .png">
                                            </div>
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
                @php use Illuminate\Support\Facades\File; $defaultImagePath = public_path('assets/images/users/userDefault.png'); @endphp
                <div class="card-profile-image mt-4">
                    @if(Auth::user()->photo_path)
                        @php $userImagePath = public_path(Auth::user()->photo_path); @endphp
                        @if(File::exists($userImagePath))
                            <img src="{{ asset(Auth::user()->photo_path) }}" class="rounded-circle img-responsive-square" alt="user-image">
                        @else
                            @if(File::exists($defaultImagePath))
                                <img src="{{ asset('assets/images/users/userDefault.png') }}" class="rounded-circle img-responsive-square" alt="user-image">
                            @endif
                        @endif
                    @else
                        @if(File::exists($defaultImagePath))
                            <img src="{{ asset('assets/images/users/userDefault.png') }}" class="rounded-circle img-responsive-square" alt="user-image">
                        @endif
                    @endif
                </div>
                <div class="card-body body-detail-a">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-center">
                                <h5 class="font-weight-bold">{{  Auth::user()->name }}</h5>
                                <p>{{  Auth::user()->role }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 order-lg-1">
            <div class="card">
                <div class="card-header p-3 header-detail-a">
                    <h4 class="text-bold">{{ __('messages.user_info') }}</h4>
                </div>
                <div class="card-body body-detail-a">
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <div class="fw-bold"><span>{{ __('messages.full_name') }} :</span></div>
                            <span>{{ Auth::user()->name ?? '-' }}</span>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="fw-bold"><span>E-Mail :</span></div>
                            <span>{{ Auth::user()->email ?? '-' }}</span>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="fw-bold"><span>{{ __('messages.dealer_type') }} :</span></div>
                            <span>{{ Auth::user()->dealer_type ?? '-' }}</span>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="fw-bold"><span>{{ __('messages.dealer_name') }} :</span></div>
                            <span>{{ Auth::user()->dealer_name ?? '-' }}</span>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="fw-bold"><span>{{ __('messages.department') }} :</span></div>
                            <span>{{ Auth::user()->department ?? '-' }}</span>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="fw-bold"><span>{{ __('messages.role_in_system') }} :</span></div>
                            <span>{{ Auth::user()->role ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection