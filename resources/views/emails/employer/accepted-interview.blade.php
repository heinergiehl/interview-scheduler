{{-- resources/views/emails/employer/confirmed-interview.blade.php --}}
@component('mail::message')
# Interview Date Accepted
Hi {{ $suggestion->employer->name }},<br><br>
Your interview with **{{ $suggestion->candidate->name }}** on **{{ \Carbon\Carbon::parse($suggestion->suggested_date_time)->format('F j, Y \\a\\t g:i A') }}** has been accepted
.<br><br>
Thanks,<br>
Heiner from {{ config('app.name') }}
@endcomponent