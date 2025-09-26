@extends('layouts.master')
@section('konten')
<div class="page-content">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-12 text-center">
                    <h4 class="text-bold">Candidate User List</h4>
                </div>
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
