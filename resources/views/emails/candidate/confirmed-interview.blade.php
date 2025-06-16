{{-- resources/views/emails/candidate/confirmed-interview.blade.php --}}
@component('mail::message')
# Interview Date Confirmed
Hi {{ $suggestion->candidate->name }},<br><br>
Your interview with **{{ $suggestion->employer->name }}** on **{{ \Carbon\Carbon::parse($suggestion->suggested_date_time)->format('F j, Y \\a\\t g:i A') }}** has been confirmed.<br><br>
Thanks,<br>
Heiner from {{ config('app.name') }}
@endcomponent