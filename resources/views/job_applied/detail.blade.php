@extends('layouts.master')
@section('konten')
<div class="page-content">
    <div class="card">
        <div class="card-header">
            <h4 class="text-bold">Job Applied Detail: {{ $datas[0]['position_name'] . "-" . $datas[0]['dept_name'] }}</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered dt-responsive w-100" id="jobAppliedDetailTable">
                    <thead class="table-light">
                        <tr>
                            <th class="align-middle text-center">ID</th>
                            <th class="align-middle text-center">Applicant Name</th>
                            <th class="align-middle text-center">Applicant Email</th>
                            <th class="align-middle text-center">Applied At</th>
                            <th class="align-middle text-center">Status</th>
                            <th class="align-middle text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($datas as $data)
                            <tr>
                                <td>{{ $data->id }}</td>
                                <td>
                                    {{ $data->candidate_first_name . " " . $data->candidate_last_name }}
                                    @if ($data->is_seen == '0')
                                        <span class="align-middle ms-2" title="Not seen yet">
                                            <span style="display:inline-block;width:10px;height:10px;background:#dc3545;border-radius:50%;margin-right:3px;"></span>
                                        </span>
                                    @else
                                        <span class="align-middle ms-2" title="Already seen">
                                            <span style="display:inline-block;width:10px;height:10px;background:#28a745;border-radius:50%;margin-right:3px;"></span>
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $data->email }}</td>
                                <td>{{ $data->created_at }}</td>
                                <td>
                                    @if($data->progress_status)
                                        <span class="badge bg-primary">{{ $data->progress_status }}</span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('jobapplied.seen', encrypt($data->id)) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-info btn-sm ms-2">
                                            Detail Info
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <a href="{{ route('jobapplied.index') }}" class="btn btn-secondary mt-3">Back to Job Applied</a>
        </div>
    </div>
</div>
<script>
    $(function() {
        var dataTable = $('#jobAppliedDetailTable').DataTable({
            processing: true,
            responsive: true,
        });
    });
</script>
@endsection