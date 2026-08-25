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
        
        .form-card { background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 30px; width: 100%; max-width: 520px; box-shadow: 0 20px 40px rgba(0,0,0,0.5); }
        .form-card h2 { font-size: 1.3rem; color: #38bdf8; margin-bottom: 5px; display: flex; align-items: center; gap: 10px; }
        .form-card p { font-size: 0.82rem; color: #94a3b8; margin-bottom: 20px; }

        .form-group { margin-bottom: 15px; }
        .form-group label { font-size: 0.83rem; color: #94a3b8; display: block; margin-bottom: 5px; font-weight: 600; }
        .form-group input, .form-group select { width: 100%; background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 11px 14px; color: #fff; font-size: 0.9rem; outline: none; transition: all 0.3s; }
        .form-group input:focus, .form-group select:focus { border-color: #38bdf8; box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2); }

        .btn-action { background: linear-gradient(135deg, #0284c7, #2563eb); border: none; color: #fff; padding: 12px 18px; border-radius: 12px; font-size: 0.88rem; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.3s; cursor: pointer; text-decoration: none; width: 100%; }
        .btn-action:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3); }
        .btn-cancel { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #cbd5e1; margin-top: 10px; }
        .btn-cancel:hover { background: rgba(255,255,255,0.15); color: #fff; }

        .alert-msg { padding: 10px 15px; border-radius: 10px; font-size: 0.85rem; margin-bottom: 15px; display: flex; align-items: center; gap: 8px; }
        .alert-error { background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5; }
        .loading-tse { font-size: 0.78rem; color: #38bdf8; margin-top: 4px; display: none; }
    </style>
</head>
<body>

    <div class="form-card">
        <h2><i class="fa-solid fa-user-pen"></i> Editar Usuario Institucional</h2>
        <p>Modifique los datos institucionales del usuario seleccionado.</p>

        <?php if (!empty($error)): ?>
            <div class="alert-msg alert-error">
                <i class="fa-solid fa-triangle-exclamation"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="/sistema/public/index.php?route=soporte-actualizar-usuario" method="POST">
            <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

            <div class="form-group">
                <label for="cedulaInput">Cédula Identificación</label>
                <input type="text" id="cedulaInput" name="cedula" value="<?= htmlspecialchars($usuario['cedula']) ?>" required>
                <div id="tseLoading" class="loading-tse"><i class="fa-solid fa-spinner fa-spin"></i> Consultando datos en padrón...</div>
            </div>

            <div class="form-group">
                <label for="nombreInput">Nombre(s)</label>
                <input type="text" id="nombreInput" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
            </div>

            <div class="form-group">
                <label for="apellidosInput">Apellidos</label>
                <input type="text" id="apellidosInput" name="apellidos" value="<?= htmlspecialchars($usuario['apellidos']) ?>" required>
            </div>

            <div class="form-group">
                <label for="usuarioInput">Nombre de Usuario</label>
                <input type="text" id="usuarioInput" name="usuario" value="<?= htmlspecialchars($usuario['usuario']) ?>" required>
            </div>

            <div class="form-group">
                <label for="correoInput">Correo Institucional</label>
                <input type="email" id="correoInput" name="correo" value="<?= htmlspecialchars($usuario['correo']) ?>">
            </div>

            <div class="form-group">
                <label for="rolInput">Rol Asignado</label>
                <select id="rolInput" name="rol">
                    <option value="profesor" <?= $usuario['rol'] === 'profesor' ? 'selected' : '' ?>>Docente</option>
                    <option value="administrativo" <?= $usuario['rol'] === 'administrativo' ? 'selected' : '' ?>>Administrativo</option>
                    <option value="admin" <?= $usuario['rol'] === 'admin' ? 'selected' : '' ?>>Administrador / Soporte</option>
                </select>
            </div>

            <div class="form-group">
                <label for="passwordInput">Nueva Contraseña (Dejar en blanco para mantener la actual)</label>
                <input type="password" id="passwordInput" name="password" placeholder="••••••••">
            </div>

            <button type="submit" class="btn-action">
                <i class="fa-solid fa-floppy-disk"></i> Guardar Cambios
            </button>
            <a href="/sistema/public/index.php?route=soporte-panel" class="btn-action btn-cancel">
                <i class="fa-solid fa-xmark"></i> Cancelar
            </a>
        </form>
    </div>

    <script>
        document.getElementById('cedulaInput').addEventListener('blur', function() {
            let cedula = this.value.trim();
            if (cedula.length >= 9) {
                let loadingEl = document.getElementById('tseLoading');
                loadingEl.style.display = 'block';

                fetch(`https://api.hacienda.go.cr/fe/ae?identificacion=${cedula}`)
                    .then(response => response.json())
                    .then(data => {
                        loadingEl.style.display = 'none';
                        if (data && data.nombre) {
                            let partes = data.nombre.trim().replace(/\s+/g, ' ').split(' ');
                            
                            if (partes.length >= 3) {
                                // Extraer siempre los dos apellidos del final
                                let apellido2 = partes.pop();
                                let apellido1 = partes.pop();
                                
                                // Todo el resto anterior pertenece a los Nombres (ej: Juan Carlos)
                                let nombres = partes.join(' ');

                                document.getElementById('nombreInput').value = nombres;
                                document.getElementById('apellidosInput').value = `${apellido1} ${apellido2}`;
                            } else if (partes.length === 2) {
                                document.getElementById('nombreInput').value = partes[0];
                                document.getElementById('apellidosInput').value = partes[1];
                            } else {
                                document.getElementById('nombreInput').value = data.nombre;
                                document.getElementById('apellidosInput').value = "";
                            }
                        }
                    })
                    .catch(err => {
                        loadingEl.style.display = 'none';
                        console.warn("Consulta al TSE/Hacienda no disponible.");
                    });
            }
        });
    </script>
</body>
</html>
