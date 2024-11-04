@component('mail::layout')

    @slot('header')
        @component('mail::header', ['url' => ''])
            <img src="{{ asset($logoUrl) }}" alt="{{ config('app.name') }}" height="50">
        @endcomponent
    @endslot

    @slot('subcopy')
        Dear {{ $name }},

        <p> Please see the message below regarding your application for school admission.</p>
        Exam Instruction:<strong>{{ $notification_text }}</strong>
        @component('mail::button', [
            'url' => URL::signedRoute('admissionexam.show', ['id' => $admission_id, 'exam_id' => $exam_id]),
        ])
            Click Here To Take Exam
        @endcomponent
    @endslot

    @slot('footer')
        @component('mail::footer')
            Kind regards,<br>
            © {{ date('Y') }} {{ $school }}. All rights reserved.
        @endcomponent
    @endslot
@endcomponent
