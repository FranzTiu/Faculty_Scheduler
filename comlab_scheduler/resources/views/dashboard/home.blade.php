@extends('layouts.app')

@section('hero')
    <section class="hero-banner relative w-full">
        {{-- Hero Semester Selection (Pill) --}}
        <div class="absolute top-28 right-4 md:right-14 z-20">
            <div class="custom-dropdown" id="heroSemesterDropdown">
                <div class="custom-dropdown-selected shadow-lg"
                    onclick="toggleCustomDropdown(event, 'heroSemesterDropdown')">
                    <span id="selectedHeroSemesterText">Loading...</span>
                    <div class="dropdown-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </div>
                </div>
                <div class="custom-dropdown-options custom-scrollbar" id="heroSemesterOptions"></div>
                <select id="heroSemesterSelect" class="hidden" onchange="handleSemesterChange('heroSemesterSelect')"></select>
            </div>
        </div>

        <div class="stat-cards-container flex flex-wrap justify-center">
            <div class="stat-card" onclick="animateCard(this)">
                <div class="stat-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                    </svg>
                </div>
                <div class="stat-info">
                    <span id="facultyCount" class="stat-number">0</span>
                    <span class="stat-label">Teachers</span>
                </div>
            </div>
            <div class="stat-card" onclick="animateCard(this)">
                <div class="stat-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z" />
                    </svg>
                </div>
                <div class="stat-info">
                    <span id="scheduleCount" class="stat-number">0</span>
                    <span class="stat-label">Assigned Schedules</span>
                </div>
            </div>
            <div class="stat-card" onclick="animateCard(this)">
                <div class="stat-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M11 11.5c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm10 4.5l-4-3v9l4-3v-3zm-6-2.5V8c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2v-4.5zM11 13H5V8h6v5z" />
                    </svg>
                </div>
                <div class="stat-info">
                    <span id="roomCount" class="stat-number">0</span>
                    <span class="stat-label">Computer Labs</span>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <section id="homeSection" class="content-section active block">
        <div class="mb-8 pt-4">
            <h1
                class="text-4xl md:text-5xl font-extrabold mb-10 text-left uppercase font-['Playfair_Display'] tracking-wide">
                <span class="text-[#1e1b4b]">COMLAB</span> <span class="text-[#fbbf24]">SCHEDULER</span>
            </h1>

            <div class="flex flex-col sm:flex-row gap-4 md:gap-8 mb-10 justify-center items-center">
                <button id="toggleSchedules"
                    class="toggle-btn active w-full sm:w-48 !py-3 !text-lg !font-extrabold rounded-full transition-all"
                    onclick="toggleHomeView('schedules')">Schedules</button>
                <button id="toggleTeachers"
                    class="toggle-btn outline w-full sm:w-48 !py-3 !text-lg !font-extrabold rounded-full transition-all"
                    onclick="toggleHomeView('teachers')">Teachers</button>

            </div>

            <div class="filter-section mb-6">
                <!-- Custom Dropdown for Room Filter -->
                <div class="custom-dropdown" id="roomFilterDropdown">
                    <div class="custom-dropdown-selected" onclick="toggleCustomDropdown(event)">
                        <span id="selectedRoomText">All Schedule</span>
                        <div class="dropdown-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </div>
                    </div>
                    <div class="custom-dropdown-options" id="roomDropdownOptions">
                        <!-- Options will be populated via app.js -->
                    </div>
                    <!-- Hidden select to keep existing filter logic working -->
                    <select id="roomFilter" class="hidden" onchange="handleFilterChange()">
                        <option value="all">All Schedule</option>
                    </select>
                </div>
            </div>

            <div id="comlabGrid" class="comlab-grid w-full">
                <!-- Lab cards will be loaded dynamically -->
                <div class="col-span-full p-8 text-slate-400 text-center">Loading schedules...</div>
            </div>
            <div id="teacherFilterContainer" class="filter-section mb-6 hidden">
                <div class="custom-dropdown" id="teacherFilterDropdown">
                    <div class="custom-dropdown-selected" onclick="toggleCustomDropdown(event, 'teacherFilterDropdown')">
                        <span id="selectedTeacherText">All Teacher</span>
                        <div class="dropdown-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </div>
                    </div>
                    <div class="custom-dropdown-options" id="teacherDropdownOptions">
                        <!-- Options will be populated via app.js -->
                    </div>
                    <select id="teacherSelectFilter" class="hidden" onchange="handleFilterChange()">
                        <option value="all">All Teacher</option>
                    </select>
                </div>
            </div>
            <div id="teacherGrid" class="teacher-grid-wrapper w-full hidden">
                <!-- Teacher table will be loaded dynamically -->
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Re-init semesters to ensure hero dropdown is caught
            if (typeof initSemesters === 'function') {
                initSemesters();
            }
            loadCounts();
            toggleHomeView('schedules'); 
        });
    </script>
@endpush