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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-600">Present Today</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $stats['present'] ?? 0 }}</p>
                </div>
                <div class="ml-4">
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-600">Absent Today</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $stats['absent'] ?? 0 }}</p>
                </div>
                <div class="ml-4">
                    <div class="p-3 bg-red-100 rounded-full">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-600">Late Today</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $stats['late'] ?? 0 }}</p>
                </div>
                <div class="ml-4">
                    <div class="p-3 bg-amber-100 rounded-full">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-600">On Leave</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $stats['leave'] ?? 0 }}</p>
                </div>
                <div class="ml-4">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
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
                    <!-- Live Search - Separate from form submission -->
                    <div class="relative">
                        <input type="text" id="liveAttendanceSearch" value="{{ request('employee_search') }}" placeholder="🔍 Live search employee (type instantly)..." class="px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent min-w-[280px] bg-blue-50 border-blue-200">
                        <div id="searchIndicator" class="absolute right-3 top-2.5 text-blue-500 opacity-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <!-- Hidden field for form submission -->
                    <input type="hidden" name="employee_search" id="hiddenEmployeeSearch" value="{{ request('employee_search') }}">
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
                       class="px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center gap-2">
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
                        <th class="px-3 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider min-w-[80px]">Time In</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider min-w-[80px]">Time Out</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider min-w-[80px]">Overtime</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider min-w-[80px]">Status</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider min-w-[120px]">Actions</th>
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
                        <td class="px-3 py-3 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                @if(!$record->check_in)
                                    <button onclick="timeIn({{ $record->id }})" class="px-3 py-1 text-xs font-medium text-green-600 bg-green-50 border border-green-200 rounded-md hover:bg-green-100 transition-colors duration-200">
                                        Time In
                                    </button>
                                @endif
                                @if(!$record->check_out && $record->check_in)
                                    <button onclick="timeOut({{ $record->id }})" class="px-3 py-1 text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded-md hover:bg-red-100 transition-colors duration-200">
                                        Time Out
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-500">
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
        </div>

        <!-- Pagination -->
        {{ $attendance->links() }}
    </div>
</div>

<!-- Attendance Modal -->
<div id="attendanceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4">
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Attendance</h3>
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
                               class="w-full px-3 py-2 pr-10 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-blue-50 border-blue-200" 
                               placeholder="🔍 Live search employee (type 1 letter)..." autocomplete="off">
                        <div id="searchLoadingIndicator" class="absolute right-3 top-2.5 text-blue-500 opacity-0 transition-opacity duration-200">
                            <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                        <div id="employeeResults" class="absolute z-10 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-lg hidden max-h-48 overflow-y-auto"></div>
                    </div>
                </div>
                
                <!-- Hidden fields for employee data -->
                <input type="hidden" id="employeeId" name="employee_id">
                <input type="hidden" id="employeeName" name="employee_name">
                
                <!-- Date -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Date</label>
                    <input type="date" id="attendanceDate" name="date" required
                           class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <!-- Time In -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Time In</label>
                    <input type="time" id="checkIn" name="time_in" required
                           class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <!-- Time Out -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Time Out</label>
                    <input type="time" id="checkOut" name="time_out"
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
                    <select id="status" name="status" required
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
    document.querySelector('#attendanceModal h3').textContent = 'Mark Attendance';
    document.getElementById('attendanceForm').action = '/attendance';
    document.querySelector('#attendanceForm button[type="submit"]').textContent = 'Save Attendance';
    document.getElementById('attendanceForm').reset();
    document.getElementById('employeeResults').classList.add('hidden');
    selectedEmployee = null;
    
    document.getElementById('attendanceDate').value = new Date().toISOString().split('T')[0];
    
    document.getElementById('attendanceModal').classList.remove('hidden');
}

function closeAttendanceModal() {
    document.getElementById('attendanceModal').classList.add('hidden');
    document.getElementById('attendanceForm').reset();
    document.getElementById('employeeResults').classList.add('hidden');
    selectedEmployee = null;
}

document.getElementById('employeeSearch').addEventListener('input', function(e) {
    const search = e.target.value.trim();
    const resultsDiv = document.getElementById('employeeResults');
    const loadingIndicator = document.getElementById('searchLoadingIndicator');
    
    if (search.length < 1) {
        resultsDiv.classList.add('hidden');
        loadingIndicator.style.opacity = '0';
        return;
    }
    
    if (window.employeeSearchTimeout) {
        clearTimeout(window.employeeSearchTimeout);
    }
    
    loadingIndicator.style.opacity = '1';
    
    window.employeeSearchTimeout = setTimeout(() => {
        fetch(`/employees/search-attendance?search=${encodeURIComponent(search)}`)
            .then(response => response.json())
            .then(employees => {
                loadingIndicator.style.opacity = '0';
                
                if (employees.length === 0) {
                    resultsDiv.innerHTML = `
                        <div class="p-3 text-sm text-slate-500">
                            No employees found for "${search}"
                            <button onclick="loadAllEmployees()" class="ml-2 text-blue-600 hover:text-blue-800 underline text-xs">
                                Show all employees
                            </button>
                        </div>
                    `;
                } else {
                    resultsDiv.innerHTML = employees.map(emp => `
                        <div onclick="selectEmployee(${emp.id}, '${emp.employee_name}')" 
                             class="p-3 hover:bg-blue-50 cursor-pointer border-b border-slate-100 last:border-b-0">
                            <div class="font-medium text-slate-900">${emp.employee_name}</div>
                            <div class="text-sm text-slate-500">ID: ${emp.id} | ${emp.position} | ${emp.department}</div>
                        </div>
                    `).join('');
                }
                resultsDiv.classList.remove('hidden');
            })
            .catch(error => {
                loadingIndicator.style.opacity = '0';
                console.error('Error searching employees:', error);
                resultsDiv.innerHTML = `<div class="p-3 text-sm text-red-500">Error searching employees</div>`;
                resultsDiv.classList.remove('hidden');
            });
    }, 300);
});

function selectEmployee(id, name) {
    selectedEmployee = { id, name };
    document.getElementById('employeeId').value = id;
    document.getElementById('employeeName').value = name;
    document.getElementById('employeeSearch').value = name;
    document.getElementById('employeeResults').classList.add('hidden');
    document.getElementById('searchLoadingIndicator').style.opacity = '0';
}

function loadAllEmployees() {
    const resultsDiv = document.getElementById('employeeResults');
    const loadingIndicator = document.getElementById('searchLoadingIndicator');
    
    loadingIndicator.style.opacity = '1';
    
    fetch(`/employees/search-attendance?search=`)
        .then(response => response.json())
        .then(employees => {
            loadingIndicator.style.opacity = '0';
            
            if (employees.length === 0) {
                resultsDiv.innerHTML = '<div class="p-3 text-sm text-slate-500">No employees found</div>';
            } else {
                resultsDiv.innerHTML = `
                    <div class="p-2 text-xs text-slate-500 border-b border-slate-100">All Employees (${employees.length})</div>
                    ${employees.map(emp => `
                        <div onclick="selectEmployee(${emp.id}, '${emp.employee_name}')" 
                             class="p-3 hover:bg-blue-50 cursor-pointer border-b border-slate-100 last:border-b-0">
                            <div class="font-medium text-slate-900">${emp.employee_name}</div>
                            <div class="text-sm text-slate-500">ID: ${emp.id} | ${emp.position} | ${emp.department}</div>
                        </div>
                    `).join('')}
                `;
            }
            resultsDiv.classList.remove('hidden');
        })
        .catch(error => {
            loadingIndicator.style.opacity = '0';
            console.error('Error loading all employees:', error);
            resultsDiv.innerHTML = '<div class="p-3 text-sm text-red-500">Error loading employees</div>';
            resultsDiv.classList.remove('hidden');
        });
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('liveAttendanceSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('tbody tr');
            
            const hiddenField = document.getElementById('hiddenEmployeeSearch');
            if (hiddenField) {
                hiddenField.value = this.value;
            }
            
            let visibleCount = 0;
            
            tableRows.forEach((row, index) => {
                const employeeName = row.cells[0] ? row.cells[0].textContent.toLowerCase() : '';
                
                if (employeeName.includes(searchTerm)) {
                    row.style.display = '';
                    row.classList.remove('hidden');
                    row.classList.add('bg-green-50', 'border-l-4', 'border-green-500');
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                    row.classList.add('hidden');
                    row.classList.remove('bg-green-50', 'border-l-4', 'border-green-500');
                }
            });
            
            let resultsCounter = document.getElementById('attendanceSearchResults');
            if (!resultsCounter) {
                resultsCounter = document.createElement('div');
                resultsCounter.id = 'attendanceSearchResults';
                resultsCounter.className = 'text-sm font-medium mt-2 mb-4 p-3 rounded-lg border transition-all duration-200';
                
                const filtersSection = document.querySelector('.bg-white.rounded-xl.shadow-sm.border.border-slate-200.p-6.mb-6');
                if (filtersSection) {
                    filtersSection.after(resultsCounter);
                }
            }
            
            if (searchTerm === '') {
                resultsCounter.textContent = '';
                resultsCounter.classList.remove('bg-blue-50', 'border-blue-200', 'text-blue-700');
                tableRows.forEach(row => {
                    row.classList.remove('bg-green-50', 'border-l-4', 'border-green-500');
                });
            } else {
                resultsCounter.textContent = `🔍 Found ${visibleCount} employees containing "${this.value}"`;
                resultsCounter.classList.add('bg-blue-50', 'border-blue-200', 'text-blue-700');
            }
        });
    }
});

document.addEventListener('click', function(e) {
    if (!e.target.closest('#employeeSearch') && !e.target.closest('#employeeResults')) {
        document.getElementById('employeeResults').classList.add('hidden');
    }
});

document.getElementById('attendanceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!selectedEmployee) {
        showNotification('Please select an employee', 'error');
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
            showNotification('Attendance created successfully!', 'success');
            closeAttendanceModal();
            location.reload();
        } else {
            showNotification(data.message || 'Error saving attendance', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error saving attendance', 'error');
    });
});

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full`;
    
    const colors = {
        success: 'bg-green-100 border border-green-400 text-green-700',
        error: 'bg-red-100 border border-red-400 text-red-700',
        info: 'bg-blue-100 border border-blue-400 text-blue-700'
    };
    
    notification.classList.add(...colors[type].split(' '));
    
    const icons = {
        success: '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        error: '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        info: '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
    };
    
    notification.innerHTML = `${icons[type]}<span class="font-medium">${message}</span>`;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
        notification.classList.add('translate-x-0');
    }, 100);
    
    setTimeout(() => {
        notification.classList.remove('translate-x-0');
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 5000);
}

// Time In function
function timeIn(attendanceId) {
    fetch(`/attendance/${attendanceId}/time-in`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Time In recorded successfully', 'success');
            location.reload();
        } else {
            showNotification(data.message || 'Failed to record Time In', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while recording Time In', 'error');
    });
}

// Time Out function
function timeOut(attendanceId) {
    fetch(`/attendance/${attendanceId}/time-out`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Time Out recorded successfully', 'success');
            location.reload();
        } else {
            showNotification(data.message || 'Failed to record Time Out', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while recording Time Out', 'error');
    });
}
</script>
@endsection
