@extends('layouts.app')

@section('title', 'Create New Claim')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Create New Claim</h1>
        <p class="text-slate-600 mt-1">Submit a new reimbursement claim</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <form method="POST" action="{{ route('claims.store') }}" class="p-6" enctype="multipart/form-data">
            @csrf
            
            <!-- Employee Selection -->
            <div class="mb-6">
                <label for="employee_id" class="block text-sm font-medium text-slate-700 mb-2">Employee</label>
                <select id="employee_id" name="employee_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    <option value="">Select an employee</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->employee_name }}</option>
                    @endforeach
                </select>
                @error('employee_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Claim Type -->
            <div class="mb-6">
                <label for="claim_type" class="block text-sm font-medium text-slate-700 mb-2">Claim Type</label>
                <select id="claim_type" name="claim_type" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    <option value="">Select claim type</option>
                    @foreach($claimTypes as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('claim_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                <textarea id="description" name="description" rows="4" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Provide details about this claim..." required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Amount and Date -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="amount" class="block text-sm font-medium text-slate-700 mb-2">Amount ($)</label>
                    <input type="number" id="amount" name="amount" step="0.01" min="0" max="999999.99" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0.00" value="{{ old('amount') }}" required>
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="date" class="block text-sm font-medium text-slate-700 mb-2">Date</label>
                    <input type="date" id="date" name="date" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="{{ old('date') }}" required>
                    @error('date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Receipt Upload -->
            <div class="mb-6">
                <label for="receipt" class="block text-sm font-medium text-slate-700 mb-2">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Receipt (Optional)
                </label>
                <div class="border-2 border-dashed border-slate-300 rounded-lg p-4 text-center hover:border-blue-400 transition-colors">
                    <input type="file" id="receipt" name="receipt" accept="image/*,.pdf" class="hidden" onchange="handleReceiptSelect(this)">
                    <label for="receipt" class="cursor-pointer">
                        <div id="receiptPreview" class="mb-3">
                            <svg class="w-12 h-12 mx-auto text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-slate-600">Click to upload receipt</p>
                        <p class="text-xs text-slate-500 mt-1">PNG, JPG, PDF up to 10MB</p>
                    </label>
                </div>
                @error('receipt')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between">
                <a href="{{ route('claims.index') }}" class="px-4 py-2 text-slate-600 hover:text-slate-900 transition-colors duration-200">
                    Cancel
                </a>
                <div class="flex gap-3">
                    <button type="reset" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors duration-200">
                        Clear Form
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Submit Claim
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set max date to today
    const dateInput = document.getElementById('date');
    const today = new Date().toISOString().split('T')[0];
    dateInput.setAttribute('max', today);
});

function handleReceiptSelect(input) {
    const file = input.files[0];
    const preview = document.getElementById('receiptPreview');
    
    if (file) {
        // Check file size (10MB limit)
        if (file.size > 10 * 1024 * 1024) {
            alert('File size must be less than 10MB');
            input.value = '';
            return;
        }
        
        // Check file type
        if (!file.type.match('image.*') && file.type !== 'application/pdf') {
            alert('Please upload an image or PDF file');
            input.value = '';
            return;
        }
        
        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            if (file.type === 'application/pdf') {
                preview.innerHTML = `
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                        <svg class="w-8 h-8 mx-auto text-red-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-sm font-medium text-red-800">${file.name}</p>
                        <p class="text-xs text-red-600">PDF Document</p>
                    </div>
                `;
            } else {
                preview.innerHTML = `
                    <img src="${e.target.result}" alt="Receipt preview" class="max-h-32 mx-auto rounded-lg shadow-md">
                    <p class="text-xs text-slate-600 mt-2">${file.name}</p>
                `;
            }
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
