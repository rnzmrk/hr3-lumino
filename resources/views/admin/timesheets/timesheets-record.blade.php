@extends('layouts.app')

@section('title', 'Timesheet Record')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Timesheet Record</h1>
        <p class="text-slate-600 mt-1">Manage and track employee timesheet entries</p>
    </div> 

    <!-- Filters and Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
        <form method="GET" action="{{ route('timesheets.record') }}">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">From</label>
                        <input type="date" name="from_date" value="{{ request('from_date') }}" class="px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="From Date">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">To</label>
                        <input type="date" name="to_date" value="{{ request('to_date') }}" class="px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="To Date">
                    </div>
                    <div class="relative">
                        <input type="text" name="employee_search" value="{{ request('employee_search') }}" placeholder="Search employee..." class="px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent min-w-[200px]" autocomplete="off">
                        <div id="employeeResults" class="absolute z-10 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-lg hidden max-h-48 overflow-y-auto"></div>
                    </div>
                    <button type="submit" class="px-3 py-2 bg-slate-100 text-slate-700 rounded hover:bg-slate-200 transition-colors duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Apply Filters
                    </button>
                    @if(request()->hasAny(['from_date', 'to_date', 'employee_search']))
                        <a href="{{ route('timesheets.record') }}" class="px-3 py-2 text-slate-600 hover:text-slate-900 transition-colors duration-200 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Clear
                        </a>
                    @endif
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('timesheets.record.export', request()->query()) }}" class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Export
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Timesheet Table -->
    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px]">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-3 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider min-w-[180px]">Employee</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider min-w-[100px]">Date</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider min-w-[80px]">Check In</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider min-w-[80px]">Check Out</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider min-w-[80px]">Overtime</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider min-w-[80px]">Total</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider min-w-[80px]">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse ($timesheets as $record)
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
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-slate-900">
                            @if($record->overtime)
                                @php
                                    // Get overtime as negative number
                                    $otRawValue = 0;
                                    if ($record->overtime instanceof \Carbon\Carbon) {
                                        $otRawValue = -(int)$record->overtime->format('H'); // Make negative
                                        error_log("DEBUG Overtime Carbon: " . $record->overtime->format('Y-m-d H:i:s') . " -> {$otRawValue}h");
                                    } else {
                                        $otRawValue = -(int)(string) $record->overtime; // Make negative
                                        error_log("DEBUG Overtime String: '" . (string) $record->overtime . "' -> {$otRawValue}h");
                                    }
                                @endphp
                                {{ $otRawValue }}h
                            @else
                                -- 
                            @endif
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-slate-900">
                            @if($record->check_in && $record->check_out)
                                @php
                                    // Get overtime as negative number
                                    $otRawValue = 0;
                                    if ($record->overtime) {
                                        if ($record->overtime instanceof \Carbon\Carbon) {
                                            $otRawValue = -(int)$record->overtime->format('H'); // Make negative
                                        } else {
                                            $otRawValue = -(int)(string) $record->overtime; // Make negative
                                        }
                                    }
                                    
                                    // Calculate grand total = work + overtime (overtime is negative, so it subtracts)
                                    $workHours = $record->total_hours + ($record->total_minutes / 60);
                                    $grandTotalHours = $workHours + $otRawValue; // Addition with negative = subtraction
                                    $grandTotalDisplayHours = intdiv($grandTotalHours, 1);
                                    $grandTotalDisplayMinutes = round(($grandTotalHours - $grandTotalDisplayHours) * 60);
                                @endphp
                                
                                <div class="space-y-1">
                                    <div class="text-xs text-gray-600">
                                        {{ $record->total_hours }}h {{ $record->total_minutes }}m + {{ $otRawValue }}h OT
                                    </div>
                                    <div class="text-sm font-semibold text-slate-900 border-t pt-1">
                                        = {{ $grandTotalDisplayHours }}h {{ $grandTotalDisplayMinutes }}m
                                    </div>
                                </div>
                            @else
                                <div class="text-xs text-gray-500">
                                    Incomplete data
                                </div>
                            @endif
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap"></td>
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
                        <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p class="text-sm font-medium text-slate-900">No timesheet records found</p>
                                <p class="text-xs text-slate-500 mt-1">Get started by adding timesheet records</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        {{ $timesheets->links() }}
    </div>
</div>
@endsection

<script>
// Employee search functionality
document.addEventListener('DOMContentLoaded', function() {
    const employeeSearch = document.querySelector('input[name="employee_search"]');
    const resultsDiv = document.getElementById('employeeResults');
    
    if (employeeSearch && resultsDiv) {
        employeeSearch.addEventListener('input', function(e) {
            const search = e.target.value.trim();
            
            if (search.length < 2) {
                resultsDiv.classList.add('hidden');
                return;
            }
            
            fetch(`/employees/search-timesheet?search=${encodeURIComponent(search)}`)
                .then(response => response.json())
                .then(employees => {
                    if (employees.length === 0) {
                        resultsDiv.innerHTML = '<div class="p-3 text-sm text-slate-500">No employees found</div>';
                    } else {
                        resultsDiv.innerHTML = employees.map(emp => `
                            <div onclick="selectEmployee('${emp.employee_name}')" 
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
    }
    
    function selectEmployee(name) {
        employeeSearch.value = name;
        resultsDiv.classList.add('hidden');
    }
    
    // Add overtime hour function
    function addOvertimeHour(recordId) {
        if (confirm('Add 1 hour to overtime for this record?')) {
            fetch(`/timesheets/add-overtime/${recordId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Overtime updated successfully!');
                    location.reload(); // Refresh the page to see updated values
                } else {
                    alert('Error updating overtime: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating overtime');
            });
        }
    }
});
</script>