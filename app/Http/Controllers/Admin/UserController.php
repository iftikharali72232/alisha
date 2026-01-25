<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function toggleStatus(User $user)
    {
        $user->update(['status' => $user->status == 1 ? 0 : 1]);
        return redirect()->back()->with('success', 'User status updated successfully.');
    }

    public function toggleAdmin(User $user)
    {
        $user->update(['is_admin' => !$user->is_admin]);
        return redirect()->back()->with('success', 'User admin privileges updated successfully.');
    }
}
