@extends('layouts.app')

@section('title', 'Overtime Management')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Overtime Management</h1>
                <p class="text-gray-600 mt-2">Review and manage employee overtime requests</p>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex flex-wrap items-center gap-4">
            <!-- Date Filter -->
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700">Date:</label>
                <input type="date" id="dateFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <!-- Search -->
            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" id="searchInput" placeholder="Search employees..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            
            <!-- Status Filter -->
            <select id="statusFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Status</option>
                <option value="present">Present</option>
                <option value="late">Late</option>
                <option value="awol">AWOL</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
            
            <button id="filterButton" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Filter
            </button>
            <button id="clearButton" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                Clear
            </button>
        </div>
    </div>

    <!-- Overtime Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Overtime Requests</h3>
                <div class="flex items-center gap-2">
                    <a href="{{ route('overtime.export', request()->query()) }}" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors inline-flex items-center">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check In</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check Out</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Work Hours</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Overtime</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Hours</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($overtimeRequests as $attendance)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $attendance->employee_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($attendance->date)->format('M d, Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                try {
                                    if ($attendance->check_in instanceof \Carbon\Carbon) {
                                        echo $attendance->check_in->format('h:i A');
                                    } else {
                                        $checkInString = (string) $attendance->check_in;
                                        if (strpos($checkInString, ' ') !== false) {
                                            if (preg_match('/\d{4}-\d{2}-\d{2}.*\d{4}-\d{2}-\d{2}/', $checkInString)) {
                                                $parts = explode(' ', $checkInString);
                                                echo \Carbon\Carbon::parse($parts[0] . ' ' . $parts[1] . ' ' . $parts[2])->format('h:i A');
                                            } else {
                                                echo \Carbon\Carbon::parse($checkInString)->format('h:i A');
                                            }
                                        } else {
                                            echo \Carbon\Carbon::parse($attendance->date . ' ' . $checkInString)->format('h:i A');
                                        }
                                    }
                                } catch (\Exception $e) {
                                    echo '--';
                                }
                            @endphp
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                try {
                                    if ($attendance->check_out instanceof \Carbon\Carbon) {
                                        echo $attendance->check_out->format('h:i A');
                                    } else {
                                        $checkOutString = (string) $attendance->check_out;
                                        if (strpos($checkOutString, ' ') !== false) {
                                            if (preg_match('/\d{4}-\d{2}-\d{2}.*\d{4}-\d{2}-\d{2}/', $checkOutString)) {
                                                $parts = explode(' ', $checkOutString);
                                                echo \Carbon\Carbon::parse($parts[0] . ' ' . $parts[1] . ' ' . $parts[2])->format('h:i A');
                                            } else {
                                                echo \Carbon\Carbon::parse($checkOutString)->format('h:i A');
                                            }
                                        } else {
                                            echo \Carbon\Carbon::parse($attendance->date . ' ' . $checkOutString)->format('h:i A');
                                        }
                                    }
                                } catch (\Exception $e) {
                                    echo '--';
                                }
                            @endphp
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($attendance->check_in && $attendance->check_out)
                                @php
                                    // Safe parsing with error handling
                                    $checkIn = null;
                                    $checkOut = null;
                                    
                                    try {
                                        // Handle check_in time parsing
                                        if ($attendance->check_in instanceof \Carbon\Carbon) {
                                            $checkIn = $attendance->check_in;
                                        } else {
                                            $checkInString = (string) $attendance->check_in;
                                            // Check if it's already a full datetime or just time
                                            if (strpos($checkInString, ' ') !== false) {
                                                // Check for malformed double date strings
                                                if (preg_match('/\d{4}-\d{2}-\d{2}.*\d{4}-\d{2}-\d{2}/', $checkInString)) {
                                                    // Extract just the first date part
                                                    $parts = explode(' ', $checkInString);
                                                    $checkIn = \Carbon\Carbon::parse($parts[0] . ' ' . $parts[1] . ' ' . $parts[2]);
                                                } else {
                                                    $checkIn = \Carbon\Carbon::parse($checkInString);
                                                }
                                            } else {
                                                $checkIn = \Carbon\Carbon::parse($attendance->date . ' ' . $checkInString);
                                            }
                                        }
                                    } catch (\Exception $e) {
                                        $checkIn = null;
                                    }
                                    
                                    try {
                                        // Handle check_out time parsing
                                        if ($attendance->check_out instanceof \Carbon\Carbon) {
                                            $checkOut = $attendance->check_out;
                                        } else {
                                            $checkOutString = (string) $attendance->check_out;
                                            // Check if it's already a full datetime or just time
                                            if (strpos($checkOutString, ' ') !== false) {
                                                // Check for malformed double date strings
                                                if (preg_match('/\d{4}-\d{2}-\d{2}.*\d{4}-\d{2}-\d{2}/', $checkOutString)) {
                                                    // Extract just the first date part
                                                    $parts = explode(' ', $checkOutString);
                                                    $checkOut = \Carbon\Carbon::parse($parts[0] . ' ' . $parts[1] . ' ' . $parts[2]);
                                                } else {
                                                    $checkOut = \Carbon\Carbon::parse($checkOutString);
                                                }
                                            } else {
                                                $checkOut = \Carbon\Carbon::parse($attendance->date . ' ' . $checkOutString);
                                            }
                                        }
                                    } catch (\Exception $e) {
                                        $checkOut = null;
                                    }
                                    
                                    if ($checkIn && $checkOut) {
                                        if ($checkOut->lt($checkIn)) {
                                            $checkOut->addDay();
                                        }
                                        $workMinutes = $checkOut->diffInMinutes($checkIn);
                                        $workHours = intdiv($workMinutes, 60);
                                        $workMinutesRemainder = $workMinutes % 60;
                                    } else {
                                        $workHours = 0;
                                        $workMinutesRemainder = 0;
                                    }
                                @endphp
                                <div class="text-sm font-medium text-gray-900">{{ $workHours }}h {{ $workMinutesRemainder }}m</div>
                            @else
                                <div class="text-sm text-gray-500">--</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($attendance->overtime)
                                @php
                                    // Get overtime as negative number  
                                    $otRawValue = 0;
                                    if ($attendance->overtime instanceof \Carbon\Carbon) {
                                        $otRawValue = -(int)$attendance->overtime->format('H'); // Make negative
                                    } else {
                                        $otRawValue = -(int)(string) $attendance->overtime; // Make negative
                                    }
                                @endphp
                                <div class="text-sm font-medium text-gray-900">{{ $otRawValue }}h</div>
                            @else
                                <div class="text-sm text-gray-500">--</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($attendance->check_in && $attendance->check_out)
                                @php
                                    // Calculate total hours with proper parsing
                                    // Handle check_in time parsing
                                    if ($attendance->check_in instanceof \Carbon\Carbon) {
                                        $checkIn = $attendance->check_in;
                                    } else {
                                        $checkInString = (string) $attendance->check_in;
                                        // Check if it's already a full datetime or just time
                                        if (strpos($checkInString, ' ') !== false) {
                                            $checkIn = \Carbon\Carbon::parse($checkInString);
                                        } else {
                                            $checkIn = \Carbon\Carbon::parse($attendance->date . ' ' . $checkInString);
                                        }
                                    }
                                    
                                    // Handle check_out time parsing
                                    if ($attendance->check_out instanceof \Carbon\Carbon) {
                                        $checkOut = $attendance->check_out;
                                    } else {
                                        $checkOutString = (string) $attendance->check_out;
                                        // Check if it's already a full datetime or just time
                                        if (strpos($checkOutString, ' ') !== false) {
                                            $checkOut = \Carbon\Carbon::parse($checkOutString);
                                        } else {
                                            $checkOut = \Carbon\Carbon::parse($attendance->date . ' ' . $checkOutString);
                                        }
                                    }
                                    
                                    if ($checkOut->lt($checkIn)) {
                                        $checkOut->addDay();
                                    }
                                    $workMinutes = $checkOut->diffInMinutes($checkIn);
                                    
                                    $otRawValue = 0;
                                    if ($attendance->overtime) {
                                        if ($attendance->overtime instanceof \Carbon\Carbon) {
                                            $otRawValue = -(int)$attendance->overtime->format('H'); // Make negative
                                        } else {
                                            $otRawValue = -(int)(string) $attendance->overtime; // Make negative
                                        }
                                    }
                                    
                                    $totalMinutes = $workMinutes + ($otRawValue * 60); // Addition with negative = subtraction
                                    $totalHours = intdiv($totalMinutes, 60);
                                    $totalMinutesRemainder = $totalMinutes % 60;
                                @endphp
                                <div class="text-sm font-medium text-gray-900">{{ $totalHours }}h {{ $totalMinutesRemainder }}m</div>
                            @else
                                <div class="text-sm text-gray-500">--</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' : ($attendance->status === 'awol' ? 'bg-red-100 text-red-800' : ($attendance->status === 'late' ? 'bg-yellow-100 text-yellow-800' : ($attendance->status === 'approved' ? 'bg-green-100 text-green-800' : ($attendance->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')))) }}">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <button onclick="editOvertimeRequest({{ $attendance->id }})" class="px-3 py-1 text-xs font-medium text-purple-600 bg-purple-50 border border-purple-200 rounded-md hover:bg-purple-100">Edit</button>
                                <button onclick="viewOvertimeRequest({{ $attendance->id }})" class="px-3 py-1 text-xs font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100">View</button>
                                @if($attendance->status === 'pending')
                                    <button onclick="updateOvertimeStatus({{ $attendance->id }}, 'approved')" class="px-3 py-1 text-xs font-medium text-green-600 bg-green-50 border border-green-200 rounded-md hover:bg-green-100">Approve</button>
                                    <button onclick="updateOvertimeStatus({{ $attendance->id }}, 'rejected')" class="px-3 py-1 text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded-md hover:bg-red-100">Reject</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-lg font-medium text-gray-900 mb-1">No overtime requests found</p>
                                <p class="text-sm text-gray-500">Overtime requests will appear here when employees submit them.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($overtimeRequests->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing 
                    <span class="font-medium">{{ $overtimeRequests->firstItem() }}</span> 
                    to 
                    <span class="font-medium">{{ $overtimeRequests->lastItem() }}</span> 
                    of 
                    <span class="font-medium">{{ $overtimeRequests->total() }}</span> 
                    results
                </div>
                <div class="flex items-center space-x-1">
                    {{-- Custom pagination links for better styling --}}
                    @if($overtimeRequests->onFirstPage())
                        <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                            Previous
                        </span>
                    @else
                        <a href="{{ $overtimeRequests->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            Previous
                        </a>
                    @endif
                    
                    {{-- Page numbers --}}
                    @foreach($overtimeRequests->links()->elements as $element)
                        @if(is_string($element))
                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                                {{ $element }}
                            </span>
                        @endif
                    @endforeach
                    
                    @foreach(range(1, $overtimeRequests->lastPage()) as $page)
                        @if($page == $overtimeRequests->currentPage())
                            <span aria-current="page" class="relative inline-flex items-center px-4 py-2 border border-blue-500 bg-blue-50 text-sm font-medium text-blue-600">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $overtimeRequests->url($page) }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                    
                    @if($overtimeRequests->hasMorePages())
                        <a href="{{ $overtimeRequests->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            Next
                        </a>
                    @else
                        <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                            Next
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
// Filter functionality
function filterByStatus(status) {
    document.getElementById('statusFilter').value = status;
    filterOvertimeRequests();
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('dateFilter').value = '';
    filterOvertimeRequests();
}

function filterOvertimeRequests() {
    const searchTerm = document.getElementById('searchInput').value;
    const statusFilter = document.getElementById('statusFilter').value;
    const dateFilter = document.getElementById('dateFilter').value;
    
    // Build query parameters
    const params = new URLSearchParams();
    if (searchTerm) params.append('search', searchTerm);
    if (statusFilter) params.append('status', statusFilter);
    if (dateFilter) params.append('date', dateFilter);
    
    // Redirect to filtered page
    window.location.href = '/overtime' + (params.toString() ? '?' + params.toString() : '');
}

// Edit overtime request
function editOvertimeRequest(id) {
    fetch(`/overtime/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const request = data.overtime_request;
                
                const modalHtml = `
                    <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; align-items: center; justify-content: center;" onclick="this.remove()">
                        <div style="background: white; padding: 30px; border-radius: 10px; max-width: 500px; width: 90%; max-height: 80vh; overflow-y: auto;" onclick="event.stopPropagation()">
                            <h3 style="margin: 0 0 20px 0; color: #1f2937; font-size: 1.5rem; font-weight: bold;">Edit Overtime Request</h3>
                            
                            <form id="editOvertimeForm" onsubmit="saveOvertimeRequest(event, ${id})">
                                <div style="margin-bottom: 15px;">
                                    <label style="display: block; margin-bottom: 5px; color: #374151; font-weight: 500;">Employee:</label>
                                    <input type="text" value="${request.employee_name}" readonly style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 6px; background: #f9fafb;">
                                </div>
                                
                                <div style="margin-bottom: 15px;">
                                    <label style="display: block; margin-bottom: 5px; color: #374151; font-weight: 500;">Date:</label>
                                    <input type="text" value="${new Date(request.date).toLocaleDateString()}" readonly style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 6px; background: #f9fafb;">
                                </div>
                                
                                <div style="margin-bottom: 15px;">
                                    <label style="display: block; margin-bottom: 5px; color: #374151; font-weight: 500;">Check In:</label>
                                    <input type="time" id="editCheckIn" value="${request.check_in}" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 6px;">
                                </div>
                                
                                <div style="margin-bottom: 15px;">
                                    <label style="display: block; margin-bottom: 5px; color: #374151; font-weight: 500;">Check Out:</label>
                                    <input type="time" id="editCheckOut" value="${request.check_out}" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 6px;">
                                </div>
                                
                                <div style="margin-bottom: 15px;">
                                    <label style="display: block; margin-bottom: 5px; color: #374151; font-weight: 500;">Overtime Hours:</label>
                                    <input type="time" id="editOvertime" value="${request.overtime || ''}" step="900" placeholder="e.g., 2:30" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 6px;">
                                    <small style="color: #6b7280; font-size: 0.75rem;">Format: HH:MM (e.g., 2:30 for 2 hours 30 minutes)</small>
                                </div>
                                
                                <div style="margin-bottom: 15px;">
                                    <label style="display: block; margin-bottom: 5px; color: #374151; font-weight: 500;">Status:</label>
                                    <select id="editStatus" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 6px;">
                                        <option value="present" ${request.status === 'present' ? 'selected' : ''}>Present</option>
                                        <option value="late" ${request.status === 'late' ? 'selected' : ''}>Late</option>
                                        <option value="awol" ${request.status === 'awol' ? 'selected' : ''}>AWOL</option>
                                        <option value="pending" ${request.status === 'pending' ? 'selected' : ''}>Pending</option>
                                        <option value="approved" ${request.status === 'approved' ? 'selected' : ''}>Approved</option>
                                        <option value="rejected" ${request.status === 'rejected' ? 'selected' : ''}>Rejected</option>
                                    </select>
                                </div>
                                
                                <div style="margin-bottom: 15px;">
                                    <label style="display: block; margin-bottom: 5px; color: #374151; font-weight: 500;">Admin Notes:</label>
                                    <textarea id="editAdminNotes" rows="3" placeholder="Optional notes..." style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 6px;">${request.admin_notes || ''}</textarea>
                                </div>
                                
                                <div style="text-align: right; margin-top: 20px;">
                                    <button type="button" onclick="this.closest('div').parentElement.remove()" style="background: #6b7280; color: white; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer; margin-right: 10px;">
                                        Cancel
                                    </button>
                                    <button type="submit" style="background: #3b82f6; color: white; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer;">
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                `;
                
                document.body.insertAdjacentHTML('beforeend', modalHtml);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load overtime request for editing');
        });
}

// Save overtime request
function saveOvertimeRequest(event, id) {
    event.preventDefault();
    
    const formData = {
        check_in: document.getElementById('editCheckIn').value,
        check_out: document.getElementById('editCheckOut').value,
        overtime: document.getElementById('editOvertime').value,
        status: document.getElementById('editStatus').value,
        admin_notes: document.getElementById('editAdminNotes').value
    };
    
    fetch(`/overtime/${id}/update`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to update overtime request'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the overtime request');
    });
}

// View overtime request details
function viewOvertimeRequest(id) {
    fetch(`/overtime/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const request = data.overtime_request;
                
                const modalHtml = `
                    <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; align-items: center; justify-content: center;" onclick="this.remove()">
                        <div style="background: white; padding: 30px; border-radius: 10px; max-width: 500px; width: 90%; max-height: 80vh; overflow-y: auto;" onclick="event.stopPropagation()">
                            <h3 style="margin: 0 0 20px 0; color: #1f2937; font-size: 1.5rem; font-weight: bold;">Overtime Request Details</h3>
                            
                            <div style="margin-bottom: 15px;">
                                <strong style="color: #6b7280;">Employee:</strong> ${request.employee_name}<br>
                                <strong style="color: #6b7280;">Date:</strong> ${new Date(request.date).toLocaleDateString()}<br>
                                <strong style="color: #6b7280;">Check In:</strong> ${request.check_in}<br>
                                <strong style="color: #6b7280;">Check Out:</strong> ${request.check_out}<br>
                                <strong style="color: #6b7280;">Overtime:</strong> ${request.overtime || '--'}<br>
                                <strong style="color: #6b7280;">Status:</strong> 
                                <span style="padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600; ${getStatusBadgeStyle(request.status)}">
                                    ${request.status.charAt(0).toUpperCase() + request.status.slice(1)}
                                </span>
                            </div>
                            
                            ${request.admin_notes ? `
                            <div style="margin-bottom: 15px;">
                                <strong style="color: #6b7280;">Admin Notes:</strong><br>
                                <span style="color: #374151;">${request.admin_notes}</span>
                            </div>
                            ` : ''}
                            
                            <div style="text-align: right; margin-top: 20px;">
                                <button onclick="this.closest('div').parentElement.remove()" style="background: #3b82f6; color: white; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer;">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                document.body.insertAdjacentHTML('beforeend', modalHtml);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load overtime request details');
        });
}

// Update overtime status
function updateOvertimeStatus(id, status) {
    const notes = prompt(`Enter admin notes for ${status} action (optional):`);
    
    fetch(`/overtime/${id}/status`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            status: status,
            admin_notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to update status'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the status');
    });
}

// Helper function for status badge styles
function getStatusBadgeStyle(status) {
    const styles = {
        'present': 'background: #d1fae5; color: #065f46;',
        'awol': 'background: #fee2e2; color: #991b1b;',
        'late': 'background: #fef3c7; color: #92400e;',
        'pending': 'background: #fef3c7; color: #92400e;',
        'approved': 'background: #d1fae5; color: #065f46;',
        'rejected': 'background: #fee2e2; color: #991b1b;'
    };
    return styles[status] || 'background: #f3f4f6; color: #374151;';
}

// Event listeners
document.getElementById('filterButton').addEventListener('click', filterOvertimeRequests);
document.getElementById('clearButton').addEventListener('click', clearFilters);

// Preserve filter values from URL parameters
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    // Set filter values from URL
    if (urlParams.get('search')) {
        document.getElementById('searchInput').value = urlParams.get('search');
    }
    if (urlParams.get('status')) {
        document.getElementById('statusFilter').value = urlParams.get('status');
    }
    if (urlParams.get('date')) {
        document.getElementById('dateFilter').value = urlParams.get('date');
    }
});
</script>

@endsection