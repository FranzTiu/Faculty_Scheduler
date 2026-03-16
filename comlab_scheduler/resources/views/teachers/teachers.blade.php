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
                <div class="custom-dropdown" id="teacherStatusFilterDropdown">
                    <div class="custom-dropdown-selected"
                        onclick="toggleCustomDropdown(event, 'teacherStatusFilterDropdown')">
                        <span id="selectedTeacherStatusText">All Teachers</span>
                        <div class="dropdown-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </div>
                    </div>
                    <div class="custom-dropdown-options" id="teacherStatusOptions">
                        <div class="custom-option selected" data-value="all"
                            onclick="selectCustomOption('all', 'All Teachers', 'teacherStatusFilterDropdown', 'selectedTeacherStatusText', 'teacherStatusFilter')">
                            All Teachers</div>
                        <div class="custom-option" data-value="Full-time"
                            onclick="selectCustomOption('Full-time', 'Full-time', 'teacherStatusFilterDropdown', 'selectedTeacherStatusText', 'teacherStatusFilter')">
                            Full-time</div>
                        <div class="custom-option" data-value="Part-Time"
                            onclick="selectCustomOption('Part-Time', 'Part-Time', 'teacherStatusFilterDropdown', 'selectedTeacherStatusText', 'teacherStatusFilter')">
                            Part-Time</div>
                    </div>
                    <select id="teacherStatusFilter" class="hidden">
                        <option value="all">All Teachers</option>
                        <option value="Full-time">Full-time</option>
                        <option value="Part-Time">Part-Time</option>
                    </select>
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
                        <!-- Data will be loaded dynamically -->
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