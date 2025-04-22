@extends('layouts.master')
@section('konten')
<div class="page-content">
    <div class="row custom-margin">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div class="page-title-left">
                    <a href="{{ route('office.index') }}" class="btn btn-light waves-effect btn-label waves-light">
                        <i class="mdi mdi-arrow-left label-icon"></i> {{ __('messages.back_to_list') }}
                    </a>
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('office.index') }}">{{ __('messages.mst_office') }}</a></li>
                        <li class="breadcrumb-item active"> {{ __('messages.edit') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CARD --}}
    <div class="card">
        <div class="card-header">
            <h4 class="text-bold">{{ __('messages.edit') }}</h4>
        </div>
        <form class="formLoad" action="{{ route('office.update', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <label class="form-label">Office Type</label><label style="color: darkred">*</label>
                        <select class="form-select select2" style="width: 100%" name="type" required>
                            <option value="" selected>-- Select Type --</option>
                            <option disabled>──────────</option>
                            @foreach($officeTypes as $item)
                                <option value="{{ $item->name_value }}" {{ $data->type == $item->name_value ? 'selected' : '' }}> {{ $item->name_value }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="form-label">Code</label><label style="color: darkred">*</label>
                        <input class="form-control" name="code" type="text" value="{{ $data->code }}" placeholder="Input Office Code.." required>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="form-label">Name</label><label style="color: darkred">*</label>
                        <input class="form-control" name="name" type="text" value="{{ $data->name }}" placeholder="Input Office Name.." required>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <label class="form-label">Address</label><label style="color: darkred">*</label>
                        <textarea class="form-control" rows="3" type="text" class="form-control" name="address" placeholder="(Input Office Address, Ex. Street/Unit/Floor/No)" required>{{ $data->address }}</textarea>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <select class="form-select select2" style="width: 100%" name="province" id="province" class="form-control" required>
                            <option value="" selected>-- Select Province --</option>
                            @foreach ($listProvinces as $item)
                                <option value="{{ $item['nama'] }}" {{ $data->province == $item['nama'] ? 'selected' : '' }} data-idProv="{{ $item['id'] }}">
                                    {{ $item['nama'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <select class="form-select select2" style="width: 100%" name="city" id="city" class="form-control" required>
                            <option value="" selected>- Select City -</option>
                            @foreach ($listCities as $item)
                                <option value="{{ $item['nama'] }}" {{ $data->city == $item['nama'] ? 'selected' : '' }} data-idcity="{{ $item['id'] }}">
                                    {{ $item['nama'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <select class="form-select select2" style="width: 100%" name="district" id="district" class="form-control" required>
                            <option value="" selected>- Select District -</option>
                            @foreach ($listDistricts as $item)
                                <option value="{{ $item['nama'] }}" {{ $data->district == $item['nama'] ? 'selected' : '' }} data-iddistrict="{{ $item['id'] }}">
                                    {{ $item['nama'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <select class="form-select select2" style="width: 100%" name="subdistrict" id="subdistrict" class="form-control" required>
                            <option value="" selected>- Select Subdistrict -</option>
                            @foreach ($listSubDistricts as $item)
                                <option value="{{ $item['nama'] }}" {{ $data->subdistrict == $item['nama'] ? 'selected' : '' }} data-postalCode="{{ $item['kodepos'] }}">
                                    {{ $item['nama'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <input name="postal_code" id="postal_code" type="text" class="form-control" placeholder="Input Postal Code.." value="{{ $data->postal_code }}" required>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row text-end">
                    <div>
                        <a href="javascript:location.reload();" type="button" class="btn btn-secondary waves-effect btn-label waves-light">
                            <i class="mdi mdi-reload label-icon"></i>{{ __('messages.reset') }}
                        </a>
                        <button type="submit" class="btn btn-primary waves-effect btn-label waves-light">
                            <i class="mdi mdi-update label-icon"></i>{{ __('messages.update') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@include('office.regionalscript')

@endsection