@extends('layouts.app')

@section('title', 'Overtime Requests')

@section('content')
<div class="p-6">
    <!-- Include Notifications -->
    @include('admin.leaves.leave-types._notifications')
    
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Overtime Requests</h1>
        <p class="text-slate-600 mt-1">Manage employee overtime requests</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-amber-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-600">Pending</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['pending']['count'] }}</p>
                </div>
                <div class="ml-4">
                    <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-emerald-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-600">Approved</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['approved']['count'] }}</p>
                </div>
                <div class="ml-4">
                    <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-600">Rejected</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['rejected']['count'] }}</p>
                </div>
                <div class="ml-4">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-600">Total Requests</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['total']['count'] }}</p>
                </div>
                <div class="ml-4">
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Overtime Request Button -->
    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route('overtime-requests.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Overtime Request
        </a>
        
        <a href="#" onclick="exportToCsv(event)" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span id="exportText">Export CSV</span>
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" action="{{ route('overtime-requests.index') }}" id="filterForm">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search employee name or reason..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           id="searchInput">
                </div>
                
                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                
                <!-- Date From -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Date To -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <div class="mt-4 flex justify-end space-x-2">
                <button type="button" onclick="clearFilters()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    Clear Filters
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Overtime Requests Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Request ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Employee Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Reason</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Hours</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($overtimeRequests as $overtimeRequest)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">#{{ $overtimeRequest->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">{{ $overtimeRequest->employee_name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ Str::limit($overtimeRequest->reason, 100) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">{{ $overtimeRequest->date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">{{ number_format($overtimeRequest->hours, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $overtimeRequest->status_badge_class }}">
                                    {{ $overtimeRequest->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="viewOvertimeRequest({{ $overtimeRequest->id }})" class="text-blue-600 hover:text-blue-900 mr-3">View</button>
                                @if($overtimeRequest->status === 'pending')
                                    <button onclick="updateOvertimeStatus({{ $overtimeRequest->id }}, 'approved', '{{ route('overtime-requests.update.status', $overtimeRequest->id) }}')" class="text-green-600 hover:text-green-900 mr-3">Approve</button>
                                    <button onclick="updateOvertimeStatus({{ $overtimeRequest->id }}, 'rejected', '{{ route('overtime-requests.update.status', $overtimeRequest->id) }}')" class="text-red-600 hover:text-red-900">Reject</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-lg font-medium text-slate-900 mb-1">No overtime requests found</p>
                                    <p class="text-sm text-slate-600 mb-4">Get started by creating a new overtime request.</p>
                                    <a href="{{ route('overtime-requests.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                        Create Overtime Request
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($overtimeRequests->hasPages())
        <div class="mt-6">
            {{ $overtimeRequests->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<script>
// Export to CSV function - Pure JavaScript solution
function exportToCsv(event) {
    event.preventDefault();
    
    // Show loading state
    const exportBtn = event.currentTarget;
    const exportText = document.getElementById('exportText');
    const originalText = exportText.textContent;
    const originalIcon = exportBtn.querySelector('svg').outerHTML;
    
    exportBtn.classList.add('opacity-75');
    exportText.textContent = 'Exporting...';
    exportBtn.querySelector('svg').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>';
    exportBtn.querySelector('svg').classList.add('animate-spin');
    
    try {
        // Extract data from table
        const table = document.querySelector('table');
        const rows = table.querySelectorAll('tbody tr');
        
        // Create CSV data
        const csvData = [];
        
        // Add header
        csvData.push(['Request ID', 'Employee Name', 'Reason', 'Date', 'Hours', 'Status', 'Admin Notes', 'Created At']);
        
        // Add data rows
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length >= 7) {
                const rowData = [
                    cells[0].textContent.trim(), // Request ID
                    cells[1].textContent.trim(), // Employee Name
                    cells[2].textContent.trim(), // Reason
                    cells[3].textContent.trim(), // Date
                    cells[4].textContent.trim(), // Hours
                    cells[5].textContent.trim(), // Status
                    '', // Admin Notes (not shown in table)
                    '' // Created At (not shown in table)
                ];
                csvData.push(rowData);
            }
        });
        
        // Convert to CSV string
        const csvContent = convertToCsv(csvData);
        
        // Create download
        const blob = new Blob(['\ufeff' + csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        
        const filename = 'overtime_requests_' + new Date().toISOString().slice(0, 19).replace(/[:-]/g, '_') + '.csv';
        
        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Reset button
        exportBtn.classList.remove('opacity-75');
        exportText.textContent = originalText;
        exportBtn.querySelector('svg').outerHTML = originalIcon;
        
    } catch (error) {
        console.error('Export error:', error);
        alert('Error exporting data: ' + error.message);
        
        // Reset button
        exportBtn.classList.remove('opacity-75');
        exportText.textContent = originalText;
        exportBtn.querySelector('svg').outerHTML = originalIcon;
    }
}

// Convert array data to CSV string
function convertToCsv(data) {
    return data.map(row => {
        return row.map(field => {
            // Escape quotes and wrap in quotes if contains comma or quote
            if (typeof field === 'string' && (field.includes(',') || field.includes('"') || field.includes('\n'))) {
                return '"' + field.replace(/"/g, '""') + '"';
            }
            return field;
        }).join(',');
    }).join('\n');
}

// Live search functionality
let searchTimeout;
document.getElementById('searchInput').addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(function() {
        document.getElementById('filterForm').submit();
    }, 500); // Wait 500ms after user stops typing
});

// Clear filters function
function clearFilters() {
    // Clear all form inputs
    document.getElementById('filterForm').reset();
    
    // Redirect to clean URL
    window.location.href = '{{ route('overtime-requests.index') }}';
}

// View overtime request details
function viewOvertimeRequest(id) {
    window.location.href = `{{ route('overtime-requests.show', ':id') }}`.replace(':id', id);
}

// Update overtime request status
function updateOvertimeStatus(id, status, url) {
    const notes = prompt(`Enter admin notes for ${status} action (optional):`);
    
    // If user clicked cancel, don't proceed
    if (notes === null) {
        return;
    }
    
    fetch(url, {
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
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload(); // Reload to see updated status
        } else {
            alert('Error: ' + (data.message || 'Failed to update status'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the status: ' + error.message);
    });
}
</script>
@endsection
