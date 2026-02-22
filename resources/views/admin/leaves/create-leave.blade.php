@extends('layouts.app')

@section('title', 'Create Leave Request')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Create Leave Request</h1>
                <p class="text-gray-600 mt-2">Submit a new leave request</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('leaves.manage') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    ← Back to Leave Management
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form id="leaveRequestForm">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Employee Name -->
                <div>
                    <label for="employee_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Employee Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="employee_name" name="employee_name" required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Position -->
                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700 mb-2">
                        Position <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="position" name="position" required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Leave Type -->
                <div>
                    <label for="leave_type" class="block text-sm font-medium text-gray-700 mb-2">
                        Leave Type <span class="text-red-500">*</span>
                    </label>
                    <select id="leave_type" name="leave_type" required
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Leave Type</option>
                        @foreach($leaveTypes as $leaveType)
                            <option value="{{ $leaveType->leave_name }}">{{ $leaveType->leave_name }} ({{ $leaveType->leave_duration }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Leave Dates -->
                <div class="md:col-span-2">
                    <label for="leave_dates" class="block text-sm font-medium text-gray-700 mb-2">
                        Leave Dates <span class="text-red-500">*</span>
                    </label>
                    
                    <!-- Date Range Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="start_date" class="block text-xs font-medium text-gray-600 mb-1">Start Date</label>
                            <input type="date" id="start_date" name="start_date" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="end_date" class="block text-xs font-medium text-gray-600 mb-1">End Date</label>
                            <input type="date" id="end_date" name="end_date" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <!-- Individual Date Selection -->
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-gray-600 mb-2">Or Select Individual Dates</label>
                        <input type="date" id="individual_date" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <button type="button" onclick="addIndividualDate()" class="mt-2 px-3 py-1 bg-gray-500 text-white text-sm rounded hover:bg-gray-600 transition-colors">
                            + Add Date
                        </button>
                    </div>
                    
                    <!-- Selected Dates Display -->
                    <div id="selected_dates_display" class="hidden">
                        <label class="block text-xs font-medium text-gray-600 mb-2">Selected Leave Dates:</label>
                        <div id="dates_list" class="flex flex-wrap gap-2 mb-2"></div>
                        <input type="hidden" id="leave_dates_array" name="leave_dates" value="">
                    </div>
                    
                    <p class="text-xs text-gray-500 mt-1">Select a date range or pick individual dates. All selected dates will be included in your leave request.</p>
                </div>

                <!-- Reason -->
                <div class="md:col-span-2">
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Reason <span class="text-red-500">*</span>
                    </label>
                    <textarea id="reason" name="reason" rows="4" required
                              class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Please provide a reason for your leave request..."></textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Submit Leave Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Helper function to get CSRF token
function getCsrfToken() {
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    if (metaTag) {
        return metaTag.getAttribute('content');
    }
    
    // Fallback: try to get from form input
    const csrfInput = document.querySelector('input[name="_token"]');
    if (csrfInput) {
        return csrfInput.value;
    }
    
    // Last resort: return empty string (may cause 419 error but won't crash)
    console.warn('CSRF token not found');
    return '';
}

// Array to store selected dates
let selectedDates = [];

// Add individual date to selection
function addIndividualDate() {
    const dateInput = document.getElementById('individual_date');
    const date = dateInput.value;
    
    if (date && !selectedDates.includes(date)) {
        selectedDates.push(date);
        selectedDates.sort(); // Sort dates chronologically
        updateDatesDisplay();
        dateInput.value = ''; // Clear input
    }
}

// Remove date from selection
function removeDate(date) {
    selectedDates = selectedDates.filter(d => d !== date);
    updateDatesDisplay();
}

// Update the display of selected dates
function updateDatesDisplay() {
    const displayDiv = document.getElementById('selected_dates_display');
    const datesList = document.getElementById('dates_list');
    const hiddenInput = document.getElementById('leave_dates_array');
    
    if (selectedDates.length > 0) {
        displayDiv.classList.remove('hidden');
        
        // Clear current list
        datesList.innerHTML = '';
        
        // Add each date as a removable tag
        selectedDates.forEach(date => {
            const dateObj = new Date(date);
            const formattedDate = dateObj.toLocaleDateString('en-US', { 
                weekday: 'short', 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            });
            
            const dateTag = document.createElement('div');
            dateTag.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800';
            dateTag.innerHTML = `
                ${formattedDate}
                <button type="button" onclick="removeDate('${date}')" class="ml-2 text-blue-600 hover:text-blue-800">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            `;
            datesList.appendChild(dateTag);
        });
        
        // Update hidden input
        hiddenInput.value = JSON.stringify(selectedDates);
    } else {
        displayDiv.classList.add('hidden');
        hiddenInput.value = '';
    }
}

// Handle date range selection
function handleDateRange() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    
    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        
        if (start <= end) {
            // Clear existing individual dates
            selectedDates = [];
            
            // Add all dates in range
            const currentDate = new Date(start);
            while (currentDate <= end) {
                const dateStr = currentDate.toISOString().split('T')[0];
                if (!selectedDates.includes(dateStr)) {
                    selectedDates.push(dateStr);
                }
                currentDate.setDate(currentDate.getDate() + 1);
            }
            
            updateDatesDisplay();
        } else {
            alert('End date must be after or equal to start date');
            document.getElementById('end_date').value = '';
        }
    }
}

// Event listeners for date range
document.getElementById('start_date').addEventListener('change', function() {
    const endDate = document.getElementById('end_date');
    if (this.value && endDate.value) {
        handleDateRange();
    }
});

document.getElementById('end_date').addEventListener('change', function() {
    const startDate = document.getElementById('start_date');
    if (this.value && startDate.value) {
        handleDateRange();
    }
});

// Form submission
document.getElementById('leaveRequestForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    console.log('Form submission started');
    console.log('Selected dates:', selectedDates);
    
    // Basic form validation
    const employeeName = document.getElementById('employee_name').value.trim();
    const position = document.getElementById('position').value.trim();
    const leaveType = document.getElementById('leave_type').value;
    const reason = document.getElementById('reason').value.trim();
    
    console.log('Form values:', {
        employeeName,
        position,
        leaveType,
        reason,
        selectedDatesCount: selectedDates.length
    });
    
    // Validate required fields
    if (!employeeName) {
        alert('Please enter employee name');
        return;
    }
    
    if (!position) {
        alert('Please enter position');
        return;
    }
    
    if (!leaveType) {
        alert('Please select leave type');
        return;
    }
    
    if (!reason) {
        alert('Please enter reason');
        return;
    }
    
    // Validate that at least one date is selected
    if (selectedDates.length === 0) {
        alert('Please select at least one leave date');
        return;
    }
    
    const formData = new FormData(this);
    
    // Add leave dates as array
    selectedDates.forEach(date => {
        formData.append('leave_dates[]', date);
    });
    
    // Remove the individual date inputs from form data
    formData.delete('start_date');
    formData.delete('end_date');
    formData.delete('individual_date');
    formData.delete('leave_dates');
    
    console.log('FormData prepared:');
    for (let [key, value] of formData.entries()) {
        console.log(key, value);
    }
    
    fetch('/leaves', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': getCsrfToken()
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response ok:', response.ok);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            alert(data.message);
            window.location.href = '{{ route("leaves.manage") }}';
        } else {
            alert('Error: ' + (data.message || 'Failed to submit leave request'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while submitting the leave request: ' + error.message);
    });
});
</script>

@endsection
