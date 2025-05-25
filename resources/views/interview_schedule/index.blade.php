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
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Applicant Name</th>
                            <th>Email</th>
                            <th>Position</th>
                            <th>Interview Date</th>
                            <th>Address</th>
                            <th>Notes</th>
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
                            <td>{{ $applicant->candidate_first_name ?? '-' }} {{ $applicant->candidate_last_name ?? '' }}</td>
                            <td>{{ $applicant->email ?? '-' }}</td>
                            <td>{{ $position }}</td>
                            <td>{{ $schedule->interview_date }}</td>
                            <td>{{ $schedule->interview_address }}</td>
                            <td>{{ $schedule->interview_notes }}</td>
                            <td>{{ $schedule->creator->name ?? '-' }}</td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $schedule->id }}">Edit</button>
                                <form action="{{ route('interview_schedule.delete', $schedule->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this schedule?')">Delete</button>
                                </form>
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
@endsection
