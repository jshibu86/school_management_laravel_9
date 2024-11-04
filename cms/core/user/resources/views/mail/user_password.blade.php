@component('mail::message')

# Hi {{$data->name}},

This is Your Login Credentials 

@component('mail::panel')
User name : <strong>{{ $data->username }}</strong>
<br/>
Password  :<strong>{{ $password }}</strong>
@endcomponent



@component('mail::button', ['url' => route('backendlogin')])
Login
@endcomponent

Thanks,<br>

© {{ date('Y') }} {{isset(Configurations::getConfig('site')->school_name) ? Configurations::getConfig('site')->school_name : 'School Management'}}.
    
@endcomponent