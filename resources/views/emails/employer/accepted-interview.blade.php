@component('mail::message')
# Interview Slot Accepted
Hello {{ $suggestion->employer->name }},
Good news—the interview slot you proposed has been **accepted**.
**Interview Details**
| Field | Info |
|-------------------|----------------------------------------------------------------|
| **Interview ID** | {{ $suggestion->id }} |
| **Date & Time** | {{ \Carbon\Carbon::parse($suggestion->suggested_date_time)->format('F j, Y \\a\\t g:i A') }} |
| **Candidate** | {{ $suggestion->candidate->name }} |
Thanks for coordinating,
{{ config('app.name') }}
@endcomponent