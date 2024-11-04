@component('mail::message')

# Hi {{ @$studentinfo->full_name }},

Successfully Completed Your Fee Payment

@component('mail::panel')
Total Amount : <strong></strong>
<br/>
Paid Date:<strong>{{ date("Y-m-d") }}</strong>
@endcomponent





Thanks,<br>

© {{ date('Y') }} {{isset(Configurations::getConfig('site')->school_name) ? Configurations::getConfig('site')->school_name : 'School Management'}}.
    
@endcomponent