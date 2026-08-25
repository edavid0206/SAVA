<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAVA &bull; Editar Usuario Institucional</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: #030712; color: #f8fafc; min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 20px; }
        
        .form-card { background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 30px; width: 100%; max-width: 500px; box-shadow: 0 20px 40px rgba(0,0,0,0.5); }
        .form-card h2 { font-size: 1.3rem; color: #38bdf8; margin-bottom: 5px; display: flex; align-items: center; gap: 10px; }
        .form-card p { font-size: 0.82rem; color: #94a3b8; margin-bottom: 20px; }

        .form-group { margin-bottom: 15px; }
        .form-group label { font-size: 0.83rem; color: #94a3b8; display: block; margin-bottom: 5px; font-weight: 600; }
        .form-group input, .form-group select { width: 100%; background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 10px 14px; color: #fff; font-size: 0.9rem; outline: none; transition: all 0.3s; }
        .form-group input:focus, .form-group select:focus { border-color: #38bdf8; box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2); }

        .btn-action { background: linear-gradient(135deg, #0284c7, #2563eb); border: none; color: #fff; padding: 10px 18px; border-radius: 12px; font-size: 0.88rem; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.3s; cursor: pointer; text-decoration: none; width: 100%; }
        .btn-action:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3); }
        .btn-cancel { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #cbd5e1; margin-top: 10px; }
        .btn-cancel:hover { background: rgba(255,255,255,0.15); color: #fff; }

        .alert-msg { padding: 10px 15px; border-radius: 10px; font-size: 0.85rem; margin-bottom: 15px; display: flex; align-items: center; gap: 8px; }
        .alert-error { background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5; }
    </style>
</head>
<body>

    <div class="form-card">
        <h2><i class="fa-solid fa-user-pen"></i> Editar Usuario Institucional</h2>
        <p>Modificando registro en la base de datos (ID: #<?php echo $usuario['id'] ?? ''; ?>)</p>

        <?php if (!empty($error)): ?>
            <div class="alert-msg alert-error"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="/sistema/public/index.php?route=soporte-actualizar-usuario" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($usuario['id'] ?? ''); ?>">

            <div class="form-group">
                <label>Cédula (Registro Civil / TSE)</label>
                <div style="display:flex; gap:8px;">
                    <input type="text" id="cedulaInput" name="cedula" value="<?php echo htmlspecialchars($usuario['cedula'] ?? ''); ?>" required>
                    <button type="button" onclick="consultarTSE()" class="btn-action" style="width:auto; padding:0 15px; background:#0284c7;" title="Consultar TSE"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </div>

            <div class="form-group">
                <label>Nombre de Usuario (Login)</label>
                <input type="text" name="usuario" value="<?php echo htmlspecialchars($usuario['usuario'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" id="nombreInput" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label>Apellidos</label>
                <input type="text" id="apellidosInput" name="apellidos" value="<?php echo htmlspecialchars($usuario['apellidos'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label>Correo Institucional</label>
                <input type="email" name="correo" value="<?php echo htmlspecialchars($usuario['correo'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label>Rol en el Sistema</label>
                <select name="rol" required>
                    <option value="profesor" <?php echo (($usuario['rol'] ?? '') === 'profesor') ? 'selected' : ''; ?>>Docente</option>
                    <option value="administrativo" <?php echo (($usuario['rol'] ?? '') === 'administrativo') ? 'selected' : ''; ?>>Administrativo</option>
                    <option value="admin" <?php echo (($usuario['rol'] ?? '') === 'admin') ? 'selected' : ''; ?>>Administrador / Soporte</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label>Nueva Contraseña (Opcional)</label>
                <input type="password" name="password" placeholder="Dejar en blanco para mantener la actual">
            </div>

            <button type="submit" class="btn-action">Guardar Cambios</button>
            <a href="/sistema/public/index.php?route=soporte-panel" class="btn-action btn-cancel">Cancelar</a>
        </form>
    </div>

    <script>
        async function consultarTSE() {
            const cedula = document.getElementById('cedulaInput').value.trim();
            if (!cedula) {
                alert('Por favor ingrese un número de cédula.');
                return;
            }
            try {
                const response = await fetch(`https://api.hacienda.go.cr/fe/ae?identificacion=${cedula}`);
                if (!response.ok) throw new Error('No se pudo conectar con el servicio.');
                const data = await response.json();
                if (data && data.nombre) {
                    const partes = data.nombre.split(' ');
                    document.getElementById('nombreInput').value = partes[0] || '';
                    document.getElementById('apellidosInput').value = partes.slice(1).join(' ') || '';
                } else {
                    alert('No se encontraron datos para esta cédula.');
                }
            } catch (error) {
                alert('Error al consultar el servicio de cedulación. Puede digitar los datos manualmente.');
            }
        }
    </script>
</body>
</html>
