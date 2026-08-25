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

        .support-tabs { display: flex; gap: 10px; width: 100%; margin-bottom: 25px; overflow-x: auto; padding-bottom: 5px; }
        .tab-btn { background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.08); color: #94a3b8; padding: 12px 22px; border-radius: 14px; font-size: 0.9rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 10px; transition: all 0.3s ease; white-space: nowrap; }
        .tab-btn:hover { color: #f8fafc; background: rgba(30, 41, 59, 0.8); }
        .tab-btn.active { background: linear-gradient(135deg, #0284c7, #2563eb); color: #fff; border-color: rgba(56, 189, 248, 0.4); box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3); }

        .tab-content { display: none; width: 100%; animation: fadeIn 0.4s ease forwards; }
        .tab-content.active { display: block; }

        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 25px; }
        .stat-card { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.08); padding: 20px; border-radius: 18px; display: flex; align-items: center; gap: 18px; box-shadow: 0 10px 25px rgba(0,0,0,0.3); }
        .stat-icon { width: 50px; height: 50px; background: linear-gradient(135deg, #0284c7, #2563eb); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: #fff; }
        .stat-info h3 { font-size: 1.6rem; font-weight: 700; color: #f8fafc; }
        .stat-info p { font-size: 0.8rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }

        .content-card { background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.4); margin-bottom: 25px; }
        .content-card h3 { font-size: 1.15rem; margin-bottom: 15px; color: #f8fafc; display: flex; align-items: center; gap: 10px; justify-content: space-between; flex-wrap: wrap; }

        .search-box { position: relative; min-width: 280px; }
        .search-box input { width: 100%; background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 10px 15px 10px 40px; color: #fff; font-size: 0.88rem; outline: none; transition: all 0.3s; }
        .search-box input:focus { border-color: #38bdf8; box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2); }
        .search-box i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.9rem; }

        .logs-filters { display: flex; gap: 10px; margin-bottom: 15px; flex-wrap: wrap; align-items: center; justify-content: space-between; }
        .logs-filter-group { display: flex; gap: 10px; flex-wrap: wrap; }
        .filter-btn { background: rgba(30, 41, 59, 0.8); border: 1px solid rgba(255, 255, 255, 0.1); color: #94a3b8; padding: 6px 14px; border-radius: 10px; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: all 0.2s; }
        .filter-btn:hover, .filter-btn.active { background: #0284c7; color: #fff; border-color: #38bdf8; }

        .live-indicator { display: inline-flex; align-items: center; gap: 6px; font-size: 0.78rem; color: #34d399; font-weight: 600; background: rgba(52, 211, 153, 0.1); padding: 4px 10px; border-radius: 20px; border: 1px solid rgba(52, 211, 153, 0.2); }
        .live-dot { width: 8px; height: 8px; background-color: #34d399; border-radius: 50%; animation: pulseLive 1.5s infinite; }
        @keyframes pulseLive {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.3); opacity: 1; box-shadow: 0 0 8px #34d399; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }

        .alert-msg { padding: 12px 18px; border-radius: 12px; font-size: 0.88rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; transition: opacity 0.5s ease, transform 0.5s ease; }
        .alert-success { background: rgba(14, 159, 110, 0.2); border: 1px solid rgba(52, 211, 153, 0.4); color: #34d399; }
        .alert-error { background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5; }
        .fade-out { opacity: 0; transform: translateY(-10px); }

        .table-flash { animation: flashEffect 0.8s ease; }
        @keyframes flashEffect {
            0% { background-color: rgba(56, 189, 248, 0.3); }
            50% { background-color: rgba(56, 189, 248, 0.1); }
            100% { background-color: transparent; }
        }

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
        .badge-log { padding: 4px 10px; border-radius: 8px; font-size: 0.72rem; font-weight: 700; background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); }

        .btn-table { padding: 5px 10px; border-radius: 8px; font-size: 0.75rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: opacity 0.2s; }
        .btn-table:hover { opacity: 0.8; }
        .btn-edit { background: rgba(52, 211, 153, 0.2); color: #34d399; border: 1px solid rgba(52, 211, 153, 0.3); }
        .btn-toggle { background: rgba(56, 189, 248, 0.2); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); }
        .btn-del { background: rgba(239, 68, 68, 0.2); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.3); }
        
        .loading-tse { font-size: 0.78rem; color: #38bdf8; margin-top: 4px; display: none; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
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
        <div id="alertMessage" class="alert-msg alert-success"><i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div id="alertError" class="alert-msg alert-error"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Menú de Pestañas -->
    <div class="support-tabs">
        <button class="tab-btn active" onclick="switchTab(event, 'tab-overview')" id="btn-tab-overview">
            <i class="fa-solid fa-chart-pie"></i> Resumen y Servidor
        </button>
        <button class="tab-btn" onclick="switchTab(event, 'tab-register')" id="btn-tab-register">
            <i class="fa-solid fa-user-plus"></i> Registrar Nuevo Usuario
        </button>
        <button class="tab-btn" onclick="switchTab(event, 'tab-directory')" id="btn-tab-directory">
            <i class="fa-solid fa-address-book"></i> Directorio de Usuarios
        </button>
        <button class="tab-btn" onclick="switchTab(event, 'tab-logs')" id="btn-tab-logs">
            <i class="fa-solid fa-clock-rotate-left"></i> Logs y Auditoría
        </button>
    </div>

    <!-- PESTAÑA 1: RESUMEN Y SERVIDOR -->
    <div id="tab-overview" class="tab-content active">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                <div class="stat-info">
                    <h3><?php echo $stats['total']; ?></h3>
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
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ea580c, #ca8a04);"><i class="fa-solid fa-database"></i></div>
                <div class="stat-info">
                    <h3><?php echo $stats['db_size']; ?> MB</h3>
                    <p>Tamaño Base de Datos</p>
                </div>
            </div>
        </div>

        <div class="content-card">
            <h3><i class="fa-solid fa-server"></i> Monitoreo y Herramientas del Servidor</h3>
            <p style="color: #94a3b8; margin-bottom: 20px; line-height: 1.6;">Gestión directa de mantenimiento y diagnóstico de infraestructura para el sistema SAVA.</p>
            
            <div style="display: flex; gap: 15px; flex-wrap: wrap; margin-bottom: 20px;">
                <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(255,255,255,0.08); padding: 15px 20px; border-radius: 14px; flex: 1; min-width: 200px;">
                    <span style="font-size: 0.78rem; color: #94a3b8; display: block; text-transform: uppercase;">Versión PHP</span>
                    <strong style="font-size: 1.1rem; color: #38bdf8;"><?php echo $serverInfo['php_version']; ?></strong>
                </div>
                <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(255,255,255,0.08); padding: 15px 20px; border-radius: 14px; flex: 1; min-width: 200px;">
                    <span style="font-size: 0.78rem; color: #94a3b8; display: block; text-transform: uppercase;">Espacio Libre en Disco</span>
                    <strong style="font-size: 1.1rem; color: #34d399;"><?php echo $serverInfo['disk_free']; ?> / <?php echo $serverInfo['disk_total']; ?></strong>
                </div>
            </div>

            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <a href="/sistema/public/index.php?route=soporte-backup" class="btn-action" style="background: linear-gradient(135deg, #0284c7, #2563eb); border: none; padding: 12px 22px;">
                    <i class="fa-solid fa-download"></i> Descargar Respaldo de Base de Datos (.sql)
                </a>
            </div>
        </div>
    </div>

    <!-- PESTAÑA 2: REGISTRAR USUARIO -->
    <div id="tab-register" class="tab-content">
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
    </div>

    <!-- PESTAÑA 3: DIRECTORIO DE USUARIOS -->
    <div id="tab-directory" class="tab-content">
        <div class="content-card" id="directoryCard">
            <h3>
                <span><i class="fa-solid fa-address-book"></i> Directorio de Usuarios</span>
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchInput" placeholder="Filtrar por nombre, cédula, rol..." onkeyup="filterTable('searchInput', 'userTable')">
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
    </div>

    <!-- PESTAÑA 4: LOGS Y AUDITORÍA -->
    <div id="tab-logs" class="tab-content">
        <div class="content-card">
            <h3>
                <span>
                    <i class="fa-solid fa-clock-rotate-left"></i> Historial de Auditoría y Accesos 
                    <span class="live-indicator" style="margin-left: 10px;"><span class="live-dot"></span> En Vivo</span>
                </span>
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchLogsInput" placeholder="Buscar por usuario, acción, IP..." onkeyup="filterTable('searchLogsInput', 'logsTable')">
                </div>
            </h3>

            <!-- Botones de Filtro Rápido -->
            <div class="logs-filters">
                <div class="logs-filter-group">
                    <button class="filter-btn active" onclick="filterLogs('TODOS', this)">Todos</button>
                    <button class="filter-btn" onclick="filterLogs('INICIO DE SESIÓN', this)">Logins</button>
                    <button class="filter-btn" onclick="filterLogs('CIERRE DE SESIÓN', this)">Logouts</button>
                    <button class="filter-btn" onclick="filterLogs('CAMBIO DE ESTADO', this)">Estados</button>
                    <button class="filter-btn" onclick="filterLogs('CREAR USUARIO', this)">Creaciones</button>
                    <button class="filter-btn" onclick="filterLogs('ELIMINAR USUARIO', this)">Eliminaciones</button>
                    <button class="filter-btn" onclick="filterLogs('RESPALDO BD', this)">Respaldos</button>
                </div>
                <button class="filter-btn" onclick="refreshLogsManual()" title="Actualizar ahora" style="background: rgba(56, 189, 248, 0.2); color: #38bdf8; border-color: rgba(56, 189, 248, 0.4);">
                    <i class="fa-solid fa-sync"></i> Actualizar
                </button>
            </div>

            <div class="table-responsive">
                <table id="logsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Acción</th>
                            <th>Detalles y Responsable</th>
                            <th>IP</th>
                            <th>Fecha y Hora</th>
                        </tr>
                    </thead>
                    <tbody id="logsTableBody">
                        <?php if (!empty($logs)): ?>
                            <?php foreach ($logs as $log): ?>
                            <tr>
                                <td>#<?php echo $log['id']; ?></td>
                                <td><span class="badge-log"><?php echo htmlspecialchars($log['accion']); ?></span></td>
                                <td><?php echo htmlspecialchars($log['detalles']); ?></td>
                                <td><code><?php echo htmlspecialchars($log['ip_address']); ?></code></td>
                                <td><?php echo htmlspecialchars($log['created_at']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: #94a3b8; padding: 20px;">No hay registros de auditoría disponibles aún.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Función reutilizable de búsqueda en tablas
        function filterTable(inputId, tableId) {
            const input = document.getElementById(inputId);
            const filter = input.value.toLowerCase();
            const table = document.getElementById(tableId);
            const tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                let visible = false;
                const td = tr[i].getElementsByTagName('td');
                for (let j = 0; j < td.length; j++) {
                    if (td[j]) {
                        const txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toLowerCase().indexOf(filter) > -1) {
                            visible = true;
                            break;
                        }
                    }
                }
                tr[i].style.display = visible ? '' : 'none';
            }
        }

        // Filtro rápido por tipo de acción en la tabla de logs
        function filterLogs(accionTipo, btnElement) {
            document.querySelectorAll('.logs-filter-group .filter-btn').forEach(btn => btn.classList.remove('active'));
            btnElement.classList.add('active');

            const table = document.getElementById('logsTable');
            const tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                if (accionTipo === 'TODOS') {
                    tr[i].style.display = '';
                } else {
                    const badge = tr[i].querySelector('.badge-log');
                    if (badge) {
                        const txt = badge.textContent || badge.innerText;
                        tr[i].style.display = (txt.trim() === accionTipo) ? '' : 'none';
                    }
                }
            }
        }

        // --- AUTO-REFRESCO DE LOGS CADA 5 SEGUNDOS ---
        function refreshLogs() {
            const logsTab = document.getElementById('tab-logs');
            if (logsTab && logsTab.classList.contains('active')) {
                fetch(window.location.href)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newLogsBody = doc.getElementById('logsTableBody');
                        if (newLogsBody) {
                            document.getElementById('logsTableBody').innerHTML = newLogsBody.innerHTML;
                        }
                    })
                    .catch(err => console.error("Error al actualizar logs en vivo:", err));
            }
        }

        function refreshLogsManual() {
            refreshLogs();
        }

        setInterval(refreshLogs, 5000);

        function switchTab(evt, tabId) {
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => content.classList.remove('active'));

            const buttons = document.querySelectorAll('.tab-btn');
            buttons.forEach(btn => btn.classList.remove('active'));

            document.getElementById(tabId).classList.add('active');
            if (evt && evt.currentTarget) {
                evt.currentTarget.classList.add('active');
            } else {
                const btn = document.getElementById('btn-' + tabId);
                if (btn) btn.classList.add('active');
            }

            localStorage.setItem('support_active_tab', tabId);
        }

        document.addEventListener("DOMContentLoaded", function() {
            const savedTab = localStorage.getItem('support_active_tab');
            if (savedTab && document.getElementById(savedTab)) {
                switchTab(null, savedTab);
            }

            const alertMsg = document.getElementById('alertMessage');
            const alertErr = document.getElementById('alertError');

            if (alertMsg || alertErr) {
                const tableCard = document.getElementById('directoryCard');
                if (tableCard) {
                    tableCard.classList.add('table-flash');
                }

                setTimeout(() => {
                    if (alertMsg) {
                        alertMsg.classList.add('fade-out');
                        setTimeout(() => alertMsg.remove(), 500);
                    }
                    if (alertErr) {
                        alertErr.classList.add('fade-out');
                        setTimeout(() => alertErr.remove(), 500);
                    }
                }, 3000);
            }
        });

        // Script de consulta de cédula limpio
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
                            let partesNombre = data.nombre.trim().replace(/\s+/g, ' ').split(' ');
                            if (partesNombre.length >= 3) {
                                let apellido2 = partesNombre.pop();
                                let apellido1 = partesNombre.pop();
                                let nombreReal = partesNombre.join(' ');

                                document.getElementById('nombreInput').value = nombreReal;
                                document.getElementById('apellidosInput').value = `${apellido1} ${apellido2}`;
                                
                                let primerNombre = partesNombre[0].toLowerCase();
                                let primerApellido = apellido1.toLowerCase();
                                document.getElementById('usuarioInput').value = `${primerNombre}.${primerApellido}`;
                            } else {
                                document.getElementById('nombreInput').value = data.nombre;
                                document.getElementById('apellidosInput').value = "";
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
