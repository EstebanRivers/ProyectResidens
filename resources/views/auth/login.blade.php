{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Iniciar Sesión - UHTA')

@section('content')
<div class="login-container">
    <div class="login-left">
        <div class="building-image">
            <!-- Imagen del edificio como en tu diseño -->
            <img src="{{ asset('images/building.jpg') }}" alt="UHTA Building">
        </div>
    </div>
    
    <div class="login-right">
        <div class="login-form">
            <div class="logo">
                <img src="{{ asset('images/uhta-logo.png') }}" alt="UHTA Logo">
            </div>
            
            <h2>UHTA</h2>
            <p class="subtitle">UNIVERSIDAD DE HIDALGO TABASCO AMBIENTAL</p>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Usuario o correo electrónico</label>
                    <input type="email" id="email" name="email" placeholder="Ingresa tu usuario" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required>
                    @error('password')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                
                <button type="submit" class="login-btn">Ingresar</button>
            </form>
        </div>
    </div>
</div>
@endsection
