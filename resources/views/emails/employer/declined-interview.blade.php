{{-- resources/views/emails/employer/declined-interview.blade.php --}}
@component('mail::message')
# Interview Date Declined
Hi {{ $suggestion->employer->name }},<br><br>
The candidate **{{ $suggestion->candidate->name }}** has declined the interview date you suggested on **{{ \Carbon\Carbon::parse($suggestion->suggested_date_time)->format('F j, Y \\a\\t g:i A') }}**.<br>
You can suggest a new date for the interview.<br><br>
Thanks,<br>
Heiner from {{ config('app.name') }}
@endcomponent