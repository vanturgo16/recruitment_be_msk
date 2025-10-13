@extends('layouts.master')
@section('konten')

<!-- daterangepicker -->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/daterangepicker/css/daterangepicker.css') }}"/>
<script src="{{ asset('assets/libs/moment/js/moment.min.js') }}"></script>
<script src="{{ asset('assets/libs/daterangepicker/js/daterangepicker.min.js') }}"></script>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center mt-3">
                            <div class="col-12">
                                <div class="text-center">
                                    <h5>{{ __('messages.welcome') }} {{ __('messages.app_name') }}</h5>
                                    <p class="text-muted">{{ __('messages.welcome_sub') }} {{ __('messages.company_name') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 text-center mt-4">
                @if($underDev == 1)
                    <div class="maintenance-cog-icon text-primary pt-4">
                        <i class="mdi mdi-cog spin-right display-3"></i>
                        <i class="mdi mdi-cog spin-left display-4 cog-icon"></i>
                    </div>
                    <h3 class="mt-4">Site is Under Development</h3>
                    <p><i class="fas fa-tools me-2"></i>Hang tight while we finish things up. 👌</p>
                @else
                    <div class="card">
                        <div class="card-header">
                            <div class="text-center">
                                <h5 class="text-bold">{{ __('messages.title_count_applicant') }}</h5>

                                <div class="d-flex justify-content-center">
                                    <div class="flex-shrink-1">
                                        <input type="text" id="dateRange" class="form-control text-center" style="width:250px"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row" id="summaryWrapper">
                                <div class="col-lg-3">
                                    <div class="card card-h-100">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-12">
                                                    <h4 class="mb-3">
                                                        <span class="counter-value" id="not_check" data-target="">0</span>
                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="text-nowrap">
                                                <span class="badge bg-secondary-subtle text-white">{{ __('messages.not_check') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="card card-h-100">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-12">
                                                    <h4 class="mb-3">
                                                        <span class="counter-value" id="in_progress" data-target="">0</span>
                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="text-nowrap">
                                                <span class="badge bg-info-subtle text-white">{{ __('messages.in_progress') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="card card-h-100">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-12">
                                                    <h4 class="mb-3">
                                                        <span class="counter-value" id="rejected" data-target="">0</span>
                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="text-nowrap">
                                                <span class="badge bg-danger-subtle text-white">{{ __('messages.rejected') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="card card-h-100">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-12">
                                                    <h4 class="mb-3">
                                                        <span class="counter-value" id="approve" data-target="">0</span>
                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="text-nowrap">
                                                <span class="badge bg-success-subtle text-white">{{ __('messages.approve') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        $(function() {
                            function loadSummary(start, end) {
                                $("#processing").removeClass("hidden");
                                $.ajax({
                                    url: "{{ route('getDataSummary') }}",
                                    type: "GET",
                                    data: {
                                        dateFrom: start.format('YYYY-MM-DD'),
                                        dateTo: end.format('YYYY-MM-DD')
                                    },
                                    success: function(res) {
                                        $("#not_check").attr("data-target", res.countNotReview).text(res.countNotReview);
                                        $("#in_progress").attr("data-target", res.countInProgress).text(res.countInProgress);
                                        $("#rejected").attr("data-target", res.countReject).text(res.countReject);
                                        $("#approve").attr("data-target", res.countHired).text(res.countHired);
                                    },
                                    complete: function() {
                                        $("#processing").addClass("hidden");
                                    }
                                });
                            }
                        
                            // default: this month
                            let start = moment().startOf('month');
                            let end = moment().endOf('month');
                            $('#dateRange').daterangepicker({
                                startDate: start,
                                endDate: end,
                                ranges: {
                                    'Today': [moment(), moment()],
                                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                                    'This Year': [moment().startOf('year'), moment().endOf('year')]
                                }
                            }, function(start, end, label) {
                                $('#dateRange').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                                loadSummary(start, end);
                            });
                            loadSummary(start, end);
                        });
                    </script>                        
                @endif
            </div>
        </div>
    </div>
</div>

@endsection