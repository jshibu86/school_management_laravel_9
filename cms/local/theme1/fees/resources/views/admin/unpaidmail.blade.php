@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url'), 'logoUrl' => $logoUrl])
        <img src="{{ $logoUrl }}" alt="{{ config('app.name') }}" height="50px" width = "auto">
        @endcomponent
    @endslot

    {{-- Body --}}
    # Hello 
    @if($gender == "male")
    Mr.
    @else
    Mrs.
    @endif
    {{ $name }},
    @slot('subcopy')
  

    {!! $reminder_text !!} You have to pay your remaining fees amount **{{ $unpaid_amount }}** before the semester ends.
    @component('mail::button',['url' => route('dobackendlogin', ['redirect' => route('fees.create')])])
    Pay Your Fee
    @endcomponent
    
    Thank you,<br>
    {{$school}}
    @endslot
   
    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ $school }}. All rights reserved.
        @endcomponent
    @endslot
@endcomponent
