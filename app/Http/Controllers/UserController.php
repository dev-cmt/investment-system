<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of clients/users.
     */
    public function index(Request $request): View
    {
        $query = User::with('roles')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(10)->withQueryString();
        $roles = Role::orderBy('name')->get();

        return view('backend.users.index', compact('users', 'roles'));
    }

    /**
     * Show form for creating a new user/client.
     */
    public function create(): View
    {
        $roles = Role::orderBy('name')->get();
        return view('backend.users.create', compact('roles'));
    }

    /**
     * Store a newly created user/client in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|string|exists:roles,name',
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = ImageHelper::uploadImage($request->file('avatar'), 'uploads/profiles');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'password' => Hash::make($validated['password']),
            'avatar' => $avatarPath,
        ]);

        if (!empty($validated['role'])) {
            $user->assignRole($validated['role']);
        } else {
            $user->assignRole('investor');
        }

        return redirect()->route('users.index')->with('success', 'Client account created successfully.');
    }

    /**
     * Show form for editing user/client.
     */
    public function edit(User $user): View
    {
        $roles = Role::orderBy('name')->get();
        $userRole = $user->roles->first()?->name;
        return view('backend.users.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update user/client in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'nullable|string|exists:roles,name',
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ]);

        if ($request->hasFile('avatar')) {
            $user->avatar = ImageHelper::uploadImage($request->file('avatar'), 'uploads/profiles', $user->avatar);
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->bio = $validated['bio'] ?? null;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        if (!empty($validated['role'])) {
            $user->syncRoles([$validated['role']]);
        }

        return redirect()->route('users.index')->with('success', 'Client account updated successfully.');
    }

    /**
     * Remove the specified user/client.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }

        if ($user->avatar) {
            ImageHelper::deleteImage($user->avatar);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Client account deleted successfully.');
    }
}
