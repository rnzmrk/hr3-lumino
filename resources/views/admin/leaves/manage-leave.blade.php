@extends('layouts.app')

@section('title', 'Manage Leave')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Leave Management</h1>
                <p class="text-gray-600 mt-2">Review and manage employee leave requests</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('leaves.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    + New Request
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-400 hover:shadow-lg transition-shadow cursor-pointer" onclick="filterByStatus('pending')">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['pending']['count'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['pending']['new_today'] }} new today</p>
                </div>
                <div class="ml-4">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-400 hover:shadow-lg transition-shadow cursor-pointer" onclick="filterByStatus('approved')">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Approved</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['approved']['count'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['approved']['new_today'] }} new today</p>
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
        
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-400 hover:shadow-lg transition-shadow cursor-pointer" onclick="filterByStatus('rejected')">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Rejected</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['rejected']['count'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['rejected']['new_today'] }} new today</p>
                </div>
                <div class="ml-4">
                    <div class="p-3 bg-red-100 rounded-full">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-gray-400 hover:shadow-lg transition-shadow cursor-pointer" onclick="clearFilters()">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total']['count'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['total']['new_today'] }} new today</p>
                </div>
                <div class="ml-4">
                    <div class="p-3 bg-gray-100 rounded-full">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center gap-4">
            <!-- Search -->
            <div class="flex-1 min-w-[300px]">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" id="searchInput" placeholder="Search employees..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            
            <!-- Status Filter -->
            <select id="statusFilter" class="block w-40 pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
            
            <!-- Leave Type Filter -->
            <select id="typeFilter" class="block w-40 pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg">
                <option value="">All Types</option>
                <option value="sick">Sick Leave</option>
                <option value="vacation">Vacation</option>
                <option value="personal">Personal</option>
                <option value="maternity">Maternity</option>
                <option value="emergency">Emergency</option>
            </select>
            
            <button onclick="filterLeaveRequests()" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Filter
            </button>
            <button onclick="clearFilters()" class="px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Clear
            </button>
        </div>
    </div>

    <!-- Leave Requests Table -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Leave Requests</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Leave Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Leave Dates</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse ($leaveRequests as $leaveRequest)
                    <tr class="hover:bg-gray-50" data-id="{{ $leaveRequest->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $leaveRequest->employee_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $leaveRequest->position }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $leaveRequest->leave_type_badge_class }}">
                                {{ $leaveRequest->leave_type_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($leaveRequest->formatted_dates)
                                <div class="text-sm text-gray-900">
                                    {{ implode(', ', $leaveRequest->formatted_dates) }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ count($leaveRequest->formatted_dates) }} day(s)
                                </div>
                            @else
                                <div class="text-sm text-gray-500">No dates</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $leaveRequest->reason }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $leaveRequest->status_badge_class }}">
                                {{ $leaveRequest->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <button onclick="viewLeaveRequest({{ $leaveRequest->id }})" class="px-3 py-1 text-xs font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">View</button>
                                @if($leaveRequest->status === 'pending')
                                    <button onclick="updateLeaveStatus({{ $leaveRequest->id }}, 'approved')" class="px-3 py-1 text-xs font-medium text-green-600 bg-green-50 border border-green-200 rounded-md hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">Approve</button>
                                    <button onclick="updateLeaveStatus({{ $leaveRequest->id }}, 'rejected')" class="px-3 py-1 text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded-md hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Reject</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-lg font-medium text-gray-900 mb-1">No leave requests found</p>
                                <p class="text-sm text-gray-500">Get started by creating a new leave request.</p>
                                <a href="{{ route('leaves.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Create Leave Request
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Filter functionality for leave requests
function filterLeaveRequests() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
    const typeFilter = document.getElementById('typeFilter').value.toLowerCase();
    
    const tableRows = document.querySelectorAll('tbody tr');
    
    tableRows.forEach(row => {
        // Get text content from each cell, handling HTML elements
        const employeeName = row.cells[0] ? row.cells[0].textContent.toLowerCase().trim() : '';
        const position = row.cells[1] ? row.cells[1].textContent.toLowerCase().trim() : '';
        const leaveType = row.cells[2] ? row.cells[2].textContent.toLowerCase().trim() : '';
        const leaveDates = row.cells[3] ? row.cells[3].textContent.toLowerCase().trim() : '';
        const reason = row.cells[4] ? row.cells[4].textContent.toLowerCase().trim() : '';
        const status = row.cells[5] ? row.cells[5].textContent.toLowerCase().trim() : '';
        
        // Check if row matches all filters
        let matchesSearch = !searchTerm || 
            employeeName.includes(searchTerm) || 
            position.includes(searchTerm) || 
            leaveType.includes(searchTerm) || 
            leaveDates.includes(searchTerm) || 
            reason.includes(searchTerm);
        
        let matchesStatus = !statusFilter || status.includes(statusFilter);
        let matchesType = !typeFilter || leaveType.includes(typeFilter);
        
        if (matchesSearch && matchesStatus && matchesType) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Clear all filters
function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('typeFilter').value = '';
    
    // Show all rows
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.style.display = ''; 
    });
}

// Real-time search
document.getElementById('searchInput').addEventListener('input', filterLeaveRequests);
document.getElementById('statusFilter').addEventListener('change', filterLeaveRequests);
document.getElementById('typeFilter').addEventListener('change', filterLeaveRequests);

// Filter by status when clicking on cards
function filterByStatus(status) {
    document.getElementById('statusFilter').value = status;
    filterLeaveRequests();
}

// View leave request details - Simple popup using table data
function viewLeaveRequest(id) {
    // Find the table row with the matching ID
    const row = document.querySelector(`tr[data-id="${id}"]`);
    if (!row) {
        alert('Leave request not found');
        return;
    }
    
    // Get data from table cells
    const cells = row.cells;
    const employeeName = cells[0] ? cells[0].textContent.trim() : '';
    const position = cells[1] ? cells[1].textContent.trim() : '';
    const leaveType = cells[2] ? cells[2].textContent.trim() : '';
    const leaveDates = cells[3] ? cells[3].textContent.trim() : '';
    const reason = cells[4] ? cells[4].textContent.trim() : '';
    const status = cells[5] ? cells[5].textContent.trim() : '';
    
    // Create simple popup with table data
    const modalHtml = `
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; align-items: center; justify-content: center;" onclick="this.remove()">
            <div style="background: white; padding: 30px; border-radius: 10px; max-width: 600px; width: 90%; max-height: 80vh; overflow-y: auto;" onclick="event.stopPropagation()">
                <h3 style="margin: 0 0 20px 0; color: #1f2937; font-size: 1.5rem; font-weight: bold;">Leave Request Details</h3>
                
                <div style="margin-bottom: 15px;">
                    <strong style="color: #6b7280;">Employee Name:</strong><br>
                    <span style="color: #374151;">${employeeName}</span>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <strong style="color: #6b7280;">Position:</strong><br>
                    <span style="color: #374151;">${position}</span>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <strong style="color: #6b7280;">Leave Type:</strong><br>
                    <span style="color: #374151;">${leaveType}</span>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <strong style="color: #6b7280;">Leave Dates:</strong><br>
                    <span style="color: #374151;">${leaveDates}</span>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <strong style="color: #6b7280;">Reason:</strong><br>
                    <span style="color: #374151;">${reason}</span>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <strong style="color: #6b7280;">Status:</strong><br>
                    <span style="color: #374151;">${status}</span>
                </div>
                
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

// Helper function to get badge styles
function getBadgeStyle(type) {
    const styles = {
        'pending': 'background: #fef3c7; color: #92400e;',
        'approved': 'background: #d1fae5; color: #065f46;',
        'rejected': 'background: #fee2e2; color: #991b1b;',
        'sick': 'background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5;',
        'vacation': 'background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd;',
        'personal': 'background: #f3e8ff; color: #6b21a8; border: 1px solid #d8b4fe;',
        'maternity': 'background: #fce7f3; color: #9f1239; border: 1px solid #f9a8d4;',
        'emergency': 'background: #fed7aa; color: #9a3412; border: 1px solid #fdba74;'
    };
    return styles[type] || 'background: #f3f4f6; color: #374151;';
}

// Update leave request status
function updateLeaveStatus(id, status) {
    const notes = prompt(`Enter admin notes for ${status} action (optional):`);
    
    if (notes === null) {
        return; // User cancelled the prompt
    }
    
    fetch(`/leave-requests/${id}/status`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            status: status,
            admin_notes: notes || ''
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
            // Show success notification
            showNotification(data.message || `Leave request ${status} successfully!`, 'success');
            
            // Update the status badge in the table without reload
            const row = document.querySelector(`tr[data-id="${id}"]`);
            if (row) {
                const statusCell = row.cells[5]; // Status column
                if (statusCell) {
                    // Update status badge
                    statusCell.innerHTML = `<span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusBadgeClass(status)}">${status.charAt(0).toUpperCase() + status.slice(1)}</span>`;
                }
                
                // Remove approve/reject buttons if status is no longer pending
                const actionsCell = row.cells[6]; // Actions column
                if (actionsCell && status !== 'pending') {
                    const approveBtn = actionsCell.querySelector('button[onclick*="approved"]');
                    const rejectBtn = actionsCell.querySelector('button[onclick*="rejected"]');
                    if (approveBtn) approveBtn.remove();
                    if (rejectBtn) rejectBtn.remove();
                }
            }
        } else {
            showNotification(data.message || `Failed to ${status} leave request`, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while updating the status', 'error');
    });
}

// Helper function to get status badge class
function getStatusBadgeClass(status) {
    const classes = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'approved': 'bg-green-100 text-green-800',
        'rejected': 'bg-red-100 text-red-800'
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
}

// Notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    const colors = {
        success: 'bg-green-100 border border-green-400 text-green-700',
        error: 'bg-red-100 border border-red-400 text-red-700',
        info: 'bg-blue-100 border border-blue-400 text-blue-700'
    };
    
    notification.className = `${colors[type]} px-4 py-3 rounded relative mb-4`;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    
    notification.innerHTML = `
        <span class="block sm:inline">${message}</span>
        <button onclick="this.parentElement.remove()" class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <span class="text-2xl">&times;</span>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}
</script>

@endsection
