@extends('layouts.master')
@section('konten')
<div class="page-content">
    {{-- MAIN CARD --}}
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-4">
                    @if(in_array(Auth::user()->role, ['Super Admin', 'Admin']))
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-account-plus label-icon"></i> {{ __('messages.add_new') }}</button>
                        
                        {{-- Modal Add --}}
                        <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-top modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">{{ __('messages.add_new') }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form class="formLoad" action="{{ route('user.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">{{ __('messages.name') }}</label><label style="color: darkred">*</label>
                                                    <input class="form-control" type="text" name="name" placeholder="Input {{ __('messages.name') }}.." required>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Email</label><label style="color: darkred">*</label>
                                                    <input class="form-control" type="email" name="email" placeholder="Input Email.." required>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">{{ __('messages.dealer_type') }}</label> <label class="text-danger">*</label>
                                                    <select class="form-control select2" name="dealer_type" required>
                                                        <option value="" disabled selected>- {{ __('messages.select') }} {{ __('messages.dealer_type') }} -</option>
                                                        @foreach($dealerTypes as $item)
                                                            <option value="{{ $item->name_value }}">{{ $item->name_value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">{{ __('messages.dealer_name') }}</label><label style="color: darkred">*</label>
                                                    <input class="form-control" type="text" name="dealer_name" placeholder="Input {{ __('messages.dealer_name') }}.." required>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">{{ __('messages.department') }}</label> <label class="text-danger">*</label>
                                                    <select class="form-control select2" name="department" required>
                                                        <option value="" disabled selected>- {{ __('messages.select') }} {{ __('messages.department') }} -</option>
                                                        @foreach($departments as $item)
                                                            <option value="{{ $item->name_value }}">{{ $item->name_value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Role</label> <label class="text-danger">*</label>
                                                    <select class="form-control select2" name="role" required>
                                                        <option value="" disabled selected>- {{ __('messages.select') }} Role -</option>
                                                        @foreach($roleUsers as $item)
                                                            <option value="{{ $item->name_value }}">{{ $item->name_value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                                            <button type="submit" class="btn btn-success waves-effect btn-label waves-light"><i class="mdi mdi-account-plus label-icon"></i>{{ __('messages.add') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-4">
                    <div class="text-center">
                        <h4 class="text-bold">{{ __('messages.mng_user') }}</h4>
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
                        <th class="align-middle text-center">{{ __('messages.name') }}</th>
                        <th class="align-middle text-center">Dealer</th>
                        <th class="align-middle text-center">{{ __('messages.department') }}</th>
                        <th class="align-middle text-center">Role</th>
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
            ajax: '{!! route('user.datas') !!}',
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
                    data: 'name',
                    orderable: true,
                    render: function(data, type, row) {
                        return '<b>' + row.name + '</b><br>' + row.email;
                    },
                },
                {
                    data: 'dealer_name',
                    name: 'dealer_name',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                    render: function(data, type, row) {
                        return row.dealer_type + '<br>' + data;
                    },
                },
                {
                    data: 'department',
                    name: 'department',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                },
                {
                    orderable: true,
                    data: 'role',
                    name: 'role',
                    className: 'align-top fw-bold',
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
                {
                    data: 'dealer_type',
                    name: 'dealer_type',
                    searchable: true,
                    visible: false
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

@endsection