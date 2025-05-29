@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Configura Google Authenticator</h2>
    <p>Escanea este código QR con la app de Google Authenticator:</p>
    <div>
        <img src="{{ $QR_Image }}" alt="QR Code">
    </div>
    <p><strong>O usa esta clave manual:</strong> {{ $user->googletwofactor_secret }}</p>
    <p>Después escanear, <a href="{{ route('twofactor.verify') }}">verifica tu código</a>.</p>
</div>
@endsection
