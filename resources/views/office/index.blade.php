@extends('layouts.master')
@section('konten')

<div class="page-content">
    {{-- MAIN CARD --}}
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-4">
                    @if(in_array(Auth::user()->role, ['Super Admin', 'Admin']))
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#addNew"><i class="mdi mdi-plus label-icon"></i> {{ __('messages.add_new') }}</button>
                        {{-- Modal Add --}}
                        <div class="modal fade" id="addNew" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-top modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">{{ __('messages.add_new') }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form class="formLoad" action="{{ route('office.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                                            <div class="row">
                                                <div class="col-lg-12 mb-3">
                                                    <label class="form-label">Office Type</label><label style="color: darkred">*</label>
                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="type" required>
                                                        <option value="" selected>-- Select Type --</option>
                                                        <option disabled>──────────</option>
                                                        @foreach($officeTypes as $item)
                                                            <option value="{{ $item->name_value }}" {{ old('name_value') == $item->name_value ? 'selected' : '' }}> {{ $item->name_value }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Code</label><label style="color: darkred">*</label>
                                                    <input class="form-control" name="code" type="text" value="{{ old('code') }}" placeholder="Input Office Code.." required>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Name</label><label style="color: darkred">*</label>
                                                    <input class="form-control" name="name" type="text" value="{{ old('name') }}" placeholder="Input Office Name.." required>
                                                </div>
                                                <div class="col-lg-12 mb-3">
                                                    <label class="form-label">Address</label><label style="color: darkred">*</label>
                                                    <textarea class="form-control" rows="3" type="text" class="form-control" name="address" placeholder="(Input Office Address, Ex. Street/Unit/Floor/No)" required>{{ old('address') }}</textarea>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="province" id="province" class="form-control" required>
                                                        <option value="" selected>-- Select Province --</option>
                                                        @foreach ($listProvinces as $item)
                                                            <option value="{{ $item['nama'] }}" data-idProv="{{ $item['id'] }}">
                                                                {{ $item['nama'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="city" id="city" class="form-control" required>
                                                        <option value="" selected>- Select City -</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="district" id="district" class="form-control" required>
                                                        <option value="" selected>- Select District -</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="subdistrict" id="subdistrict" class="form-control" required>
                                                        <option value="" selected>- Select Subdistrict -</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <input name="postal_code" id="postal_code" type="text" class="form-control" placeholder="Input Postal Code.." value="{{ old('postal_code') }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                                            <button type="submit" class="btn btn-success waves-effect btn-label waves-light">
                                                <i class="mdi mdi-plus label-icon"></i>{{ __('messages.add') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-4">
                    <div class="text-center">
                        <h4 class="text-bold">{{ __('messages.mst_office') }}</h4>
                    </div>
                </div>
                <div class="col-4"></div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered dt-responsive w-100" id="ssTable">
                <thead class="table-light">
                    <tr>
                        <th class="align-middle text-center">No</th>
                        <th class="align-middle text-center">Type</th>
                        <th class="align-middle text-center">Code</th>
                        <th class="align-middle text-center">Name</th>
                        <th class="align-middle text-center">Address</th>
                        <th class="align-middle text-center">Status</th>
                        <th class="align-middle text-center">{{ __('messages.action') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    $(function() {
        var dataTable = $('#ssTable').DataTable({
            processing: true,
            serverSide: true,
            scrollY: '100vh',
            ajax: '{!! route('office.index') !!}',
            columns: [{
                data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false,
                    searchable: false,
                    className: 'align-top text-center',
                },
                {
                    data: 'type',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                },
                {
                    data: 'code',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-bold',
                },
                {
                    data: 'name',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                },
                {
                    data: 'address',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                    render: function(data, type, row) {
                        const parts = [
                            row.address,
                            row.subdistrict,
                            row.district,
                            row.city,
                            row.province,
                            row.postal_code
                        ];
                        const filtered = parts.filter(part => part && part.trim() !== '');
                        return filtered.join(', ');
                    }
                },
                {
                    data: 'is_active',
                    orderable: true,
                    className: 'align-top text-center',
                    render: function(data, type, row) {
                        var html
                        if(row.is_active == 1){
                            html = '<span class="badge bg-success text-white">Active</span>';
                        } else {
                            html = '<span class="badge bg-danger text-white">Inactive</span>';
                        }
                        return html;
                    },
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'align-top text-center',
                },
            ],
        });
        $('#vertical-menu-btn').on('click', function() {
            setTimeout(function() {
                dataTable.columns.adjust().draw();
                window.dispatchEvent(new Event('resize'));
            }, 10);
        });
    });
</script>

@include('office.regionalscript')

@endsection