@component('mail::message')
# Hello {{ $name }}!

You have been sent a temporary link to upload files.

Click the button below to access the platform:

@component('mail::button', ['url' => $accessLink])
Access Toomba Secure
@endcomponent

@if ($password)
The password to access is: **{{ $password }}**
@endif

This link will expire on: **{{ $expirationDate }}**.

Thanks, 
Toomba Secure Team.
@endcomponent