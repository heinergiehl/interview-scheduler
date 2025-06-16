@component('mail::message')
# Interview Date Accepted
Hi {{ $suggestion->candidate->name }},<br><br>
Your interview with **{{ $suggestion->employer->name }}** on **{{ \Carbon\Carbon::parse($suggestion->suggested_date_time)->format('F j, Y \\a\\t g:i A') }}** has been accepted.<br><br>
Thanks,<br>
Heiner from {{ config('app.name') }}
@endcomponent