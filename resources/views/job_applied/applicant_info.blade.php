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
        $progressSteps = [
            'LAMARAN TERKIRIM' => ['icon' => 'mdi-send-check', 'badge' => 'secondary', 'text' => 'dark'],
            'REVIEW ADM'       => ['icon' => 'mdi-hourglass', 'badge' => 'warning', 'text' => 'white'],
            'TESTED'           => ['icon' => 'mdi-file-document-outline', 'badge' => 'warning', 'text' => 'white'],
            'INTERVIEW'        => ['icon' => 'mdi-account-tie', 'badge' => 'warning', 'text' => 'white'],
            'OFFERING'         => ['icon' => 'mdi-help-circle-outline', 'badge' => 'warning', 'text' => 'white'],
            'MCU'              => ['icon' => 'mdi-medical-bag', 'badge' => 'warning', 'text' => 'white'],
            'SIGN'             => ['icon' => 'mdi-pen', 'badge' => 'warning', 'text' => 'white'],
            'HIRED'            => ['icon' => 'mdi-check-circle', 'badge' => 'success', 'text' => 'white'],
            'REJECT'           => ['icon' => 'mdi-close-circle', 'badge' => 'danger', 'text' => 'white'],
            // fallback/default for undefined or unknown statuses
            'DEFAULT'          => ['icon' => 'mdi-help-circle-outline', 'badge' => 'secondary', 'text' => 'dark'],
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
                        <th class="align-middle text-center">DATA APPLICANT</th>
                        <th class="align-middle text-center">RESPONSE SCREENING</th>
                        <th class="align-middle text-center">LAST APPLIED JOB REJECTED</th>
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
                            @if($latestApply)
                                <a href="{{ route('jobapplied.applicantinfo_public', encrypt($latestApply->id_last_apply)) }}">
                                    {{ $latestApply->latest_position }}
                                </a> at: {{ $latestApply->latest_applied_date }}<br><br>
                                <b>Last Status: </b>{{ $latestApply->latest_status }}<br>
                                <b>Reason Reject: </b>{{ $latestApply->latest_notes }}
                            @else
                                <div class="text-center">-</div>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <hr>
            <table class="table table-bordered dt-responsive w-100">
                <thead class="table-light">
                    <tr>
                        <th class="align-middle text-center">REVIEW ADMINISTRATION</th>
                        <th class="align-middle text-center">TESTED</th>
                        <th class="align-middle text-center">INTERVIEW</th>
                        <th class="align-middle text-center">OFFERING</th>
                        <th class="align-middle text-center">MEDICAL CHECK UP</th>
                        <th class="align-middle text-center">SIGNING CONTRACT</th>
                        <th class="align-middle text-center">STATUS</th>
                    </tr>
                </thead>
                
                <tbody>
                    <tr>
                        <td class="align-top text-center">
                            @if(Auth::user()->role === 'Admin HR' && $stepAdmin->status === null)
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
                                @if($stepAdmin->status === 1)
                                    <span class="badge bg-success">Approved</span>
                                @elseif($stepAdmin->status === 2)
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    -
                                @endif
                            @endif
                        </td>
                        <td class="align-top text-center">
                            @if(isset($stepTest))
                                @if($stepTest->status === 1)
                                    <span class="badge bg-success">Approved</span>
                                @elseif($stepTest->status === 2)
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td class="align-top text-center">
                            @if(isset($stepInterview))
                                @if($stepInterview->status === 1)
                                    @if($stepInterview->approver_2 === null)
                                        <span class="badge bg-warning">Waiting App. User</span>
                                    @else
                                        <span class="badge bg-success">Approved</span>
                                    @endif
                                @elseif($stepInterview->status === 2)
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td class="align-top text-center">
                            @if(isset($stepOffering))
                                @if($stepOffering->status === 1)
                                    <span class="badge bg-success">Approved</span>
                                @elseif($stepOffering->status === 2)
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td class="align-top text-center">
                            @if(isset($stepMCU))
                                @if($stepMCU->status === 1)
                                    <span class="badge bg-success">Approved</span>
                                @elseif($stepMCU->status === 2)
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td class="align-top text-center">
                            @if(isset($stepSign))
                                @if($stepSign->status === 1)
                                    <span class="badge bg-success">Approved</span>
                                @elseif($stepSign->status === 2)
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td rowspan="3" class="align-middle text-center">
                            @php
                                $status = $jobApply->status == 2 ? 'REJECT' : $jobApply->progress_status;
                                $step = $progressSteps[$status] ?? $progressSteps['DEFAULT'];
                            @endphp
                            <span class="badge bg-{{ $step['badge'] }} text-{{ $step['text'] }}">
                                <i class="mdi {{ $step['icon'] }}"></i> {{ $status }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="align-top">
                            @if($stepAdmin->status !== null)
                                <span class="text-muted small">{{ $stepAdmin->approver_1 ?? '-' }}</span><br>
                                <span class="text-muted small">at: {{ $stepAdmin->result_updated ?? '-' }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="align-top">
                            @if(isset($stepTest))
                                <span class="text-muted small">{{ $stepTest->approver_1 ?? '-' }}</span><br>
                                <span class="text-muted small">at: {{ $stepTest->result_updated ?? '-' }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="align-top">
                            @if(isset($stepInterview))
                                <span class="text-muted small">{{ $stepInterview->approver_1 ?? '-' }} <b>(Admin)</b></span><br>
                                <span class="text-muted small">{{ $stepInterview->approver_2 ?? '-' }} <b>(User)</b></span><br>
                                <span class="text-muted small">at: {{ $stepInterview->result_updated ?? '-' }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="align-top">
                            @if(isset($stepOffering))
                                <span class="text-muted small">{{ $stepOffering->approver_1 ?? '-' }}</span><br>
                                <span class="text-muted small">at: {{ $stepOffering->result_updated ?? '-' }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="align-top">
                            @if(isset($stepMCU))
                                <span class="text-muted small">{{ $stepMCU->approver_1 ?? '-' }}</span><br>
                                <span class="text-muted small">at: {{ $stepMCU->result_updated ?? '-' }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="align-top">
                            @if(isset($stepSign))
                                <span class="text-muted small">{{ $stepSign->approver_1 ?? '-' }}</span><br>
                                <span class="text-muted small">at: {{ $stepSign->result_updated ?? '-' }}</span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="text-muted small">
                                @if($stepAdmin->status !== null)
                                    <b>Note:</b><br> {{ $stepAdmin->result_notes }}
                                @else
                                    -
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="text-muted small">
                                @if(isset($stepTest))
                                    <b>Note:</b><br> {{ $stepTest->result_notes }}
                                @else
                                    -
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="text-muted small">
                                @if(isset($stepInterview))
                                    <b>Note:</b><br> {{ $stepInterview->result_notes }}
                                @else
                                    -
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="text-muted small">
                                @if(isset($stepOffering))
                                    <b>Note:</b><br> {{ $stepOffering->result_notes }}
                                @else
                                    -
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="text-muted small">
                                @if(isset($stepMCU))
                                    <b>Note:</b><br> {{ $stepMCU->result_notes }}
                                @else
                                    -
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="text-muted small">
                                @if(isset($stepSign))
                                    <b>Note:</b><br> {{ $stepSign->result_notes }}
                                @else
                                    -
                                @endif
                            </div>
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
                                    {{-- <div class="col-md-4 mb-2">
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
                                    </div> --}}
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
                                @if($workExpInfo->isNotEmpty())
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
                                @else
                                    None
                                @endif
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
                                            <p class="text-muted">
                                                {{ $item->answer }}
                                                {{-- @if($index + 1 == 13)
                                                    Rp {{ number_format((int) $item->answer, 0, ',', '.') }}
                                                @else
                                                    {{ $item->answer }}
                                                @endif --}}
                                            </p>
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
