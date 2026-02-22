<!-- Sidebar Component -->
<aside class="fixed left-0 top-0 z-40 w-64 h-screen bg-white border-r border-slate-200">
    <!-- Logo Section -->
    <div class="flex items-center justify-center h-16 px-6 border-b border-slate-200">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/newlogo.svg') }}" alt="HR3 Lumino" class="w-8 h-8">
            <span class="text-xl font-bold text-slate-900">HR3 Lumino</span>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="h-[calc(100vh-4rem)] px-4 py-6 space-y-6 overflow-y-auto">
        <!-- Dashboard -->
        <div class="space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg bg-blue-900 text-blue-100 hover:bg-blue-600 transition-colors duration-200">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Dashboard
            </a>
        </div>

        <!-- Main Content Section -->
        <div class="space-y-2">
            <h3 class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Main Content</h3>
            
            <!-- Time & Attendance -->
            <div class="space-y-1">
                <a href="/attendance" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-blue-500 hover:text-white transition-colors duration-200">
                    <svg class="w-5 h-5 mr-3 text-slate-500 hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Time & Attendance
                </a>
            </div>

            <!-- Timesheet with Dropdown -->
            <div class="space-y-1">
                <button onclick="toggleDropdown('timesheet')" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-blue-500 hover:text-white transition-colors duration-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-slate-500 hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Timesheet
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200 text-slate-400 hover:text-white" id="timesheet-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="timesheet-dropdown" class="hidden pl-12 space-y-1">
                    <a href="/timesheets-record" class="block px-4 py-2 text-sm text-slate-600 rounded-lg hover:bg-blue-500 hover:text-white transition-colors duration-200">Record</a>
                    <a href="/timesheets-report" class="block px-4 py-2 text-sm text-slate-600 rounded-lg hover:bg-blue-500 hover:text-white transition-colors duration-200">Report</a>
                </div>
            </div>

            <!-- Leave Management with Dropdown -->
            <div class="space-y-1">
                <button onclick="toggleDropdown('leave')" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-blue-500 hover:text-white transition-colors duration-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-slate-500 hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Leave Management
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200 text-slate-400 hover:text-white" id="leave-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="leave-dropdown" class="hidden pl-12 space-y-1">
                    <a href="{{ route('leaves.manage') }}" class="block px-4 py-2 text-sm text-slate-600 rounded-lg hover:bg-blue-500 hover:text-white transition-colors duration-200">Manage Leave</a>
                    <a href="{{ route('leave-types.index') }}" class="block px-4 py-2 text-sm text-slate-600 rounded-lg hover:bg-blue-500 hover:text-white transition-colors duration-200">Leave List</a>
                </div>
            </div>

            <!-- Shift & Schedule with Dropdown -->
            <div class="space-y-1">
                <button onclick="toggleDropdown('shift')" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-blue-500 hover:text-white transition-colors duration-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-slate-500 hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Shift & Schedule
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200 text-slate-400 hover:text-white" id="shift-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="shift-dropdown" class="hidden pl-12 space-y-1">
                    <a href="/schedule-management" class="block px-4 py-2 text-sm text-slate-600 rounded-lg hover:bg-blue-500 hover:text-white transition-colors duration-200">Schedule Management</a>
                    <a href="/overtime" class="block px-4 py-2 text-sm text-slate-600 rounded-lg hover:bg-blue-500 hover:text-white transition-colors duration-200">Overtime</a>
                    <a href="{{ route('overtime-requests.index') }}" class="block px-4 py-2 text-sm text-slate-600 rounded-lg hover:bg-blue-500 hover:text-white transition-colors duration-200">Overtime Requests</a>
                </div>
            </div>

            <!-- Claims & Reimbursement -->
            <div class="space-y-1">
                <a href="/claims-reimbursement" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-blue-500 hover:text-white transition-colors duration-200">
                    <svg class="w-5 h-5 mr-3 text-slate-500 hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    Claims & Reimbursement
                </a>
            </div>

            <!-- HR Line Separator -->
            <hr class="my-4 border-slate-200">

            <!-- Others -->
            <div class="space-y-1">
                <a href="/audit-logs" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-blue-500 hover:text-white transition-colors duration-200">
                    <svg class="w-5 h-5 mr-3 text-slate-500 hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Audit Logs
                </a>
            </div>
        </div>
    </nav>
</aside>

<!-- JavaScript for Dropdown Functionality Only -->
<script>
function toggleDropdown(id) {
    const dropdown = document.getElementById(id + '-dropdown');
    const arrow = document.getElementById(id + '-arrow');
    
    if (dropdown.classList.contains('hidden')) {
        dropdown.classList.remove('hidden');
        arrow.style.transform = 'rotate(180deg)';
    } else {
        dropdown.classList.add('hidden');
        arrow.style.transform = 'rotate(0deg)';
    }
}
</script>