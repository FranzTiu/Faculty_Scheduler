@extends('layouts.app')

@section('content')
    <section id="facultySection" class="content-section active block">
        <div class="mb-8 pt-8 w-full">
            <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6">
                <h1 class="text-4xl md:text-5xl font-extrabold text-left uppercase font-['Playfair_Display'] tracking-wide">
                    <span class="text-[#1e1b4b]">MANAGE</span> <span class="text-[#fbbf24]">TEACHERS</span>
                </h1>
                <button
                    class="toggle-btn outline !px-8 !py-3 !text-lg !font-extrabold rounded-full transition-all whitespace-nowrap"
                    onclick="openModal('faculty')">
                    Add Teacher
                </button>
            </div>

            <div class="filter-section mb-6">
                <div class="flex flex-col md:flex-row gap-4 items-stretch md:items-center justify-between w-full">

                    {{-- Teacher Name Filter (left) --}}
                    <div class="custom-dropdown" id="teacherNameFilterDropdown">
                        <div class="custom-dropdown-selected"
                            onclick="toggleCustomDropdown(event, 'teacherNameFilterDropdown')">
                            <span id="selectedTeacherNameText">All Teachers</span>
                            <div class="dropdown-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </div>
                        </div>
                        <div class="custom-dropdown-options custom-scrollbar" id="teacherNameOptions">
                            {{-- Populated dynamically by app.js --}}
                        </div>
                        <select id="teacherNameFilter" class="hidden"></select>
                    </div>

                    {{-- Employment Status Filter (right) --}}
                    <div class="custom-dropdown ml-auto" id="teacherStatusFilterDropdown">
                        <div class="custom-dropdown-selected"
                            onclick="toggleCustomDropdown(event, 'teacherStatusFilterDropdown')">
                            <span id="selectedTeacherStatusText">All Status</span>
                            <div class="dropdown-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </div>
                        </div>
                        <div class="custom-dropdown-options" id="teacherStatusOptions">
                            <div class="custom-option selected" data-value="all"
                                onclick="selectCustomOption(event, 'all', 'All Status', 'teacherStatusFilterDropdown', 'selectedTeacherStatusText', 'teacherStatusFilter')">
                                All Status</div>
                            <div class="custom-option" data-value="Full-time"
                                onclick="selectCustomOption(event, 'Full-time', 'Full-time', 'teacherStatusFilterDropdown', 'selectedTeacherStatusText', 'teacherStatusFilter')">
                                Full-time</div>
                            <div class="custom-option" data-value="Part-Time"
                                onclick="selectCustomOption(event, 'Part-Time', 'Part-Time', 'teacherStatusFilterDropdown', 'selectedTeacherStatusText', 'teacherStatusFilter')">
                                Part-Time</div>
                        </div>
                        <select id="teacherStatusFilter" class="hidden">
                            <option value="all">All Status</option>
                            <option value="Full-time">Full-time</option>
                            <option value="Part-Time">Part-Time</option>
                        </select>
                    </div>

                </div>
            </div>

            <div class="teacher-table-container overflow-x-auto w-full bg-white rounded-xl shadow-md border-none">
                <table id="facultyTable" class="teacher-manage-table w-full">
                    <thead>
                        <tr>
                            <th>Teacher(s) Name</th>
                            <th>Employment Status</th>
                            <th>Subjects</th>
                            <th>Section(s)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="facultyTableBody">
                        <tr>
                            <td colspan="5" class="empty-state-cell">Loading teachers...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            loadTeacherManagementTable();
        });
    </script>
@endpush