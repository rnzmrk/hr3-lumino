@extends('layouts.app')

@section('title', 'Schedule Management')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Schedule Management</h1>
                <p class="text-gray-600 mt-2">Manage employee work schedules and shift assignments</p>
            </div>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors" onclick="openAddShiftModal()">
                + Add Shift
            </button>
        </div>
    </div>

    <!-- Shift Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Morning Shift Card -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Morning Shift</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $morningShiftCount }}</p>
                    <p class="text-xs text-gray-500 mt-1">6:00 AM - 12:00 PM</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Afternoon Shift Card -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Afternoon Shift</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $afternoonShiftCount }}</p>
                    <p class="text-xs text-gray-500 mt-1">12:00 PM - 6:00 PM</p>
                </div>
                <div class="bg-orange-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Night Shift Card -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Night Shift</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $nightShiftCount }}</p>
                    <p class="text-xs text-gray-500 mt-1">6:00 PM - 12:00 AM</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Shifts Card -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Shifts</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalShiftsCount }}</p>
                    <p class="text-xs text-gray-500 mt-1">All shift types</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form action="/schedule-management" method="GET" class="flex flex-wrap items-center gap-4">
            <!-- Employee Search -->
            <div class="flex-1 min-w-[300px]">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="employee_search" placeholder="Search employees..." value="{{ request('employee_search') }}"
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            
            <!-- Shift Type Filter -->
            <select name="shift_type" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Shifts</option>
                <option value="Morning Shift" {{ request('shift_type') == 'Morning Shift' ? 'selected' : '' }}>Morning Shift</option>
                <option value="Afternoon Shift" {{ request('shift_type') == 'Afternoon Shift' ? 'selected' : '' }}>Afternoon Shift</option>
                <option value="Night Shift" {{ request('shift_type') == 'Night Shift' ? 'selected' : '' }}>Night Shift</option>
            </select>
            
            <!-- Day Filter -->
            <select name="day_filter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Days</option>
                <option value="Monday" {{ request('day_filter') == 'Monday' ? 'selected' : '' }}>Monday</option>
                <option value="Tuesday" {{ request('day_filter') == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                <option value="Wednesday" {{ request('day_filter') == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                <option value="Thursday" {{ request('day_filter') == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                <option value="Friday" {{ request('day_filter') == 'Friday' ? 'selected' : '' }}>Friday</option>
                <option value="Saturday" {{ request('day_filter') == 'Saturday' ? 'selected' : '' }}>Saturday</option>
                <option value="Sunday" {{ request('day_filter') == 'Sunday' ? 'selected' : '' }}>Sunday</option>
            </select>
            
            <!-- Filter Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Filter
                </button>
                <a href="/schedule-management" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Shifts Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Employee Schedules</h3>
                <div class="flex items-center gap-2">
                    <a href="{{ route('schedule.management.export', request()->query()) }}" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors inline-flex items-center">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Export
                    </a>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shift Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Schedule Start</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Schedule End</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if($shifts->count() > 0)
                        @foreach($shifts as $shift)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $shift->employee_name }}</div>
                                    <div class="text-sm text-gray-500">ID: {{ $shift->employee_id }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @switch($shift->shift_type)
                                        @case('Morning Shift')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                                {{ $shift->shift_type }}
                                            </span>
                                            @break
                                        @case('Afternoon Shift')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-50 text-orange-700 border border-orange-200">
                                                {{ $shift->shift_type }}
                                            </span>
                                            @break
                                        @case('Night Shift')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-50 text-purple-700 border border-purple-200">
                                                {{ $shift->shift_type }}
                                            </span>
                                            @break
                                        @default
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-50 text-gray-700 border border-gray-200">
                                                {{ $shift->shift_type }}
                                            </span>
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($shift->schedule_start)->format('g:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($shift->schedule_end)->format('g:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $shift->days }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="/shifts/{{ $shift->id }}/edit" class="px-3 py-1 text-xs font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 inline-block">
                                            Edit
                                        </a>
                                        <form action="/shifts/{{ $shift->id }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded-md hover:bg-red-100"
                                                    onclick="return confirm('Are you sure you want to delete this shift?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr id="noDataRow">
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <p>No shifts scheduled yet</p>
                                    <p class="text-sm">Click "Add Shift" to create your first shift schedule</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing 
                    <span class="font-medium">{{ $shifts->firstItem() }}</span> to 
                    <span class="font-medium">{{ $shifts->lastItem() }}</span> of 
                    <span class="font-medium">{{ $shifts->total() }}</span> results
                </div>
                <div class="flex items-center space-x-2">
                    @if($shifts->hasPages())
                        {{-- Previous Button --}}
                        @if($shifts->onFirstPage())
                            <button class="px-3 py-1 text-sm bg-white border border-gray-300 rounded-md text-gray-400 cursor-not-allowed" disabled>
                                Previous
                            </button>
                        @else
                            <a href="{{ $shifts->previousPageUrl() }}" class="px-3 py-1 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Previous
                            </a>
                        @endif

                        {{-- Page Numbers --}}
                        @foreach($shifts->links() as $link)
                            @if($link['url'])
                                @if($link['active'])
                                    <button class="px-3 py-1 text-sm bg-blue-600 text-white rounded-md">
                                        {{ $link['label'] }}
                                    </button>
                                @else
                                    <a href="{{ $link['url'] }}" class="px-3 py-1 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                        {{ $link['label'] }}
                                    </a>
                                @endif
                            @else
                                <span class="px-3 py-1 text-sm text-gray-400">...</span>
                            @endif
                        @endforeach

                        {{-- Next Button --}}
                        @if($shifts->hasMorePages())
                            <a href="{{ $shifts->nextPageUrl() }}" class="px-3 py-1 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Next
                            </a>
                        @else
                            <button class="px-3 py-1 text-sm bg-white border border-gray-300 rounded-md text-gray-400 cursor-not-allowed" disabled>
                                Next
                            </button>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Shift Modal -->
<div id="addShiftModal" class="fixed inset-0 bg-black bg-opacity-30 hidden z-50 flex items-center justify-center transition-opacity duration-300">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 transform transition-transform duration-300 scale-95" id="modalContent">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Add New Shift</h3>
                <button onclick="closeAddShiftModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <form action="/shifts" method="POST">
            @csrf
            <div class="px-6 py-4">
                <!-- Employee Search -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Employee</label>
                    <div class="relative">
                        <input type="text" id="employeeSearch" placeholder="Type employee name..." 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        
                        <!-- Hidden fields for employee data -->
                        <input type="hidden" id="employeeId" name="employee_id">
                        <input type="text" id="employeeNameDisplay" readonly 
                               class="mt-2 w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg" 
                               placeholder="Selected employee will appear here">
                        
                        <!-- Search Results Dropdown -->
                        <div id="employeeSearchResults" class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg hidden max-h-48 overflow-y-auto">
                        </div>
                    </div>
                </div>
                
                <!-- Shift Type -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Shift Type</label>
                    <select name="shift_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Select Shift Type</option>
                        <option value="Morning Shift">Morning Shift</option>
                        <option value="Afternoon Shift">Afternoon Shift</option>
                        <option value="Night Shift">Night Shift</option>
                    </select>
                </div>
                
                <!-- Schedule Time -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                        <input type="time" name="schedule_start" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                        <input type="time" name="schedule_end" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                </div>
                
                <!-- Working Days -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Working Days</label>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                            <label class="flex items-center">
                                <input type="checkbox" name="days[]" value="{{ $day }}" class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-700">{{ $day }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" onclick="closeAddShiftModal()" class="px-4 py-2 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Add Shift
                </button>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50">
        {{ session('error') }}
    </div>
@endif

<script>
// Employee data from controller
const employees = @json($employees ?? []);

function openAddShiftModal() {
    const modal = document.getElementById('addShiftModal');
    const modalContent = document.getElementById('modalContent');
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.add('opacity-100');
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
    }, 10);
}

function closeAddShiftModal() {
    const modal = document.getElementById('addShiftModal');
    const modalContent = document.getElementById('modalContent');
    
    modal.classList.remove('opacity-100');
    modalContent.classList.remove('scale-100');
    modalContent.classList.add('scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        clearEmployeeSearch();
    }, 300);
}

function clearEmployeeSearch() {
    document.getElementById('employeeSearch').value = '';
    document.getElementById('employeeId').value = '';
    document.getElementById('employeeNameDisplay').value = '';
    document.getElementById('employeeSearchResults').classList.add('hidden');
}

function selectEmployee(id, name) {
    document.getElementById('employeeId').value = id;
    document.getElementById('employeeNameDisplay').value = name + ' (ID: ' + id + ')';
    document.getElementById('employeeSearch').value = name;
    document.getElementById('employeeSearchResults').classList.add('hidden');
}

// Employee search functionality
document.getElementById('employeeSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const resultsDiv = document.getElementById('employeeSearchResults');
    
    if (searchTerm.length < 2) {
        resultsDiv.classList.add('hidden');
        return;
    }
    
    const filteredEmployees = employees.filter(emp => 
        emp.employee_name.toLowerCase().includes(searchTerm) ||
        emp.id.toString().includes(searchTerm)
    );
    
    if (filteredEmployees.length > 0) {
        resultsDiv.innerHTML = filteredEmployees.map(emp => `
            <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0" 
                 onclick="selectEmployee(${emp.id}, '${emp.employee_name}')">
                <div class="font-medium text-gray-900">${emp.employee_name}</div>
                <div class="text-sm text-gray-500">ID: ${emp.id}</div>
            </div>
        `).join('');
        resultsDiv.classList.remove('hidden');
    } else {
        resultsDiv.innerHTML = '<div class="px-4 py-2 text-gray-500">No employees found</div>';
        resultsDiv.classList.remove('hidden');
    }
});

// Close search results when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('#employeeSearch') && !e.target.closest('#employeeSearchResults')) {
        document.getElementById('employeeSearchResults').classList.add('hidden');
    }
});

// Auto hide messages after 3 seconds
setTimeout(function() {
    const messages = document.querySelectorAll('.fixed.top-4.right-4');
    messages.forEach(function(msg) {
        msg.style.display = 'none';
    });
}, 3000);
</script>
@endsection
