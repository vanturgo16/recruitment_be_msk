@extends('layouts.master')
@section('konten')
<div class="page-content">
    <div class="card">
        <div class="card-header">
            <h4 class="text-bold">Create Test Schedule</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('test_schedule.store') }}" method="POST">
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
                    <label for="test_date" class="form-label">Testing Date</label>
                    <input type="datetime-local" name="test_date" id="test_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="test_address" class="form-label">Testing Address</label>
                    <input type="text" name="test_address" id="test_address" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="test_notes" class="form-label">Testing Notes</label>
                    <textarea name="test_notes" id="test_notes" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('test_schedule.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
