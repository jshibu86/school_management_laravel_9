@component('mail::layout')

    @slot('header')
        @component('mail::header', ['url' => ''])
            <img src="{{ asset($logoUrl) }}" alt="{{ config('app.name') }}" height="50">
        @endcomponent
    @endslot

    @slot('subcopy')
        Dear {{ $name }},

        {{ $rejection_text }}
        @if (!empty($rejection_data['emailexamscores']))
            <h2>Your Exam Scores:</h2>
            <p>Attempted Questions: {{ $rejection_data['emailexamscores']['onlineexam']['total_answered'] }}</p>
            <p>Obtained Scores: {{ $rejection_data['emailexamscores']['onlineexam']['total_marks'] }}</p>
            <p>Result: {{ 'Pass' }}</p>
        @endif

        @slot('footer')
            @component('mail::footer')
                Kind regards,<br>
                © {{ date('Y') }} {{ $school }}. All rights reserved.
            @endcomponent
        @endslot
    @endcomponent
