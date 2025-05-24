@extends('layouts.master')
@section('konten')
<div class="page-content">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="text-bold mb-0">Profile</h4>
        </div>
        <div class="card-body">
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
