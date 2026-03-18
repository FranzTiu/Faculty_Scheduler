@extends('layouts.app')

@section('content')
    <section id="roomsSection" class="content-section active block">
        <div class="mb-8 pt-8 w-full">
            {{-- Header matching other pages --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6">
                <h1 class="text-4xl md:text-5xl font-extrabold text-left uppercase font-['Playfair_Display'] tracking-wide">
                    <span class="text-[#1e1b4b]">COMLABS</span> <span class="text-[#fbbf24]">&amp;</span> <span
                        class="text-[#1e1b4b]">SUBJECTS</span>
                </h1>
                <button
                    class="toggle-btn outline !px-8 !py-3 !text-lg !font-extrabold rounded-full transition-all whitespace-nowrap"
                    id="combinedAddBtn" onclick="openEditRoomModal()">
                    Add ComLab
                </button>
            </div>

            <div class="flex flex-col sm:flex-row justify-center items-center gap-4 md:gap-8 mb-10">
                <button id="toggleComLabs"
                    class="view-toggle-btn active w-full sm:w-48 !py-3 !text-lg !font-extrabold rounded-full transition-all"
                    style="font-family: 'Playfair Display', serif;" onclick="switchCombinedView('comlabs')">ComLabs</button>
                <button id="toggleSubjects"
                    class="view-toggle-btn w-full sm:w-48 !py-3 !text-lg !font-extrabold rounded-full transition-all"
                    style="font-family: 'Playfair Display', serif;"
                    onclick="switchCombinedView('subjects')">Subjects</button>
            </div>

            <div class="filter-section mb-6">
                <div class="flex flex-col md:flex-row gap-4 items-stretch md:items-center justify-start">
                    <div class="custom-dropdown" id="combinedFilterDropdown">
                    <div class="custom-dropdown-selected" onclick="toggleCustomDropdown(event, 'combinedFilterDropdown')">
                        <span id="selectedCombinedText">All Campuses</span>
                        <div class="dropdown-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </div>
                    </div>
                    <div class="custom-dropdown-options custom-scrollbar" id="combinedFilterOptions">
                        <!-- Populated via app.js -->
                    </div>
                    <select id="combinedManagementFilter" class="hidden" onchange="loadScheduleCombinedData()">
                        <option value="all">All Schedule</option>
                    </select>
                    </div>

                    {{-- Year Level Filter (Subjects view) --}}
                    <div class="custom-dropdown hidden" id="yearLevelDropdown">
                        <div class="custom-dropdown-selected" onclick="toggleCustomDropdown(event, 'yearLevelDropdown')">
                            <span id="selectedYearLevelText">All Year Levels</span>
                            <div class="dropdown-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </div>
                        </div>
                        <div class="custom-dropdown-options custom-scrollbar" id="yearLevelOptions">
                            {{-- populated via app.js --}}
                        </div>
                        <select id="yearLevelFilter" class="hidden" onchange="loadScheduleCombinedData()">
                            <option value="all">All Year Levels</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="teacher-table-container overflow-x-auto w-full bg-white rounded-xl shadow-md border-none">
                <table id="scheduleCombinedTable" class="teacher-manage-table w-full">
                    <thead>
                        <tr id="scheduleHeaderRow">
                            <th>ComLab(s) Name</th>
                            <th>Campus</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="scheduleCombinedBody">
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            loadScheduleCombinedData();
            populateCombinedFilter(true); // Pre-warm campus cache so dropdown is instant
        });
    </script>
@endpush