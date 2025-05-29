@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Verifica tu código 2FA</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ url('/twofactor/verify') }}" method="POST">
        @csrf
        <div>
            <label for="code">Código de verificación:</label>
            <input type="text" name="code" required>
        </div>
        <button type="submit">Verificar</button>
    </form>
</div>
@endsection
