<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmailVerificationPromptController extends Controller
{
    /**
     * Show the email verification prompt page.
     */
    public function __invoke(Request $request): RedirectResponse|Response
    {
        return $request->user()->hasVerifiedEmail()
            ? ($request->user()->is_applicant
                ? redirect()->intended(route('applicant.home', absolute: false))
                : redirect()->intended(route('employer.home', absolute: false)))
            : Inertia::render('auth/VerifyEmail', ['status' => $request->session()->get('status')]);
    }
}
