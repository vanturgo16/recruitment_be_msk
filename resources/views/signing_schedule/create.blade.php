@extends('layouts.master')
@section('konten')
<div class="page-content">
    <div class="card">
        <div class="card-header">
            <h4 class="text-bold">Create Signing Schedule</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('signing_schedule.store') }}" method="POST">
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
                    <label for="sign_date" class="form-label">Signing Date</label>
                    <input type="datetime-local" name="sign_date" id="sign_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="sign_address" class="form-label">Signing Address</label>
                    <input type="text" name="sign_address" id="sign_address" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="sign_notes" class="form-label">Signing Notes</label>
                    <textarea name="sign_notes" id="sign_notes" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('signing_schedule.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
