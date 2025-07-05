@extends('layouts.master')
@section('konten')

<div class="page-content">
    {{-- MAIN CARD --}}
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-12">
                    <div class="text-center">
                        <h4 class="text-bold">{{ __('messages.emp_list') }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-12 justify-content-end d-flex">
                    <a href="{{ route('employee.export.excel') }}" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Download Excel
                    </a>
                </div>
            </div>
            <table class="table table-bordered dt-responsive nowrap w-100" id="ssTable">
                <thead class="table-light">
                    <tr>
                        <th class="align-middle text-center">No</th>
                        <th class="align-middle text-center">{{ __('messages.emp_no') }}</th>
                        <th class="align-middle text-center">Email</th>
                        <th class="align-middle text-center">{{ __('messages.department') }}</th>
                        <th class="align-middle text-center">{{ __('messages.position') }}</th>
                        <th class="align-middle text-center">{{ __('messages.placement') }}</th>
                        <th class="align-middle text-center">Status</th>
                        <th class="align-middle text-center">{{ __('messages.action') }}</th>
                    </tr>
                </thead>
            </table>
    </div>
</div>

<script>
    $(function() {
        var dataTable = $('#ssTable').DataTable({
            processing: true,
            serverSide: true,
            scrollY: '100vh',
            ajax: '{!! route('employee.index') !!}',
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
                    data: 'emp_no',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-bold',
                },
                {
                    data: 'email',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                },
                {
                    data: 'dept_name',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                },
                {
                    data: 'position_name',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                },
                {
                    data: 'office_name',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
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

@endsection