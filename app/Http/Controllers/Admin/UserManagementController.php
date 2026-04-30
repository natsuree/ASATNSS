<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::query()->orderByDesc('is_admin')->orderBy('name')->paginate(15),
        ]);
    }

    public function promote(User $user): RedirectResponse
    {
        $user->update(['is_admin' => true]);

        return redirect()->route('admin.users.index')->with('status', 'user-promoted');
    }

    public function demote(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return redirect()
                ->route('admin.users.index')
                ->with('status', 'self-demotion-blocked');
        }

        $user->update(['is_admin' => false]);

        return redirect()->route('admin.users.index')->with('status', 'user-demoted');
    }
}
