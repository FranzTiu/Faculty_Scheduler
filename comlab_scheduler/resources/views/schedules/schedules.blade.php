@extends('layouts.app')

@section('content')
    <section id="schedulesSection" class="content-section active block">
        <div class="mb-8 pt-4 w-full">
            {{-- Header matching Home page styling --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6">
                <h1 class="text-4xl md:text-5xl font-extrabold text-left uppercase font-['Playfair_Display'] tracking-wide">
                    <span class="text-[#1e1b4b]">COMLAB</span> <span class="text-[#fbbf24]">SCHEDULER</span>
                </h1>
                <button
                    class="toggle-btn outline !px-8 !py-3 !text-lg !font-extrabold rounded-full transition-all whitespace-nowrap"
                    onclick="openModal('schedules')">
                    Add Schedule
                </button>
            </div>

            {{-- Filter matching Home page --}}
            <div class="filter-section mb-6">
                <div class="custom-dropdown" id="roomFilterDropdown">
                    <div class="custom-dropdown-selected" onclick="toggleCustomDropdown(event, 'roomFilterDropdown')">
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
                    <!-- Hidden select for technical logic -->
                    <select id="roomFilter" class="hidden" onchange="handleFilterChange()">
                        <option value="all">All Schedule</option>
                    </select>
                </div>
            </div>

            {{-- Grid matching Home page (Summary view) --}}
            <div id="comlabGrid" class="comlab-grid w-full">
                <div class="col-span-full p-8 text-slate-400 text-center">Loading schedules...</div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof populateRoomDropdowns === 'function') {
                populateRoomDropdowns();
            }
            loadLabGrid();
        });
    </script>
@endpush