@extends('layouts.master')
@section('konten')
<div class="page-content">
    <div class="card">
        @php
            $isAdminHR = Auth::user()->role === 'Admin HR';
            $isEmployeeHead = Auth::user()->role === 'Employee' && in_array(Auth::user()->hie_level, [2,3]);
            $isApproved1 = (isset($mainProfile) && $mainProfile->is_approved_1 == 1) || (isset($jobApply) && $jobApply->is_approved_1 == 1);
            $isRejected1 = (isset($jobApply) && $jobApply->is_approved_1 === 0);
            $isApproved2 = (isset($mainProfile) && $mainProfile->is_approved_2 == 1) || (isset($jobApply) && $jobApply->is_approved_2 == 1);
            $isRejected2 = (isset($jobApply) && $jobApply->is_approved_2 === 0);
            $isApproved2Null = (isset($mainProfile) && is_null($mainProfile->is_approved_2)) || (isset($jobApply) && is_null($jobApply->is_approved_2));
        @endphp
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="text-bold mb-0">Profile</h4>
            @if($isAdminHR && !$isApproved1 && !$isRejected1)
                <!-- Modal for Admin HR Approval Reason -->
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approveAdminModal" id="approveAdminBtn">
                    Approval Administration
                </button>
                <!-- Modal -->
                <div class="modal fade" id="approveAdminModal" tabindex="-1" aria-labelledby="approveAdminModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('jobapplied.approveadmin', encrypt($idJobApply)) }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="approveAdminModalLabel">Administration Approval</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <textarea name="approved_reason_1" class="form-control mb-2" rows="3" placeholder="Enter reason for approval or rejection" required></textarea>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="approval_action" id="approveRadio" value="approve" checked>
                                        <label class="form-check-label" for="approveRadio">Approve</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="approval_action" id="rejectRadio" value="reject">
                                        <label class="form-check-label" for="rejectRadio">Reject</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End Modal -->
            @elseif($isEmployeeHead && $isApproved1 && !$isApproved2 && $isApproved2Null && !$isRejected1 && !$isRejected2)
                <!-- Modal for Head Approval Reason -->
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#approveHeadModal" id="approveHeadBtn">
                    Head Approval
                </button>
                <!-- Modal -->
                <div class="modal fade" id="approveHeadModal" tabindex="-1" aria-labelledby="approveHeadModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('jobapplied.approvehead', encrypt($idJobApply)) }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="approveHeadModalLabel">Head Approval</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <textarea name="approved_reason_2" class="form-control mb-2" rows="3" placeholder="Enter reason for approval or rejection" required></textarea>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="approval_action_2" id="approveRadio2" value="approve" checked>
                                        <label class="form-check-label" for="approveRadio2">Approve</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="approval_action_2" id="rejectRadio2" value="reject">
                                        <label class="form-check-label" for="rejectRadio2">Reject</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End Modal -->
            @endif
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-12 mb-2">
                    <div class="card mb-3">
                        <div class="card-header bg-light d-flex align-items-center">
                            <span class="fw-bold me-2">Approval Administration Status</span>
                        </div>
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <div class="fw-bold mb-1">HR Approval</div>
                                    @if((isset($mainProfile) && $mainProfile->is_approved_1 == 1) || (isset($jobApply) && $jobApply->is_approved_1 == 1))
                                        <span class="badge bg-success">Approved</span>
                                        <span class="ms-2 text-muted small">by: {{ $approved_by_1_name ?? '-' }}</span>
                                        <span class="ms-2 text-muted small">at: {{ $mainProfile->approved_at_1 ?? $jobApply->approved_at_1 ?? '-' }}</span>
                                        @if($jobApply->approved_reason_1)
                                            <div class="mt-1 text-muted small"><b>Reason:</b> {{ $jobApply->approved_reason_1 }}</div>
                                        @endif
                                    @elseif(isset($jobApply) && $jobApply->is_approved_1 === 0)
                                        <span class="badge bg-danger">Rejected</span>
                                        <span class="ms-2 text-muted small">by: {{ $approved_by_1_name ?? '-' }}</span>
                                        <span class="ms-2 text-muted small">at: {{ $mainProfile->approved_at_1 ?? $jobApply->approved_at_1 ?? '-' }}</span>
                                        @if($jobApply->approved_reason_1)
                                            <div class="mt-1 text-muted small"><b>Reason:</b> {{ $jobApply->approved_reason_1 }}</div>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">Not Approved</span>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="fw-bold mb-1">Head Approval</div>
                                    @if((isset($mainProfile) && $mainProfile->is_approved_2 == 1) || (isset($jobApply) && $jobApply->is_approved_2 == 1))
                                        <span class="badge bg-success">Approved</span>
                                        <span class="ms-2 text-muted small">by: {{ $approved_by_2_name ?? '-' }}</span>
                                        <span class="ms-2 text-muted small">at: {{ $mainProfile->approved_at_2 ?? $jobApply->approved_at_2 ?? '-' }}</span>
                                        @if($jobApply->approved_reason_2)
                                            <div class="mt-1 text-muted small"><b>Reason:</b> {{ $jobApply->approved_reason_2 }}</div>
                                        @endif
                                    @elseif(isset($jobApply) && $jobApply->is_approved_2 === 0)
                                        <span class="badge bg-danger">Rejected</span>
                                        <span class="ms-2 text-muted small">by: {{ $approved_by_2_name ?? '-' }}</span>
                                        <span class="ms-2 text-muted small">at: {{ $mainProfile->approved_at_2 ?? $jobApply->approved_at_2 ?? '-' }}</span>
                                        @if($jobApply->approved_reason_2)
                                            <div class="mt-1 text-muted small"><b>Reason:</b> {{ $jobApply->approved_reason_2 }}</div>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">Not Approved</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4 mb-2">
                    <div class="fw-bold">Photo :</div>
                    @if($mainProfile && $mainProfile->self_photo)
                        <a href="{{ url($mainProfile->self_photo) }}" target="_blank" class="btn btn-outline-danger btn-sm"><i class="bx bx-show"></i> Current Photo</a>
                    @else
                        <button class="btn btn-outline-danger btn-sm" disabled><i class="bx bx-hide"></i> No Photo</button>
                    @endif
                </div>
                <div class="col-md-4 mb-2">
                    <div class="fw-bold">CV / Resume :</div>
                    @if($mainProfile && $mainProfile->cv_path)
                        <a href="{{ url($mainProfile->cv_path) }}" target="_blank" class="btn btn-outline-danger btn-sm"><i class="bx bx-show"></i> Current CV</a>
                    @else
                        <button class="btn btn-outline-danger btn-sm" disabled><i class="bx bx-hide"></i> No CV</button>
                    @endif
                </div>
                <div class="col-md-4 mb-2">
                    <div class="fw-bold">Email :</div>
                    <span>{{ $candidate->email ?? '-' }}</span>
                </div>
            </div>
            <hr>
            <div class="row mb-3">
                <div class="col-md-3 mb-2">
                    <div class="fw-bold">First Name :</div>
                    <span>{{ $candidate->candidate_first_name ?? '-' }}</span>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="fw-bold">Last Name :</div>
                    <span>{{ $candidate->candidate_last_name ?? '-' }}</span>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="fw-bold">Phone Number :</div>
                    <span>{{ $candidate->phone ?? '-' }}</span>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="fw-bold">ID Number :</div>
                    <span>{{ $candidate->id_card_no ?? '-' }}</span>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="fw-bold">Birthplace :</div>
                    <span>{{ $mainProfile->birthplace ?? '-' }}</span>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="fw-bold">Birthdate :</div>
                    <span>{{ $mainProfile->birthdate ?? '-' }}</span>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="fw-bold">Gender :</div>
                    <span>{{ $mainProfile->gender ?? '-' }}</span>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="fw-bold">Marital Status :</div>
                    <span>{{ $mainProfile->marriage_status ?? '-' }}</span>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="fw-bold">ID Card Address :</div>
                    <span>{{ $mainProfile->id_card_address ?? '-' }}</span>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="fw-bold">Domicile Address :</div>
                    <span>{{ $mainProfile->domicile_address ?? '-' }}</span>
                </div>
            </div>
            <hr>
            <h4 class="fw-bold">Education</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-hover dt-responsive w-100" id="tableEducation">
                    <thead class="table-light">
                        <tr>
                            <th class="align-middle text-center">#</th>
                            <th class="align-middle">Level</th>
                            <th class="align-middle">Institution</th>
                            <th class="align-middle">City</th>
                            <th class="align-middle">Major</th>
                            <th class="align-middle">Period</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eduInfo as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="fw-bold">{{ $item->edu_grade }}</td>
                            <td>{{ $item->edu_institution }}</td>
                            <td>{{ $item->edu_city }}</td>
                            <td>{{ $item->edu_major }}</td>
                            <td>{{ $item->edu_start_year }} -> {{ $item->edu_end_year }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h4 class="text-bold">Other Info</h4>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6 mb-2">
                    <div class="fw-bold">Medical History :</div>
                    <span>{{ $generalInfo->illness_history_desc ?? 'None' }}</span>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="fw-bold">Criminal Record :</div>
                    <span>{{ $generalInfo->criminal_history_desc ?? 'None' }}</span>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="fw-bold">Organization Experience :</div>
                    <span>{{ $generalInfo->mass_org_history_desc ?? 'None' }}</span>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="fw-bold">Training Experience :</div>
                    <span>{!! $generalInfo->training_exp_desc ?? 'None' !!}</span>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="fw-bold">Source Info :</div>
                    <span>{{ $generalInfo->source_info ?? '-' }}</span>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="fw-bold">Experienced / Fresh Graduate :</div>
                    <span>{{ $generalInfo->experience ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h4 class="text-bold">Experience</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover dt-responsive w-100" id="tableExperience">
                    <thead class="table-light">
                        <tr>
                            <th class="align-middle text-center">#</th>
                            <th class="align-middle">Institution / Company</th>
                            <th class="align-middle">City</th>
                            <th class="align-middle">Position</th>
                            <th class="align-middle">Period</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($workExpInfo as $itemWe)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="fw-bold">{{ $itemWe->we_institution }}</td>
                            <td>{{ $itemWe->we_city }}</td>
                            <td>{{ $itemWe->we_position }}</td>
                            <td>{{ $itemWe->we_start }} -> {{ $itemWe->we_end ?? 'Now' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <a href="{{ route('jobapplied.detail', encrypt($idJobList)) }}" class="btn btn-secondary mt-3">
                Back to Applicant List
            </a>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#tableEducation').DataTable({
            paging: true,
            searching: false,
            lengthChange: false,
            responsive: true,
            rowReorder: {
                selector: 'td:nth-child(2)'
            }
        });
    });
    $(document).ready(function() {
        var table = $('#tableExperience').DataTable({
            paging: true,
            searching: false,
            lengthChange: false,
            responsive: true,
            rowReorder: {
                selector: 'td:nth-child(2)'
            }
        });
    });
</script>
@endsection
