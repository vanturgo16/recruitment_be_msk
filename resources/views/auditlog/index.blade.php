@extends('layouts.master')
@section('konten')

<div class="page-content">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-4">
                    <span class="badge bg-primary text-white">{{ \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->translatedFormat('F Y') }}</span>
                </div>
                <div class="col-4">
                    <div class="text-center">
                        <h4 class="text-bold">{{ __('messages.audit_log') }}</h4>
                    </div>
                </div>
                <div class="col-4">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-sm btn-secondary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#modalFilter">
                            <i class="mdi mdi-filter label-icon"></i> Filter DateTime
                        </button>
                        {{-- Modal Filter --}}
                        <div class="modal fade" id="modalFilter" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-top" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Filter {{ __('messages.month') }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form class="formLoad" action="{{ route('auditlog.index') }}" method="GET" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body p-4">
                                            <div class="row">
                                                <div class="col-lg-12 mb-3">
                                                    <label class="form-label">DateTime</label> <label class="text-danger">*</label>
                                                    <input type="month" class="form-control" name="monthYear" value="{{ $monthYear }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                                            <button type="submit" class="btn btn-success waves-effect btn-label waves-light"><i class="mdi mdi-eye label-icon"></i></i>{{ __('messages.show') }}</button>
                                        </div>
                                    </form>
                                </div>
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
                        <th class="align-middle text-center">Email</th>
                        <th class="align-middle text-center">Access From</th>
                        <th class="align-middle text-center">Activity</th>
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
            ajax: {
                url: '{!! route('auditlog.index') !!}',
                type: 'GET',
                data: function(d) {
                    d.monthYear = '{{ $monthYear }}';
                }
            },
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
                    orderable: true,
                    data: 'username',
                    name: 'username'
                },
                {
                    data: 'ip_address',
                    orderable: true,
                    render: function(data, type, row) {
                        return row.ip_address + ' - <b>' + row.access_from + '</b><br>' + row.created;
                    },
                },
                {
                    data: 'activity',
                    searchable: true,
                    orderable: true,
                    className: 'align-top',
                    render: function (data, type, row) {
                        const maxLineLength = 35;
                        let actLog = data;
                        if (data.length > maxLineLength) {
                            const words = data.split(' ');
                            let line = '';
                            let wrappedText = '';
                            words.forEach(word => {
                                if ((line + word).length > maxLineLength) {
                                    wrappedText += line.trim() + '<br>';
                                    line = word + ' ';
                                } else {
                                    line += word + ' ';
                                }
                            });
                            actLog = wrappedText + line.trim();
                        }
                        return actLog;
                    }
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