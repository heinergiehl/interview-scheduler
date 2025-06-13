{{-- resources/views/emails/employer/confirmed-interview.blade.php --}}
@component('mail::message')
# Interview Date Confirmed
You’ve just confirmed an interview with **{{ $suggestion->candidate->name }}**.
| Detail | Information |
| --------------- |--------------------------------|
| **Candidate** | {{ $suggestion->candidate->name }} |
| **Date & Time** | {{ $suggestion->suggested_date_time }} |
| **Confirmed By**| {{ $suggestion->employer->name }} |
@component('mail::button', ['url' => route('employer.suggestions.index', $suggestion)])
View Interview Details
@endcomponent
Thanks,<br>
**{{ config('app.name') }} Team**
@endcomponent