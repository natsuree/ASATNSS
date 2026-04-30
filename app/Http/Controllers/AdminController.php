<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(Request $request): View
    {
        $applications = Application::with('user')->latest()->get();

        return view('admin.applications.index', [
            'applications' => $applications,
            'pendingCount' => Application::where('status', Application::STATUS_PENDING)->count(),
            'approvedCount' => Application::where('status', Application::STATUS_APPROVED)->count(),
            'rejectedCount' => Application::where('status', Application::STATUS_REJECTED)->count(),
            'notifications' => Notification::with('user')->latest()->limit(8)->get(),
        ]);
    }

    public function update(Request $request, Application $application): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:Pending,Approved,Rejected'],
        ]);

        DB::transaction(function () use ($application, $validated) {
            $application->update($validated);

            Notification::create([
                'user_id' => $application->user_id,
                'title' => 'Application '.$application->status,
                'message' => 'Your scholarship application status was updated to '.$application->status.'.',
                'status' => 'Unread',
                'is_read' => false,
            ]);
        });

        return redirect()->route('admin.applications.index')->with('status', 'application-updated');
    }
}
