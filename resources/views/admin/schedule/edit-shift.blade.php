@extends('layouts.app')

@section('title', 'Edit Shift')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Edit Shift</h1>
        <p class="text-slate-600 mt-1">Modify shift schedule for {{ $shift->employee_name }}</p>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <form action="/shifts/{{ $shift->id }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Employee Information -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Employee Name</label>
                        <input type="text" value="{{ $shift->employee_name }}" readonly 
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50 text-slate-600">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Shift Type</label>
                        <select name="shift_type" required 
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="Morning Shift" {{ $shift->shift_type === 'Morning Shift' ? 'selected' : '' }}>
                                Morning Shift
                            </option>
                            <option value="Afternoon Shift" {{ $shift->shift_type === 'Afternoon Shift' ? 'selected' : '' }}>
                                Afternoon Shift
                            </option>
                            <option value="Night Shift" {{ $shift->shift_type === 'Night Shift' ? 'selected' : '' }}>
                                Night Shift
                            </option>
                        </select>
                    </div>
                </div>
                
                <!-- Schedule Information -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Start Time</label>
                        <input type="time" name="schedule_start" value="{{ $shift->schedule_start }}" required
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">End Time</label>
                        <input type="time" name="schedule_end" value="{{ $shift->schedule_end }}" required
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>
            
            <!-- Days Selection -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-slate-700 mb-3">Working Days</label>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="days[]" value="{{ $day }}" 
                                   {{ in_array($day, $shiftDays) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                            <span class="text-sm text-slate-700">{{ $day }}</span>
                        </label>
                    @endforeach
                </div>
                @error('days')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Form Actions -->
            <div class="mt-8 flex items-center justify-between border-t border-slate-200 pt-6">
                <a href="{{ route('schedule.management') }}" 
                   class="px-4 py-2 text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors duration-200">
                    Cancel
                </a>
                <div class="space-x-3">
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        Update Shift
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
