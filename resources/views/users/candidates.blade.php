@extends('layouts.master')
@section('konten')
<div class="page-content">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-4">
                    @if(in_array(Auth::user()->role, ['Super Admin', 'Admin']))
                        @if($isEnable2FA)
                            <button type="button" class="btn btn-danger waves-effect btn-label waves-light" 
                                data-bs-toggle="modal" data-bs-target="#disable2FA">
                                <i class="mdi mdi-lock-open-remove label-icon"></i> Disable 2FA
                            </button>
                            <div class="modal fade" id="disable2FA" tabindex="-1" aria-labelledby="disable2FALabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-top">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title" id="disable2FALabel"><i class="mdi mdi-lock-open-remove"></i> Disable Two-Factor Authentication</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            Are you sure you want to <b>disable 2FA</b> for <b>All Candidates</b>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                            <form class="formLoad" action="{{ route('user.disable2faCandidate') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger waves-effect btn-label waves-light"><i class="mdi mdi-lock-open-remove label-icon"></i>Disable</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <button type="button" class="btn btn-success waves-effect btn-label waves-light" 
                                data-bs-toggle="modal" data-bs-target="#enable2FA">
                                <i class="mdi mdi-lock-check label-icon"></i> Enable 2FA
                            </button>
                            <div class="modal fade" id="enable2FA" tabindex="-1" aria-labelledby="enable2FALabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-top">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title" id="enable2FALabel="><i class="mdi mdi-lock-check"></i> Enable Two-Factor Authentication</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            Are you sure you want to <b>enable 2FA</b> for <b>All Candidates</b>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                            <form class="formLoad" action="{{ route('user.enable2faCandidate') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success waves-effect btn-label waves-light"><i class="mdi mdi-lock-check label-icon"></i>Enable</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="col-4">
                    <div class="text-center">
                        <h4 class="text-bold">Candidate User List</h4>
                    </div>
                </div>
                <div class="col-4"></div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered dt-responsive w-100" id="candidateTable">
                <thead class="table-light">
                    <tr>
                        <th class="align-middle text-center">No</th>
                        <th class="align-middle text-center">Name</th>
                        <th class="align-middle text-center">Email</th>
                        <th class="align-middle text-center">Login Counter</th>
                        <th class="align-middle text-center">Last Seen</th>
                        <th class="align-middle text-center">Account Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    $(function() {
        var dataTable = $('#candidateTable').DataTable({
            
            scrollX: true,
            responsive: false,
            processing: true,
            serverSide: true,
            ajax: '{!! route('user.candidates') !!}',
            columns: [
                {
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
                        return '<b>' + row.name + '</b>';
                    },
                },
                {
                    data: 'email',
                    orderable: true,
                },
                {
                    data: 'login_counter',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-center',
                },
                {
                    data: 'last_seen',
                    orderable: true,
                    className: 'align-top',
                    render: function(data, type, row) {
                        var statusLogin = '';
                        var lastSeen = new Date(data);
                        var now = new Date();
                        var diffMinutes = (now - lastSeen) / 60000;
                        if (data && diffMinutes <= 5) {
                            statusLogin = '<span class="badge bg-success text-white"><i class="fas fa-circle"></i> Online</span>';
                        } else {
                            statusLogin = '<span class="badge bg-secondary text-white"><i class="fas fa-circle-notch"></i> Offline</span>';
                        }
                        return statusLogin + '<br>' + (data ? data : '-');
                    },
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
