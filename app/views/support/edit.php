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
        body { background-color: #030712; color: #f8fafc; min-height: 100vh; display: flex; flex-direction: column; padding: 25px; align-items: center; justify-content: center; }
        .content-card { background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.5); width: 100%; max-width: 600px; }
        .content-card h3 { font-size: 1.25rem; margin-bottom: 20px; color: #38bdf8; display: flex; align-items: center; gap: 10px; }
        .form-grid { display: flex; flex-direction: column; gap: 15px; margin-bottom: 20px; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-group label { font-size: 0.8rem; font-weight: 600; color: #38bdf8; }
        .form-control { width: 100%; background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px 15px; color: #fff; font-size: 0.9rem; outline: none; }
        .form-control:focus { border-color: #38bdf8; box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2); }
        .btn-action { background: linear-gradient(135deg, #0284c7, #2563eb); border: none; color: #fff; padding: 12px 25px; border-radius: 12px; font-size: 0.9rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; transition: all 0.3s; }
        .btn-action:hover { opacity: 0.9; transform: translateY(-2px); }
        .btn-secondary { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); margin-right: 10px; }
        .alert-msg { padding: 12px 18px; border-radius: 12px; font-size: 0.88rem; margin-bottom: 20px; width: 100%; max-width: 600px; display: flex; align-items: center; gap: 10px; }
        .alert-error { background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5; }
    </style>
</head>
<body>
    <?php if (!empty($_GET['error'])): ?>
        <div class="alert-msg alert-error"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>
    <div class="content-card">
        <h3><i class="fa-solid fa-user-pen"></i> Editar Usuario Institucional</h3>
        <form action="/sistema/public/index.php?route=soporte-actualizar-usuario" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($usuario['id'] ?? ''); ?>">
            <div class="form-grid">
                <div class="form-group">
                    <label>Cédula *</label>
                    <input type="text" name="cedula" class="form-control" value="<?php echo htmlspecialchars($usuario['cedula'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Nombre(s) *</label>
                    <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($usuario['nombre'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Apellidos *</label>
                    <input type="text" name="apellidos" class="form-control" value="<?php echo htmlspecialchars($usuario['apellidos'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Usuario *</label>
                    <input type="text" name="usuario" class="form-control" value="<?php echo htmlspecialchars($usuario['usuario'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Correo Institucional</label>
                    <input type="email" name="correo" class="form-control" value="<?php echo htmlspecialchars($usuario['correo'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Nueva Contraseña (Dejar en blanco para mantener la actual)</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••">
                </div>
                <div class="form-group">
                    <label>Rol de Usuario *</label>
                    <select name="rol" class="form-control" required>
                        <option value="profesor" <?php echo (($usuario['rol'] ?? '') === 'profesor') ? 'selected' : ''; ?>>Docente</option>
                        <option value="administrativo" <?php echo (($usuario['rol'] ?? '') === 'administrativo') ? 'selected' : ''; ?>>Administrativo</option>
                        <option value="admin" <?php echo (($usuario['rol'] ?? '') === 'admin' || ($usuario['rol'] ?? '') === 'soporte') ? 'selected' : ''; ?>>Administrador / Soporte</option>
                    </select>
                </div>
            </div>
            <div style="display: flex; gap: 10px; margin-top: 15px;">
                <a href="/sistema/public/index.php?route=soporte-panel" class="btn-action btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Cancelar
                </a>
                <button type="submit" class="btn-action">
                    <i class="fa-solid fa-floppy-disk"></i> Actualizar Usuario
                </button>
            </div>
        </form>
    </div>
</body>
</html>