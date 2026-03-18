<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserApprovalController extends Controller
{
    public function index(): View
    {
        $pendingUsers = User::query()
            ->where('is_superadmin', false)
            ->where('is_approved', false)
            ->orderBy('created_at')
            ->get();

        return view('admin.pending-users', [
            'pendingUsers' => $pendingUsers,
        ]);
    }

    public function approve(User $user): RedirectResponse
    {
        if ($user->is_superadmin) {
            return redirect()
                ->route('admin.users.pending')
                ->with('status', 'Superadmin accounts are always approved.');
        }

        $user->update([
            'is_approved' => true,
        ]);

        return redirect()
            ->route('admin.users.pending')
            ->with('status', "{$user->full_name} approved successfully.");
    }
}
