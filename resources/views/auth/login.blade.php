@extends('layouts.app')

@section('title', 'Iniciar Sesión - UHTA')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="login-container">
    <!-- Imagen lado izquierdo -->
    <div class="login-left"></div>   

    <!-- Formulario lado derecho -->
    <div class="login-right">
        <div class="login-form">
            
            <!-- Logo -->
            <div class="logo">
                <img src="{{ asset('images/uhta-logo.png') }}" alt="Logo UHTA" loading="lazy">
            </div>

            <!-- Formulario -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Campo usuario/correo -->
                <div class="form-group">
                    <label for="email">Usuario o correo electrónico</label>
                    <input type="text" id="email" name="email" 
                           placeholder="Ingrese su usuario"
                           value="{{ old('email') }}" required autofocus
                           autocomplete="username">
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Campo contraseña -->
                <div class="form-group password-group">
                    <label for="password">Contraseña</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" 
                               placeholder="Ingrese su contraseña"
                               required autocomplete="current-password">
                        <span class="toggle-password" onclick="togglePassword()">
                            <i class="icon-eye"></i>
                        </span>
                    </div>
                    @error('password')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Botón ingresar -->
                <button type="submit" class="login-btn">Ingresar</button>
            </form>
        </div>
    </div>
</div>

<!-- Script para mostrar/ocultar contraseña -->
<script>
    function togglePassword() {
        const input = document.getElementById('password');
        input.type = input.type === 'password' ? 'text' : 'password';
    }
</script>
@endsection

