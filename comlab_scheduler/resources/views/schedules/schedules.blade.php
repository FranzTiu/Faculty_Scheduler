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
                    id="addScheduleBtn"
                    class="toggle-btn outline !px-8 !py-3 !text-lg !font-extrabold rounded-full transition-all whitespace-nowrap"
                    onclick="openModal('schedules')">
                    Add Schedule
                </button>
            </div>

            {{-- Filter matching Home page --}}
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6 select-none">
                {{-- Day Filter (Left) --}}
                <div class="custom-dropdown" id="dayFilterDropdown">
                    <div class="custom-dropdown-selected" onclick="toggleCustomDropdown(event, 'dayFilterDropdown')">
                        <span id="selectedDayText">All Schedule</span>
                        <div class="dropdown-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </div>
                    </div>
                    <div class="custom-dropdown-options" id="dayDropdownOptions">
                        <div class="custom-option selected" data-value="all" onclick="selectCustomOption(event, 'all', 'All Schedule', 'dayFilterDropdown', 'selectedDayText', 'dayFilter')">All Schedule</div>
                        <div class="custom-option" data-value="Monday" onclick="selectCustomOption(event, 'Monday', 'Monday', 'dayFilterDropdown', 'selectedDayText', 'dayFilter')">Monday</div>
                        <div class="custom-option" data-value="Tuesday" onclick="selectCustomOption(event, 'Tuesday', 'Tuesday', 'dayFilterDropdown', 'selectedDayText', 'dayFilter')">Tuesday</div>
                        <div class="custom-option" data-value="Wednesday" onclick="selectCustomOption(event, 'Wednesday', 'Wednesday', 'dayFilterDropdown', 'selectedDayText', 'dayFilter')">Wednesday</div>
                        <div class="custom-option" data-value="Thursday" onclick="selectCustomOption(event, 'Thursday', 'Thursday', 'dayFilterDropdown', 'selectedDayText', 'dayFilter')">Thursday</div>
                        <div class="custom-option" data-value="Friday" onclick="selectCustomOption(event, 'Friday', 'Friday', 'dayFilterDropdown', 'selectedDayText', 'dayFilter')">Friday</div>
                        <div class="custom-option" data-value="Saturday" onclick="selectCustomOption(event, 'Saturday', 'Saturday', 'dayFilterDropdown', 'selectedDayText', 'dayFilter')">Saturday</div>
                    </div>
                    <select id="dayFilter" class="hidden" onchange="handleSchedulePageFilterChange()">
                        <option value="all">All Schedule</option>
                    </select>
                </div>

                {{-- Time Filter (Right) --}}
                <div class="custom-dropdown" id="timeFilterDropdown">
                    <div class="custom-dropdown-selected" onclick="toggleCustomDropdown(event, 'timeFilterDropdown')">
                        <span id="selectedTimeText">All Time</span>
                        <div class="dropdown-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </div>
                    </div>
                    <div class="custom-dropdown-options" id="timeDropdownOptions">
                         <div class="custom-option selected" data-value="all" onclick="selectCustomOption(event, 'all', 'All Time', 'timeFilterDropdown', 'selectedTimeText', 'timeFilter')">All Time</div>
                    </div>
                    <select id="timeFilter" class="hidden" onchange="handleSchedulePageFilterChange()">
                        <option value="all">All Time</option>
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
            loadSchedulePageData();
        });
    </script>
@endpush