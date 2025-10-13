@extends('layouts.master')
@section('konten')

<div class="page-content">
    {{-- MAIN CARD --}}
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-4">
                    @if(in_array(Auth::user()->role, ['Super Admin', 'Admin', 'Admin HR']))
                        <a  href="{{ url($template->path) }}" download="{{ $template->file_name }}" type="button" class="btn btn-success waves-effect btn-label waves-light"><i class="mdi mdi-download label-icon"></i> Template</a>
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#import"><i class="mdi mdi-import label-icon"></i> Import Excel</button>
                        {{-- Modal Import --}}
                        <div class="modal fade" id="import" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-top modal-md" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Import Employee Data</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form class="formLoad" action="{{ route('employee.importData') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                                            <div class="row">
                                                <div class="col-lg-12 mb-3">
                                                    <label class="form-label">Data Excel</label> <label class="text-danger">*</label>
                                                    <input class="form-control" name="file" type="file" accept=".xls,.xlsx" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                                            <button type="submit" class="btn btn-success waves-effect btn-label waves-light">
                                                <i class="mdi mdi-import label-icon"></i>Import
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
                        <h4 class="text-bold">{{ __('messages.emp_list') }}</h4>
                    </div>
                </div>
                <div class="col-4 text-end">
                    <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#modalExportExcel"><i class="fas fa-file-excel label-icon"></i> Download Excel</button>
                    <div class="modal fade" id="modalExportExcel" tabindex="-1" aria-labelledby="modalExportExcelLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="GET" action="{{ route('employee.export.excel') }}" id="formExportExcel">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalExportExcelLabel">Export Excel - Department Filter</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-2">
                                            <label class="form-label">Select Department:</label>
                                            <div style="max-height:300px;overflow:auto;">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" id="checkAllDept">
                                                    <label class="form-check-label fw-bold" for="checkAllDept">All Departments</label>
                                                </div>
                                                @foreach($departments as $dept)
                                                    <div class="form-check">
                                                        <input class="form-check-input dept-checkbox" type="checkbox" name="departments[]" value="{{ $dept->id }}" id="dept_{{ $dept->id }}">
                                                        <label class="form-check-label" for="dept_{{ $dept->id }}">{{ $dept->dept_name }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Employee Status:</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="status" id="status_all" value="all" checked>
                                                <label class="form-check-label" for="status_all">All Status</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="status" id="status_active" value="active">
                                                <label class="form-check-label" for="status_active">Active</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="status" id="status_inactive" value="inactive">
                                                <label class="form-check-label" for="status_inactive">Inactive</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success"><i class="fas fa-file-excel"></i> Download Excel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
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
</div>

<script>
    $(function() {
        var dataTable = $('#ssTable').DataTable({
            scrollX: true,
            responsive: false,
            fixedColumns: {
                leftColumns: 2, // Freeze first two columns
                rightColumns: 1 // Freeze last column (Aksi)
            },
            processing: true,
            serverSide: true,

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

    $(document).ready(function() {
        $('#checkAllDept').on('change', function() {
            $('.dept-checkbox').prop('checked', $(this).is(':checked'));
        });
        $('.dept-checkbox').on('change', function() {
            if ($('.dept-checkbox:checked').length === $('.dept-checkbox').length) {
                $('#checkAllDept').prop('checked', true);
            } else {
                $('#checkAllDept').prop('checked', false);
            }
        });
    });
</script>

@endsection