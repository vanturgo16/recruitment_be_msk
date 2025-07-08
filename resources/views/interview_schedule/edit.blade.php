@extends('layouts.master')
@section('konten')
<div class="page-content">
    <div class="card">
        <div class="card-header">
            <h4 class="text-bold">Edit Interview Schedule</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('interview_schedule.update', $schedule->id) }}" method="POST">
                @csrf
                <input type="hidden" name="id_jobapply" id="id_jobapply" value="{{ $schedule->id_jobapply }}">
                <div class="mb-3">
                    <label class="form-label">Applicant Name</label>
                    <input type="text" class="form-control" value="{{ $schedule->jobapply->candidate->candidate_first_name ?? '-' }} {{ $schedule->jobapply->candidate->candidate_last_name ?? '' }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Position</label>
                    <input type="text" class="form-control" value="{{ $schedule->jobapply->joblist->position_name ?? '-' }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="interview_date" class="form-label">Interview Date</label>
                    <input type="datetime-local" name="interview_date" id="interview_date" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime($schedule->interview_date)) }}" required>
                </div>
                <div class="mb-3">
                    <label for="interview_address" class="form-label">Interview Address</label>
                    <input type="text" name="interview_address" id="interview_address" class="form-control" value="{{ $schedule->interview_address }}" required>
                </div>
                <div class="mb-3">
                    <label for="interview_notes" class="form-label">Interview Notes</label>
                    <textarea name="interview_notes" id="interview_notes" class="form-control">{{ $schedule->interview_notes }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('interview_schedule.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
