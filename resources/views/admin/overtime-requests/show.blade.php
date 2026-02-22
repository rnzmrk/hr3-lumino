@extends('layouts.app')

@section('title', 'Overtime Request Details')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Overtime Request Details</h1>
                <p class="text-gray-600 mt-2">Request #{{ $overtimeRequest->id }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('overtime-requests.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    ← Back to Overtime Requests
                </a>
            </div>
        </div>
    </div>

    <!-- Request Details Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Employee Information -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Employee Information</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Employee Name</label>
                        <p class="text-gray-900">{{ $overtimeRequest->employee_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Request Date</label>
                        <p class="text-gray-900">{{ $overtimeRequest->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Overtime Details -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Overtime Details</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Overtime Date</label>
                        <p class="text-gray-900">{{ $overtimeRequest->date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Hours Requested</label>
                        <p class="text-gray-900">{{ number_format($overtimeRequest->hours, 2) }} hours</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Status</label>
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $overtimeRequest->status_badge_class }}">
                            {{ $overtimeRequest->status_label }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Reason -->
            <div class="md:col-span-2">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Reason for Overtime</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-900">{{ $overtimeRequest->reason }}</p>
                </div>
            </div>

            @if($overtimeRequest->admin_notes)
            <!-- Admin Notes -->
            <div class="md:col-span-2">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Admin Notes</h3>
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-gray-900">{{ $overtimeRequest->admin_notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Action Buttons -->
        @if($overtimeRequest->status === 'pending')
        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="flex justify-end space-x-3">
                <button onclick="updateOvertimeStatus({{ $overtimeRequest->id }}, 'approved', '{{ route('overtime-requests.update.status', $overtimeRequest->id) }}')" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    Approve Request
                </button>
                <button onclick="updateOvertimeStatus({{ $overtimeRequest->id }}, 'rejected', '{{ route('overtime-requests.update.status', $overtimeRequest->id) }}')" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    Reject Request
                </button>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
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
