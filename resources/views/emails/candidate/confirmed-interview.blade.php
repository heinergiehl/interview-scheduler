{{-- resources/views/emails/candidate/confirmed-interview.blade.php --}}
<x-mail::message>
    # Interview Confirmed
    Hi {{ $suggestion->candidate->name }},
    Your interview has been scheduled for
    **{{ $suggestion->suggested_date_time }}**.
    {{-- if you have a URL to view the details, you can add a button like this: --}}
    <x-mail::button :url="route('employer.suggestions.index', $suggestion)">
        View Interview Details
    </x-mail::button>
    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>