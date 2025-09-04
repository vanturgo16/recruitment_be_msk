@extends('layouts.master')
@section('konten')
<div class="page-content">
    <div class="row custom-margin">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div class="page-title-left">
                    <a href="{{ route('jobapplied.detail', encrypt($idJobList)) }}" class="btn btn-light waves-effect btn-label waves-light">
                        <i class="mdi mdi-arrow-left label-icon"></i> {{ __('messages.back_to_list') }}
                    </a>
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('jobapplied.detail', encrypt($idJobList)) }}">Applicant List</a></li>
                        <li class="breadcrumb-item active"> Detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    @php
        $isApprovalHR = Auth::user()->role === 'Admin HR' && $jobApply->is_approved_1 === null;
        $isApprovalHead = Auth::user()->role === 'Employee' && in_array(Auth::user()->hie_level, [2,3]) && $jobApply->is_approved_1 === 1 && $jobApply->is_approved_2 === null;
        $progressSteps = [
            'LAMARAN TERKIRIM' => ['icon' => 'bi-send-check', 'badge' => 'secondary', 'text' => 'dark'],
            'REVIEW ADM'       => ['icon' => 'bi-hourglass-split', 'badge' => 'warning', 'text' => 'white'],
            'INTERVIEW'        => ['icon' => 'bi-person-lines-fill', 'badge' => 'warning', 'text' => 'white'],
            'TESTED'           => ['icon' => 'bi-file-earmark-text', 'badge' => 'warning', 'text' => 'white'],
            'OFFERING'         => ['icon' => 'bi-question-circle', 'badge' => 'warning', 'text' => 'white'],
            'MCU'              => ['icon' => 'bi-file-medical', 'badge' => 'warning', 'text' => 'white'],
            'SIGN'             => ['icon' => 'bi-pen-fill', 'badge' => 'warning', 'text' => 'white'],
            'HIRED'            => ['icon' => 'bi-check-circle', 'badge' => 'success', 'text' => 'white'],
            'REJECT'           => ['icon' => 'bi-x-circle-fill', 'badge' => 'danger', 'text' => 'white'],
            // fallback/default for undefined or unknown statuses
            'DEFAULT'          => ['icon' => 'bi-question-circle', 'badge' => 'secondary', 'text' => 'dark'],
        ];
    @endphp

    {{-- MAIN CARD --}}
    <div class="card">
        <div class="card-header bg-light">
            <h4 class="text-bold">Detail Info Applicant Candidate "{{ $candidate->candidate_first_name }} {{ $candidate->candidate_last_name }}"</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered dt-responsive w-100">
                <thead class="table-light">
                    <tr>
                        <th class="align-middle text-center">Data Applicant</th>
                        <th class="align-middle text-center">Response Screening</th>
                        <th class="align-middle text-center">Approval Administration</th>
                        <th class="align-middle text-center">Application Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="align-top text-center">
                            <button type="button" class="btn btn-sm btn-info waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#dataApplicant">
                                <i class="mdi mdi-eye label-icon"></i> Show Detail
                            </button>
                        </td>
                        <td class="align-top text-center">
                            <button type="button" class="btn btn-sm btn-info waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#responseScreening">
                                <i class="mdi mdi-eye label-icon"></i> Show Response
                            </button>
                        </td>
                        <td class="align-top">
                            @if($isApprovalHR)
                                <div class="text-center">
                                    <button type="button" class="btn btn-sm btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#approveAdminModal">
                                        <i class="mdi mdi-comment-question label-icon"></i> Your Decision
                                    </button>
                                </div>
                                <!-- Modal -->
                                <div class="modal fade" id="approveAdminModal" tabindex="-1" aria-labelledby="approveAdminModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form class="formLoad" action="{{ route('jobapplied.approveadmin', encrypt($idJobApply)) }}" method="POST">
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
                                                    <button type="submit" class="btn btn-success waves-effect btn-label waves-light">
                                                        <i class="mdi mdi-send label-icon"></i> Submit
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @else
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
                                    <div class="text-center">
                                        <span class="badge bg-secondary">Not Approved</span>
                                    </div>
                                @endif
                            @endif
                        </td>
                        <td class="align-top text-center">
                            @php
                                $status = $jobApply->status == 2 ? 'REJECT' : $jobApply->progress_status;
                                $step = $progressSteps[$status] ?? $progressSteps['DEFAULT'];
                            @endphp
                            <span class="badge bg-{{ $step['badge'] }} text-{{ $step['text'] }}">
                                <i class="bi {{ $step['icon'] }}"></i> {{ $status }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="dataApplicant" tabindex="-1" aria-labelledby="dataApplicantModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Data Applicant</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
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
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="responseScreening" tabindex="-1" aria-labelledby="responseScreeningModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Response Screening Applicant</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body small text-start" style="max-height: 67vh; overflow-y: auto;">
                        @php
                            $screening = json_decode($jobApply->screening_content);
                        @endphp

                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    @foreach($screening as $index => $item)
                                        <div class="mb-4 p-3 border rounded shadow-sm bg-light">
                                            <h5 class="mb-2">Pertanyaan {{ $index + 1 }}</h5>
                                            <p><strong>{{ $item->question }}</strong></p>
                                            <p class="text-muted">{{ $item->answer }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header bg-light">
            <h4 class="text-bold">Log Activity Progress</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered dt-responsive w-100" id="ssTable">
                <thead class="table-light">
                    <tr>
                        <th class="align-middle text-center">No</th>
                        <th class="align-middle text-center">Phase</th>
                        <th class="align-middle text-center">Activity</th>
                        <th class="align-middle text-center">Action By</th>
                    </tr>
                </thead>
            </table>
        </div>

        <script>
            $(function() {
                var dataTable = $('#ssTable').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollY: '100vh',
                    ajax: '{!! route('jobapplied.applicantinfo', encrypt($idJobApply)) !!}',
                    columns: [{
                        data: null,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            },
                            orderable: false,
                            searchable: false,
                            visible: false,
                            className: 'align-top text-center',
                        },
                        {
                            data: 'phase',
                            orderable: true,
                            searchable: true,
                            className: 'align-top text-bold',
                        },
                        {
                            data: 'activity',
                            name: 'activity',
                            orderable: true,
                            searchable: true,
                            className: 'align-top',
                            render: function(data, type, row) {
                                var notes = row.notes ? row.notes : '-';
                                return data + '<br><b>Note: </b>' + notes;
                            },
                        },
                        {
                            data: 'created',
                            name: 'created',
                            searchable: true,
                            orderable: true,
                            className: 'align-top',
                            render: function(data, type, row) {
                                return data + '<br><b>At.</b>' + dayjs(row.created_at).format('YYYY-MM-DD HH:mm');
                            },
                        },
                    ],
                });
                $('#vertical-menu-btn').on('click', function() {
                    setTimeout(function() {
                        dataTable.columns.adjust().draw();
                        window.dispatchEvent(new Event('resize'));
                    }, 10);
                });
            });
        </script>
    </div>
</div>
@endsection
