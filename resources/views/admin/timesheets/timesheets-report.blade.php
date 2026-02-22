@extends('layouts.app')

@section('title', 'Employee Attendance Report')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Employee Attendance Report</h1>
        <p class="text-slate-600 mt-1">Search employee and view monthly attendance with hours and days present</p>
    </div>

    <!-- Employee Search Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
        <div class="flex flex-wrap items-center gap-4">
            <!-- Employee Search Bar -->
            <div class="relative flex-1 min-w-[300px] max-w-md">
                <input type="text" id="employeeSearch" placeholder="Search employee by name or ID..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <svg class="absolute left-3 top-2.5 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            
            <!-- Date Range Filters -->
            <div class="flex items-center gap-2">
                <div class="flex flex-col">
                    <label class="text-xs text-slate-600 mb-1">From Date</label>
                    <input type="date" id="fromDate" class="px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex flex-col">
                    <label class="text-xs text-slate-600 mb-1">To Date</label>
                    <input type="date" id="toDate" class="px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div> 
            
            <button onclick="searchEmployee()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Search
            </button>
        </div>
    </div>

    <!-- Employee Details Card (Hidden by default) -->
    <div id="employeeDetails" class="hidden mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 id="employeeName" class="text-xl font-bold text-slate-900">John Smith</h2>
                    <p id="employeeId" class="text-sm text-slate-600">EMP001</p>
                    <p id="employeeDepartment" class="text-sm text-slate-600">Engineering Department</p>
                </div>
                <div class="text-right">
                    <p id="reportMonth" class="text-sm font-medium text-slate-600">December 2024</p>
                    <p class="text-xs text-slate-500">Attendance Report</p>
                </div>
            </div>

            <!-- Attendance Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-600">Days Present</p>
                            <p id="daysPresent" class="text-2xl font-bold text-blue-900">0</p>
                        </div>
                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="bg-emerald-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-emerald-600">Total Hours</p>
                            <p id="totalHours" class="text-2xl font-bold text-emerald-900">0.0</p>
                        </div>
                        <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="bg-amber-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-amber-600">Total Overtime</p>
                            <p id="overtimeHours" class="text-2xl font-bold text-amber-900">0.0</p>
                        </div>
                        <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Daily Attendance Table -->
            <div class="border border-slate-200 rounded-lg overflow-hidden">
                <div class="bg-slate-50 px-4 py-3 border-b border-slate-200">
                    <h3 class="text-sm font-semibold text-slate-900">Daily Attendance Details</h3>
                </div>
                <div class="overflow-x-auto max-h-96 overflow-y-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-200 sticky top-0">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Day</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Check In</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Check Out</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Overtime</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody id="attendanceTableBody" class="bg-white divide-y divide-slate-200">
                            <!-- Attendance records will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Export Options -->
            <div class="flex justify-end gap-2 mt-6">
                <button onclick="exportToCSV()" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors duration-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export CSV
                </button>
            </div>
        </div>
    </div>

    <!-- No Results Message (Hidden by default) -->
    <div id="noResults" class="hidden">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
            <svg class="w-16 h-16 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-lg font-medium text-slate-900 mb-2">No Employee Found</h3>
            <p class="text-slate-600">Try searching with a different employee name or ID.</p>
        </div>
    </div>
</div>

<script>
// Function to update cards with data (global scope)
function updateSummaryCards(data) {
    console.log('Updating cards with data:', data);
    
    // Update days present
    const daysPresentElement = document.getElementById('daysPresent');
    if (daysPresentElement && data.summary && data.summary.days_present !== undefined) {
        daysPresentElement.textContent = data.summary.days_present;
        console.log('Days Present updated:', data.summary.days_present);
    }
    
    // Update total hours
    const totalHoursElement = document.getElementById('totalHours');
    if (totalHoursElement && data.summary && data.summary.total_hours !== undefined) {
        totalHoursElement.textContent = data.summary.total_hours;
        console.log('Total Hours updated:', data.summary.total_hours);
    }
    
    // Update overtime hours
    const overtimeHoursElement = document.getElementById('overtimeHours');
    if (overtimeHoursElement && data.summary && data.summary.overtime_hours !== undefined) {
        overtimeHoursElement.textContent = data.summary.overtime_hours;
        console.log('Overtime Hours updated:', data.summary.overtime_hours);
    }
}

// Employee search functionality
document.addEventListener('DOMContentLoaded', function() {
    const employeeSearch = document.getElementById('employeeSearch');
    const resultsDiv = document.createElement('div');
    resultsDiv.className = 'absolute z-10 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-lg hidden max-h-48 overflow-y-auto';
    resultsDiv.id = 'employeeResults';
    employeeSearch.parentElement.appendChild(resultsDiv);
    
    // Set default date range (current month)
    function setDefaultDateRange() {
        const today = new Date();
        const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
        const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        
        document.getElementById('fromDate').value = firstDay.toISOString().split('T')[0];
        document.getElementById('toDate').value = lastDay.toISOString().split('T')[0];
    }
    
    // Initialize default dates on page load
    setDefaultDateRange();
    
    // Employee search autocomplete
    employeeSearch.addEventListener('input', function(e) {
        const search = e.target.value.trim();
        
        if (search.length < 2) {
            resultsDiv.classList.add('hidden');
            return;
        }
        
        fetch(`/employees/search-report?search=${encodeURIComponent(search)}`)
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
    
    // Select employee from dropdown
    window.selectEmployee = function(name) {
        employeeSearch.value = name;
        resultsDiv.classList.add('hidden');
    };
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#employeeSearch') && !e.target.closest('#employeeResults')) {
            resultsDiv.classList.add('hidden');
        }
    });
});

// Set month filter function
function setMonthFilter() {
    const filter = document.getElementById('monthFilter').value;
    const today = new Date();
    let fromDate, toDate;
    
    switch(filter) {
        case 'current':
            fromDate = new Date(today.getFullYear(), today.getMonth(), 1);
            toDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            break;
        case 'last':
            fromDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            toDate = new Date(today.getFullYear(), today.getMonth(), 0);
            break;
        case '2months':
            fromDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            toDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            break;
        case '3months':
            fromDate = new Date(today.getFullYear(), today.getMonth() - 2, 1);
            toDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            break;
        default:
            return;
    }
    
    document.getElementById('fromDate').value = fromDate.toISOString().split('T')[0];
    document.getElementById('toDate').value = toDate.toISOString().split('T')[0];
}

// Search employee report
function searchEmployee() {
    const searchTerm = document.getElementById('employeeSearch').value.trim();
    const fromDate = document.getElementById('fromDate').value;
    const toDate = document.getElementById('toDate').value;
    
    console.log('Search clicked:', { searchTerm, fromDate, toDate });
    
    // Validate inputs
    if (!searchTerm) {
        alert('Please enter employee name or ID');
        return;
    }
    
    if (!fromDate || !toDate) {
        alert('Please select both from and to dates');
        return;
    }
    
    if (new Date(fromDate) > new Date(toDate)) {
        alert('From date cannot be later than to date');
        return;
    }
    
    // Show loading state
    document.getElementById('employeeDetails').classList.add('hidden');
    document.getElementById('noResults').classList.add('hidden');
    
    // Fetch report data
    const formData = new FormData();
    formData.append('employee_id', searchTerm);
    formData.append('from_date', fromDate);
    formData.append('to_date', toDate);
    
    console.log('Sending request to /employee-report');
    
    fetch('/employee-report', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('=== FULL RESPONSE DATA ===');
        console.log('Response data:', data);
        console.log('Employee:', data.employee);
        console.log('Summary:', data.summary);
        console.log('Attendance records:', data.attendance?.length || 0);
        
        if (data.error) {
            throw new Error(data.error);
        }
        
        // Show employee details
        document.getElementById('employeeDetails').classList.remove('hidden');
        document.getElementById('noResults').classList.add('hidden');
        
        // Update employee info
        document.getElementById('employeeName').textContent = data.employee.name;
        document.getElementById('employeeId').textContent = 'EMP' + data.employee.id.toString().padStart(3, '0');
        document.getElementById('employeeDepartment').textContent = data.employee.department;
        
        // Format date range for display
        const fromDateObj = new Date(fromDate);
        const toDateObj = new Date(toDate);
        const options = { month: 'long', year: 'numeric' };
        const fromFormatted = fromDateObj.toLocaleDateString('en-US', options);
        const toFormatted = toDateObj.toLocaleDateString('en-US', options);
        
        if (fromFormatted === toFormatted) {
            document.getElementById('reportMonth').textContent = fromFormatted;
        } else {
            document.getElementById('reportMonth').textContent = `${fromFormatted} - ${toFormatted}`;
        }
        
        // Update summary stats using the new function
        console.log('Summary data:', data.summary);
        console.log('Days Present:', data.summary.days_present);
        console.log('Total Hours:', data.summary.total_hours);
        console.log('Overtime Hours:', data.summary.overtime_hours);
        
        // Use the new function to update cards
        updateSummaryCards(data);
        
        // Populate attendance table
        const tableBody = document.getElementById('attendanceTableBody');
        tableBody.innerHTML = '';
        
        if (data.attendance.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="6" class="px-3 py-4 text-center text-slate-500">No attendance records found for this period</td></tr>';
        } else {
            data.attendance.forEach(record => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-slate-50';
                
                let statusBadge = '';
                if (record.status === 'present') {
                    statusBadge = '<span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">Present</span>';
                } else if (record.status === 'late') {
                    statusBadge = '<span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">Late</span>';
                } else if (record.status === 'absent') {
                    statusBadge = '<span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Absent</span>';
                } else {
                    statusBadge = '<span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-100 text-slate-800">' + record.status + '</span>';
                }
                
                row.innerHTML = `
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-slate-900">${record.date}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-slate-600">${record.day}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-slate-900">${record.check_in}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-slate-900">${record.check_out}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-slate-900">${record.overtime}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-slate-900">${record.total_hours}</td>
                `;
                
                tableBody.appendChild(row);
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('employeeDetails').classList.add('hidden');
        document.getElementById('noResults').classList.remove('hidden');
        document.querySelector('#noResults h3').textContent = 'Error Loading Report';
        document.querySelector('#noResults p').textContent = error.message || 'Failed to load employee report. Please try again.';
    });
}

// Export to CSV function
function exportToCSV() {
    const employeeName = document.getElementById('employeeName').textContent;
    const fromDate = document.getElementById('fromDate').value;
    const toDate = document.getElementById('toDate').value;
    const daysPresent = document.getElementById('daysPresent').textContent;
    const totalHours = document.getElementById('totalHours').textContent;
    const overtimeHours = document.getElementById('overtimeHours').textContent;
    
    // Get table data
    const tableBody = document.getElementById('attendanceTableBody');
    const rows = tableBody.getElementsByTagName('tr');
    
    // Create CSV content
    let csvContent = '\ufeff'; // UTF-8 BOM for Excel compatibility
    
    // Add header information
    csvContent += 'Employee Attendance Report\n';
    csvContent += `Employee Name: ${employeeName}\n`;
    csvContent += `Period: ${fromDate} to ${toDate}\n`;
    csvContent += `Days Present: ${daysPresent}\n`;
    csvContent += `Total Hours: ${totalHours}\n`;
    csvContent += `Overtime Hours: ${overtimeHours}\n\n`;
    
    // Add table headers
    csvContent += 'Date,Day,Check In,Check Out,Overtime,Total Hours\n';
    
    // Add table rows
    for (let row of rows) {
        const cells = row.getElementsByTagName('td');
        if (cells.length > 0) {
            const rowData = []; 
            for (let cell of cells) {
                rowData.push('"' + cell.textContent.trim() + '"');
            }
            csvContent += rowData.join(',') + '\n';
        }
    }
    
    // Create download link
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    
    // Generate filename
    const fileName = `attendance_report_${employeeName.replace(/\s+/g, '_')}_${fromDate}_to_${toDate}.csv`;
    
    link.setAttribute('href', url);
    link.setAttribute('download', fileName);
    link.style.visibility = 'hidden';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Allow search on Enter key
document.getElementById('employeeSearch').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        searchEmployee();
    }
});
</script>
@endsection