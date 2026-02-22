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

    <!-- Calendar and Events Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Beautiful Real-time Calendar -->
        <div class="lg:col-span-2 bg-gradient-to-br from-white to-blue-50 rounded-2xl shadow-lg border border-blue-100 p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Calendar 2026</h3>
                <div class="flex items-center gap-3">
                    <button id="prevMonth" class="p-3 bg-white/80 backdrop-blur-sm hover:bg-white rounded-xl transition-all hover:shadow-md">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <span id="currentMonth" class="text-lg font-semibold text-slate-800 min-w-[150px] text-center bg-white/60 backdrop-blur-sm px-4 py-2 rounded-xl"></span>
                    <button id="nextMonth" class="p-3 bg-white/80 backdrop-blur-sm hover:bg-white rounded-xl transition-all hover:shadow-md">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Calendar Grid -->
            <div class="grid grid-cols-7 gap-2 mb-6">
                <!-- Day Headers -->
                <div class="text-center text-xs font-bold text-blue-600 py-3 bg-gradient-to-b from-blue-100 to-transparent rounded-lg">SUN</div>
                <div class="text-center text-xs font-bold text-blue-600 py-3 bg-gradient-to-b from-blue-100 to-transparent rounded-lg">MON</div>
                <div class="text-center text-xs font-bold text-blue-600 py-3 bg-gradient-to-b from-blue-100 to-transparent rounded-lg">TUE</div>
                <div class="text-center text-xs font-bold text-blue-600 py-3 bg-gradient-to-b from-blue-100 to-transparent rounded-lg">WED</div>
                <div class="text-center text-xs font-bold text-blue-600 py-3 bg-gradient-to-b from-blue-100 to-transparent rounded-lg">THU</div>
                <div class="text-center text-xs font-bold text-blue-600 py-3 bg-gradient-to-b from-blue-100 to-transparent rounded-lg">FRI</div>
                <div class="text-center text-xs font-bold text-blue-600 py-3 bg-gradient-to-b from-blue-100 to-transparent rounded-lg">SAT</div>
                
                <!-- Calendar Days -->
                <div id="calendarDays" class="col-span-7 grid grid-cols-7 gap-2">
                    <!-- Days will be populated by JavaScript -->
                </div>
            </div>
            
            <!-- Real-time Clock -->
            <div class="mt-6 pt-6 border-t border-blue-200">
                <div class="flex items-center justify-between bg-white/60 backdrop-blur-sm rounded-xl p-4">
                    <div>
                        <p class="text-sm font-medium text-blue-600 mb-1">🇵🇭 Philippines Time</p>
                        <p id="philippineTime" class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">--:--:--</p>
                    </div>
                    <div class="text-right">
                        <p id="philippineDate" class="text-sm font-medium text-slate-700 mb-1">Loading...</p>
                        <p id="timezone" class="text-xs text-slate-500">PST (UTC+8)</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- All 2026 Events & Holidays -->
        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl shadow-lg border border-purple-200 p-6">
            <h3 class="text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600 mb-4">🎉 Events & Holidays</h3>
            <div class="mb-4 space-y-3">
                <div class="flex gap-2">
                    <select id="yearSelector" class="flex-1 px-3 py-2 bg-white/80 backdrop-blur-sm border border-purple-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
                        <!-- Years will be populated by JavaScript -->
                    </select>
                    <select id="eventFilter" class="flex-1 px-3 py-2 bg-white/80 backdrop-blur-sm border border-purple-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
                        <option value="all">All Events</option>
                        <option value="holiday">Holidays</option>
                        <option value="seasonal">Seasonal</option>
                        <option value="cultural">Cultural</option>
                    </select>
                </div>
                <button id="refreshEvents" class="w-full px-3 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:from-purple-600 hover:to-pink-600 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh Events
                </button>
            </div>
            <div id="eventsList" class="space-y-3 max-h-96 overflow-y-auto">
                <!-- Events will be populated by JavaScript -->
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-purple-400 mx-auto mb-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <p class="text-purple-600">Loading events...</p>
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
                    <input type="text" id="liveScheduleSearch" name="employee_search" placeholder="🔍 Live search employees (type 1 letter)..." value="{{ request('employee_search') }}"
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-blue-50 border-blue-200">
                    <div id="searchResultsCounter" class="absolute right-3 top-2.5 text-blue-500 opacity-0 transition-opacity duration-200">
                        <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </div>
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
        
        <form action="/shifts" method="POST" id="addShiftForm">
            @csrf
            <div class="px-6 py-4">
                <!-- Employee Search -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Employee</label>
                    <div class="relative">
                        <input type="text" id="employeeSearch" placeholder="🔍 Live search employee (type 1 letter)..." 
                               class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-blue-50 border-blue-200">
                        <div id="modalSearchLoading" class="absolute right-3 top-2.5 text-blue-500 opacity-0 transition-opacity duration-200">
                            <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                        
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
console.log('Loaded employees:', employees.length, 'employees');

// Live Search for Schedule Table - TRUE LIKE search as you type
document.getElementById('liveScheduleSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase().trim();
    const tableRows = document.querySelectorAll('tbody tr');
    const loadingIndicator = document.getElementById('searchResultsCounter');
    
    // Clear any existing timeout for immediate response
    if (window.scheduleSearchTimeout) {
        clearTimeout(window.scheduleSearchTimeout);
    }
    
    // Show loading indicator
    loadingIndicator.style.opacity = '1';
    
    // IMMEDIATE filtering - no delay for truly live experience
    window.scheduleSearchTimeout = setTimeout(() => {
        let visibleCount = 0;
        
        tableRows.forEach(row => {
            // Get employee name from the first cell
            const employeeNameCell = row.cells[0];
            if (employeeNameCell) {
                const employeeName = employeeNameCell.textContent.toLowerCase();
                
                // TRUE LIKE search - show row if employee name CONTAINS search term anywhere
                if (searchTerm === '' || employeeName.includes(searchTerm)) {
                    row.style.display = '';
                    row.classList.remove('hidden');
                    // Add highlight effect for matching rows
                    row.classList.add('bg-green-50', 'border-l-4', 'border-green-500');
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                    row.classList.add('hidden');
                    // Remove highlight from hidden rows
                    row.classList.remove('bg-green-50', 'border-l-4', 'border-green-500');
                }
            }
        });
        
        // Hide loading indicator
        loadingIndicator.style.opacity = '0';
        
        // Update or create results counter immediately
        let resultsCounter = document.getElementById('scheduleSearchResults');
        if (!resultsCounter) {
            resultsCounter = document.createElement('div');
            resultsCounter.id = 'scheduleSearchResults';
            resultsCounter.className = 'text-sm font-medium mt-2 mb-4 p-3 rounded-lg border transition-all duration-200';
            
            // Insert after the filters section
            const filtersSection = document.querySelector('.bg-white.rounded-lg.shadow.p-6.mb-6');
            if (filtersSection) {
                filtersSection.after(resultsCounter);
            }
        }
        
        if (searchTerm === '') {
            resultsCounter.textContent = '';
            resultsCounter.classList.remove('bg-blue-50', 'border-blue-200', 'text-blue-700');
            // Remove all highlights when search is cleared
            tableRows.forEach(row => {
                row.classList.remove('bg-green-50', 'border-l-4', 'border-green-500');
            });
        } else {
            resultsCounter.textContent = `🔍 Found ${visibleCount} employees containing "${e.target.value}"`;
            resultsCounter.classList.add('bg-blue-50', 'border-blue-200', 'text-blue-700');
            
            // Add pulse animation to results counter
            resultsCounter.style.transform = 'scale(1.02)';
            setTimeout(() => {
                resultsCounter.style.transform = 'scale(1)';
            }, 100);
        }
    }, 200); // Small delay for smooth typing
});

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
    document.getElementById('modalSearchLoading').style.opacity = '0';
}

function selectEmployee(id, name) {
    console.log('Employee selected:', id, name);
    document.getElementById('employeeId').value = id;
    document.getElementById('employeeNameDisplay').value = name + ' (ID: ' + id + ')';
    document.getElementById('employeeSearch').value = name;
    document.getElementById('employeeSearchResults').classList.add('hidden');
    document.getElementById('modalSearchLoading').style.opacity = '0';
    
    // Add visual feedback
    document.getElementById('employeeNameDisplay').style.backgroundColor = '#dcfce7';
    setTimeout(() => {
        document.getElementById('employeeNameDisplay').style.backgroundColor = '';
    }, 1000);
}

// Employee search functionality - LIVE SEARCH as you type
document.getElementById('employeeSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const resultsDiv = document.getElementById('employeeSearchResults');
    const loadingIndicator = document.getElementById('modalSearchLoading');
    
    console.log('Searching for:', searchTerm);
    
    // IMMEDIATE live search - search with just 1 character
    if (searchTerm.length < 1) {
        resultsDiv.classList.add('hidden');
        loadingIndicator.style.opacity = '0';
        return;
    }
    
    // Clear any existing timeout for debouncing
    if (window.modalSearchTimeout) {
        clearTimeout(window.modalSearchTimeout);
    }
    
    // Show loading indicator
    loadingIndicator.style.opacity = '1';
    
    // Small delay for typing performance (300ms)
    window.modalSearchTimeout = setTimeout(() => {
        const filteredEmployees = employees.filter(emp => 
            emp.employee_name.toLowerCase().includes(searchTerm) ||
            emp.id.toString().includes(searchTerm)
        );
        
        console.log('Found employees:', filteredEmployees.length);
        
        // Hide loading indicator
        loadingIndicator.style.opacity = '0';
        
        if (filteredEmployees.length > 0) {
            resultsDiv.innerHTML = filteredEmployees.map(emp => `
                <div class="px-4 py-2 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-b-0" 
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
    }, 300); // 300ms delay for smooth typing
});

// Close search results when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('#employeeSearch') && !e.target.closest('#employeeSearchResults')) {
        document.getElementById('employeeSearchResults').classList.add('hidden');
    }
});

// Form submission with notification
document.getElementById('addShiftForm').addEventListener('submit', function(e) {
    console.log('=== FORM SUBMISSION STARTED ===');
    
    const addShiftForm = document.getElementById('addShiftForm');
    const employeeId = document.getElementById('employeeId').value;
    const shiftTypeSelect = addShiftForm.querySelector('select[name="shift_type"]');
    const shiftType = shiftTypeSelect ? shiftTypeSelect.value : '';
    const shiftTypeSelectedIndex = shiftTypeSelect ? shiftTypeSelect.selectedIndex : -1;
    const startDate = addShiftForm.querySelector('input[name="schedule_start"]').value;
    const endDate = addShiftForm.querySelector('input[name="schedule_end"]').value;
    const selectedDays = addShiftForm.querySelectorAll('input[name="days[]"]:checked');
    
    console.log('Form data:', { 
        addShiftFormFound: !!addShiftForm,
        employeeId, 
        shiftType, 
        shiftTypeSelectedIndex,
        shiftTypeSelectExists: !!shiftTypeSelect,
        shiftTypeOptions: shiftTypeSelect ? Array.from(shiftTypeSelect.options).map((opt, index) => ({
            index: index,
            value: opt.value, 
            text: opt.text, 
            selected: opt.selected
        })) : [],
        startDate, 
        endDate, 
        selectedDaysCount: selectedDays.length,
        selectedDays: Array.from(selectedDays).map(cb => cb.value)
    });
    
    // Validation - check each field specifically
    if (!employeeId || employeeId.trim() === '') {
        console.log('❌ Validation failed: No employee selected');
        e.preventDefault();
        alert('Please select an employee from the search results');
        return;
    }
    
    // Check shift type more thoroughly
    if (!shiftTypeSelect) {
        console.log('❌ Validation failed: Shift type select element not found in add form');
        e.preventDefault();
        alert('Shift type dropdown not found');
        return;
    }
    
    if (shiftTypeSelectedIndex <= 0) {
        console.log('❌ Validation failed: No shift type selected (index: ' + shiftTypeSelectedIndex + ')');
        e.preventDefault();
        alert('Please select a shift type from the dropdown');
        return;
    }
    
    if (!shiftType || shiftType.trim() === '' || shiftType === 'Select Shift Type') {
        console.log('❌ Validation failed: Invalid shift type value: "' + shiftType + '"');
        e.preventDefault();
        alert('Please select a valid shift type');
        return;
    }
    
    if (!startDate || startDate.trim() === '') {
        console.log('❌ Validation failed: No start time selected');
        e.preventDefault();
        alert('Please select a start time');
        return;
    }
    
    if (!endDate || endDate.trim() === '') {
        console.log('❌ Validation failed: No end time selected');
        e.preventDefault();
        alert('Please select an end time');
        return;
    }
    
    if (selectedDays.length === 0) {
        console.log('❌ Validation failed: No working days selected');
        e.preventDefault();
        alert('Please select at least one working day (Monday-Sunday)');
        return;
    }
    
    console.log('✅ All validation passed, submitting form...');
    
    // Show loading notification
    showNotification('Creating schedule...', 'info');
});

// Notification function
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full`;
    
    // Set colors based on type
    const colors = {
        success: 'bg-green-100 border border-green-400 text-green-700',
        error: 'bg-red-100 border border-red-400 text-red-700',
        info: 'bg-blue-100 border border-blue-400 text-blue-700'
    };
    
    notification.classList.add(...colors[type].split(' '));
    
    // Add icon based on type
    const icons = {
        success: '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        error: '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        info: '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
    };
    
    notification.innerHTML = `${icons[type]}<span class="font-medium">${message}</span>`;
    
    document.body.appendChild(notification);
    
    // Slide in animation
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
        notification.classList.add('translate-x-0');
    }, 100);
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        notification.classList.remove('translate-x-0');
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 5000);
}

// Auto hide messages after 3 seconds
setTimeout(function() {
    const messages = document.querySelectorAll('.fixed.top-4.right-4');
    messages.forEach(function(msg) {
        msg.style.display = 'none';
    });
}, 3000);

// Calendar functionality (identical to dashboard)
let currentDate = new Date();
let currentMonth = currentDate.getMonth();
let currentYear = currentDate.getFullYear();

function updateCalendar() {
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'];
    
    document.getElementById('currentMonth').textContent = 
        `${monthNames[currentMonth]} ${currentYear}`;
    
    const firstDay = new Date(currentYear, currentMonth, 1).getDay();
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
    const today = new Date();
    
    let calendarHTML = '';
    
    // Empty cells for days before month starts
    for (let i = 0; i < firstDay; i++) {
        calendarHTML += '<div class="p-3"></div>';
    }
    
    // Days of the month
    for (let day = 1; day <= daysInMonth; day++) {
        const isToday = today.getFullYear() === currentYear && 
                       today.getMonth() === currentMonth && 
                       today.getDate() === day;
        const isWeekend = new Date(currentYear, currentMonth, day).getDay() === 0 || 
                         new Date(currentYear, currentMonth, day).getDay() === 6;
        
        // Check if this day has events
        const currentDateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const dayEvents = allEvents.filter(event => event.date === currentDateStr);
        const hasEvent = dayEvents.length > 0;
        
        let classes = 'p-2 text-center rounded-xl cursor-pointer transition-all transform hover:scale-105 min-h-[60px] flex flex-col items-center justify-center ';
        if (isToday) {
            classes += 'bg-gradient-to-r from-blue-500 to-purple-600 text-white font-bold shadow-lg';
        } else if (hasEvent) {
            classes += 'bg-gradient-to-r from-pink-100 to-purple-100 text-purple-800 font-semibold border-2 border-purple-300';
        } else if (isWeekend) {
            classes += 'bg-gradient-to-b from-blue-50 to-transparent text-blue-600 hover:from-blue-100';
        } else {
            classes += 'hover:bg-gradient-to-b hover:from-blue-50 hover:to-transparent text-slate-800';
        }
        
        let dayContent = `<div class="text-lg font-bold">${day}</div>`;
        
        // Add event labels if there are events
        if (hasEvent && dayEvents.length > 0) {
            const eventNames = dayEvents.slice(0, 2).map(event => {
                const maxLength = 8;
                const shortName = event.name.length > maxLength ? 
                    event.name.substring(0, maxLength) + '...' : event.name;
                return `<div class="text-xs mt-1 truncate">${shortName}</div>`;
            }).join('');
            
            const moreText = dayEvents.length > 2 ? 
                `<div class="text-xs opacity-75">+${dayEvents.length - 2} more</div>` : '';
            
            dayContent += eventNames + moreText;
        }
        
        calendarHTML += `<div class="${classes}" title="${dayEvents.map(e => e.name).join(', ')}">${dayContent}</div>`;
    }
    
    document.getElementById('calendarDays').innerHTML = calendarHTML;
}

function updatePhilippineTime() {
    const now = new Date();
    // Convert to Philippines Time (UTC+8)
    const philippinesTime = new Date(now.toLocaleString("en-US", {timeZone: "Asia/Manila"}));
    
    const timeString = philippinesTime.toLocaleTimeString('en-US', {
        hour12: false,
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
    
    const dateString = philippinesTime.toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    document.getElementById('philippineTime').textContent = timeString;
    document.getElementById('philippineDate').textContent = dateString;
}

// Global variables
let selectedYear = new Date().getFullYear();
let allEvents = [];

// Initialize year selector
function initializeYearSelector() {
    const yearSelector = document.getElementById('yearSelector');
    const currentYear = new Date().getFullYear();
    
    // Add years from current year to 5 years ahead
    for (let year = currentYear; year <= currentYear + 5; year++) {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        if (year === selectedYear) {
            option.selected = true;
        }
        yearSelector.appendChild(option);
    }
}

// Fetch Philippine holidays from API
async function fetchPhilippineHolidays(year) {
    try {
        // Using a free holiday API (you may need to replace with a more comprehensive one)
        const response = await fetch(`https://date.nager.at/api/v3/PublicHolidays/${year}/PH`);
        const holidays = await response.json();
        
        return holidays.map(holiday => ({
            date: holiday.date,
            name: holiday.localName || holiday.name,
            type: 'holiday',
            emoji: '🇵🇭',
            source: 'api'
        }));
    } catch (error) {
        console.log('API failed, using fallback data for Philippines holidays');
        return getFallbackPhilippineHolidays(year);
    }
}

// Fallback Philippine holidays if API fails
function getFallbackPhilippineHolidays(year) {
    const holidays = [
        { date: `${year}-01-01`, name: "New Year's Day", type: 'holiday', emoji: '🎊' },
        { date: `${year}-04-09`, name: 'Day of Valor', type: 'holiday', emoji: '🇵🇭' },
        { date: `${year}-05-01`, name: 'Labor Day', type: 'holiday', emoji: '🇵🇭' },
        { date: `${year}-06-12`, name: 'Independence Day', type: 'holiday', emoji: '🇵🇭' },
        { date: `${year}-08-25`, name: 'National Heroes Day', type: 'holiday', emoji: '🇵🇭' },
        { date: `${year}-11-30`, name: 'Bonifacio Day', type: 'holiday', emoji: '🇵🇭' },
        { date: `${year}-12-25`, name: 'Christmas Day', type: 'holiday', emoji: '🎅' },
        { date: `${year}-12-30`, name: 'Rizal Day', type: 'holiday', emoji: '🇵🇭' }
    ];
    
    // Calculate movable holidays
    const edsaDate = calculateEDSADay(year);
    if (edsaDate) {
        holidays.push({ date: edsaDate, name: 'EDSA People Power Revolution', type: 'holiday', emoji: '🇵🇭' });
    }
    
    const holyWeek = calculateHolyWeek(year);
    if (holyWeek) {
        holidays.push(
            { date: holyWeek.maundyThursday, name: 'Maundy Thursday', type: 'holiday', emoji: '✝️' },
            { date: holyWeek.goodFriday, name: 'Good Friday', type: 'holiday', emoji: '✝️' },
            { date: holyWeek.blackSaturday, name: 'Black Saturday', type: 'holiday', emoji: '✝️' }
        );
    }
    
    return holidays;
}

// Calculate EDSA Day (February 25)
function calculateEDSADay(year) {
    return `${year}-02-25`;
}

// Calculate Holy Week dates
function calculateHolyWeek(year) {
    // More accurate Easter calculation using Computus algorithm
    const a = year % 19;
    const b = Math.floor(year / 100);
    const c = year % 100;
    const d = Math.floor(b / 4);
    const e = b % 4;
    const f = Math.floor((b + 8) / 25);
    const g = Math.floor((b - f + 1) / 3);
    const h = (19 * a + b - d - g + 15) % 30;
    const i = Math.floor(c / 4);
    const k = c % 4;
    const l = (32 + 2 * e + 2 * i - h - k) % 7;
    const m = Math.floor((a + 11 * h + 22 * l) / 451);
    const month = Math.floor((h + l - 7 * m + 114) / 31);
    const day = ((h + l - 7 * m + 114) % 31) + 1;
    
    const easterSunday = new Date(year, month - 1, day);
    const goodFriday = new Date(easterSunday);
    goodFriday.setDate(easterSunday.getDate() - 2);
    const maundyThursday = new Date(easterSunday);
    maundyThursday.setDate(easterSunday.getDate() - 3);
    const blackSaturday = new Date(easterSunday);
    blackSaturday.setDate(easterSunday.getDate() - 1);
    
    return {
        maundyThursday: formatDate(maundyThursday),
        goodFriday: formatDate(goodFriday),
        blackSaturday: formatDate(blackSaturday)
    };
}

// Get seasonal events
function getSeasonalEvents(year) {
    const events = [
        { date: `${year}-02-14`, name: "Valentine's Day", type: 'seasonal', emoji: '💝' },
        { date: `${year}-03-20`, name: 'Spring Equinox', type: 'seasonal', emoji: '🌸' },
        { date: `${year}-04-01`, name: "April Fools' Day", type: 'seasonal', emoji: '😄' },
        { date: `${year}-06-21`, name: 'Summer Solstice', type: 'seasonal', emoji: '☀️' },
        { date: `${year}-09-22`, name: 'Autumn Equinox', type: 'seasonal', emoji: '🍂' },
        { date: `${year}-10-31`, name: 'Halloween', type: 'seasonal', emoji: '🎃' },
        { date: `${year}-12-24`, name: 'Christmas Eve', type: 'seasonal', emoji: '🎄' },
        { date: `${year}-12-31`, name: "New Year's Eve", type: 'seasonal', emoji: '🍾' }
    ];
    
    // Mother's Day (Second Sunday in May)
    const mothersDay = calculateMothersDay(year);
    if (mothersDay) events.push({ date: mothersDay, name: "Mother's Day", type: 'seasonal', emoji: '💐' });
    
    // Father's Day (Third Sunday in June)
    const fathersDay = calculateFathersDay(year);
    if (fathersDay) events.push({ date: fathersDay, name: "Father's Day", type: 'seasonal', emoji: '👔' });
    
    return events;
}

// Calculate Mother's Day (Second Sunday in May)
function calculateMothersDay(year) {
    const firstMay = new Date(year, 4, 1);
    const firstSunday = new Date(firstMay);
    while (firstSunday.getDay() !== 0) {
        firstSunday.setDate(firstSunday.getDate() + 1);
    }
    const mothersDay = new Date(firstSunday);
    mothersDay.setDate(firstSunday.getDate() + 7);
    return formatDate(mothersDay);
}

// Calculate Father's Day (Third Sunday in June)
function calculateFathersDay(year) {
    const firstJune = new Date(year, 5, 1);
    const firstSunday = new Date(firstJune);
    while (firstSunday.getDay() !== 0) {
        firstSunday.setDate(firstSunday.getDate() + 1);
    }
    const fathersDay = new Date(firstSunday);
    fathersDay.setDate(firstSunday.getDate() + 14);
    return formatDate(fathersDay);
}

// Get cultural events
function getCulturalEvents(year) {
    const events = [
        { date: `${year}-01-29`, name: 'Chinese New Year', type: 'cultural', emoji: '🧧' },
        { date: `${year}-03-17`, name: "St. Patrick's Day", type: 'cultural', emoji: '🍀' },
        { date: `${year}-04-20`, name: 'Easter Sunday', type: 'cultural', emoji: '🐰' },
        { date: `${year}-07-04`, name: 'Independence Day (US)', type: 'cultural', emoji: '🇺🇸' },
        { date: `${year}-11-01`, name: "All Saints' Day", type: 'cultural', emoji: '⚰️' },
        { date: `${year}-11-02`, name: "All Souls' Day", type: 'cultural', emoji: '🙏' },
        { date: `${year}-11-11`, name: 'Veterans Day', type: 'cultural', emoji: '🇺🇸' },
        { date: `${year}-12-08`, name: 'Feast of the Immaculate Conception', type: 'cultural', emoji: '⭐' },
        { date: `${year}-12-16`, name: 'Simbang Gabi Starts', type: 'cultural', emoji: '🌟' }
    ];
    
    // Thanksgiving (Fourth Thursday in November)
    const thanksgiving = calculateThanksgiving(year);
    if (thanksgiving) events.push({ date: thanksgiving, name: 'Thanksgiving', type: 'cultural', emoji: '🦃' });
    
    return events;
}

// Calculate Thanksgiving (Fourth Thursday in November)
function calculateThanksgiving(year) {
    const firstNov = new Date(year, 10, 1);
    const firstThursday = new Date(firstNov);
    while (firstThursday.getDay() !== 4) {
        firstThursday.setDate(firstThursday.getDate() + 1);
    }
    const thanksgiving = new Date(firstThursday);
    thanksgiving.setDate(firstThursday.getDate() + 21);
    return formatDate(thanksgiving);
}

// Format date to YYYY-MM-DD
function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

// Fetch all events for the selected year
async function fetchEventsForYear(year) {
    try {
        const [holidays, seasonal, cultural] = await Promise.all([
            fetchPhilippineHolidays(year),
            Promise.resolve(getSeasonalEvents(year)),
            Promise.resolve(getCulturalEvents(year))
        ]);
        
        allEvents = [...holidays, ...seasonal, ...cultural];
        allEvents.sort((a, b) => new Date(a.date) - new Date(b.date));
        
        updateEventsList();
        updateCalendar();
    } catch (error) {
        console.error('Error fetching events:', error);
        showError('Failed to load events. Please try again.');
    }
}

// Show error message
function showError(message) {
    const eventsList = document.getElementById('eventsList');
    eventsList.innerHTML = `
        <div class="text-center py-8">
            <svg class="w-12 h-12 text-red-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-red-600">${message}</p>
        </div>
    `;
}

function updateEventsList() {
    const filter = document.getElementById('eventFilter')?.value || 'all';
    const today = new Date();
    let filteredEvents = [...allEvents];
    
    // Apply filter
    if (filter !== 'all') {
        filteredEvents = filteredEvents.filter(event => event.type === filter);
    }
    
    // Sort by date
    filteredEvents.sort((a, b) => new Date(a.date) - new Date(b.date));
    
    // Generate HTML
    let eventsHTML = '';
    
    if (filteredEvents.length === 0) {
        eventsHTML = `
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-purple-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-purple-600">No events found for ${selectedYear}</p>
            </div>
        `;
    } else {
        filteredEvents.forEach(event => {
            const eventDate = new Date(event.date);
            const daysUntil = Math.ceil((eventDate - today) / (1000 * 60 * 60 * 24));
            const isPast = daysUntil < 0;
            const isToday = daysUntil === 0;
            
            let typeBg = '';
            let typeText = '';
            
            if (event.type === 'holiday') {
                typeBg = 'bg-red-100 text-red-800';
                typeText = 'Holiday';
            } else if (event.type === 'cultural') {
                typeBg = 'bg-blue-100 text-blue-800';
                typeText = 'Cultural';
            } else if (event.type === 'seasonal') {
                typeBg = 'bg-green-100 text-green-800';
                typeText = 'Seasonal';
            }
            
            eventsHTML += `
                <div class="p-3 rounded-xl bg-white/80 backdrop-blur-sm border border-purple-200 hover:bg-white hover:shadow-md transition-all cursor-pointer">
                    <div class="flex items-start gap-3">
                        <div class="text-2xl">${event.emoji}</div>
                        <div class="flex-1">
                            <p class="font-semibold text-slate-900">${event.name}</p>
                            <p class="text-sm text-slate-600 mt-1">
                                ${eventDate.toLocaleDateString('en-US', { 
                                    weekday: 'short', 
                                    month: 'short', 
                                    day: 'numeric',
                                    year: 'numeric'
                                })}
                            </p>
                            ${event.source === 'api' ? '<p class="text-xs text-blue-500 mt-1">📡 From API</p>' : ''}
                        </div>
                        <div class="flex flex-col items-end gap-1">
                            <span class="px-2 py-1 text-xs font-medium ${typeBg} rounded-full">
                                ${typeText}
                            </span>
                            <p class="text-xs text-slate-500">
                                ${isPast ? 'Past' : isToday ? '🎉 Today' : `${daysUntil} days`}
                            </p>
                        </div>
                    </div>
                </div>
            `;
        });
    }
    
    document.getElementById('eventsList').innerHTML = eventsHTML;
}

// Event listeners
document.getElementById('prevMonth').addEventListener('click', () => {
    currentMonth--;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    }
    updateCalendar();
});

document.getElementById('nextMonth').addEventListener('click', () => {
    currentMonth++;
    if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    updateCalendar();
});

// Event filter listener
document.getElementById('eventFilter')?.addEventListener('change', updateEventsList);

// Year selector listener
document.getElementById('yearSelector')?.addEventListener('change', (e) => {
    selectedYear = parseInt(e.target.value);
    fetchEventsForYear(selectedYear);
});

// Refresh button listener
document.getElementById('refreshEvents')?.addEventListener('click', () => {
    fetchEventsForYear(selectedYear);
});

// Initialize
initializeYearSelector();
fetchEventsForYear(selectedYear);
updatePhilippineTime();

// Update time every second
setInterval(updatePhilippineTime, 1000);
</script>
@endsection
