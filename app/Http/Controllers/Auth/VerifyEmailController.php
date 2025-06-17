<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        // redirect route based on wether is_applicant or not
        $redirectRoute = $request->user()->is_applicant ? 'applicant.home' : 'employer.home';
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route($redirectRoute, absolute: false) . '?verified=1');
        }
        if ($request->user()->markEmailAsVerified()) {
            /** @var \Illuminate\Contracts\Auth\MustVerifyEmail $user */
            $user = $request->user();
            event(new Verified($user));
        }
        return redirect()->intended(route($redirectRoute, absolute: false) . '?verified=1');
    }
}
