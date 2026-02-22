<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of leave types.
     */
    public function index()
    {
        $leaves = Leave::latest()->get();
        return view('admin.leaves.leave-types.index', compact('leaves'));
    }

    /**
     * Show the form for creating a new leave type.
     */
    public function create()
    {
        return view('admin.leaves.leave-types.create');
    }

    /**
     * Store a newly created leave type in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'leave_name' => 'required|string|max:255',
            'leave_description' => 'nullable|string|max:1000',
            'leave_duration' => 'required|string|max:255'
        ]);

        $leave = Leave::create([
            'leave_name' => $request->leave_name,
            'leave_description' => $request->leave_description,
            'leave_duration' => $request->leave_duration
        ]);

        return redirect()->route('leave-types.index')
            ->with('success', "✅ Leave type '{$leave->leave_name}' created successfully! Duration: {$leave->leave_duration}");
    }

    /**
     * Display the specified leave type.
     */
    public function show(Leave $leave)
    {
        return view('admin.leaves.leave-types.show', compact('leave'));
    }

    /**
     * Show the form for editing the specified leave type.
     */
    public function edit(Leave $leave)
    {
        return view('admin.leaves.leave-types.edit', compact('leave'));
    }

    /**
     * Update the specified leave type in storage.
     */
    public function update(Request $request, Leave $leave)
    {
        $request->validate([
            'leave_name' => 'required|string|max:255',
            'leave_description' => 'nullable|string|max:1000',
            'leave_duration' => 'required|string|max:255'
        ]);

        $leave->update([
            'leave_name' => $request->leave_name,
            'leave_description' => $request->leave_description,
            'leave_duration' => $request->leave_duration
        ]);

        return redirect()->route('leave-types.index')
            ->with('success', "✅ Leave type '{$leave->leave_name}' updated successfully! Duration: {$leave->leave_duration}");
    }

    /**
     * Remove the specified leave type from storage.
     */
    public function destroy(Leave $leave)
    {
        $leaveName = $leave->leave_name;
        $leave->delete();

        return redirect()->route('leave-types.index')
            ->with('success', "🗑️ Leave type '{$leaveName}' deleted successfully!");
    }
}
