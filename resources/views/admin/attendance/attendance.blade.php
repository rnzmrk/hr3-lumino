@extends('layouts.app')

@section('title', 'Time & Attendance')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Time & Attendance</h1>
        <p class="text-slate-600 mt-1">Manage employee attendance and time records</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Present {{ request('date') ? 'on ' . \Carbon\Carbon::parse(request('date'))->format('M j') : 'Today' }}</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ $presentCount }}</p>
                    <p class="text-xs text-slate-500 mt-1">Employees present</p>
                </div>
                <div class="w-14 h-14 bg-emerald-50 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Absent {{ request('date') ? 'on ' . \Carbon\Carbon::parse(request('date'))->format('M j') : 'Today' }}</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ $absentCount }}</p>
                    <p class="text-xs text-slate-500 mt-1">Employees absent</p>
                </div>
                <div class="w-14 h-14 bg-red-50 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">On Leave {{ request('date') ? 'on ' . \Carbon\Carbon::parse(request('date'))->format('M j') : 'Today' }}</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ $onLeaveCount }}</p>
                    <p class="text-xs text-slate-500 mt-1">Employees on leave</p>
                </div>
                <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Late {{ request('date') ? 'on ' . \Carbon\Carbon::parse(request('date'))->format('M j') : 'Today' }}</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ $lateCount }}</p>
                    <p class="text-xs text-slate-500 mt-1">Employees late</p>
                </div>
                <div class="w-14 h-14 bg-amber-50 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
        <form method="GET" action="{{ route('attendance.index') }}">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3">
                    <input type="date" name="date" value="{{ request('date') }}" class="px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Date">
                    <input type="text" name="employee_search" value="{{ request('employee_search') }}" placeholder="Search employee..." class="px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent min-w-[200px]">
                    <button type="submit" class="px-3 py-2 bg-slate-100 text-slate-700 rounded hover:bg-slate-200 transition-colors duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Apply Filters
                    </button>
                    @if(request()->hasAny(['date', 'employee_search']))
                        <a href="{{ route('attendance.index') }}" class="px-3 py-2 text-slate-600 hover:text-slate-900 transition-colors duration-200 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Clear
                        </a>
                    @endif
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('attendance.export') }}?{{ request()->getQueryString() }}" 
                       class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Export
                    </a>
                    <button type="button" onclick="openAttendanceModal()" class="px-3 py-2 bg-slate-600 text-white rounded hover:bg-slate-700 transition-colors duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Attendance Table -->
    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[800px]">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-3 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider min-w-[180px]">Employee</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider min-w-[90px]">Date</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider min-w-[80px]">Check In</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider min-w-[80px]">Check Out</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider min-w-[80px]">Overtime</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider min-w-[80px]">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse ($attendance as $record)
                    <tr class="hover:bg-slate-50 transition-colors duration-150">
                        <td class="px-3 py-3 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-slate-900">{{ $record->employee_name }}</div>
                                <div class="text-sm text-slate-500">EMP{{ str_pad($record->employee_id, 3, '0', STR_PAD_LEFT) }}</div>
                            </div>
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-slate-900">{{ $record->date->format('M d, Y') }}</td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-slate-900">{{ $record->check_in ? $record->check_in->format('h:i A') : '--' }}</td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-slate-900">{{ $record->check_out ? $record->check_out->format('h:i A') : '--' }}</td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-slate-900">{{ $record->overtime ? $record->overtime->format('h\h i\m') : '--' }}</td>
                        <td class="px-3 py-3 whitespace-nowrap">
                            @switch($record->status)
                                @case('present')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Present
                                    </span>
                                    @break
                                @case('late')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">
                                        Late
                                    </span>
                                    @break
                                @case('awol')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        AWOL
                                    </span>
                                    @break
                                @case('leave')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        On Leave
                                    </span>
                                    @break
                                @case('absent')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Absent
                                    </span>
                                    @break
                            @endswitch
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p class="text-sm font-medium text-slate-900">No attendance records found</p>
                                <p class="text-xs text-slate-500 mt-1">Get started by adding attendance records</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        <!-- Pagination -->
        {{ $attendance->links() }}
    </div>
</div>
@endsection

<!-- Attendance Modal -->
<div id="attendanceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4">
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Mark Attendance</h3>
                <button onclick="closeAttendanceModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <form id="attendanceForm" class="p-6">
            @csrf
            <div class="space-y-4">
                <!-- Employee Search -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Search Employee</label>
                    <div class="relative">
                        <input type="text" id="employeeSearch" name="employee_search" 
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               placeholder="Type employee name or ID..." autocomplete="off">
                        <div id="employeeResults" class="absolute z-10 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-lg hidden max-h-48 overflow-y-auto"></div>
                    </div>
                </div>
                
                <!-- Hidden Employee Fields -->
                <input type="hidden" id="employeeId" name="employee_id">
                <input type="hidden" id="employeeName" name="employee_name">
                
                <!-- Date -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Date</label>
                    <input type="date" id="attendanceDate" name="date" required
                           class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <!-- Check In -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Check In</label>
                    <input type="time" id="checkIn" name="check_in"
                           class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <!-- Check Out -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Check Out</label>
                    <input type="time" id="checkOut" name="check_out"
                           class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <!-- Overtime -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Overtime</label>
                    <input type="time" id="overtime" name="overtime"
                           class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                    <select id="status" name="status" 
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Status</option>
                        <option value="present">Present</option>
                        <option value="late">Late</option>
                        <option value="absent">Absent</option>
                        <option value="leave">On Leave</option>
                        <option value="awol">AWOL</option>
                    </select>
                </div>
            </div>
            
            <div class="flex gap-3 mt-6">
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    Save Attendance
                </button>
                <button type="button" onclick="closeAttendanceModal()" class="flex-1 px-4 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition-colors duration-200">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let selectedEmployee = null;

function openAttendanceModal() {
    document.getElementById('attendanceModal').classList.remove('hidden');
    document.getElementById('attendanceDate').value = new Date().toISOString().split('T')[0];
}

function closeAttendanceModal() {
    document.getElementById('attendanceModal').classList.add('hidden');
    document.getElementById('attendanceForm').reset();
    document.getElementById('employeeResults').classList.add('hidden');
    selectedEmployee = null;
}

// Employee search functionality
document.getElementById('employeeSearch').addEventListener('input', function(e) {
    const search = e.target.value.trim();
    const resultsDiv = document.getElementById('employeeResults');
    
    if (search.length < 2) {
        resultsDiv.classList.add('hidden');
        return;
    }
    
    fetch(`/employees/search-attendance?search=${encodeURIComponent(search)}`)
        .then(response => response.json())
        .then(employees => {
            if (employees.length === 0) {
                resultsDiv.innerHTML = '<div class="p-3 text-sm text-slate-500">No employees found</div>';
            } else {
                resultsDiv.innerHTML = employees.map(emp => `
                    <div onclick="selectEmployee(${emp.id}, '${emp.employee_name}')" 
                         class="p-3 hover:bg-slate-50 cursor-pointer border-b border-slate-100 last:border-b-0">
                        <div class="font-medium text-slate-900">${emp.employee_name}</div>
                        <div class="text-sm text-slate-500">ID: ${emp.id} | ${emp.position} | ${emp.department}</div>
                    </div>
                `).join('');
            }
            resultsDiv.classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error searching employees:', error);
        });
});

function selectEmployee(id, name) {
    selectedEmployee = { id, name };
    document.getElementById('employeeId').value = id;
    document.getElementById('employeeName').value = name;
    document.getElementById('employeeSearch').value = name;
    document.getElementById('employeeResults').classList.add('hidden');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('#employeeSearch') && !e.target.closest('#employeeResults')) {
        document.getElementById('employeeResults').classList.add('hidden');
    }
});

// Form submission
document.getElementById('attendanceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!selectedEmployee) {
        alert('Please select an employee');
        return;
    }
    
    const formData = new FormData(this);
    
    fetch('/attendance', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeAttendanceModal();
            location.reload(); // Reload to show new attendance record
        } else {
            alert(data.message || 'Error saving attendance');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving attendance');
    });
});
</script>