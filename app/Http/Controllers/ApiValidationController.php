<?php

namespace App\Http\Controllers;

use App\Services\EmailValidationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApiValidationController extends Controller
{
    public function __construct(private readonly EmailValidationService $emailValidationService)
    {
    }

    public function email(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        return response()->json($this->emailValidationService->validate($validated['email']));
    }
}
