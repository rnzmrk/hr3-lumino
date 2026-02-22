@extends('layouts.app')

@section('title', 'Create Overtime Request')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Create Overtime Request</h1>
                <p class="text-gray-600 mt-2">Submit a new overtime request</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('overtime-requests.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    ← Back to Overtime Requests
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('overtime-requests.store') }}" method="POST">
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

                <!-- Date -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                        Overtime Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="date" name="date" required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Hours -->
                <div>
                    <label for="hours" class="block text-sm font-medium text-gray-700 mb-2">
                        Overtime Hours <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="hours" name="hours" required step="0.5" min="0.5" max="12" placeholder="e.g., 2.5"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Enter overtime hours (e.g., 2.5 for 2.5 hours)</p>
                </div>

                <!-- Reason -->
                <div class="md:col-span-2">
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Reason <span class="text-red-500">*</span>
                    </label>
                    <textarea id="reason" name="reason" rows="4" required
                              class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Please provide a reason for the overtime request..."></textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Submit Overtime Request
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
