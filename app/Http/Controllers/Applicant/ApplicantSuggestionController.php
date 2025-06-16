<?php
// app/Http/Controllers/Applicant/SuggestionController.php
namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Http\Resources\InterviewAppointmentSuggestionResource;
use App\Models\InterviewAppointmentSuggestion;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ApplicantSuggestionController extends Controller
{
    /* ------------------------------------------------------------------
     |  GET /applicant/suggestions
     |  Lists all PENDING suggestions with employer info
     *------------------------------------------------------------------*/
    public function index(Request $request)
    {
        // get all candiates, meaning users that are not is_applicant
        $me = $request->user()->id; // get my user ID
        $canditates = User::where('is_applicant', true)
            ->where('id', '!=', $me) // exclude myself
            ->orderBy('name')
            ->get(['id', 'name']);
        $perPage = $request->integer('per_page', 5);
        $paginator = InterviewAppointmentSuggestion::with(['user', 'candidate', 'employer'])
            ->where('appointment_status', 'confirmed')
            ->orWhere('appointment_status', 'accepted')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();
        // wrap the whole paginator in a ResourceCollection:
        $collection = InterviewAppointmentSuggestionResource::collection($paginator);
        // **then** pass the RESPONDER’s array form to Inertia:
        return Inertia::render('Applicant/Suggestions/Index', [
            'suggestions' => $collection->response()
                ->getData(true),
            'candidates'  => $canditates,
        ]);
    }
    /* ------------------------------------------------------------------
     |  POST /applicant/suggestions/{suggestion}/accept
     |  Confirms the slot – employers get notified via model method
     *------------------------------------------------------------------*/
    public function accept(InterviewAppointmentSuggestion $suggestion)
    {
        abort_if($suggestion->status === 'accepted', 404);      // already handled?
        $suggestion->accept();                                 // model handles tx + notifications
        return back()->with('success', 'Interview scheduled.');
    }
    /* ------------------------------------------------------------------
     |  POST /applicant/suggestions/{suggestion}/decline
     |  Marks a slot as declined
     *------------------------------------------------------------------*/
    public function decline(InterviewAppointmentSuggestion $suggestion)
    {
        if ($suggestion->appointment_status === 'confirmed') {
            $suggestion->decline(); // model handles tx + notifications
        }
        return back()->with('success', 'Slot declined.');
    }
}
