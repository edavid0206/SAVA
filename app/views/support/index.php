<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAVA &bull; Nivel Soporte Técnico</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-color: #030712;
            --gradient-bg: linear-gradient(135deg, #0f172a, #1e1b4b, #020617);
            --bubble-grad: radial-gradient(circle at 30% 30%, rgba(56, 189, 248, 0.45), rgba(14, 165, 233, 0.05));
            --bubble-border: rgba(56, 189, 248, 0.25);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        html, body { height: 100vh; overflow: hidden; background-color: var(--bg-color); color: #f8fafc; }
        
        /* Fondo dinámico de burbujas interactivas en capa base */
        .bubbles-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 1;
            background: var(--gradient-bg);
            overflow: hidden;
            pointer-events: none;
        }

        .bubble {
            position: absolute;
            bottom: -100px;
            background: var(--bubble-grad);
            border: 1px solid var(--bubble-border);
            border-radius: 50%;
            box-shadow: 0 0 20px rgba(56, 189, 248, 0.15), inset 0 0 15px rgba(255, 255, 255, 0.25);
            animation: riseAndWobble linear infinite;
            backdrop-filter: blur(2px);
            z-index: 2;
            pointer-events: auto;
            cursor: pointer;
            transition: transform 0.1s ease, opacity 0.3s ease;
        }

        .bubble.pop {
            transform: scale(1.6) !important;
            opacity: 0 !important;
            filter: brightness(2);
            transition: transform 0.2s ease-out, opacity 0.2s ease-out;
        }

        @keyframes riseAndWobble {
            0% { transform: translateY(0) translateX(0) scale(1); opacity: 0; }
            15% { opacity: 0.85; }
            85% { opacity: 0.85; }
            100% { transform: translateY(-110vh) translateX(50px) scale(1.1); opacity: 0; }
        }

        body { display: flex; flex-direction: column; padding: 12px; position: relative; z-index: 10; }
        
        @media (min-width: 768px) {
            body { padding: 20px; }
        }

        .header-bar { display: flex; justify-content: space-between; align-items: center; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); padding: 12px 20px; border-radius: 16px; margin-bottom: 15px; box-shadow: 0 15px 35px rgba(0,0,0,0.4); flex-wrap: wrap; gap: 12px; flex-shrink: 0; position: relative; z-index: 15; }
        
        .header-title h2 { font-size: 1.15rem; color: #38bdf8; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        @media (min-width: 768px) {
            .header-title h2 { font-size: 1.4rem; }
        }
        .header-title p { font-size: 0.75rem; color: #94a3b8; }
        @media (min-width: 768px) {
            .header-title p { font-size: 0.82rem; }
        }
        
        .nav-buttons { display: flex; gap: 8px; width: 100%; justify-content: flex-start; flex-wrap: wrap; }
        @media (min-width: 600px) {
            .nav-buttons { width: auto; }
        }

        .btn-action { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #fff; padding: 8px 14px; border-radius: 10px; text-decoration: none; font-size: 0.8rem; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 6px; transition: all 0.3s; cursor: pointer; flex: 1; min-width: 120px; }
        @media (min-width: 600px) {
            .btn-action { flex: unset; min-width: auto; padding: 9px 16px; font-size: 0.85rem; }
        }
        .btn-action:hover { background: rgba(56, 189, 248, 0.2); border-color: #38bdf8; transform: translateY(-2px); }
        .btn-danger { background: rgba(239, 68, 68, 0.15); border-color: rgba(239, 68, 68, 0.3); color: #fca5a5; }
        .btn-danger:hover { background: rgba(239, 68, 68, 0.3); color: #fff; }

        .support-tabs { display: flex; gap: 8px; width: 100%; margin-bottom: 15px; overflow-x: auto; padding-bottom: 4px; -webkit-overflow-scrolling: touch; flex-shrink: 0; position: relative; z-index: 15; }

        .tab-btn { background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(255, 255, 255, 0.08); color: #94a3b8; padding: 9px 16px; border-radius: 10px; font-size: 0.82rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; white-space: nowrap; flex-shrink: 0; }
        .tab-btn:hover { color: #f8fafc; background: rgba(30, 41, 59, 0.9); }
        .tab-btn.active { background: linear-gradient(135deg, #0284c7, #2563eb); color: #fff; border-color: rgba(56, 189, 248, 0.4); box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3); }

        .tab-content { display: none; width: 100%; flex: 1; min-height: 0; flex-direction: column; animation: fadeIn 0.4s ease forwards; position: relative; z-index: 15; }
        .tab-content.active { display: flex; }

        .stats-grid { display: grid; grid-template-columns: repeat(1, 1fr); gap: 12px; margin-bottom: 15px; flex-shrink: 0; }
        @media (min-width: 480px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (min-width: 1024px) {
            .stats-grid { grid-template-columns: repeat(5, 1fr); gap: 15px; }
        }

        .stat-card { background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.08); padding: 15px; border-radius: 14px; display: flex; align-items: center; gap: 14px; box-shadow: 0 10px 25px rgba(0,0,0,0.3); }
        .stat-icon { width: 42px; height: 42px; background: linear-gradient(135deg, #0284c7, #2563eb); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: #fff; flex-shrink: 0; }
        .stat-info h3 { font-size: 1.3rem; font-weight: 700; color: #f8fafc; }
        .stat-info p { font-size: 0.72rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }

        .content-card { background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 15px; box-shadow: 0 20px 40px rgba(0,0,0,0.4); display: flex; flex-direction: column; flex: 1; min-height: 0; overflow: hidden; position: relative; z-index: 15; }
        @media (min-width: 768px) {
            .content-card { border-radius: 18px; padding: 20px; }
        }

        .content-card h3 { font-size: 1.05rem; margin-bottom: 12px; color: #f8fafc; display: flex; align-items: center; gap: 10px; justify-content: space-between; flex-wrap: wrap; flex-shrink: 0; }

        .search-box { position: relative; width: 100%; margin-top: 5px; }
        @media (min-width: 500px) {
            .search-box { width: auto; min-width: 250px; margin-top: 0; }
        }
        .search-box input { width: 100%; background: rgba(30, 41, 59, 0.8); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; padding: 8px 12px 8px 36px; color: #fff; font-size: 0.85rem; outline: none; transition: all 0.3s; }
        .search-box input:focus { border-color: #38bdf8; box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2); }
        .search-box i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem; }

        .alert-msg { padding: 10px 15px; border-radius: 10px; font-size: 0.82rem; margin-bottom: 15px; display: flex; align-items: center; gap: 8px; flex-shrink: 0; position: relative; z-index: 15; }
        .alert-success { background: rgba(14, 159, 110, 0.2); border: 1px solid rgba(52, 211, 153, 0.4); color: #34d399; }
        .alert-error { background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5; }
        .fade-out { opacity: 0; transform: translateY(-10px); }

        .form-grid { display: grid; grid-template-columns: 1fr; gap: 12px; margin-bottom: 12px; }
        @media (min-width: 600px) {
            .form-grid { grid-template-columns: repeat(2, 1fr); gap: 15px; }
        }
        @media (min-width: 1024px) {
            .form-grid { grid-template-columns: repeat(3, 1fr); }
        }

        .form-control { width: 100%; background: rgba(30, 41, 59, 0.8); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; padding: 10px 14px; color: #fff; font-size: 0.88rem; outline: none; }
        .form-control:focus { border-color: #38bdf8; box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2); }
        
        .table-responsive { width: 100%; flex: 1; min-height: 0; overflow-y: auto; overflow-x: auto; -webkit-overflow-scrolling: touch; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; background: rgba(15, 23, 42, 0.5); }
        
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.85rem; min-width: 800px; }
        th { background: rgba(30, 41, 59, 0.98); color: #38bdf8; padding: 12px 15px; font-weight: 600; position: sticky; top: 0; z-index: 10; white-space: nowrap; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
        td { padding: 10px 15px; border-bottom: 1px solid rgba(255,255,255,0.06); color: #cbd5e1; vertical-align: middle; }
        tr:hover td { background: rgba(255,255,255,0.02); }

        .badge-rol { padding: 3px 8px; border-radius: 6px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; white-space: nowrap; }
        .badge-admin { background: rgba(124, 58, 237, 0.2); color: #a78bfa; border: 1px solid rgba(167, 139, 250, 0.3); }
        .badge-profesor { background: rgba(14, 165, 233, 0.2); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); }
        .badge-administrativo { background: rgba(217, 119, 6, 0.2); color: #fcd34d; border: 1px solid rgba(252, 211, 77, 0.3); }
        .badge-log { padding: 3px 8px; border-radius: 6px; font-size: 0.7rem; font-weight: 700; background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); white-space: nowrap; }

        .btn-table { padding: 5px 8px; border-radius: 6px; font-size: 0.72rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; transition: opacity 0.2s; white-space: nowrap; }
        .btn-table:hover { opacity: 0.8; }
        .btn-edit { background: rgba(52, 211, 153, 0.2); color: #34d399; border: 1px solid rgba(52, 211, 153, 0.3); }
        .btn-del { background: rgba(239, 68, 68, 0.2); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.3); }
        
        .loading-tse { font-size: 0.78rem; color: #38bdf8; margin-top: 4px; display: none; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <!-- Contenedor dinámico de burbujas interactivas -->
    <div class="bubbles-background" id="bubblesContainer"></div>

    <div class="header-bar">
        <div class="header-title">
            <h2><i class="fa-solid fa-screwdriver-wrench"></i> Nivel Soporte Técnico</h2>
            <p>Administración general del sistema y control de usuarios institucionales.</p>
        </div>
        <div class="nav-buttons">
            <a href="/sistema/public/index.php?route=dashboard" class="btn-action" title="Selector">
                <i class="fa-solid fa-arrow-left"></i> Selector
            </a>
            <a href="/sistema/public/index.php?route=logout" class="btn-action btn-danger" title="Salir">
                <i class="fa-solid fa-power-off"></i> Salir
            </a>
        </div>
    </div>

    <?php if (!empty($mensaje)): ?>
        <div id="alertMessage" class="alert-msg alert-success"><i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div id="alertError" class="alert-msg alert-error"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Menú de Pestañas -->
    <div class="support-tabs">
        <button class="tab-btn active" onclick="switchTab(event, 'tab-overview')" id="btn-tab-overview">
            <i class="fa-solid fa-chart-pie"></i> Resumen
        </button>
        <button class="tab-btn" onclick="switchTab(event, 'tab-register')" id="btn-tab-register">
            <i class="fa-solid fa-user-plus"></i> Registrar
        </button>
        <button class="tab-btn" onclick="switchTab(event, 'tab-directory')" id="btn-tab-directory">
            <i class="fa-solid fa-address-book"></i> Directorio
        </button>
        <button class="tab-btn" onclick="switchTab(event, 'tab-logs')" id="btn-tab-logs">
            <i class="fa-solid fa-clock-rotate-left"></i> Auditoría
        </button>
    </div>

    <!-- PESTAÑA 1: RESUMEN Y SERVIDOR -->
    <div id="tab-overview" class="tab-content" style="overflow-y: auto;">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                <div class="stat-info">
                    <h3><?php echo $stats['total'] ?? 0; ?></h3>
                    <p>Total Usuarios</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #059669, #0d9488);"><i class="fa-solid fa-chalkboard-user"></i></div>
                <div class="stat-info">
                    <h3><?php echo $stats['docentes'] ?? 0; ?></h3>
                    <p>Docentes</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #d97706, #ca8a04);"><i class="fa-solid fa-user-tie"></i></div>
                <div class="stat-info">
                    <h3><?php echo $stats['administrativos'] ?? 0; ?></h3>
                    <p>Administrativos</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #7c3aed, #4f46e5);"><i class="fa-solid fa-shield-halved"></i></div>
                <div class="stat-info">
                    <h3><?php echo $stats['soporte'] ?? 0; ?></h3>
                    <p>Soporte / Admin</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ea580c, #b45309);"><i class="fa-solid fa-database"></i></div>
                <div class="stat-info">
                    <h3><?php echo $stats['db_size'] ?? 0; ?> MB</h3>
                    <p>Base de Datos</p>
                </div>
            </div>
        </div>

        <div class="content-card" style="flex: unset;">
            <h3><i class="fa-solid fa-server"></i> Monitoreo y Servidor</h3>
            <p style="color: #94a3b8; margin-bottom: 12px; font-size: 0.82rem; line-height: 1.5;">Gestión de mantenimiento y diagnóstico de infraestructura para SAVA.</p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-bottom: 15px;">
                <div style="background: rgba(30, 41, 59, 0.8); border: 1px solid rgba(255,255,255,0.08); padding: 12px 16px; border-radius: 10px;">
                    <span style="font-size: 0.72rem; color: #94a3b8; display: block; text-transform: uppercase;">Versión PHP</span>
                    <strong style="font-size: 0.95rem; color: #38bdf8;"><?php echo $serverInfo['php_version'] ?? PHP_VERSION; ?></strong>
                </div>
                <div style="background: rgba(30, 41, 59, 0.8); border: 1px solid rgba(255,255,255,0.08); padding: 12px 16px; border-radius: 10px;">
                    <span style="font-size: 0.72rem; color: #94a3b8; display: block; text-transform: uppercase;">Espacio en Disco</span>
                    <strong style="font-size: 0.95rem; color: #34d399;"><?php echo ($serverInfo['disk_free'] ?? 'N/A') . ' / ' . ($serverInfo['disk_total'] ?? 'N/A'); ?></strong>
                </div>
            </div>

            <!-- Botón de Optimización / Limpieza de Caché -->
            <div style="padding-top: 12px; border-top: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <div>
                    <h4 style="font-size: 0.85rem; color: #f8fafc; margin-bottom: 3px;"><i class="fa-solid fa-broom"></i> Optimización de Sistema</h4>
                    <p style="font-size: 0.75rem; color: #94a3b8;">Limpia archivos temporales y caché del sistema.</p>
                </div>
                <a href="/sistema/public/index.php?route=soporte-optimizar" class="btn-action" style="background: linear-gradient(135deg, #059669, #0d9488); border: none;" onclick="return confirm('¿Desea optimizar el sistema y limpiar los datos de caché?');">
                    <i class="fa-solid fa-bolt"></i> Optimizar Caché
                </a>
            </div>
        </div>
    </div>

    <!-- PESTAÑA 2: REGISTRAR USUARIO -->
    <div id="tab-register" class="tab-content" style="overflow-y: auto;">
        <div class="content-card" style="flex: unset;">
            <h3><i class="fa-solid fa-user-plus"></i> Registrar Nuevo Usuario</h3>
            <form action="/sistema/public/index.php?route=soporte-crear-usuario" method="POST" id="registerForm">
                <input type="hidden" name="coordenadas_gps" id="coordenadasGpsInput">
                <div class="form-grid">
                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label style="font-size: 0.78rem; font-weight: 600; color: #38bdf8;">Cédula *</label>
                        <input type="text" id="cedulaInput" name="cedula" class="form-control" placeholder="Ej: 101230456" maxlength="12" required>
                        <div id="tseLoading" class="loading-tse"><i class="fa-solid fa-spinner fa-spin"></i> Consultando Padrón...</div>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label style="font-size: 0.78rem; font-weight: 600; color: #38bdf8;">Nombre(s) *</label>
                        <input type="text" id="nombreInput" name="nombre" class="form-control" placeholder="Nombre" required>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label style="font-size: 0.78rem; font-weight: 600; color: #38bdf8;">Apellidos *</label>
                        <input type="text" id="apellidosInput" name="apellidos" class="form-control" placeholder="Apellidos" required>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label style="font-size: 0.78rem; font-weight: 600; color: #38bdf8;">Usuario *</label>
                        <input type="text" id="usuarioInput" name="usuario" class="form-control" placeholder="nombre.apellido" required>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label style="font-size: 0.78rem; font-weight: 600; color: #38bdf8;">Correo Institucional</label>
                        <input type="email" name="correo" class="form-control" placeholder="correo@mep.go.cr">
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label style="font-size: 0.78rem; font-weight: 600; color: #38bdf8;">Contraseña Temporal *</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label style="font-size: 0.78rem; font-weight: 600; color: #38bdf8;">Rol de Usuario *</label>
                        <select name="rol" class="form-control">
                            <option value="profesor">Docente</option>
                            <option value="administrativo">Administrativo</option>
                            <option value="admin">Administrador / Soporte</option>
                        </select>
                    </div>
                </div>
                <button type="button" onclick="capturarUbicacionYEnviar('registerForm')" class="btn-action" style="background: linear-gradient(135deg, #0284c7, #2563eb); border: none; padding: 10px 22px; margin-top: 12px;">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar Usuario
                </button>
            </form>
        </div>
    </div>

    <!-- PESTAÑA 3: DIRECTORIO DE USUARIOS -->
    <div id="tab-directory" class="tab-content">
        <div class="content-card" id="directoryCard">
            <h3>
                <span><i class="fa-solid fa-address-book"></i> Directorio</span>
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchInput" placeholder="Filtrar por nombre, cédula..." onkeyup="filterTable('searchInput', 'userTable')">
                </div>
            </h3>
            <div class="table-responsive">
                <table id="userTable">
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
                        <?php if (!empty($usuarios)): ?>
                            <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td>#<?php echo $u['id']; ?></td>
                                <td><?php echo htmlspecialchars($u['cedula']); ?></td>
                                <td><strong><?php echo htmlspecialchars($u['nombre'] . ' ' . $u['apellidos']); ?></strong></td>
                                <td><code><?php echo htmlspecialchars($u['usuario']); ?></code></td>
                                <td><?php echo htmlspecialchars($u['correo']); ?></td>
                                <td>
                                    <span class="badge-rol <?php 
                                        if ($u['rol'] === 'soporte' || $u['rol'] === 'admin') echo 'badge-admin';
                                        elseif ($u['rol'] === 'administrativo') echo 'badge-administrativo';
                                        else echo 'badge-profesor';
                                    ?>">
                                        <?php echo htmlspecialchars($u['rol']); ?>
                                    </span>
                                </td>
                                <td><span style="color: <?php echo $u['estado'] ? '#34d399' : '#fca5a5'; ?>;"><?php echo $u['estado'] ? 'Activo' : 'Inactivo'; ?></span></td>
                                <td>
                                    <div style="display: flex; gap: 6px;">
                                        <a href="/sistema/public/index.php?route=soporte-editar&id=<?php echo $u['id']; ?>" class="btn-table btn-edit" title="Editar"><i class="fa-solid fa-pen"></i></a>
                                        <?php if ((int)$u['id'] !== (int)($_SESSION['user']['id'] ?? 0)): ?>
                                        <a href="/sistema/public/index.php?route=soporte-eliminar&id=<?php echo $u['id']; ?>" class="btn-table btn-del" onclick="return confirm('¿Estás seguro de eliminar este usuario?');" title="Eliminar"><i class="fa-solid fa-trash"></i></a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- PESTAÑA 4: LOGS Y AUDITORÍA -->
    <div id="tab-logs" class="tab-content">
        <div class="content-card">
            <h3>
                <span><i class="fa-solid fa-clock-rotate-left"></i> Auditoría</span>
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchLogsInput" placeholder="Buscar por usuario, acción..." onkeyup="filterTable('searchLogsInput', 'logsTable')">
                </div>
            </h3>

            <div class="table-responsive">
                <table id="logsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario Responsable</th>
                            <th>Acción</th>
                            <th>Detalles</th>
                            <th>IP / Red</th>
                            <th>Coordenadas GPS</th>
                            <th>Fecha y Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($logs)): ?>
                            <?php foreach ($logs as $log): ?>
                            <tr>
                                <td>#<?php echo $log['id']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars(($log['nombre'] ?? '') . ' ' . ($log['apellidos'] ?? 'Sistema')); ?></strong><br>
                                    <span style="font-size: 0.72rem; color: #38bdf8;">@<?php echo htmlspecialchars($log['username'] ?? 'sistema'); ?></span>
                                </td>
                                <td><span class="badge-log"><?php echo htmlspecialchars($log['accion']); ?></span></td>
                                <td><?php echo htmlspecialchars($log['detalles']); ?></td>
                                <td><code><?php echo htmlspecialchars($log['ip_address'] ?? 'N/A'); ?></code></td>
                                <td>
                                    <?php if (!empty($log['coordenadas'])): ?>
                                        <a href="https://maps.google.com/?q=<?php echo $log['coordenadas']; ?>" target="_blank" style="color: #38bdf8; text-decoration: none; font-size: 0.8rem;" title="Ver en Google Maps">
                                            <i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($log['coordenadas']); ?>
                                        </a>
                                    <?php else: ?>
                                        <span style="color: #64748b; font-size: 0.75rem;">No disponible</span>
                                    <?php endif; ?>
                                </td>
                                <td style="white-space: nowrap;"><?php echo !empty($log['created_at']) ? date('d/m/Y H:i:s', strtotime($log['created_at'])) : 'N/A'; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align: center; color: #94a3b8; padding: 20px;">No hay registros de auditoría disponibles.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<script>
    function capturarUbicacionYEnviar(formId) {
        const form = document.getElementById(formId);
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    document.getElementById("coordenadasGpsInput").value = lat + "," + lon;
                    form.submit();
                },
                function(error) {
                    console.warn("Geolocalización denegada o no disponible:", error.message);
                    form.submit();
                },
                { timeout: 10000, maximumAge: 60000 }
            );
        } else {
            form.submit();
        }
    }

    function limpiarTextoParaUsuario(texto) {
        return texto.toLowerCase()
            .normalize("NFD").replace(/[\u0300-\u036f]/g, "")
            .replace(/[^a-z0-9.]/g, "");
    }

    document.getElementById("cedulaInput").addEventListener("blur", function() {
        const cedula = this.value.trim();
        const loadingDiv = document.getElementById("tseLoading");
        
        if (cedula.length >= 9) {
            loadingDiv.style.display = "block";
            fetch("https://api.hacienda.go.cr/fe/ae?identificacion=" + cedula)
                .then(response => response.json())
                .then(data => {
                    loadingDiv.style.display = "none";
                    if (data && data.nombre) {
                        let partes = data.nombre.trim().split(/\s+/);
                        let nombres = "";
                        let apellidos = "";
                        
                        if (partes.length >= 4) {
                            nombres = partes[0] + " " + partes[1];
                            apellidos = partes[2] + " " + partes[3];
                        } else if (partes.length === 3) {
                            nombres = partes[0];
                            apellidos = partes[1] + " " + partes[2];
                        } else if (partes.length === 2) {
                            nombres = partes[0];
                            apellidos = partes[1];
                        } else {
                            nombres = data.nombre;
                            apellidos = "";
                        }
                        
                        document.getElementById("nombreInput").value = nombres;
                        document.getElementById("apellidosInput").value = apellidos;

                        let primerNombre = partes[0] || "";
                        let primerApellido = (partes.length >= 4) ? partes[2] : (partes.length === 3 ? partes[1] : (partes.length === 2 ? partes[1] : ""));
                        
                        let usuarioGenerado = limpiarTextoParaUsuario(primerNombre + "." + primerApellido);
                        document.getElementById("usuarioInput").value = usuarioGenerado;
                    }
                })
                .catch(error => {
                    loadingDiv.style.display = "none";
                    console.error("Error al consultar la cédula:", error);
                });
        }
    });

    document.getElementById("usuarioInput").addEventListener("input", function() {
        this.value = limpiarTextoParaUsuario(this.value);
    });

    function filterTable(inputId, tableId) {
        const input = document.getElementById(inputId);
        const filter = input.value.toLowerCase();
        const table = document.getElementById(tableId);
        const tr = table.getElementsByTagName("tr");
        for (let i = 1; i < tr.length; i++) {
            let visible = false;
            const td = tr[i].getElementsByTagName("td");
            for (let j = 0; j < td.length; j++) {
                if (td[j]) {
                    const txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        visible = true;
                        break;
                    }
                }
            }
            tr[i].style.display = visible ? "" : "none";
        }
    }

    function switchTab(evt, tabId) {
        const contents = document.querySelectorAll(".tab-content");
        contents.forEach(content => content.classList.remove("active"));
        const buttons = document.querySelectorAll(".tab-btn");
        buttons.forEach(btn => btn.classList.remove("active"));
        document.getElementById(tabId).classList.add("active");
        if (evt && evt.currentTarget) {
            evt.currentTarget.classList.add("active");
        } else {
            const btn = document.getElementById("btn-" + tabId);
            if (btn) btn.classList.add("active");
        }
        localStorage.setItem("support_active_tab", tabId);
    }

    document.addEventListener("DOMContentLoaded", function() {
        const savedTab = localStorage.getItem("support_active_tab");
        if (savedTab && document.getElementById(savedTab)) {
            switchTab(null, savedTab);
        }

        const alertMessages = document.querySelectorAll(".alert-msg");
        if (alertMessages.length > 0) {
            setTimeout(function() {
                alertMessages.forEach(alert => {
                    alert.classList.add("fade-out");
                    setTimeout(() => alert.remove(), 500);
                });
            }, 3000);
        }

        // Motor de burbujas interactivas
        const container = document.getElementById('bubblesContainer');
        if (container) {
            const bubbleCount = 20;
            for (let i = 0; i < bubbleCount; i++) {
                const bubble = document.createElement('div');
                bubble.classList.add('bubble');

                const size = Math.floor(Math.random() * 65) + 20;
                const leftPos = Math.random() * 100;
                const duration = Math.random() * 12 + 8;
                const delay = Math.random() * 10;

                bubble.style.width = `${size}px`;
                bubble.style.height = `${size}px`;
                bubble.style.left = `${leftPos}%`;
                bubble.style.animationDuration = `${duration}s`;
                bubble.style.animationDelay = `${delay}s`;

                bubble.addEventListener('mouseenter', function() { popBubble(bubble); });
                bubble.addEventListener('touchstart', function(e) { e.preventDefault(); popBubble(bubble); });

                container.appendChild(bubble);
            }
        }

        function popBubble(bubble) {
            if (bubble.classList.contains('pop')) return;
            bubble.classList.add('pop');
            setTimeout(() => {
                bubble.remove();
                createNewBubble(document.getElementById('bubblesContainer'));
            }, 300);
        }

        function createNewBubble(parent) {
            if (!parent) return;
            const bubble = document.createElement('div');
            bubble.classList.add('bubble');
            const size = Math.floor(Math.random() * 65) + 20;
            const leftPos = Math.random() * 100;
            const duration = Math.random() * 12 + 8;

            bubble.style.width = `${size}px`;
            bubble.style.height = `${size}px`;
            bubble.style.left = `${leftPos}%`;
            bubble.style.animationDuration = `${duration}s`;
            bubble.style.animationDelay = `0s`;

            bubble.addEventListener('mouseenter', function() { popBubble(bubble); });
            bubble.addEventListener('touchstart', function(e) { e.preventDefault(); popBubble(bubble); });

            parent.appendChild(bubble);
        }
    });
</script>
</body>
</html>
