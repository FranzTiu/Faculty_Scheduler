@extends('layouts.app')

@section('content')
<section id="schedulesSection" class="content-section active block">
    <div class="mb-8 w-full">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <h1 class="main-title text-3xl font-extrabold uppercase text-center md:text-left font-['Playfair_Display']">
                <span class="text-[#1e1b4b]">ROOMS</span> 
                <span class="text-[#fbbf24]">SCHEDULES</span>
            </h1>
            <x-button variant="outline" class="whitespace-nowrap text-lg" onclick="openModal('schedules')">Add Schedule</x-button>
        </div>

        <div class="filter-section mb-8 relative z-10 text-center md:text-left">
            <select id="scheduleVisualFilter" class="filter-btn" onchange="renderSchedulesVisualGrid()">
                <option value="all">All schedule</option>
            </select>
        </div>

        <div id="schedulesVisualGrid" class="comlab-grid w-full">
            <div class="col-span-full p-8 text-slate-400 text-center">Loading schedules...</div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        loadScheduleVisualData();
    });
</script>
@endpush
