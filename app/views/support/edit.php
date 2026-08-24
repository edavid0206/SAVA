<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAVA &bull; Editar Usuario</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: #030712; color: #f8fafc; min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 20px; }
        
        .edit-container { background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(25px); border: 1px solid rgba(255,255,255,0.1); border-radius: 24px; padding: 35px; width: 100%; max-width: 600px; box-shadow: 0 25px 50px rgba(0,0,0,0.5); }
        .edit-header { display: flex; align-items: center; gap: 15px; margin-bottom: 25px; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 15px; }
        .edit-header i { font-size: 1.8rem; color: #38bdf8; }
        .edit-header h2 { font-size: 1.35rem; color: #f8fafc; }
        .edit-header p { font-size: 0.85rem; color: #94a3b8; }

        .alert-msg { padding: 12px; border-radius: 10px; font-size: 0.85rem; margin-bottom: 20px; text-align: center; }
        .alert-error { background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5; }

        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 0.85rem; font-weight: 600; color: #cbd5e1; margin-bottom: 6px; }
        .form-control { width: 100%; background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px 15px; color: #fff; font-size: 0.9rem; outline: none; }
        .form-control:focus { border-color: #38bdf8; box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2); }
        
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        
        .btn-container { display: flex; gap: 12px; margin-top: 25px; }
        .btn-submit { flex: 1; background: linear-gradient(135deg, #0284c7, #2563eb); color: white; border: none; padding: 12px; border-radius: 12px; font-weight: 600; cursor: pointer; transition: transform 0.2s; display: inline-flex; justify-content: center; align-items: center; gap: 8px; text-decoration: none; }
        .btn-submit:hover { transform: translateY(-2px); }
        .btn-cancel { background: rgba(255,255,255,0.08); color: #cbd5e1; border: 1px solid rgba(255,255,255,0.15); }
        .btn-cancel:hover { background: rgba(255,255,255,0.15); color: #fff; }
    </style>
</head>
<body>

    <div class="edit-container">
        <div class="edit-header">
            <i class="fa-solid fa-user-pen"></i>
            <div>
                <h2>Editar Usuario Institucional</h2>
                <p>Modificando registro en la base de datos (ID: #<?php echo $usuarioEditar['id']; ?>)</p>
            </div>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert-msg alert-error"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="/sistema/public/index.php?route=soporte-actualizar-usuario" method="POST">
            <input type="hidden" name="id" value="<?php echo $usuarioEditar['id']; ?>">

            <div class="form-row">
                <div class="form-group">
                    <label>Cédula</label>
                    <input type="text" name="cedula" class="form-control" value="<?php echo htmlspecialchars($usuarioEditar['cedula']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Nombre de Usuario</label>
                    <input type="text" name="usuario" class="form-control" value="<?php echo htmlspecialchars($usuarioEditar['usuario']); ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($usuarioEditar['nombre']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Apellidos</label>
                    <input type="text" name="apellidos" class="form-control" value="<?php echo htmlspecialchars($usuarioEditar['apellidos']); ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label>Correo Institucional</label>
                <input type="email" name="correo" class="form-control" value="<?php echo htmlspecialchars($usuarioEditar['correo']); ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Rol en el Sistema</label>
                    <select name="rol" class="form-control">
                        <option value="profesor" <?php echo ($usuarioEditar['rol'] === 'profesor') ? 'selected' : ''; ?>>Docente</option>
                        <option value="administrativo" <?php echo ($usuarioEditar['rol'] === 'administrativo') ? 'selected' : ''; ?>>Administrativo</option>
                        <option value="admin" <?php echo ($usuarioEditar['rol'] === 'admin') ? 'selected' : ''; ?>>Administrador / Soporte</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Nueva Contraseña (Opcional)</label>
                    <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para no cambiar">
                </div>
            </div>

            <div class="btn-container">
                <a href="/sistema/public/index.php?route=soporte-panel" class="btn-submit btn-cancel">Cancelar</a>
                <button type="submit" class="btn-submit"><i class="fa-solid fa-floppy-disk"></i> Guardar Cambios</button>
            </div>
        </form>
    </div>

</body>
</html>
