<?php

namespace App\Http\Controllers\Employer;

use App\Events\InterviewAppointmentConfirmed;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\BulkDestroyAppointmentSuggestionRequest;
use App\Http\Requests\StoreInterviewSuggestionRequest;
use App\Http\Requests\UpdateInterviewSuggestionRequest;
use App\Http\Resources\InterviewAppointmentSuggestionResource;
use App\Models\InterviewAppointmentSuggestion;
use App\Models\User;
use Illuminate\Http\Request;
//Inertia
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Carbon;

class EmployerSuggestionController extends Controller
{
    use AuthorizesRequests;
    public function __construct() {}
    public function index(Request $request)
    {
        $perPage = $request->integer('per_page', 5);
        $me      = $request->user()->id;
        // get all candiates, meaning users that are not is_applicant
        $canditates = User::where('is_applicant', true)
            ->where('id', '!=', $me) // exclude myself
            ->orderBy('name')
            ->get(['id', 'name']);
        $paginator = InterviewAppointmentSuggestion::with(['candidate', 'employer'])
            ->orderByRaw('employer_id = ? desc', [$me]) // put my suggestions first
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();
        // wrap the whole paginator in a ResourceCollection:
        $collection = InterviewAppointmentSuggestionResource::collection($paginator);
        // **then** pass the RESPONDER’s array form to Inertia:
        return Inertia::render('Employer/Suggestions/Index', [
            'suggestions' => $collection->response()
                ->getData(true),
            'candidates' => $canditates,
        ]);
    }
    public function store(StoreInterviewSuggestionRequest $request)
    {
        $dt       = $request->input('suggested_at');
        $candidate_id = $request->input('candidate_id');
        $employer = $request->user();
        try {
            InterviewAppointmentSuggestion::create([
                'employer_id'         => $employer->id,
                'suggested_date_time' => $dt,
                'appointment_status'  => 'draft',
                'candidate_id'        => $candidate_id,
            ]);
        } catch (QueryException $e) {
            // last-resort race-condition catch
            if ($e->getCode() === '23505') {
                throw ValidationException::withMessages([
                    'suggested_at' => 'That slot is no longer available.',
                ]);
            }
            throw $e;
        }
        return back()->with('success', 'Slot proposed!');
    }
    public function update(UpdateInterviewSuggestionRequest $r, InterviewAppointmentSuggestion $suggestion)
    {
        $this->authorize('update', $suggestion);
        $wasConfirmed = $r->appointment_status === 'confirmed'
            && $suggestion->appointment_status !== 'confirmed';
        if ($wasConfirmed) {
            $suggestion->confirm(Carbon::parse($r->suggested_at));
        }
        return back()->with('success', 'Slot updated.');
    }
    public function destroy(InterviewAppointmentSuggestion $suggestion)
    {
        $this->authorize('delete', $suggestion);
        abort_if($suggestion->employer_id !== request()->user()->id, 403);
        $suggestion->delete();
        // Inertia will see this redirect and re-render your Index page with
        // the updated suggestions payload (minus the deleted one)
        return redirect()
            ->route('employer.home')
            ->with('success', 'Slot removed.');
    }
    /**
     * Delete one *or* many suggestions belonging to the authenticated employer.
     * Expects DELETE /employer/suggestions with JSON  { ids: [1,2,3] }
     */
    public function bulkDestroy(BulkDestroyAppointmentSuggestionRequest $request)
    {
        // Option A – policy already OK’d; ids are validated
        $deleted = InterviewAppointmentSuggestion::whereIn('id', $request->validated('ids'))->delete();
        return back()->with(
            'success',
            sprintf('%d slot%s removed.', $deleted, $deleted === 1 ? '' : 's')
        );
    }
}
