<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SAVA &bull; Nivel Administrativo</title>
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
        html, body { min-height: 100vh; overflow-y: auto; background-color: var(--bg-color); color: #f8fafc; }
        
        .bubbles-background {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            z-index: 0; background: var(--gradient-bg); overflow: hidden; pointer-events: none;
        }

        .bubble {
            position: absolute; bottom: -100px;
            background: var(--bubble-grad); border: 1px solid var(--bubble-border);
            border-radius: 50%; box-shadow: 0 0 20px rgba(56, 189, 248, 0.15), inset 0 0 15px rgba(255, 255, 255, 0.25);
            animation: riseAndWobble linear infinite; backdrop-filter: blur(2px);
            z-index: 1; pointer-events: auto; cursor: pointer; transition: transform 0.1s ease, opacity 0.3s ease;
        }

        .bubble.pop {
            transform: scale(1.6) !important; opacity: 0 !important; filter: brightness(2);
            transition: transform 0.2s ease-out, opacity 0.2s ease-out;
        }

        @keyframes riseAndWobble {
            0% { transform: translateY(0) translateX(0) scale(1); opacity: 0; }
            15% { opacity: 0.85; }
            85% { opacity: 0.85; }
            100% { transform: translateY(-110vh) translateX(50px) scale(1.1); opacity: 0; }
        }

        body { display: flex; flex-direction: column; padding: 12px; position: relative; z-index: 1; }
        @media (min-width: 768px) { body { padding: 20px; } }

        .header-bar { display: flex; justify-content: space-between; align-items: center; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); padding: 12px 20px; border-radius: 16px; margin-bottom: 15px; box-shadow: 0 15px 35px rgba(0,0,0,0.4); flex-wrap: wrap; gap: 12px; flex-shrink: 0; position: relative; z-index: 10; }
        .header-title h2 { font-size: 1.15rem; color: #38bdf8; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        @media (min-width: 768px) { .header-title h2 { font-size: 1.4rem; } }
        .header-title p { font-size: 0.75rem; color: #94a3b8; }
        
        .nav-buttons { display: flex; gap: 8px; width: 100%; justify-content: flex-start; flex-wrap: wrap; }
        @media (min-width: 600px) { .nav-buttons { width: auto; } }

        .btn-action { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #fff; padding: 8px 14px; border-radius: 10px; text-decoration: none; font-size: 0.8rem; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 6px; transition: all 0.3s; cursor: pointer; flex: 1; min-width: 120px; }
        .btn-action:hover { background: rgba(56, 189, 248, 0.2); border-color: #38bdf8; transform: translateY(-2px); }
        .btn-danger { background: rgba(239, 68, 68, 0.15); border-color: rgba(239, 68, 68, 0.3); color: #fca5a5; }
        .btn-danger:hover { background: rgba(239, 68, 68, 0.3); color: #fff; }

        .admin-tabs { display: flex; gap: 8px; width: 100%; margin-bottom: 15px; overflow-x: auto; padding-bottom: 4px; -webkit-overflow-scrolling: touch; flex-shrink: 0; position: relative; z-index: 10; }
        .tab-btn { background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.08); color: #94a3b8; padding: 9px 16px; border-radius: 10px; font-size: 0.82rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; white-space: nowrap; flex-shrink: 0; }
        .tab-btn:hover { color: #f8fafc; background: rgba(30, 41, 59, 0.8); }
        .tab-btn.active { background: linear-gradient(135deg, #0284c7, #2563eb); color: #fff; border-color: rgba(56, 189, 248, 0.4); box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3); }

        .tab-content { display: none; width: 100%; flex-direction: column; animation: fadeIn 0.4s ease forwards; position: relative; z-index: 10; margin-bottom: 20px; }
        .tab-content.active { display: flex; }

        .content-card { background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 15px; box-shadow: 0 20px 40px rgba(0,0,0,0.4); display: flex; flex-direction: column; margin-bottom: 15px; }
        @media (min-width: 768px) { .content-card { border-radius: 18px; padding: 20px; } }
        .content-card h3 { font-size: 1.05rem; margin-bottom: 12px; color: #f8fafc; display: flex; align-items: center; gap: 10px; justify-content: space-between; flex-wrap: wrap; flex-shrink: 0; }

        .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px; }
        .kpi-card { background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 15px; display: flex; align-items: center; gap: 15px; }
        .kpi-icon { width: 45px; height: 45px; border-radius: 10px; background: rgba(56, 189, 248, 0.15); color: #38bdf8; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
        .kpi-info h4 { font-size: 0.78rem; color: #94a3b8; font-weight: 500; text-transform: uppercase; }
        .kpi-info p { font-size: 1.3rem; font-weight: 700; color: #fff; }

        .search-box { position: relative; width: 100%; margin-top: 5px; }
        @media (min-width: 500px) { .search-box { width: auto; min-width: 250px; margin-top: 0; } }
        .search-box input { width: 100%; background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; padding: 8px 12px 8px 36px; color: #fff; font-size: 0.85rem; outline: none; }
        .search-box input:focus { border-color: #38bdf8; box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2); }
        .search-box i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem; }

        .alert-msg { padding: 10px 15px; border-radius: 10px; font-size: 0.82rem; margin-bottom: 15px; display: flex; align-items: center; gap: 8px; flex-shrink: 0; position: relative; z-index: 10; }
        .alert-success { background: rgba(14, 159, 110, 0.2); border: 1px solid rgba(52, 211, 153, 0.4); color: #34d399; }
        .alert-error { background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5; }
        .fade-out { opacity: 0; transform: translateY(-10px); }

        .form-grid { display: grid; grid-template-columns: 1fr; gap: 12px; margin-bottom: 12px; }
        @media (min-width: 600px) { .form-grid { grid-template-columns: repeat(2, 1fr); gap: 15px; } }
        @media (min-width: 1024px) { .form-grid { grid-template-columns: repeat(3, 1fr); } }

        .form-control, .form-select { width: 100%; background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; padding: 10px 14px; color: #fff; font-size: 0.88rem; outline: none; }
        .form-control:focus, .form-select:focus { border-color: #38bdf8; box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2); }
        .form-select option { background: #0f172a; color: #fff; }

        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; background: rgba(15, 23, 42, 0.3); max-height: 450px; overflow-y: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.85rem; min-width: 800px; }
        th { background: rgba(30, 41, 59, 0.98); color: #38bdf8; padding: 12px 15px; font-weight: 600; position: sticky; top: 0; z-index: 10; white-space: nowrap; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
        td { padding: 10px 15px; border-bottom: 1px solid rgba(255,255,255,0.06); color: #cbd5e1; vertical-align: middle; }
        tr:hover td { background: rgba(255,255,255,0.02); }

        .btn-table { padding: 5px 8px; border-radius: 6px; font-size: 0.72rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; transition: opacity 0.2s; white-space: nowrap; }
        .btn-table:hover { opacity: 0.8; }
        .btn-edit { background: rgba(52, 211, 153, 0.2); color: #34d399; border: 1px solid rgba(52, 211, 153, 0.3); }
        .btn-del { background: rgba(239, 68, 68, 0.2); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.3); }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="bubbles-background" id="bubblesContainer"></div>

    <div class="header-bar">
        <div class="header-title">
            <h2><i class="fa-solid fa-user-shield"></i> Nivel Administrativo</h2>
            <p>Control institucional, ciclos lectivos, matrícula, cargas docentes y lección guía.</p>
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

    <!-- Menú de Pestañas Administrativas Integrales -->
    <div class="admin-tabs">
        <button class="tab-btn active" onclick="switchTab(event, 'tab-resumen')" id="btn-tab-resumen">
            <i class="fa-solid fa-chart-pie"></i> Dashboard Gerencial
        </button>
        <button class="tab-btn" onclick="switchTab(event, 'tab-periodos')" id="btn-tab-periodos">
            <i class="fa-solid fa-calendar-days"></i> Ciclo y Semestres
        </button>
        <button class="tab-btn" onclick="switchTab(event, 'tab-docentes')" id="btn-tab-docentes">
            <i class="fa-solid fa-chalkboard-user"></i> Docentes y Materias
        </button>
        <button class="tab-btn" onclick="switchTab(event, 'tab-asignacion')" id="btn-tab-asignacion">
            <i class="fa-solid fa-network-wired"></i> Asignación de Cargas
        </button>
        <button class="tab-btn" onclick="switchTab(event, 'tab-guia')" id="btn-tab-guia">
            <i class="fa-solid fa-user-graduate"></i> Lección Guía (Homeroom)
        </button>
        <button class="tab-btn" onclick="switchTab(event, 'tab-directorio')" id="btn-tab-directorio">
            <i class="fa-solid fa-address-book"></i> Directorio Estudiantes
        </button>
    </div>

    <!-- PESTAÑA DASHBOARD GERENCIAL -->
    <div id="tab-resumen" class="tab-content active">
        <div class="content-card">
            <h3><i class="fa-solid fa-chart-pie"></i> Panel de Control y Monitoreo Institucional</h3>
            <p style="color: #94a3b8; margin-bottom: 20px; font-size: 0.82rem;">Indicadores generales del Colegio Valle Azul y alertas operativas de asistencia.</p>
            
            <!-- Tarjetas KPI Principales -->
            <div class="kpi-grid">
                <div class="kpi-card">
                    <div class="kpi-icon"><i class="fa-solid fa-layer-group"></i></div>
                    <div class="kpi-info">
                        <h4>Secciones Regulares</h4>
                        <p><?php echo $totalRegular ?? 0; ?></p>
                    </div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-icon" style="background: rgba(168, 85, 247, 0.15); color: #c084fc;"><i class="fa-solid fa-book-bookmark"></i></div>
                    <div class="kpi-info">
                        <h4>Secciones Plan Nacional</h4>
                        <p><?php echo $totalPN ?? 0; ?></p>
                    </div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-icon" style="background: rgba(52, 211, 153, 0.15); color: #34d399;"><i class="fa-solid fa-user-graduate"></i></div>
                    <div class="kpi-info">
                        <h4>Total Estudiantes</h4>
                        <p><?php echo $totalEstudiantes ?? 0; ?></p>
                    </div>
                </div>
            </div>

            <!-- Sección de Alertas Operativas -->
            <div style="display: grid; grid-template-columns: 1fr; gap: 20px; margin-top: 10px;">
                
                <!-- Docentes que no pasan lista -->
                <div style="background: rgba(30, 41, 59, 0.4); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 15px;">
                    <h4 style="font-size: 0.95rem; color: #fca5a5; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-triangle-exclamation"></i> Docentes sin Registro Reciente de Asistencia
                    </h4>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Docente</th>
                                    <th>Correo</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($docentesSinLista)): ?>
                                    <?php foreach ($docentesSinLista as $doc): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($doc['nombre'] . ' ' . $doc['apellidos']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($doc['correo'] ?? 'N/A'); ?></td>
                                        <td><span style="color: #fca5a5; font-size: 0.78rem;">Pendiente de pase de lista</span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="3" style="text-align: center; color: #34d399; padding: 15px;"><i class="fa-solid fa-circle-check"></i> Todos los docentes han registrado asistencia recientemente.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Top 10 Estudiantes con Mayor Ausentismo -->
                <div style="background: rgba(30, 41, 59, 0.4); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 15px;">
                    <h4 style="font-size: 0.95rem; color: #38bdf8; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-user-xmark"></i> Top 10 Estudiantes con Mayor Ausentismo
                    </h4>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Estudiante</th>
                                    <th>Nivel y Sección</th>
                                    <th>Ausencias Registradas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($topAusentismo)): ?>
                                    <?php foreach ($topAusentismo as $alt): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($alt['nombre'] . ' ' . $alt['apellidos']); ?></strong></td>
                                        <td><code><?php echo htmlspecialchars($alt['nivel_nombre'] . ' - ' . $alt['seccion_nombre']); ?></code></td>
                                        <td><span style="color: #fca5a5; font-weight: 600;"><?php echo $alt['total_ausencias']; ?> faltas</span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="3" style="text-align: center; color: #34d399; padding: 15px;"><i class="fa-solid fa-circle-check"></i> No hay registros críticos de ausentismo.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- PESTAÑA CICLO LECTIVO Y SEMESTRES -->
    <div id="tab-periodos" class="tab-content">
        <div class="content-card">
            <h3><i class="fa-solid fa-calendar-days"></i> Gestión de Periodos Lectivos y Rotación de Talleres</h3>
            <p style="color: #94a3b8; margin-bottom: 15px; font-size: 0.82rem;">Habilite o alterne el semestre activo para la rotación institucional de talleres (7° a 9°).</p>
            <form action="/sistema/public/index.php?route=admin-crear-periodo" method="POST">
                <div class="form-grid">
                    <div>
                        <label style="font-size: 0.78rem; font-weight: 600; color: #38bdf8; display: block; margin-bottom: 5px;">Año Lectivo</label>
                        <input type="number" name="anio" value="2026" required class="form-control">
                    </div>
                    <div>
                        <label style="font-size: 0.78rem; font-weight: 600; color: #38bdf8; display: block; margin-bottom: 5px;">Semestre Activo</label>
                        <select name="semestre" class="form-select">
                            <option value="1">I Semestre</option>
                            <option value="2">II Semestre (Rotación Talleres)</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-size: 0.78rem; font-weight: 600; color: #38bdf8; display: block; margin-bottom: 5px;">Descripción Oficial</label>
                        <input type="text" name="nombre" placeholder="Ej. Periodo Lectivo Oficial 2026" required class="form-control">
                    </div>
                </div>
                <button type="submit" class="btn-action" style="background: linear-gradient(135deg, #0284c7, #2563eb); border: none; padding: 10px 20px; margin-top: 15px;">
                    <i class="fa-solid fa-power-off"></i> Habilitar y Activar Periodo
                </button>
            </form>
        </div>
    </div>

    <!-- PESTAÑA DOCENTES Y MATERIAS -->
    <div id="tab-docentes" class="tab-content">
        <div class="content-card">
            <h3><i class="fa-solid fa-user-plus"></i> Registro de Docentes e Habilitación de Asignaturas</h3>
            <form action="/sistema/public/index.php?route=admin-crear-docente" method="POST">
                <div class="form-grid">
                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label style="font-size: 0.78rem; font-weight: 600; color: #38bdf8;">Cédula *</label>
                        <input type="text" name="cedula" class="form-control" placeholder="Cédula" required>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label style="font-size: 0.78rem; font-weight: 600; color: #38bdf8;">Nombre(s) *</label>
                        <input type="text" name="nombre" class="form-control" style="text-transform: uppercase;" placeholder="Nombre" required>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label style="font-size: 0.78rem; font-weight: 600; color: #38bdf8;">Apellidos *</label>
                        <input type="text" name="apellidos" class="form-control" style="text-transform: uppercase;" placeholder="Apellidos" required>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label style="font-size: 0.78rem; font-weight: 600; color: #38bdf8;">Correo Institucional</label>
                        <input type="email" name="correo" class="form-control" placeholder="correo@mep.go.cr">
                    </div>
                </div>
                <div style="margin-top: 15px;">
                    <label style="font-size: 0.82rem; font-weight: 700; color: #38bdf8; display: block; margin-bottom: 8px;">Asignaturas que imparte:</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; background: rgba(30, 41, 59, 0.4); padding: 12px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.08);">
                        <?php if (!empty($materias)): ?>
                            <?php foreach($materias as $asig): ?>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <input type="checkbox" name="materias[]" value="<?php echo $asig['id']; ?>" id="mat_<?php echo $asig['id']; ?>" style="accent-color: #0ea5e9;">
                                    <label for="mat_<?php echo $asig['id']; ?>" style="font-size: 0.82rem; color: #cbd5e1; cursor: pointer;"><?php echo htmlspecialchars($asig['nombre']); ?></label>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <button type="submit" class="btn-action" style="background: linear-gradient(135deg, #059669, #0d9488); border: none; padding: 10px 20px; margin-top: 15px;">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar Docente
                </button>
            </form>
        </div>

        <div class="content-card">
            <h3>
                <span><i class="fa-solid fa-address-book"></i> Listado de Docentes Activos</span>
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchDocentes" placeholder="Filtrar docente..." onkeyup="filterTable('searchDocentes', 'tablaDocentes')">
                </div>
            </h3>
            <div class="table-responsive">
                <table id="tablaDocentes">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre del Docente</th>
                            <th>Correo</th>
                            <th>Materias Asignadas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($todosLosDocentes)): ?>
                            <?php foreach ($todosLosDocentes as $doc): ?>
                            <tr>
                                <td>#<?php echo $doc['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($doc['nombre'] . ' ' . $doc['apellidos']); ?></strong></td>
                                <td><?php echo htmlspecialchars($doc['correo'] ?? 'N/A'); ?></td>
                                <td><code><?php echo htmlspecialchars($doc['materias_nombres'] ?? 'Sin asignar'); ?></code></td>
                                <td>
                                    <div style="display: flex; gap: 6px;">
                                        <a href="/sistema/public/index.php?route=admin-eliminar-docente&id=<?php echo $doc['id']; ?>" class="btn-table btn-del" onclick="return confirm('¿Seguro que desea desactivar este docente?');" title="Eliminar"><i class="fa-solid fa-trash"></i></a>
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

    <!-- PESTAÑA ASIGNACIÓN DE CARGAS -->
    <div id="tab-asignacion" class="tab-content">
        <div class="content-card">
            <h3><i class="fa-solid fa-network-wired"></i> Asignar Docentes a Secciones</h3>
            <p style="color: #94a3b8; margin-bottom: 15px; font-size: 0.82rem;">Si asignas a un docente donde ya hay otro impartiendo la misma materia, el sistema gestionará el conflicto de historial.</p>
            <form id="formAsignacionSeccion">
                <div class="form-grid">
                    <div>
                        <label style="font-size: 0.78rem; font-weight: 600; color: #38bdf8; display: block; margin-bottom: 5px;">Sección</label>
                        <select class="form-select" name="seccion_id" required>
                            <option value="">-- Seleccione Sección --</option>
                            <?php if (!empty($secciones)): ?>
                                <?php foreach($secciones as $sec): ?>
                                    <option value="<?php echo $sec['id']; ?>"><?php echo htmlspecialchars($sec['nivel_nombre'] . ' - ' . $sec['seccion_nombre']); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <label style="font-size: 0.78rem; font-weight: 600; color: #38bdf8; display: block; margin-bottom: 5px;">Asignatura</label>
                        <select class="form-select" name="asignatura_id" required>
                            <option value="">-- Seleccione Asignatura --</option>
                            <?php if (!empty($materias)): ?>
                                <?php foreach($materias as $asig): ?>
                                    <option value="<?php echo $asig['id']; ?>"><?php echo htmlspecialchars($asig['nombre']); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <label style="font-size: 0.78rem; font-weight: 600; color: #38bdf8; display: block; margin-bottom: 5px;">Docente</label>
                        <select class="form-select" name="docente_id" required>
                            <option value="">-- Seleccione Docente --</option>
                            <?php if (!empty($todosLosDocentes)): ?>
                                <?php foreach($todosLosDocentes as $doc): ?>
                                    <option value="<?php echo $doc['id']; ?>"><?php echo htmlspecialchars($doc['nombre'] . ' ' . $doc['apellidos']); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <button type="button" onclick="verificarYAsignar()" class="btn-action" style="background: linear-gradient(135deg, #0284c7, #2563eb); border: none; padding: 10px 20px; margin-top: 15px;">
                    <i class="fa-solid fa-link"></i> Vincular Docente a Sección
                </button>
            </form>
        </div>
    </div>

    <!-- PESTAÑA LECCIÓN GUÍA -->
    <div id="tab-guia" class="tab-content">
        <div class="content-card">
            <h3><i class="fa-solid fa-user-graduate"></i> Asignación de Lección Guía (Homeroom)</h3>
            <form action="/sistema/public/index.php?route=admin-actualizar-guia" method="POST">
                <div class="form-grid">
                    <div>
                        <label style="font-size: 0.78rem; font-weight: 600; color: #38bdf8; display: block; margin-bottom: 5px;">Docente Guía</label>
                        <select class="form-select" name="docente_guia_id" required>
                            <option value="">-- Seleccionar Docente --</option>
                            <?php if (!empty($todosLosDocentes)): ?>
                                <?php foreach($todosLosDocentes as $doc): ?>
                                    <option value="<?php echo $doc['id']; ?>"><?php echo htmlspecialchars($doc['nombre'] . ' ' . $doc['apellidos']); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <label style="font-size: 0.78rem; font-weight: 600; color: #38bdf8; display: block; margin-bottom: 5px;">Sección a Cargo</label>
                        <select class="form-select" name="seccion_id" required>
                            <option value="">-- Seleccionar Sección --</option>
                            <?php if (!empty($secciones)): ?>
                                <?php foreach($secciones as $sec): ?>
                                    <option value="<?php echo $sec['id']; ?>"><?php echo htmlspecialchars($sec['nivel_nombre'] . ' - ' . $sec['seccion_nombre']); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn-action" style="background: linear-gradient(135deg, #7c3aed, #4f46e5); border: none; padding: 10px 20px; margin-top: 15px;">
                    <i class="fa-solid fa-check"></i> Habilitar Lección Guía
                </button>
            </form>
        </div>
    </div>

    <!-- PESTAÑA DIRECTORIO DE ESTUDIANTES -->
    <div id="tab-directorio" class="tab-content">
        <div class="content-card">
            <h3>
                <span><i class="fa-solid fa-address-book"></i> Directorio Institucional de Estudiantes</span>
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchEstudiantes" placeholder="Buscar estudiante..." onkeyup="filterTable('searchEstudiantes', 'tablaEstudiantes')">
                </div>
            </h3>
            <div class="table-responsive">
                <table id="tablaEstudiantes">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cédula</th>
                            <th>Nombre Completo</th>
                            <th>Nivel y Sección</th>
                            <th>Especialidad / Idioma</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($estudiantes)): ?>
                            <?php foreach ($estudiantes as $est): ?>
                            <tr>
                                <td>#<?php echo $est['id']; ?></td>
                                <td><?php echo htmlspecialchars($est['cedula'] ?? ''); ?></td>
                                <td><strong><?php echo htmlspecialchars(($est['nombre'] ?? '') . ' ' . ($est['apellidos'] ?? '')); ?></strong></td>
                                <td><code><?php echo htmlspecialchars(($est['nivel_nombre'] ?? '') . ' - ' . ($est['seccion_nombre'] ?? '')); ?></code></td>
                                <td><?php echo htmlspecialchars(($est['especialidad_tecnica'] ?? 'General') . ' / ' . ($est['idioma'] ?? 'Español')); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<script>
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
        localStorage.setItem("admin_active_tab", tabId);
    }

    document.addEventListener("DOMContentLoaded", function() {
        const savedTab = localStorage.getItem("admin_active_tab");
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

        const container = document.getElementById('bubblesContainer');
        if (container) {
            const bubbleCount = 22;
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
