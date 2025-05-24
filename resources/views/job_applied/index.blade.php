@extends('layouts.master')
@section('konten')
<div class="page-content">
    <div class="card">
        <div class="card-header">
            <h4 class="text-bold">Job Applied</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered dt-responsive w-100" id="jobAppliedTable">
                    <thead class="table-light">
                        <tr>
                            <th class="align-middle text-center">No</th>
                            <th class="align-middle text-center">Position</th>
                            <th class="align-middle text-center">Number of Applicant (<span style="color:red">Unseen</span>)</th>
                            <th class="align-middle text-center">Unreviewed</th>
                            <th class="align-middle text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Data will be loaded by DataTables, leave empty for now --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        var dataTable = $('#jobAppliedTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: '{!! route('jobapplied.index') !!}',
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
                    data: 'position',
                    orderable: true,
                    className: 'align-top',
                },
                {
                    data: 'number_of_applicant',
                    orderable: true,
                    className: 'align-top text-center',
                },
                {
                    data: 'unreviewed',
                    orderable: true,
                    className: 'align-top text-center',
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'align-top text-center',
                },
            ],
        });
    });
</script>
@endsection
