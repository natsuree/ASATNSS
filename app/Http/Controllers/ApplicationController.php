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

    public function index(Request $request): View|RedirectResponse
    {
        if ($redirect = $this->redirectAdminToReview($request)) {
            return $redirect;
        }

        return view('applications.index', [
            'applications' => $request->user()->applications()->latest()->paginate(10),
            'notifications' => $request->user()->notifications()->latest()->limit(5)->get(),
        ]);
    }

    public function create(Request $request): View|RedirectResponse
    {
        if ($redirect = $this->redirectAdminToReview($request)) {
            return $redirect;
        }

        return view('applications.create', [
            'profile' => $request->user()->profile,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($redirect = $this->redirectAdminToReview($request)) {
            return $redirect;
        }

        $user = $request->user();

        $validated = $request->validate([
            'student_id' => ['required', 'regex:/^[0-9\-]+$/'],
            'course' => ['required', 'string', 'max:255'],
            'year_level' => ['required', 'string', 'max:50'],
            'scholarship_type' => ['required', 'string', 'max:255'],
            'reason_for_applying' => ['nullable', 'string', 'max:5000'],
        ], [
            'student_id.required' => "Student Number must contain only numbers and '-' (no letters).",
            'student_id.regex' => "Student Number must contain only numbers and '-' (no letters).",
        ]);

        $emailValidation = $this->emailValidationService->validate($user->email);

        if (! $emailValidation['valid']) {
            Log::warning('Rejected manual application with suspicious email.', [
                'email' => $user->email,
                'reason' => $emailValidation['reason'],
            ]);

            return back()
                ->withInput()
                ->withErrors(['email' => $emailValidation['reason']]);
        }

        $application = $user->applications()->create([
            ...$validated,
            'full_name' => $user->name,
            'email' => $user->email,
        ]);

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Application submitted',
            'message' => 'Your '.$application->scholarship_type.' scholarship application is now pending review.',
            'status' => 'Unread',
            'is_read' => false,
        ]);

        return redirect()->route('applications.index')->with('status', 'application-submitted');
    }

    public function show(Request $request, Application $application): View|RedirectResponse
    {
        if ($redirect = $this->redirectAdminToReview($request)) {
            return $redirect;
        }

        abort_unless($application->user_id === $request->user()->id, 403);

        return view('applications.show', [
            'application' => $application,
        ]);
    }

    public function destroy(Request $request, Application $application): RedirectResponse
    {
        if ($redirect = $this->redirectAdminToReview($request)) {
            return $redirect;
        }

        abort_unless($application->user_id === $request->user()->id, 403);
        abort_unless($application->status === Application::STATUS_PENDING, 403);

        $application->delete();

        return redirect()->route('applications.index')->with('status', 'application-deleted');
    }

    private function redirectAdminToReview(Request $request): ?RedirectResponse
    {
        if ($request->user()?->is_admin) {
            return redirect()->route('admin.applications.index');
        }

        return null;
    }
}
