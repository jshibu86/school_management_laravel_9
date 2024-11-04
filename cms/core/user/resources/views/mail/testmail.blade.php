@component('mail::message')

# Hi,

This is Your Login Credentials 

@component('mail::panel')
User name : <strong>demo</strong>
<br/>
Password  :<strong>1233</strong>
@endcomponent



@component('mail::button', ['url' => route('backendlogin')])
Login
@endcomponent

Thanks,<br>

© {{ date('Y') }} {{isset(Configurations::getConfig('site')->school_name) ? Configurations::getConfig('site')->school_name : 'School Management'}}.
    
@endcomponent