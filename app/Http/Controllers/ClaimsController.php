<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class ClaimsController extends Controller
{
    /**
     * Display a listing of claims.
     */
    public function index(Request $request): View
    {
        $query = Claim::with('employee');

        // Apply filters
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('claim_type') && $request->claim_type !== '') {
            $query->where('claim_type', $request->claim_type);
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('employee_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('start_date') && $request->start_date !== '') {
            try {
                $startDate = \Carbon\Carbon::parse($request->start_date);
                $query->whereDate('date', '>=', $startDate);
            } catch (\Exception $e) {
                // Invalid date format, skip this filter
            }
        }

        if ($request->has('end_date') && $request->end_date !== '') {
            try {
                $endDate = \Carbon\Carbon::parse($request->end_date);
                $query->whereDate('date', '<=', $endDate);
            } catch (\Exception $e) {
                // Invalid date format, skip this filter
            }
        }

        $claims = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get statistics directly from database
        $statistics = [
            'pending_claims' => Claim::pending()->count(),
            'approved_claims' => Claim::approved()->count(),
            'rejected_claims' => Claim::rejected()->count(),
            'total_amount' => Claim::sum('amount'),
        ];

        $claimTypes = [
            'travel' => 'Travel',
            'medical' => 'Medical',
            'food' => 'Food',
            'office_supplies' => 'Office Supplies',
            'training' => 'Training',
            'others' => 'Others',
        ];

        $statuses = [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        ];

        return view('admin.claims.claimsandreimbursement', compact('claims', 'statistics', 'claimTypes', 'statuses'));
    }

    /**
     * Show the form for creating a new claim.
     */
    public function create(): View
    {
        $employees = Employee::select('id', 'employee_name')->get();
        $claimTypes = [
            'travel' => 'Travel',
            'medical' => 'Medical',
            'food' => 'Food',
            'office_supplies' => 'Office Supplies',
            'training' => 'Training',
            'others' => 'Others',
        ];

        return view('admin.claims.create', compact('employees', 'claimTypes'));
    }

    /**
     * Store a newly created claim in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'claim_type' => 'required|in:travel,medical,food,office_supplies,training,others',
            'description' => 'required|string|max:1000',
            'amount' => 'required|numeric|min:0|max:999999.99',
            'date' => 'required|date|before_or_equal:today',
            'receipt' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $employee = Employee::find($request->employee_id);
        
        // Handle receipt upload
        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receipt = $request->file('receipt');
            $receiptName = time() . '_' . $receipt->getClientOriginalName();
            $receipt->move(public_path('storage/receipts'), $receiptName);
            $receiptPath = $receiptName;
        }

        Claim::create([
            'employee_id' => $request->employee_id,
            'employee_name' => $employee->employee_name,
            'claim_type' => $request->claim_type,
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
            'status' => 'pending',
            'receipt' => $receiptPath,
        ]);

        return redirect()->route('claims.index')
            ->with('success', 'Claim created successfully.');
    }

    /**
     * Display the specified claim.
     */
    public function show(Claim $claim): View
    {
        $claim->load('employee');
        return view('admin.claims.show', compact('claim'));
    }

    /**
     * Show the form for editing the specified claim.
     */
    public function edit(Claim $claim): View
    {
        $employees = Employee::select('id', 'employee_name')->get();
        $claimTypes = [
            'travel' => 'Travel',
            'medical' => 'Medical',
            'food' => 'Food',
            'office_supplies' => 'Office Supplies',
            'training' => 'Training',
            'others' => 'Others',
        ];

        return view('admin.claims.edit', compact('claim', 'employees', 'claimTypes'));
    }

    /**
     * Update the specified claim in storage.
     */
    public function update(Request $request, Claim $claim): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'claim_type' => 'required|in:travel,medical,food,office_supplies,training,others',
            'description' => 'required|string|max:1000',
            'amount' => 'required|numeric|min:0|max:999999.99',
            'date' => 'required|date|before_or_equal:today',
            'receipt' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $employee = Employee::find($request->employee_id);
        
        // Handle receipt upload
        $receiptPath = $claim->receipt; // Keep existing receipt
        if ($request->hasFile('receipt')) {
            // Delete old receipt if exists
            if ($claim->receipt && file_exists(public_path('storage/receipts/' . $claim->receipt))) {
                unlink(public_path('storage/receipts/' . $claim->receipt));
            }
            
            $receipt = $request->file('receipt');
            $receiptName = time() . '_' . $receipt->getClientOriginalName();
            $receipt->move(public_path('storage/receipts'), $receiptName);
            $receiptPath = $receiptName;
        }

        $claim->update([
            'employee_id' => $request->employee_id,
            'employee_name' => $employee->employee_name,
            'claim_type' => $request->claim_type,
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
            'receipt' => $receiptPath,
        ]);

        return redirect()->route('claims.index')
            ->with('success', 'Claim updated successfully.');
    }

    /**
     * Update the status of the specified claim.
     */
    public function updateStatus(Request $request, Claim $claim): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,approved,rejected',
            'remarks' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $claim->update([
            'status' => $request->status,
            'remarks' => $request->remarks,
        ]);

        $statusMessage = [
            'approved' => 'Claim approved successfully.',
            'rejected' => 'Claim rejected successfully.',
            'pending' => 'Claim status updated to pending.',
        ][$request->status];

        return redirect()->route('claims.index')
            ->with('success', $statusMessage);
    }

    /**
     * Remove the specified claim from storage.
     */
    public function destroy(Claim $claim): RedirectResponse
    {
        $claim->delete();

        return redirect()->route('claims.index')
            ->with('success', 'Claim deleted successfully.');
    }

    /**
     * Get claims data for API/JSON response.
     */
    public function getClaims(Request $request): JsonResponse
    {
        $query = Claim::with('employee');

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by claim type
        if ($request->has('claim_type') && $request->claim_type !== '') {
            $query->where('claim_type', $request->claim_type);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        // Search by employee name or description
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('employee_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $claims = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($claims);
    }

    /**
     * Get claim statistics.
     */
    public function getStatistics(): JsonResponse
    {
        $stats = [
            'total_claims' => Claim::count(),
            'pending_claims' => Claim::pending()->count(),
            'approved_claims' => Claim::approved()->count(),
            'rejected_claims' => Claim::rejected()->count(),
            'total_amount' => Claim::sum('amount'),
            'approved_amount' => Claim::approved()->sum('amount'),
            'claims_by_type' => Claim::selectRaw('claim_type, COUNT(*) as count, SUM(amount) as total')
                ->groupBy('claim_type')
                ->get(),
        ];

        return response()->json($stats);
    }

    /**
     * Search employees for claim creation.
     */
    public function searchEmployees(Request $request): JsonResponse
    {
        $search = $request->get('search', '');
        
        $employees = Employee::select('id', 'employee_name', 'employee_id', 'department', 'position')
            ->where('employee_name', 'like', "%{$search}%")
            ->limit(10)
            ->get();

        return response()->json($employees);
    }

    /**
     * Export claims to CSV.
     */
    public function exportCsv(Request $request)
    {
        $query = Claim::query();
        
        // Apply same filters as index method
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        
        if ($request->filled('claim_type')) {
            $query->where('claim_type', $request->input('claim_type'));
        }
        
        if ($request->filled('employee_search')) {
            $searchTerm = $request->input('employee_search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('employee_name', 'like', "%{$searchTerm}%")
                  ->orWhere('employee_id', 'like', "%{$searchTerm}%");
            });
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->input('date_from'));
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->input('date_to'));
        }
        
        $claims = $query->orderBy('created_at', 'desc')->get();
        
        // Create CSV content
        $csvContent = "\xEF\xBB\xBF"; // UTF-8 BOM for Excel
        $csvContent .= "Employee ID,Employee Name,Claim Type,Description,Amount,Date,Status,Created At\n";
        
        foreach ($claims as $claim) {
            $csvContent .= implode(',', [
                $claim->employee_id ?? '',
                '"' . str_replace('"', '""', $claim->employee_name ?? '') . '"',
                '"' . ucfirst(str_replace('_', ' ', $claim->claim_type ?? '')) . '"',
                '"' . str_replace('"', '""', $claim->description ?? '') . '"',
                $claim->amount ?? 0,
                '"' . ($claim->date ? $claim->date->format('m/d/Y') : '') . '"',
                '"' . ucfirst($claim->status ?? '') . '"',
                '"' . ($claim->created_at ? $claim->created_at->format('m/d/Y h:i A') : '') . '"'
            ]) . "\n";
        }
        
        // Generate filename with current date
        $filename = 'claims_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        // Return CSV download
        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
