@extends('layouts.master')
@section('konten')
<div class="page-content">
    {{-- MAIN CARD --}}
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-4">
                    @if(in_array(Auth::user()->role, ['Super Admin', 'Admin']))
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-account-plus label-icon"></i> Add New User</button>
                        
                        {{-- Modal Add --}}
                        <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-top" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Add New User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form class="formLoad" action="{{ route('user.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-lg-12 mb-3">
                                                    <label class="form-label">Email</label><label style="color: darkred">*</label>
                                                    <div id="emailWarning"></div>
                                                    <input class="form-control" id="checkEmail" name="email" type="email" value="" placeholder="Input Email.." required>
                                                </div>
                                                <div class="col-lg-12 mb-3">
                                                    <label class="form-label">Role</label> <label class="text-danger">*</label>
                                                    <select class="form-control select2" name="role" required>
                                                        <option value="" disabled selected>- Select Role -</option>
                                                        @foreach($roleUsers as $item)
                                                            <option value="{{ $item->name_value }}">{{ $item->name_value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success waves-effect btn-label waves-light"><i class="mdi mdi-account-plus label-icon"></i>Add</button>
                                        </div>
                                    </form>
                                    <script>
                                        $(document).ready(function(){
                                            $('#checkEmail').on('input', function(){
                                                var email = $(this).val();
                                                checkEmailAvailability(email);
                                            });
                                    
                                            function checkEmailAvailability(email) {
                                                $.ajax({
                                                    url: 'user/check_email_employee',
                                                    type: 'POST',
                                                    data: {
                                                        email: email,
                                                        _token: '{{ csrf_token() }}'
                                                    },
                                                    success: function(response) {
                                                        $('#emailWarning').remove();
                                                        if (response.status === 'notregistered') {
                                                            $('#checkEmail').before('<div id="emailWarning"><div class="alert alert-warning alert-dismissible alert-label-icon label-arrow fade show" role="alert"><i class="mdi mdi-alert-outline label-icon"></i><strong>Warning</strong> - Email Not Registered As Employee<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div>');
                                                            $('#submitButton').prop('disabled', true);
                                                        } else {
                                                            $('#submitButton').prop('disabled', false);
                                                        }
                                                    }
                                                });
                                            }
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-4">
                    <div class="text-center">
                        <h4 class="text-bold">Manage User</h4>
                    </div>
                </div>
                <div class="col-4"></div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered dt-responsive nowrap w-100" id="ssTable">
                <thead class="table-light">
                    <tr>
                        <th class="align-middle text-center">No</th>
                        <th class="align-middle text-center">Name</th>
                        <th class="align-middle text-center">Role</th>
                        <th class="align-middle text-center">Status</th>
                        <th class="align-middle text-center">Action</th>
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
            ajax: '{!! route('user.index') !!}',
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
                    data: 'name',
                    orderable: true,
                    render: function(data, type, row) {
                        return '<b>' + row.name + '</b><br>' + row.email;
                    },
                },
                {
                    orderable: true,
                    data: 'role',
                    name: 'role',
                    className: 'align-top text-center',
                },
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

@endsection