@extends('layouts.master')
@section('konten')
<div class="page-content">
    <div class="card">
        <div class="card-header">
            <h4 class="text-bold">Test Schedules</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered" id="testTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Applicant (Name - Email - Position Applied)</th>
                            <th>Info</th>
                            <th>Created By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $schedule)
                        @php
                            $isAdminHR = Auth::user()->role === 'Admin HR';
                            $isEmployeeHead = Auth::user()->role === 'Employee' && in_array(Auth::user()->hie_level, [2,3]);
                            $applicant = $schedule->jobapply ? $schedule->jobapply->candidate : null;
                            $position = $schedule->jobapply && $schedule->jobapply->joblist && $schedule->jobapply->joblist->position ? $schedule->jobapply->joblist->position->position_name : '-';
                            $department = $schedule->jobapply && $schedule->jobapply->joblist && $schedule->jobapply->joblist->position ? $schedule->jobapply->joblist->position->department->dept_name : '-';
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                {{ $applicant->candidate_first_name ?? '-' }} {{ $applicant->candidate_last_name ?? '' }}<br>
                                <small class="text-muted">{{ $applicant->email ?? '-' }}</small>
                                <br>
                                {{ $position . " - " . $department}}
                            </td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#viewDetailModal{{ $schedule->id }}">
                                    Test Detail
                                </button>
                                <!-- Modal View Detail test-->
                                <div class="modal fade" id="viewDetailModal{{ $schedule->id }}" tabindex="-1" aria-labelledby="viewDetailModalLabel{{ $schedule->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="viewDetailModalLabel{{ $schedule->id }}">test Detail</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-2">
                                                    <strong>Test Date:</strong><br>
                                                    {{ $schedule->test_date }}
                                                </div>
                                                <div class="mb-2">
                                                    <strong>Address:</strong><br>
                                                    {{ $schedule->test_address }}
                                                </div>
                                                <div class="mb-2">
                                                    <strong>Notes:</strong><br>
                                                    {{ $schedule->test_notes }}
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal View Detail -->
                                <br>
                                <!-- jika status test bukan 0 -->
                                @if ($schedule->test_status != '0')
                                    <button type="button" class="btn btn-info btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#resulttestModal{{ $schedule->id }}">
                                    Test Result
                                    </button>
                                    <!-- Modal View Result test-->
                                    <div class="modal fade" id="resulttestModal{{ $schedule->id }}" tabindex="-1" aria-labelledby="resulttestModalLabel{{ $schedule->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="resulttestModalLabel{{ $schedule->id }}">test Detail</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-2">
                                                        <strong>Update at:</strong><br>
                                                        {{ $schedule->updated_at }}
                                                    </div>
                                                    <div class="mb-2">
                                                        <strong>Approval Test By:</strong><br>
                                                        {{ $schedule->approval1->name }}
                                                    </div>
                                                    <div class="mb-2">
                                                        <strong>Result Notes:</strong><br>
                                                        {{ $schedule->result_notes }}
                                                    </div>
                                                    <div class="mb-2">
                                                        <strong>Result Test:</strong><br>
                                                        @if ($schedule->test_status == '1')
                                                            <span class="badge bg-success">PASSED</span>
                                                        @elseif ($schedule->test_status == '2')
                                                            <span class="badge bg-danger">REJECTED</span>
                                                        @endif
                                                    </div>
                                                    <div class="mb-2">
                                                        <a href="{{ url($schedule->result_attachment) }}" class="btn btn-info btn-sm"><i class="fas fa-download"></i> Download PDF Result</a>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal View Detail -->
                                @endif
                            </td>
                            <td>{{ $schedule->creator->name ?? '-' }}</td>
                            <td>
                                <div class="dropdown">
                                    @if ($isAdminHR && $schedule->test_status == '0')
                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton{{ $schedule->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        Action
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $schedule->id }}">
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editModal{{ $schedule->id }}">Edit</a>
                                        </li>
                                        <li>
                                            <form action="{{ route('test_schedule.delete', $schedule->id) }}" method="POST" onsubmit="return confirm('Delete this schedule?')">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-danger">Delete</button>
                                            </form>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#resultModal{{ $schedule->id }}">Test Result</a>
                                        </li>
                                    </ul>
                                    @endif
                                    @if ($schedule->test_status == '1')
                                        @if ($isEmployeeHead && $schedule->ready_offering != '1')
                                            <a class="btn btn-success btn-sm" href="#" data-bs-toggle="modal" data-bs-target="#submitOfferingModal{{ $schedule->id }}"><i class="fas fa-check"></i> Submit to Offering</a>
                                        @else
                                            <span class="badge bg-success">PASSED</span>
                                        @endif
                                    @elseif ($schedule->test_status == '2')
                                        <span class="badge bg-danger">REJECTED</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        <!-- Modal Edit -->
                        <div class="modal fade" id="editModal{{ $schedule->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $schedule->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('test_schedule.update', $schedule->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel{{ $schedule->id }}">Edit Testing Schedule</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id_jobapply" value="{{ $schedule->id_jobapply }}">
                                            <div class="mb-3">
                                                <label class="form-label">Applicant Name</label>
                                                <input type="text" class="form-control" value="{{ $applicant->candidate_first_name ?? '-' }} {{ $applicant->candidate_last_name ?? '' }}" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Position</label>
                                                <input type="text" class="form-control" value="{{ $position }}" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="test_date{{ $schedule->id }}" class="form-label">Testing Date</label>
                                                <input type="datetime-local" name="test_date" id="test_date{{ $schedule->id }}" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime($schedule->test_date)) }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="test_address{{ $schedule->id }}" class="form-label">Testing Address</label>
                                                <input type="text" name="test_address" id="test_address{{ $schedule->id }}" class="form-control" value="{{ $schedule->test_address }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="test_notes{{ $schedule->id }}" class="form-label">Testing Notes</label>
                                                <textarea name="test_notes" id="test_notes{{ $schedule->id }}" class="form-control">{{ $schedule->test_notes }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal Edit -->

                        <!-- Modal Result -->
                        <div class="modal fade" id="resultModal{{ $schedule->id }}" tabindex="-1" aria-labelledby="resultModalLabel{{ $schedule->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('test_schedule.update.result', $schedule->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="resultModalLabel{{ $schedule->id }}">Input Test Result</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="approval_action" id="approveRadio" value="1" checked>
                                                    <label class="form-check-label" for="approveRadio">Approve</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="approval_action" id="rejectRadio" value="2">
                                                    <label class="form-check-label" for="rejectRadio">Reject</label>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Result Attachment <span class="text-danger">*Max 500 Kb</span></label>
                                                <input type="file" class="form-control" value="" name="result_attachment" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label fclass="form-label">Result Notes</label>
                                                <textarea name="result_notes" class="form-control"></textarea>
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
                        <!-- End Modal Result -->

                        <!-- Modal submit to offering -->
                        <div class="modal fade" id="submitOfferingModal{{ $schedule->id }}" tabindex="-1" aria-labelledby="submitOfferingModal{{ $schedule->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('test_schedule.submitOffer', encrypt($schedule->id)) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="submitOfferingModalLabel{{ $schedule->id }}">Submit to Offering</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="approval_action" id="approveRadio" value="1" checked>
                                                    <label class="form-check-label" for="approveRadio">Approve</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="approval_action" id="rejectRadio" value="2">
                                                    <label class="form-check-label" for="rejectRadio">Reject</label>
                                                </div>
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
                        <!-- End Modal submit to offering -->
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#testTable').DataTable();
    });
</script>
@endsection
