<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuditController extends Controller
{
    /**
     * Display the audit logs page.
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('user');
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('description', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('action', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('subject_type', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'LIKE', '%' . $searchTerm . '%')
                               ->orWhere('email', 'LIKE', '%' . $searchTerm . '%');
                  });
            });
        }
        
        // Filter by action
        if ($request->has('action') && $request->action != '') {
            $query->where('action', $request->action);
        }
        
        // Filter by user
        if ($request->has('user_id') && $request->user_id != '') {
            $query->where('user_id', $request->user_id);
        }
        
        $auditLogs = $query->latest()->paginate(5);
        $users = User::orderBy('name')->get();
        
        return view('admin.audit.index', compact('auditLogs', 'users'));
    }
    
    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.audit.create');
    }
    
    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
        ]);
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'department' => $request->department,
            'position' => $request->position,
        ]);
        
        return redirect()->route('audit.index')
            ->with('success', 'User created successfully.');
    }
    
    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.audit.edit', compact('user'));
    }
    
    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'department' => $request->department,
            'position' => $request->position,
        ];
        
        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }
        
        $user->update($updateData);
        
        return redirect()->route('audit.index')
            ->with('success', 'User updated successfully.');
    }
    
    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        
        return redirect()->route('audit.index')
            ->with('success', 'User deleted successfully.');
    }
}
