@extends('layouts.dashboard')

@section('title', 'Dashboard - ' . ucfirst(Auth::user()->role))
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Panel de control principal')

@section('content')
<div class="dashboard-sections">
    <!-- Welcome Section (Default) -->
    <div class="welcome-section active">
        <div class="welcome-content">
            <img src="{{ asset('images/uhta-logo.png') }}" alt="UMI" class="welcome-logo">
            <p class="welcome-text">¡Bienvenido(a) {{ Auth::user()->name }}!</p>
        </div>
    </div>

    <!-- Dynamic Sections -->
    @include('dashboard.sections.mi-informacion')
    @include('dashboard.sections.cursos.' . Auth::user()->role)
    @include('dashboard.sections.facturacion')
    @include('dashboard.sections.control-administrativo')
    @include('dashboard.sections.ajustes')
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/dashboard/navigation.js') }}"></script>
@endpush