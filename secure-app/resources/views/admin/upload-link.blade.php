@component('mail::message')
# ¡Hola {{ $name }}!

Se ha creado un enlace temporal para que subas archivos.

Haz clic en el botón de abajo para acceder al formulario de subida:

@component('mail::button', ['url' => $uploadLink])
Subir archivos
@endcomponent

@if ($password)
**Contraseña temporal:** {{ $password }}
@endif

Este enlace expirará el {{ $expirationDate }}.

Gracias,<br>
{{ config('app.name') }}
@endcomponent
