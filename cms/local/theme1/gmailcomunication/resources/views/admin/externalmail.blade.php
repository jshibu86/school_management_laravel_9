@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url'), 'logoUrl' => $logoUrl])
            <img src="{{ $logoUrl }}" alt="{{ config('app.name') }}" height="50">
        @endcomponent
    @endslot

    {{-- Body --}}
   
    @slot('subcopy')
  

    {!! $content !!}
    @endslot
    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ $school }}. All rights reserved.
        @endcomponent
    @endslot
@endcomponent
