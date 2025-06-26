@extends('layouts.master')
@section('konten')
<div class="page-content">
    <div class="card">
        <div class="card-header">
            <h4 class="text-bold">Job Applied Detail: {{ $datas[0]['position_name'] . "-" . $datas[0]['dept_name'] }}</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered dt-responsive w-100" id="jobAppliedDetailTable">
                    <thead class="table-light">
                        <tr>
                            <th class="align-middle text-center">ID</th>
                            <th class="align-middle text-center">Applicant Name</th>
                            <th class="align-middle text-center">Applicant Email</th>
                            <th class="align-middle text-center">Applied At</th>
                            <th class="align-middle text-center">Phase</th>
                            <th class="align-middle text-center">Status</th>
                            <th class="align-middle text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($datas as $data)
                            <tr>
                                <td>{{ $data->id }}</td>
                                <td>
                                    {{ $data->candidate_first_name . " " . $data->candidate_last_name }}
                                    @if ($data->is_seen == '0')
                                        <span class="align-middle ms-2" title="Not seen yet">
                                            <span style="display:inline-block;width:10px;height:10px;background:#dc3545;border-radius:50%;margin-right:3px;"></span>
                                        </span>
                                    @else
                                        <span class="align-middle ms-2" title="Already seen">
                                            <span style="display:inline-block;width:10px;height:10px;background:#28a745;border-radius:50%;margin-right:3px;"></span>
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $data->email }}</td>
                                <td>{{ $data->created_at }}</td>
                                <td>
                                    @if($data->progress_status)
                                        @if( $data->status == 2)
                                            <span class="badge bg-danger">{{ $data->progress_status }}</span>
                                        @else
                                            <span class="badge bg-primary">{{ $data->progress_status }}</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($data->progress_status)
                                        @if( $data->status == 2)
                                            <span class="badge bg-danger">REJECTED</span>
                                        @elseif( $data->status == 1)
                                            <span class="badge bg-success">DONE</span>
                                        @else
                                            <span class="badge bg-primary">PROGRESS</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('jobapplied.seen', encrypt($data->id)) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-info btn-sm ms-2">
                                            Detail Info
                                        </button>
                                    </form>
                                    @if(Auth::user()->role === 'Admin HR' && strtoupper($data->progress_status) == 'INTERVIEW')
                                        @php
                                            if (!function_exists('has_interview_schedule')) {
                                                require_once app_path('Helpers/interview_schedule_helper.php');
                                            }
                                        @endphp
                                        <!-- cek candidate interview schedule-->
                                        @if(has_interview_schedule($data->id))
                                            <a href="{{ route('interview_schedule.index', ['id_jobapply' => encrypt($data->id)]) }}" class="btn btn-outline-success btn-sm ms-2">
                                                View Schedule Interview
                                            </a>
                                        @else
                                            <a href="{{ route('interview_schedule.create', [
                                                'id_jobapply' => $data->id,
                                                'applicant_name' => $data->candidate_first_name . ' ' . $data->candidate_last_name,
                                                'position_name' => $data->position_name
                                            ]) }}" class="btn btn-outline-primary btn-sm ms-2">
                                                Set Schedule Interview
                                            </a>
                                        @endif
                                    @endif

                                    @if(Auth::user()->role === 'Admin HR' && strtoupper($data->progress_status) == 'TESTED')
                                        @php
                                            if (!function_exists('has_test_schedule')) {
                                                require_once app_path('Helpers/test_schedule_helper.php');
                                            }
                                        @endphp
                                        <!-- cek candidate test schedule-->
                                        @if(has_test_schedule($data->id))
                                            <a href="{{ route('test_schedule.index', ['id_jobapply' => encrypt($data->id)]) }}" class="btn btn-outline-success btn-sm ms-2">
                                                View Schedule Test
                                            </a>
                                        @else
                                            <a href="{{ route('test_schedule.create', [
                                                'id_jobapply' => $data->id,
                                                'applicant_name' => $data->candidate_first_name . ' ' . $data->candidate_last_name,
                                                'position_name' => $data->position_name
                                            ]) }}" class="btn btn-outline-primary btn-sm ms-2">
                                                Set Schedule Test
                                            </a>
                                        @endif
                                    @endif

                                    @if(Auth::user()->role === 'Admin HR' && strtoupper($data->progress_status) == 'OFFERING')
                                        @php
                                            if (!function_exists('has_offering_schedule')) {
                                                require_once app_path('Helpers/offering_schedule_helper.php');
                                            }
                                        @endphp
                                        <!-- cek candidate offering schedule-->
                                        @if(has_offering_schedule($data->id))
                                            <a href="{{ route('offering_schedule.index', ['id_jobapply' => encrypt($data->id)]) }}" class="btn btn-outline-success btn-sm ms-2">
                                                View Schedule Offering
                                            </a>
                                        @else
                                            <a href="{{ route('offering_schedule.create', [
                                                'id_jobapply' => $data->id,
                                                'applicant_name' => $data->candidate_first_name . ' ' . $data->candidate_last_name,
                                                'position_name' => $data->position_name
                                            ]) }}" class="btn btn-outline-primary btn-sm ms-2">
                                                Set Schedule Offering
                                            </a>
                                        @endif
                                    @endif

                                    @if(Auth::user()->role === 'Admin HR' && strtoupper($data->progress_status) == 'MCU')
                                        @php
                                            if (!function_exists('has_mcu_schedule')) {
                                                require_once app_path('Helpers/mcu_schedule_helper.php');
                                            }
                                        @endphp
                                        <!-- cek candidate mcu schedule-->
                                        @if(has_mcu_schedule($data->id))
                                            <a href="{{ route('mcu_schedule.index', ['id_jobapply' => encrypt($data->id)]) }}" class="btn btn-outline-success btn-sm ms-2">
                                                View Schedule MCU
                                            </a>
                                        @else
                                            <a href="{{ route('mcu_schedule.create', [
                                                'id_jobapply' => $data->id,
                                                'applicant_name' => $data->candidate_first_name . ' ' . $data->candidate_last_name,
                                                'position_name' => $data->position_name
                                            ]) }}" class="btn btn-outline-primary btn-sm ms-2">
                                                Set Schedule MCU
                                            </a>
                                        @endif
                                    @endif

                                    @if(Auth::user()->role === 'Admin HR' && strtoupper($data->progress_status) == 'SIGN')
                                        @php
                                            if (!function_exists('has_signing_schedule')) {
                                                require_once app_path('Helpers/signing_schedule_helper.php');
                                            }
                                        @endphp
                                        <!-- cek candidate signing schedule-->
                                        @if(has_signing_schedule($data->id))
                                            <a href="{{ route('signing_schedule.index', ['id_jobapply' => encrypt($data->id)]) }}" class="btn btn-outline-success btn-sm ms-2">
                                                View Schedule Signing
                                            </a>
                                        @else
                                            <a href="{{ route('signing_schedule.create', [
                                                'id_jobapply' => $data->id,
                                                'applicant_name' => $data->candidate_first_name . ' ' . $data->candidate_last_name,
                                                'position_name' => $data->position_name
                                            ]) }}" class="btn btn-outline-primary btn-sm ms-2">
                                                Set Schedule Signing
                                            </a>
                                        @endif
                                    @endif

                                    @if(Auth::user()->role === 'Admin HR' && strtoupper($data->progress_status) == 'HIRED')
                                        <button type="button" class="btn btn-info btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#submitEmployeeModal{{ $data->id }}">
                                            Submit As Employee
                                        </button>

                                        <!-- Modal Submit As Employee-->
                                        <div class="modal fade" id="submitEmployeeModal{{ $data->id }}" tabindex="-1" aria-labelledby="submitEmployeeModalLabel{{ $data->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="submitEmployeeModalLabel{{ $data->id }}">Submit As Employee</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Modal View Detail -->
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <a href="{{ route('jobapplied.index') }}" class="btn btn-secondary mt-3">Back to Job Applied</a>
        </div>
    </div>
</div>
<script>
    $(function() {
        var dataTable = $('#jobAppliedDetailTable').DataTable({
            processing: true,
            responsive: true,
        });
    });
</script>
@endsection