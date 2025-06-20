<?php

use App\Http\Controllers\Employer\EmployerSuggestionController;
use App\Http\Controllers\Applicant\ApplicantSuggestionController;
use App\Mail\CandidateInterviewAcceptedMail;
use App\Models\InterviewAppointmentSuggestion;
use Illuminate\Http\Request;
use Inertia\Inertia;

Route::middleware(['auth'])->group(function () {
    /* Employer ­------------------------------------------------------ */
    Route::middleware('can:employer')
        ->prefix('employer')
        ->name('employer.')
        ->group(function () {
            Route::get('suggestions',  [EmployerSuggestionController::class, 'index'])->name('home');
            Route::post('suggestions', [EmployerSuggestionController::class, 'store'])->name('suggestions.store');
            Route::put(
                'suggestions/{suggestion}',
                [EmployerSuggestionController::class, 'update']
            )->name('suggestions.update');
            Route::delete(
                'suggestions/{suggestion}',
                [EmployerSuggestionController::class, 'destroy']
            )->name('suggestions.destroy');
            // NEW: bulk delete  (same controller, different method)
            Route::delete(
                '/suggestions',
                [EmployerSuggestionController::class, 'bulkDestroy']
            )->name('suggestions.bulkDestroy');
        });
    /* Applicant ­--------------------------------------------------------- */
    Route::middleware('can:applicant')
        ->prefix('applicant')
        ->name('applicant.')
        ->group(function () {
            Route::get('suggestions', [ApplicantSuggestionController::class, 'index'])->name('home');
            Route::put(
                'suggestions/{suggestion}/accept',
                [ApplicantSuggestionController::class, 'accept']
            )->name('suggestions.accept');
            Route::put(
                'suggestions/{suggestion}/decline',
                [ApplicantSuggestionController::class, 'decline']
            )->name('suggestions.decline');
        });
});
Route::get('/email-test', function (Request $request) {
    return new CandidateInterviewAcceptedMail(
        InterviewAppointmentSuggestion::create(
            [
                'employer_id' => 10,
                'candidate_id' => 9, // assuming a candidate exists
                'suggested_date_time' => now()->addDays(2),
                'appointment_status' => 'confirmed',
            ]
        )
    );
})->name('email.test');
Route::get('/', fn() => Inertia::render('Home'))->name('landing-page');
// require auth routes
require __DIR__ . '/auth.php';
// require settings routes
require __DIR__ . '/settings.php';
