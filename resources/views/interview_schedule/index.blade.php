@extends('layouts.master')
@section('konten')
<div class="page-content">
    <div class="card">
        <div class="card-header">
            <h4 class="text-bold">Interview Schedules</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered" id="interviewTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Applicant (Name & Email)</th>
                            <th>Position Applied</th>
                            <th>Interview Detail</th>
                            <th>Created By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $schedule)
                        @php
                            $applicant = $schedule->jobapply ? $schedule->jobapply->candidate : null;
                            $position = $schedule->jobapply && $schedule->jobapply->joblist && $schedule->jobapply->joblist->position ? $schedule->jobapply->joblist->position->position_name : '-';
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                {{ $applicant->candidate_first_name ?? '-' }} {{ $applicant->candidate_last_name ?? '' }}<br>
                                <small class="text-muted">{{ $applicant->email ?? '-' }}</small>
                            </td>
                            <td>{{ $position }}</td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewDetailModal{{ $schedule->id }}">
                                    View
                                </button>
                                <!-- Modal View Detail -->
                                <div class="modal fade" id="viewDetailModal{{ $schedule->id }}" tabindex="-1" aria-labelledby="viewDetailModalLabel{{ $schedule->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="viewDetailModalLabel{{ $schedule->id }}">Interview Detail</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-2">
                                                    <strong>Interview Date:</strong><br>
                                                    {{ $schedule->interview_date }}
                                                </div>
                                                <div class="mb-2">
                                                    <strong>Address:</strong><br>
                                                    {{ $schedule->interview_address }}
                                                </div>
                                                <div class="mb-2">
                                                    <strong>Notes:</strong><br>
                                                    {{ $schedule->interview_notes }}
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal View Detail -->
                            </td>
                            <td>{{ $schedule->creator->name ?? '-' }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton{{ $schedule->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        Action
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $schedule->id }}">
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editModal{{ $schedule->id }}">Edit</a>
                                        </li>
                                        <li>
                                            <form action="{{ route('interview_schedule.delete', $schedule->id) }}" method="POST" onsubmit="return confirm('Delete this schedule?')">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-danger">Delete</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <!-- Modal Edit -->
                        <div class="modal fade" id="editModal{{ $schedule->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $schedule->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('interview_schedule.update', $schedule->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel{{ $schedule->id }}">Edit Interview Schedule</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id_jobapply" value="{{ $schedule->id_jobapply }}">
                                            <div class="mb-3">
                                                <label class="form-label">Applicant Name</label>
                                                <input type="text" class="form-control" value="{{ $applicant->candidate_first_name ?? '-' }} {{ $applicant->candidate_last_name ?? '' }}" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Position</label>
                                                <input type="text" class="form-control" value="{{ $position }}" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="interview_date{{ $schedule->id }}" class="form-label">Interview Date</label>
                                                <input type="datetime-local" name="interview_date" id="interview_date{{ $schedule->id }}" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime($schedule->interview_date)) }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="interview_address{{ $schedule->id }}" class="form-label">Interview Address</label>
                                                <input type="text" name="interview_address" id="interview_address{{ $schedule->id }}" class="form-control" value="{{ $schedule->interview_address }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="interview_notes{{ $schedule->id }}" class="form-label">Interview Notes</label>
                                                <textarea name="interview_notes" id="interview_notes{{ $schedule->id }}" class="form-control">{{ $schedule->interview_notes }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal Edit -->
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#interviewTable').DataTable();
    });
</script>
@endsection
