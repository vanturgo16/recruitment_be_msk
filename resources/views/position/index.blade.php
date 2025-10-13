@extends('layouts.master')
@section('konten')

<div class="page-content">
    {{-- MAIN CARD --}}
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-4">
                    @if(in_array(Auth::user()->role, ['Super Admin', 'Admin','Admin HR']))
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#addNew"><i class="mdi mdi-plus label-icon"></i> {{ __('messages.add_new') }}</button>
                        {{-- Modal Add --}}
                        <div class="modal fade" id="addNew" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-top modal-md" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">{{ __('messages.add_new') }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form class="formLoad" action="{{ route('position.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                                            <div class="row">
                                                <div class="col-lg-12 mb-3">
                                                    <label class="form-label">{{ __('messages.dept_name') }}</label> <label class="text-danger">*</label>
                                                    <select class="form-control select2" name="id_dept" required>
                                                        <option value="" disabled selected>- {{ __('messages.select') }} -</option>
                                                        @foreach($listDepartments as $item)
                                                            <option value="{{ $item->id }}">{{ $item->dept_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-12 mb-3">
                                                    <label class="form-label">{{ __('messages.position_name') }}</label> <label class="text-danger">*</label>
                                                    <input class="form-control" name="position_name" type="text" value="{{ old('position_name') }}" placeholder="Input Position Name.." required>
                                                </div>
                                                <div class="col-lg-12 mb-3">
                                                    <label class="form-label">{{ __('messages.hie_level') }}</label> <label class="text-danger">*</label>
                                                    <select class="form-control select2" name="hie_level" required>
                                                        <option value="" disabled selected>- {{ __('messages.select') }} -</option>
                                                        <option value="1">GM</option>
                                                        <option value="2">Manager</option>
                                                        <option value="3">Supervisor</option>
                                                        <option value="4">Admin</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-12 mb-3">
                                                    <label class="form-label">{{ __('messages.notes') }}</label>
                                                    <textarea class="form-control" rows="3" type="text" class="form-control" name="notes" placeholder="(Input Note For This Position)">{{ old('notes') }}</textarea>
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
                        <h4 class="text-bold">{{ __('messages.mst_position') }}</h4>
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
                        <th class="align-middle text-center">{{ __('messages.dept_name') }}</th>
                        <th class="align-middle text-center">{{ __('messages.position_name') }}</th>
                        <th class="align-middle text-center">{{ __('messages.hie_level') }}</th>
                        <th class="align-middle text-center">{{ __('messages.notes') }}</th>
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
            processing: true,
            serverSide: true,
            ajax: '{!! route('position.index') !!}',
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
                    data: 'dept_name',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                },
                {
                    data: 'position_name',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-bold',
                },
                {
                    data: 'hie_level',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                },
                {
                    data: 'notes',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                    render: function (data, type, row) {
                        return data ? data : '-';
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'align-top text-center',
                },
            ],
            drawCallback: function(settings) {
                var api = this.api();
                var rows = api.rows({ page: 'current' }).nodes();
                var lastCategory = null;
                var rowspan = 1;
                api.column(1, { page: 'current' }).data().each(function(category, i) {
                    if (lastCategory === category) {
                        rowspan++;
                        $(rows).eq(i).find('td:eq(1)').remove();
                    } else {
                        if (lastCategory !== null) {
                            $(rows).eq(i - rowspan).find('td:eq(1)').attr('rowspan', rowspan);
                        }
                        lastCategory = category;
                        rowspan = 1;
                    }
                });
                if (lastCategory !== null) {
                    $(rows).eq(api.column(1, { page: 'current' }).data().length - rowspan).find('td:eq(1)').attr('rowspan', rowspan);
                }
            }
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