<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Notification;
use App\Services\EmailValidationService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function __construct(private readonly EmailValidationService $emailValidationService)
    {
    }

    public function index(Request $request): View
    {
        return view('applications.index', [
            'applications' => $request->user()->applications()->latest()->paginate(10),
            'notifications' => $request->user()->notifications()->latest()->limit(5)->get(),
        ]);
    }

    public function create(Request $request): View
    {
        return view('applications.create', [
            'profile' => $request->user()->profile,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'student_id' => ['required', 'string', 'max:50'],
            'course' => ['required', 'string', 'max:255'],
            'year_level' => ['required', 'string', 'max:50'],
            'scholarship_type' => ['required', 'string', 'max:255'],
            'reason_for_applying' => ['nullable', 'string', 'max:5000'],
        ]);

        $emailValidation = $this->emailValidationService->validate($validated['email']);

        if (! $emailValidation['valid']) {
            Log::warning('Rejected manual application with suspicious email.', [
                'email' => $validated['email'],
                'reason' => $emailValidation['reason'],
            ]);

            return back()
                ->withInput()
                ->withErrors(['email' => $emailValidation['reason']]);
        }

        $application = $request->user()->applications()->create($validated);

        Notification::create([
            'user_id' => $request->user()->id,
            'title' => 'Application submitted',
            'message' => 'Your '.$application->scholarship_type.' scholarship application is now pending review.',
            'status' => 'Unread',
            'is_read' => false,
        ]);

        return redirect()->route('applications.index')->with('status', 'application-submitted');
    }

    public function show(Request $request, Application $application): View
    {
        abort_unless($application->user_id === $request->user()->id, 403);

        return view('applications.show', [
            'application' => $application,
        ]);
    }

    public function destroy(Request $request, Application $application): RedirectResponse
    {
        abort_unless($application->user_id === $request->user()->id, 403);
        abort_unless($application->status === Application::STATUS_PENDING, 403);

        $application->delete();

        return redirect()->route('applications.index')->with('status', 'application-deleted');
    }
}
