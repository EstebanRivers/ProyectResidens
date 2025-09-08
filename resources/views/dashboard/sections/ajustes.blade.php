<!-- Ajustes Section -->
<div id="ajustes" class="section-content">
    <div class="info-card">
        <div class="ajustes-tabs" style="display: flex; border-bottom: 2px solid #e9ecef; margin-bottom: 2rem;">
            <button class="ajustes-tab active" data-tab="perfil-tab" style="padding: 1rem 1.5rem; background: none; border: none; cursor: pointer; font-size: 0.9rem; font-weight: 500; color: #ffa726; border-bottom: 2px solid #ffa726;">Perfil</button>
            <button class="ajustes-tab" data-tab="seguridad-tab" style="padding: 1rem 1.5rem; background: none; border: none; cursor: pointer; font-size: 0.9rem; font-weight: 500; color: #666; border-bottom: 2px solid transparent;">Seguridad</button>
            <button class="ajustes-tab" data-tab="notificaciones-tab" style="padding: 1rem 1.5rem; background: none; border: none; cursor: pointer; font-size: 0.9rem; font-weight: 500; color: #666; border-bottom: 2px solid transparent;">Notificaciones</button>
        </div>
        
        <div id="perfil-tab" class="ajustes-section active">
            <form class="ajustes-form" method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.9rem;">Nombre completo</label>
                    <input type="text" name="name" class="form-input" value="{{ Auth::user()->name }}" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 0.9rem;">
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.9rem;">Email</label>
                    <input type="email" name="email" class="form-input" value="{{ Auth::user()->email }}" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 0.9rem;">
                </div>
                @if(Auth::user()->isAlumno())
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.9rem;">Matrícula</label>
                    <input type="text" name="matricula" class="form-input" value="{{ Auth::user()->matricula }}" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 0.9rem;">
                </div>
                @endif
                <div class="form-actions" style="margin-top: 2rem; display: flex; gap: 1rem;">
                    <button type="submit" class="btn-primary" style="padding: 0.75rem 1.5rem; background: #ffa726; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 0.9rem; font-weight: 500;">Guardar cambios</button>
                </div>
            </form>
        </div>
        
        <div id="seguridad-tab" class="ajustes-section" style="display: none;">
            <form class="ajustes-form" method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.9rem;">Contraseña actual</label>
                    <input type="password" name="current_password" class="form-input" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 0.9rem;">
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.9rem;">Nueva contraseña</label>
                    <input type="password" name="password" class="form-input" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 0.9rem;">
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.9rem;">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" class="form-input" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 0.9rem;">
                </div>
                <div class="form-actions" style="margin-top: 2rem; display: flex; gap: 1rem;">
                    <button type="submit" class="btn-primary" style="padding: 0.75rem 1.5rem; background: #ffa726; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 0.9rem; font-weight: 500;">Cambiar contraseña</button>
                </div>
            </form>
        </div>
        
        <div id="notificaciones-tab" class="ajustes-section" style="display: none;">
            <form class="ajustes-form">
                <div class="checkbox-group" style="display: flex; align-items: center; margin-bottom: 1rem;">
                    <input type="checkbox" id="email-notifications" class="form-checkbox" checked style="margin-right: 0.5rem;">
                    <label for="email-notifications" class="checkbox-label" style="font-weight: normal; margin-bottom: 0; cursor: pointer;">Recibir notificaciones por email</label>
                </div>
                <div class="checkbox-group" style="display: flex; align-items: center; margin-bottom: 1rem;">
                    <input type="checkbox" id="sms-notifications" class="form-checkbox" style="margin-right: 0.5rem;">
                    <label for="sms-notifications" class="checkbox-label" style="font-weight: normal; margin-bottom: 0; cursor: pointer;">Recibir notificaciones por SMS</label>
                </div>
                <div class="form-actions" style="margin-top: 2rem; display: flex; gap: 1rem;">
                    <button type="submit" class="btn-primary" style="padding: 0.75rem 1.5rem; background: #ffa726; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 0.9rem; font-weight: 500;">Guardar preferencias</button>
                </div>
            </form>
        </div>
    </div>
</div>