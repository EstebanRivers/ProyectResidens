@extends('layouts.dashboard')

@section('title', 'Dashboard - ' . ucfirst(Auth::user()->role))
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Panel de control - ' . ucfirst(Auth::user()->role))

@section('content')
<div class="dashboard-sections">
    <!-- Welcome Section (Default) -->
    <div class="welcome-section active">
        <div class="welcome-content">
            <img src="{{ asset('images/uhta-logo.png') }}" alt="UHTA" class="welcome-logo">
            <p class="welcome-text">¡Bienvenido(a) {{ Auth::user()->name }}!</p>
        </div>
    </div>

    <!-- Dynamic Sections -->
    @include('dashboard.sections.mi-informacion')
    
    @if(Auth::user()->isAdmin())
        @include('dashboard.sections.cursos.administrador')
    @elseif(Auth::user()->isMaestro())
        @include('dashboard.sections.cursos.maestro')
    @else
        @include('dashboard.sections.cursos.alumno')
    @endif
    
    @include('dashboard.sections.facturacion')
    @include('dashboard.sections.control-administrativo')
    @include('dashboard.sections.ajustes')
</div>

@push('scripts')
<script src="{{ asset('js/dashboard/main.js') }}"></script>
@endpush
@endsection