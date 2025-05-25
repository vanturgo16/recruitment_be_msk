<div data-simplebar class="h-100">
    <div id="sidebar-menu">
        <ul class="metismenu list-unstyled" id="side-menu">
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard*') ? 'bg-light active' : '' }}">
                    <i class="mdi mdi-home"></i><span>Dashboard</span>
                </a>
            </li>

            @if(in_array(auth()->user()->role, ['Super Admin', 'Admin','Admin HR']))
                <li class="menu-title mt-2" data-key="t-menu">Configuration</li>
                <li>
                    <a href="{{ route('user.index') }}" class="{{ request()->is('user*') && !request()->is('user/candidates') ? 'bg-light active' : '' }}">
                        <i class="mdi mdi-account-supervisor"></i><span>{{ __('messages.mng_user') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.candidates') }}" class="{{ request()->is('user/candidates') ? 'bg-light active' : '' }}">
                        <i class="mdi mdi-account-group"></i><span>Candidate Users</span>
                    </a>
                </li>
                @if(auth()->user()->role == 'Super Admin')
                <li>
                    <a href="{{ route('rule.index') }}" class="{{ request()->is('rule*') ? 'bg-light active' : '' }}">
                        <i class="mdi mdi-cog-box"></i><span>{{ __('messages.mng_rule') }}</span>
                    </a>
                </li>
                @endif
                <li>
                    <a href="{{ route('dropdown.index') }}" class="{{ request()->is('dropdown*') ? 'bg-light active' : '' }}">
                        <i class="mdi mdi-package-down"></i><span>{{ __('messages.mng_dropdown') }}</span>
                    </a>
                </li>

                <li class="menu-title mt-2" data-key="t-menu">Master Data</li>
                @php
                    $isBusinessActive = request()->is('office*') || request()->is('division*') || request()->is('department*') || request()->is('position*') || request()->is('employee*') || request()->is('blacklist*');
                @endphp
                <li class="{{ $isBusinessActive ? 'mm-active' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="mdi mdi-domain"></i><span>Business Entities</span>
                    </a>
                    <ul class="sub-menu {{ $isBusinessActive ? 'mm-show' : '' }}" aria-expanded="{{ $isBusinessActive ? 'true' : 'false' }}">
                        <li>
                            <a href="{{ route('office.index') }}" class="{{ request()->is('office*') ? 'bg-light active' : '' }}">
                                <i class="mdi mdi-office-building"></i><span>{{ __('messages.mst_office') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('division.index') }}" class="{{ request()->is('division*') ? 'bg-light active' : '' }}">
                                <i class="mdi mdi-source-branch"></i><span>{{ __('messages.mst_div') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('department.index') }}" class="{{ request()->is('department*') ? 'bg-light active' : '' }}">
                                <i class="mdi mdi-view-module"></i><span>{{ __('messages.mst_dept') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('position.index') }}" class="{{ request()->is('position*') ? 'bg-light active' : '' }}">
                                <i class="mdi mdi-briefcase-outline"></i><span>{{ __('messages.mst_position') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('employee.index') }}" class="{{ request()->is('employee*') ? 'bg-light active' : '' }}">
                                <i class="mdi mdi-badge-account-horizontal"></i><span>{{ __('messages.emp_list') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('blacklist.index') }}" class="{{ request()->is('blacklist*') ? 'bg-light active' : '' }}">
                                <i class="mdi mdi-account-cancel"></i><span>{{ __('messages.blacklist') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
            
            @if(in_array(auth()->user()->role, ['Super Admin', 'Admin', 'Admin HR','Employee']))
                <li class="menu-title mt-2" data-key="t-menu">Recruitment</li>
                <li>
                    <a href="{{ route('joblist.index') }}" class="{{ request()->is('joblist*') ? 'bg-light active' : '' }}">
                        <i class="mdi mdi-clipboard-list"></i><span>{{ __('messages.job_list') }}</span>
                    </a>
                </li>
                @if (in_array(auth()->user()->hie_level, ['2','3']))
                    <li>
                        <a href="{{ route('jobapplied.index') }}" class="{{ request()->is('job-applied*') ? 'bg-light active' : '' }}">
                            <i class="mdi mdi-clipboard-check"></i><span>Job Applied</span>
                        </a>
                    </li>
                @endif
                <li>
                    <a href="{{ route('interview_schedule.index') }}" class="{{ request()->is('interview-schedule*') ? 'bg-light active' : '' }}">
                        <i class="mdi mdi-clipboard-list"></i><span>Schedule Interview</span>
                    </a>
                </li>
                {{-- <li>
                    <a href="#" class="{{ request()->is('applicants_list*') ? 'bg-light active' : '' }}">
                        <i class="mdi mdi-account-group"></i><span>{{ __('messages.applicants_list') }}</span>
                    </a>
                </li> --}}
            @endif

            @if(in_array(auth()->user()->role, ['Super Admin', 'Admin']))
                <li class="menu-title mt-2" data-key="t-menu">{{ __('messages.other') }}</li>
                <li>
                    <a href="{{ route('auditlog.index') }}" class="{{ request()->is('auditlog*') ? 'bg-light active' : '' }}">
                        <i class="mdi mdi-chart-donut"></i><span>{{ __('messages.audit_log') }}</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>