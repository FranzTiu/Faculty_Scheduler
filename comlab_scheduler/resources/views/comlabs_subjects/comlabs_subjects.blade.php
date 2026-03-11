@extends('layouts.app')

@section('content')
<section id="roomsSection" class="content-section active block">
    <div class="mb-8 w-full">
        <h1 class="management-title text-3xl font-extrabold uppercase text-center md:text-left font-['Playfair_Display'] mb-8">
            <span class="text-[#1e1b4b]">COMLABS</span> 
            <span class="text-[#fbbf24]">&amp;</span> 
            <span class="text-[#1e1b4b]">SUBJECTS</span>
        </h1>
        
        <div class="flex flex-col sm:flex-row justify-center items-center gap-4 md:gap-8 mb-10">
            <button id="toggleComLabs" class="view-toggle-btn active w-full sm:w-48 !py-3 !text-lg !font-extrabold rounded-full transition-all" style="font-family: 'Playfair Display', serif;" onclick="switchCombinedView('comlabs')">ComLabs</button>
            <button id="toggleSubjects" class="view-toggle-btn w-full sm:w-48 !py-3 !text-lg !font-extrabold rounded-full transition-all" style="font-family: 'Playfair Display', serif;" onclick="switchCombinedView('subjects')">Subjects</button>
        </div>

        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <div class="filter-section w-full sm:w-auto text-center sm:text-left">
                <select id="combinedManagementFilter" class="filter-dropdown-navy">
                    <option value="all">All ComLabs</option>
                </select>
            </div>
            <x-button variant="outline" class="whitespace-nowrap text-lg" id="combinedAddBtn" onclick="openEditRoomModal()">Add ComLab</x-button>
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
    });
</script>
@endpush
