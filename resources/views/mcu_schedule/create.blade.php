@extends('layouts.master')
@section('konten')
<div class="page-content">
    <div class="card">
        <div class="card-header">
            <h4 class="text-bold">Create MCU Schedule</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('mcu_schedule.store') }}" method="POST">
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
                    <label for="mcu_date" class="form-label">MCU Date</label>
                    <input type="datetime-local" name="mcu_date" id="mcu_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="mcu_address" class="form-label">MCU Address</label>
                    <input type="text" name="mcu_address" id="mcu_address" value="{{ $location_mcu_value }}" class="form-control" readonly required>
                </div>
                <div class="mb-3">
                    <label for="mcu_notes" class="form-label">MCU Notes</label>
                    <textarea name="mcu_notes" id="mcu_notes" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('mcu_schedule.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
