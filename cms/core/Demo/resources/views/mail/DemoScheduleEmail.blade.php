@component('mail::layout')

    @slot('header')
        @component('mail::header', ['url' => '','logoUrl'=>$details['logo']])                                 
            <img src="{{ $details['logo'] }}" alt="Logo" height="50">  
            <h1>{{ $details['title'] }}</h1>                    
        @endcomponent
    @endslot
    @slot('subcopy')
        Hello {{ $details['recipient_name'] }} 
        <p>{{ $details['message'] }}</p>      
        <p>Scheduled Date: {{ $details['demo_date'] }}</p>
        <p>Scheduled Time: {{ $details['demo_time'] }}</p>

        <p> Kind regards,<br>   </p> 
        <p> {{ $details['admin'] }}  </p>

    @endslot
    
    @slot('footer')
        @component('mail::footer')
            
            © {{ date('Y') }}  All rights reserved.
        @endcomponent
    @endslot
@endcomponent