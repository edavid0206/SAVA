<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAVA &bull; Nivel Soporte Técnico</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: #030712; color: #f8fafc; min-height: 100vh; display: flex; flex-direction: column; padding: 25px; }
        
        .header-bar { display: flex; justify-content: space-between; align-items: center; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); padding: 20px 30px; border-radius: 20px; margin-bottom: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.4); flex-wrap: wrap; gap: 15px; }
        .header-title h2 { font-size: 1.5rem; color: #38bdf8; display: flex; align-items: center; gap: 10px; }
        .header-title p { font-size: 0.85rem; color: #94a3b8; }
        
        .nav-buttons { display: flex; gap: 12px; }
        .btn-action { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #fff; padding: 10px 18px; border-radius: 12px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s; cursor: pointer; }
        .btn-action:hover { background: rgba(56, 189, 248, 0.2); border-color: #38bdf8; transform: translateY(-2px); }
        .btn-danger { background: rgba(239, 68, 68, 0.15); border-color: rgba(239, 68, 68, 0.3); color: #fca5a5; }
        .btn-danger:hover { background: rgba(239, 68, 68, 0.3); color: #fff; }

        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 25px; }
        .stat-card { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.08); padding: 20px; border-radius: 18px; display: flex; align-items: center; gap: 18px; box-shadow: 0 10px 25px rgba(0,0,0,0.3); }
        .stat-icon { width: 50px; height: 50px; background: linear-gradient(135deg, #0284c7, #2563eb); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: #fff; }
        .stat-info h3 { font-size: 1.6rem; font-weight: 700; color: #f8fafc; }
        .stat-info p { font-size: 0.8rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }

        .content-card { background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.4); margin-bottom: 25px; }
        .content-card h3 { font-size: 1.15rem; margin-bottom: 15px; color: #f8fafc; display: flex; align-items: center; gap: 10px; }

        .alert-msg { padding: 12px 18px; border-radius: 12px; font-size: 0.88rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: rgba(14, 159, 110, 0.2); border: 1px solid rgba(52, 211, 153, 0.4); color: #34d399; }
        .alert-error { background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5; }

        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 15px; }
        .form-control { width: 100%; background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px 15px; color: #fff; font-size: 0.9rem; outline: none; }
        .form-control:focus { border-color: #38bdf8; box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2); }
        
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem; }
        th { background: rgba(30, 41, 59, 0.8); color: #38bdf8; padding: 12px 15px; font-weight: 600; }
        td { padding: 12px 15px; border-bottom: 1px solid rgba(255,255,255,0.06); color: #cbd5e1; }
        tr:hover td { background: rgba(255,255,255,0.02); }

        .badge-rol { padding: 4px 10px; border-radius: 8px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; }
        .badge-admin { background: rgba(124, 58, 237, 0.2); color: #a78bfa; border: 1px solid rgba(167, 139, 250, 0.3); }
        .badge-profesor { background: rgba(14, 165, 233, 0.2); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); }
        .badge-administrativo { background: rgba(217, 119, 6, 0.2); color: #fcd34d; border: 1px solid rgba(252, 211, 77, 0.3); }

        .btn-table { padding: 5px 10px; border-radius: 8px; font-size: 0.75rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: opacity 0.2s; }
        .btn-table:hover { opacity: 0.8; }
        .btn-edit { background: rgba(52, 211, 153, 0.2); color: #34d399; border: 1px solid rgba(52, 211, 153, 0.3); }
        .btn-toggle { background: rgba(56, 189, 248, 0.2); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); }
        .btn-del { background: rgba(239, 68, 68, 0.2); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.3); }
        
        .loading-tse { font-size: 0.78rem; color: #38bdf8; margin-top: 4px; display: none; }
    </style>
</head>
<body>

    <div class="header-bar">
        <div class="header-title">
            <h2><i class="fa-solid fa-screwdriver-wrench"></i> Nivel Soporte Técnico</h2>
            <p>Administración general del sistema, bases de datos y control de usuarios institucionales.</p>
        </div>
        <div class="nav-buttons">
            <a href="/sistema/public/index.php?route=dashboard" class="btn-action">
                <i class="fa-solid fa-arrow-left"></i> Selector de Niveles
            </a>
            <a href="/sistema/public/index.php?route=logout" class="btn-action btn-danger">
                <i class="fa-solid fa-power-off"></i> Salir
            </a>
        </div>
    </div>

    <?php if (!empty($mensaje)): ?>
        <div class="alert-msg alert-success"><i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert-msg alert-error"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
            <div class="stat-info">
                <h3><?php echo $stats['usuarios']; ?></h3>
                <p>Total Usuarios</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #059669, #0d9488);"><i class="fa-solid fa-chalkboard-user"></i></div>
            <div class="stat-info">
                <h3><?php echo $stats['docentes']; ?></h3>
                <p>Docentes Registrados</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #7c3aed, #4f46e5);"><i class="fa-solid fa-shield-halved"></i></div>
            <div class="stat-info">
                <h3><?php echo $stats['admins']; ?></h3>
                <p>Administradores</p>
            </div>
        </div>
    </div>

    <!-- Formulario con integración TSE / API de consulta por cédula -->
    <div class="content-card">
        <h3><i class="fa-solid fa-user-plus"></i> Registrar Nuevo Usuario Institucional (Consulta TSE)</h3>
        <form action="/sistema/public/index.php?route=soporte-crear-usuario" method="POST">
            <div class="form-grid">
                <div>
                    <input type="text" id="cedulaInput" name="cedula" class="form-control" placeholder="Cédula (Ej. 10xxxxxxxx)" maxlength="12" required>
                    <div id="tseLoading" class="loading-tse"><i class="fa-solid fa-spinner fa-spin"></i> Consultando padrón electoral...</div>
                </div>
                <div>
                    <input type="text" id="nombreInput" name="nombre" class="form-control" placeholder="Nombre" required>
                </div>
                <div>
                    <input type="text" id="apellidosInput" name="apellidos" class="form-control" placeholder="Apellidos" required>
                </div>
                <div>
                    <input type="text" id="usuarioInput" name="usuario" class="form-control" placeholder="Usuario (Ej. ana.mora)" required>
                </div>
                <div>
                    <input type="email" name="correo" class="form-control" placeholder="Correo Institucional">
                </div>
                <div>
                    <input type="password" name="password" class="form-control" placeholder="Contraseña Temporal" required>
                </div>
                <div>
                    <select name="rol" class="form-control">
                        <option value="profesor">Docente</option>
                        <option value="administrativo">Administrativo</option>
                        <option value="admin">Administrador / Soporte</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn-action" style="background: linear-gradient(135deg, #0284c7, #2563eb); border: none; padding: 12px 25px; margin-top: 5px;">
                <i class="fa-solid fa-floppy-disk"></i> Guardar Usuario
            </button>
        </form>
    </div>

    <div class="content-card">
        <h3><i class="fa-solid fa-address-book"></i> Directorio de Usuarios del Sistema</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cédula</th>
                        <th>Nombre Completo</th>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td>#<?php echo $u['id']; ?></td>
                        <td><?php echo htmlspecialchars($u['cedula']); ?></td>
                        <td><strong><?php echo htmlspecialchars($u['nombre'] . ' ' . $u['apellidos']); ?></strong></td>
                        <td><code><?php echo htmlspecialchars($u['usuario']); ?></code></td>
                        <td><?php echo htmlspecialchars($u['correo']); ?></td>
                        <td>
                            <span class="badge-rol <?php echo ($u['rol'] === 'admin') ? 'badge-admin' : (($u['rol'] === 'administrativo') ? 'badge-administrativo' : 'badge-profesor'); ?>">
                                <?php echo htmlspecialchars($u['rol']); ?>
                            </span>
                        </td>
                        <td>
                            <span style="color: <?php echo $u['estado'] ? '#34d399' : '#fca5a5'; ?>;">
                                <i class="fa-solid fa-circle" style="font-size: 0.5rem;"></i> <?php echo $u['estado'] ? 'Activo' : 'Inactivo'; ?>
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 6px;">
                                <a href="/sistema/public/index.php?route=soporte-editar&id=<?php echo $u['id']; ?>" class="btn-table btn-edit" title="Editar Usuario">
                                    <i class="fa-solid fa-pen-to-square"></i> Editar
                                </a>
                                <a href="/sistema/public/index.php?route=soporte-cambiar-estado&id=<?php echo $u['id']; ?>" class="btn-table btn-toggle" title="Cambiar Estado">
                                    <i class="fa-solid fa-rotate"></i>
                                </a>
                                <a href="/sistema/public/index.php?route=soporte-eliminar&id=<?php echo $u['id']; ?>" class="btn-table btn-del" onclick="return confirm('¿Está seguro de eliminar este usuario?');" title="Eliminar Usuario">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Script de consulta de cédula (API de Padrón / Interoperabilidad CR)
        document.getElementById('cedulaInput').addEventListener('blur', function() {
            let cedula = this.value.trim();
            if (cedula.length >= 9) {
                let loadingEl = document.getElementById('tseLoading');
                loadingEl.style.display = 'block';

                // Realizamos la consulta a un servicio público/API de consulta de cédulas en CR
                fetch(`https://api.hacienda.go.cr/fe/ae?identificacion=${cedula}`)
                    .then(response => response.json())
                    .then(data => {
                        loadingEl.style.display = 'none';
                        if (data && data.nombre) {
                            let partesNombre = data.nombre.trim().split(' ');
                            // Separar nombre y apellidos según la estructura típica del padrón nacional
                            if (partesNombre.length >= 3) {
                                let apellido1 = partesNombre[partesNombre.length - 2];
                                let apellido2 = partesNombre[partesNombre.length - 1];
                                let nombreReal = partesNombre.slice(0, partesNombre.length - 2).join(' ');

                                document.getElementById('nombreInput').value = nombreReal;
                                document.getElementById('apellidosInput').value = `${apellido1} ${apellido2}`;
                                
                                // Sugerir nombre de usuario institucional automáticamente (ej. nombre.apellido)
                                let primerNombre = partesNombre[0].toLowerCase();
                                let primerApellido = apellido1.toLowerCase();
                                document.getElementById('usuarioInput').value = `${primerNombre}.${primerApellido}`;
                            } else {
                                document.getElementById('nombreInput').value = data.nombre;
                            }
                        }
                    })
                    .catch(error => {
                        loadingEl.style.display = 'none';
                        console.warn("No se pudo consultar el padrón automáticamente, ingrese los datos manualmente.");
                    });
            }
        });
    </script>
</body>
</html>
