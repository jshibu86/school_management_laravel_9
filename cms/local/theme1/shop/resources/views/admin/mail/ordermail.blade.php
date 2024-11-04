@component('mail::message')

Hi {{ $user_data->name }},

@component('mail::panel')
    Your Order Placed Successfully Order Number : {{ $order_number }}
@endcomponent


Thanks,<br>
© {{ date('Y') }} {{isset(Configurations::getConfig('site')->school_name) ? Configurations::getConfig('site')->school_name : 'School Management'}}.
    
@endcomponent