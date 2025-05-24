@extends('layouts.master')
@section('konten')

<div class="page-content">
    {{-- MAIN CARD --}}
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-4">
                    @if(Auth::user()->role == 'Super Admin')
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#addNew"><i class="mdi mdi-plus label-icon"></i> {{ __('messages.add_new') }}</button>
                        {{-- Modal Add --}}
                        <div class="modal fade" id="addNew" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-top modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">{{ __('messages.add_new') }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form class="formLoad" action="{{ route('joblist.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                                            <div class="row">
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">{{ __('messages.position') }}</label> <label class="text-danger">*</label>
                                                    <select class="form-select select2" style="width: 100%" name="id_position" required>
                                                        <option value="" selected>-- {{ __('messages.select') }} --</option>
                                                        <option disabled>──────────</option>
                                                        @foreach($positions as $item)
                                                            <option value="{{ $item->id }}" {{ old('id_position') == $item->id ? 'selected' : '' }}>
                                                                {{ strtoupper($item->dept_name) }} - {{ $item->position_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">{{ __('messages.request_by') }}</label> <label class="text-danger">*</label>
                                                    <select class="form-select select2" style="width: 100%" name="position_req_user" required>
                                                        <option value="" selected>-- {{ __('messages.select') }} --</option>
                                                        <option disabled>──────────</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">{{ __('messages.rec_date_start') }}</label> <label class="text-danger">*</label>
                                                    <input class="form-control" type="date" name="rec_date_start" id="rec_date_start" required>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">{{ __('messages.rec_date_end') }}</label>
                                                    <input class="form-control" type="date" name="rec_date_end" id="rec_date_end">
                                                    <small id="date-error" class="text-danger d-none">End date cannot be earlier than start date.</small>
                                                </div>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function () {
                                                        const startDate = document.getElementById('rec_date_start');
                                                        const endDate = document.getElementById('rec_date_end');
                                                        const errorMsg = document.getElementById('date-error');
                                                
                                                        function validateDates() {
                                                            if (startDate.value && endDate.value && endDate.value < startDate.value) {
                                                                errorMsg.classList.remove('d-none');
                                                                endDate.classList.add('is-invalid');
                                                            } else {
                                                                errorMsg.classList.add('d-none');
                                                                endDate.classList.remove('is-invalid');
                                                            }
                                                        }
                                                
                                                        startDate.addEventListener('change', validateDates);
                                                        endDate.addEventListener('change', validateDates);
                                                    });
                                                </script>
                                                
                                                <div class="col-lg-12 mb-3">
                                                    <label class="form-label">{{ __('messages.jobdesc') }}</label> <label class="text-danger">*</label>
                                                    <textarea class="summernote-editor" name="jobdesc" placeholder="Input Job Description..." required></textarea>
                                                </div>
                                                <div class="col-lg-12 mb-3">
                                                    <label class="form-label">{{ __('messages.requirement') }}</label> <label class="text-danger">*</label>
                                                    <textarea class="summernote-editor" name="requirement" placeholder="Input Requirement..." required></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">{{ __('messages.min_education') }}</label>
                                                    <select class="form-select select2" style="width: 100%" name="min_education" required>
                                                        <option value="" selected>-- {{ __('messages.select') }} --</option>
                                                        <option disabled>──────────</option>
                                                        @foreach($educations as $item)
                                                            <option value="{{ $item->name_value }}" {{ old('min_education') == $item->name_value ? 'selected' : '' }}> {{ $item->name_value }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">{{ __('messages.min_yoe') }}</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" placeholder=".." name="min_yoe">
                                                        <span class="input-group-text">{{ __('messages.years') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">{{ __('messages.min_age') }}</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" placeholder=".." name="min_age">
                                                        <span class="input-group-text">{{ __('messages.years') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">{{ __('messages.max_candidate') }}</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" placeholder=".." name="max_candidate">
                                                        <span class="input-group-text">{{ __('messages.applicants') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                                            <button type="submit" class="btn btn-success waves-effect btn-label waves-light"><i class="mdi mdi-plus label-icon"></i>{{ __('messages.add') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-4">
                    <div class="text-center">
                        <h4 class="text-bold">{{ __('messages.job_list') }}</h4>
                    </div>
                </div>
                <div class="col-4"></div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered dt-responsive w-100" id="ssTable">
                <thead class="table-light">
                    <tr>
                        <th class="align-middle text-center">No</th>
                        <th class="align-middle text-center">{{ __('messages.position') }}</th>
                        <th class="align-middle text-center">{{ __('messages.request_by') }}</th>
                        <th class="align-middle text-center">{{ __('messages.rec_date_start') }}</th>
                        <th class="align-middle text-center">{{ __('messages.rec_date_end') }}</th>
                        <th class="align-middle text-center">{{ __('messages.max_candidate') }}</th>
                        {{-- <th class="align-middle text-center">{{ __('messages.applicants') }}</th> --}}
                        <th class="align-middle text-center">Status</th>
                        <th class="align-middle text-center">{{ __('messages.action') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    $(function() {
        var dataTable = $('#ssTable').DataTable({
            processing: true,
            serverSide: true,
            scrollY: '100vh',
            ajax: '{!! route('joblist.index') !!}',
            columns: [{
                data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false,
                    searchable: false,
                    className: 'align-top text-center',
                },
                {
                    data: 'position_name',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                    render: function(data, type, row) {
                        return '<b>' +data + '</b><br>' + row.dept_name;
                    },
                },
                {
                    data: 'email',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                },
                {
                    data: 'rec_date_start',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-center',
                },
                {
                    data: 'rec_date_end',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-center',
                    render: function (data, type, row) {
                        return data ? data : '-';
                    }
                },
                {
                    data: 'max_candidate',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-center',
                },
                // {
                //     data: 'number_of_applicant',
                //     orderable: true,
                //     searchable: true,
                //     className: 'align-top text-center',
                // },
                {
                    data: 'is_active',
                    orderable: true,
                    className: 'align-top text-center',
                    render: function(data, type, row) {
                        var html
                        if(row.is_active == 1){
                            html = '<span class="badge bg-success text-white">Active</span>';
                        } else {
                            html = '<span class="badge bg-danger text-white">Inactive</span>';
                        }
                        return html;
                    },
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'align-top text-center',
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

<script>
    $(document).ready(function () {
        $('select[name="id_position"]').on('change', function () {
            var positionId = $(this).val();
            var $userSelect = $('select[name="position_req_user"]');
            
            if (positionId) {
                $.ajax({
                    url: '{{ route("joblist.getuser", ":id") }}'.replace(':id', positionId),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $userSelect.empty();
                        $userSelect.append('<option value="" selected>-- Select --</option>');
                        $userSelect.append('<option disabled>──────────</option>');

                        $.each(data, function (key, value) {
                            $userSelect.append('<option value="' + key + '">' + value + '</option>');
                        });
                    }
                });
            } else {
                $userSelect.empty();
                $userSelect.append('<option value="" selected>-- Select --</option>');
                $userSelect.append('<option disabled>──────────</option>');
            }
        });
    });
</script>


@endsection