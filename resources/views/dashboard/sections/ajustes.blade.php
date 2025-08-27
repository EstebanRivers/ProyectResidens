<div id="ajustes" class="section-content">
    <div class="ajustes-content">
        <div class="ajustes-tabs">
            <button class="ajustes-tab active" data-tab="perfil-tab">Perfil</button>
            <button class="ajustes-tab" data-tab="seguridad-tab">Seguridad</button>
            <button class="ajustes-tab" data-tab="notificaciones-tab">Notificaciones</button>
        </div>
        
        <div id="perfil-tab" class="ajustes-section active">
            <form class="ajustes-form" method="POST" action="#">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nombre completo</label>
                    <input type="text" class="form-input" value="{{ Auth::user()->name }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-input" value="{{ Auth::user()->email }}">
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
        
        <div id="seguridad-tab" class="ajustes-section">
            <form class="ajustes-form" method="POST" action="#">
                @csrf
                <div class="form-group">
                    <label class="form-label">Contraseña actual</label>
                    <input type="password" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Nueva contraseña</label>
                    <input type="password" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Confirmar contraseña</label>
                    <input type="password" class="form-input">
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Cambiar contraseña</button>
                </div>
            </form>
        </div>
        
        <div id="notificaciones-tab" class="ajustes-section">
            <form class="ajustes-form" method="POST" action="#">
                @csrf
                <div class="checkbox-group">
                    <input type="checkbox" id="email-notifications" class="form-checkbox" checked>
                    <label for="email-notifications" class="checkbox-label">Recibir notificaciones por email</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="sms-notifications" class="form-checkbox">
                    <label for="sms-notifications" class="checkbox-label">Recibir notificaciones por SMS</label>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Guardar preferencias</button>
                </div>
            </form>
        </div>
    </div>
</div>