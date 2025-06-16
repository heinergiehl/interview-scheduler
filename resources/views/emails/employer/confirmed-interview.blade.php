{{-- resources/views/emails/employer/confirmed-interview.blade.php --}}
@component('mail::message')
# Interview Date Confirmed
Hi {{ $suggestion->employer->name }},<br><br>
You have a confirmed interview with **{{ $suggestion->candidate->name }}** on **{{ \Carbon\Carbon::parse($suggestion->suggested_date_time)->format('F j, Y \\a\\t g:i A') }}**.
Wait, until the applicant will accept this date. If not, you can always suggest a new date.<br><br>
<br><br>
Thanks,<br>
Heiner from {{ config('app.name') }}
@endcomponent