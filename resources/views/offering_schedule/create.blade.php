@extends('layouts.master')
@section('konten')
<div class="page-content">
    <div class="card">
        <div class="card-header">
            <h4 class="text-bold">Create Offering Schedule</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('offering_schedule.store') }}" method="POST">
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
                    <label for="offering_date" class="form-label">Offering Date</label>
                    <input type="datetime-local" name="offering_date" id="offering_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="offering_address" class="form-label">Offering Address</label>
                    <input type="text" name="offering_address" id="offering_address" value="{{ $location_offering_value }}" class="form-control" readonly required>
                </div>
                <div class="mb-3">
                    <label for="offering_notes" class="form-label">Offering Notes</label>
                    <textarea name="offering_notes" id="offering_notes" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('offering_schedule.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
