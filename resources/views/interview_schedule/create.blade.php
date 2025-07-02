@extends('layouts.master')
@section('konten')
<div class="page-content">
    <div class="card">
        <div class="card-header">
            <h4 class="text-bold">Create Interview Schedule</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('interview_schedule.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id_jobapply" id="id_jobapply" value="{{ $id_jobapply ?? '' }}">
                <div class="mb-3">
                    <label class="form-label">Applicant Name</label>
                    <input type="text" class="form-control" value="{{ $applicant_name ?? '-' }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Position</label>
                    <input type="text" class="form-control" value="{{ $position_name ?? '-' }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="interview_date" class="form-label">Interview Date</label>
                    <input type="datetime-local" name="interview_date" id="interview_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="interview_address" class="form-label">Interview Address</label>
                    <input type="text" name="interview_address" id="interview_address" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="interview_notes" class="form-label">Interview Notes</label>
                    <textarea name="interview_notes" id="interview_notes" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('interview_schedule.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
