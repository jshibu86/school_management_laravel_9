@component('mail::message')

# Hi,

This is {{@$month}} Payslip 


Thanks,<br>

© {{ date('Y') }} {{isset(Configurations::getConfig('site')->school_name) ? Configurations::getConfig('site')->school_name : 'School Management'}}.
    
@endcomponent