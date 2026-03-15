@extends('layouts.app')

@section('content')
<section id="facultySection" class="content-section active block">
    <div class="mb-8 pt-8 w-full">
        <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6">
            <h1 class="text-4xl md:text-5xl font-extrabold text-left uppercase font-['Playfair_Display'] tracking-wide">
                <span class="text-[#1e1b4b]">MANAGE</span> <span class="text-[#fbbf24]">TEACHERS</span>
            </h1>
            <x-button variant="outline" class="!px-8 !py-3 !text-lg !font-extrabold rounded-full transition-all whitespace-nowrap" onclick="openModal('faculty')">
                Add Teacher
            </x-button>
        </div>

        <div class="teacher-filter-bar mb-6 text-center md:text-left">
            <select id="teacherStatusFilter" class="filter-dropdown-navy">
                <option value="all">All Teachers</option>
                <option value="Full-time">Full-time</option>
                <option value="Part-Time">Part-Time</option>
            </select>
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
