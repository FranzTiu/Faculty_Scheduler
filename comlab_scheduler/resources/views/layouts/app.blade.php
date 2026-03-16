<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Faculty Management System</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}?v=7.0">





</head>

<body
    class="{{ auth()->check() ? 'logged-in bg-slate-50 text-slate-800 antialiased' : 'logged-out flex items-center justify-center min-h-screen bg-slate-100 p-4 antialiased' }}">

    @if(auth()->check())
        <div id="dashboardPage"
            class="dashboard-container w-full overflow-x-hidden flex flex-col min-h-screen bg-slate-50 pt-[80px]">
            @include('components.navbar')

            @yield('hero')

            <main id="mainContent" class="flex-grow w-full py-4 md:py-8 transition-all duration-300">
                @yield('content')
            </main>
        </div>

        <!-- Modals that are shared across different views -->
        <div id="modalOverlay"
            style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); z-index: 5000; justify-content: center; align-items: center; padding: 1.5rem; overflow-y: auto;">
            <div class="glass-card"
                style="width: 100%; max-width: 440px; padding: 2rem; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3);">
                <h3 id="modalTitle"
                    style="margin-bottom: 1rem; color: #fbbf24; font-family: 'Playfair Display', serif; font-size: 1.4rem; text-align: center; text-transform: uppercase; font-weight: 800;">
                    Add New</h3>
                <div id="modalError"
                    style="display: none; padding: 10px; margin-bottom: 15px; border-radius: 8px; background-color: #fee2e2; border: 1px solid #f87171; color: #b91c1c; font-size: 0.85rem; font-weight: 600;">
                </div>
                <form id="modalForm">
                    <div id="modalFields"></div>
                    <div style="display: flex; gap: 0.8rem; margin-top: 1.5rem; justify-content: center;">
                        <button type="button" class="btn"
                            style="background: #f1f5f9; color: #475569; padding: 0.6rem 1.8rem; border-radius: 50px; font-weight: 600; font-size: 0.85rem; border: none; cursor: pointer;"
                            onclick="closeModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary"
                            style="background: #fbbf24; color: #000; padding: 0.6rem 2rem; border-radius: 50px; font-weight: 700; font-size: 0.85rem; border: none; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lab Schedule Modal -->
        <div id="labModal"
            style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(6px); z-index: 5100; justify-content: center; align-items: center; padding: 1.5rem; overflow-y: auto;">
            <div class="glass-card"
                style="width: 90%; max-width: 1000px; max-height: 80vh; overflow-y: auto; padding: 2rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                    <h2 id="labModalTitle" style="font-family: 'Playfair Display', serif; color: #1e1b4b;">Lab Schedule</h2>
                    <button onclick="closeLabModal()"
                        style="background: var(--secondary); border: none; padding: 0.5rem 1rem; border-radius: 8px; cursor: pointer; font-weight: 700;">Close</button>
                </div>
                <div id="labModalContent"></div>
            </div>
        </div>

        <!-- Subject Modal (Add/Edit Subject with Room Assignment) -->
        <div id="subjectModalOverlay"
            style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(6px); z-index: 5200; justify-content: center; align-items: center; padding: 1.5rem; overflow-y: auto;">
            <div class="glass-card"
                style="width: 100%; max-width: 480px; padding: 2.2rem; border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 16px;">
                <h3 id="subjectModalTitle"
                    style="margin-bottom: 1.8rem; color: #fbbf24; font-family: 'Playfair Display', serif; font-size: 1.6rem; text-align: center;">
                    Add New Subject</h3>
                <div id="subjectError"
                    style="display: none; padding: 10px; margin-bottom: 15px; border-radius: 8px; background-color: #fee2e2; border: 1px solid #f87171; color: #b91c1c; font-size: 0.85rem; font-weight: 600;">
                </div>
                <form id="subjectForm">
                    <div class="form-group">
                        <label>Subject Code</label>
                        <input type="text" id="sm_code" required placeholder="e.g. IT-101"
                            style="font-family: 'Inter', sans-serif;">
                    </div>
                    <div class="form-group">
                        <label>Subject Name</label>
                        <input type="text" id="sm_name" required placeholder="e.g. Programming 1"
                            style="font-family: 'Inter', sans-serif;">
                    </div>

                    <div style="display: flex; gap: 0.8rem; justify-content: center; margin-top: 1.5rem;">
                        <button type="button" class="btn"
                            style="background: #f1f5f9; color: #475569; padding: 0.6rem 2rem; border-radius: 50px; font-weight: 600; border: none; cursor: pointer;"
                            onclick="closeSubjectModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary"
                            style="background: #fbbf24; color: #000; padding: 0.6rem 2.5rem; border-radius: 50px; font-weight: 700; border: none; cursor: pointer;">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Add Room Modal -->
        <div id="roomModalOverlay"
            style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(6px); z-index: 5200; justify-content: center; align-items: center; padding: 1.5rem; overflow-y: auto;">
            <div class="glass-card"
                style="width: 100%; max-width: 450px; padding: 2.2rem; border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 16px;">
                <h3 id="roomModalTitle"
                    style="margin-bottom: 1.8rem; color: #fbbf24; font-family: 'Playfair Display', serif; font-size: 1.6rem; text-align: center;">
                    Room Management</h3>
                <div id="roomError"
                    style="display: none; padding: 10px; margin-bottom: 15px; border-radius: 8px; background-color: #fee2e2; border: 1px solid #f87171; color: #b91c1c; font-size: 0.85rem; font-weight: 600;">
                </div>
                <form id="roomForm" onsubmit="saveNewRoom(event)">
                    <div class="form-group">
                        <label>Room Name</label>
                        <input type="text" id="r_name" required placeholder="Enter room name"
                            style="font-family: 'Inter', sans-serif;">
                    </div>
                    <div class="form-group">
                        <label>Campus</label>
                        <select id="r_location" required style="font-family: 'Inter', sans-serif;">
                            <option value="">Select Campus</option>
                            <option value="Main Campus">Main Campus</option>
                            <option value="Young Field">Young Field</option>
                            <option value="College Building">College Building</option>
                        </select>
                    </div>
                    <div style="display: flex; gap: 0.8rem; justify-content: center; margin-top: 1.5rem;">
                        <button type="button" class="btn"
                            style="background: #f1f5f9; color: #475569; padding: 0.6rem 2rem; border-radius: 50px; font-weight: 600; border: none; cursor: pointer;"
                            onclick="closeRoomModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary"
                            style="background: #fbbf24; color: #000; padding: 0.6rem 2.5rem; border-radius: 50px; font-weight: 700; border: none; cursor: pointer;">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <!-- Render login page if not authenticated -->
        @yield('content')
    @endif

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    </script>
    <script src="{{ asset('assets/app.js') }}?v=4.6"></script>
    @stack('scripts')
</body>

</html>