@component('mail::message')
<b>Forget Password</b>

Greeting Hoowdy,

Retailer recently received a request for a forgotten password.
To change your Retailer password please click on the button below

This link will expired in 4 hours 

@component('mail::button', ['url' => url('/reset/'.$forget)])
Reset Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
