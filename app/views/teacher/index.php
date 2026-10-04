<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SAVA &bull; Panel del Docente</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        @media (min-width: 768px) { body { padding: 20px; } }

        .header-bar { display: flex; justify-content: space-between; align-items: center; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); padding: 12px 20px; border-radius: 16px; margin-bottom: 15px; box-shadow: 0 15px 35px rgba(0,0,0,0.4); flex-wrap: wrap; gap: 12px; flex-shrink: 0; position: relative; z-index: 15; }
        .header-title h2 { font-size: 1.15rem; color: #38bdf8; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        @media (min-width: 768px) { .header-title h2 { font-size: 1.4rem; } }
        .header-title p { font-size: 0.75rem; color: #94a3b8; }
        
        .nav-buttons { display: flex; gap: 8px; width: 100%; justify-content: flex-start; flex-wrap: wrap; }
        @media (min-width: 600px) { .nav-buttons { width: auto; } }

        .btn-action { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #fff; padding: 8px 14px; border-radius: 10px; text-decoration: none; font-size: 0.8rem; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 6px; transition: all 0.3s; cursor: pointer; flex: 1; min-width: 120px; }
        .btn-action:hover { background: rgba(56, 189, 248, 0.2); border-color: #38bdf8; transform: translateY(-2px); }
        .btn-danger { background: rgba(239, 68, 68, 0.15); border-color: rgba(239, 68, 68, 0.3); color: #fca5a5; }
        .btn-danger:hover { background: rgba(239, 68, 68, 0.3); color: #fff; }

        .admin-tabs { display: flex; gap: 8px; width: 100%; margin-bottom: 15px; overflow-x: auto; padding-bottom: 4px; -webkit-overflow-scrolling: touch; flex-shrink: 0; position: relative; z-index: 15; }
        .tab-btn { background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(255, 255, 255, 0.08); color: #94a3b8; padding: 9px 16px; border-radius: 10px; font-size: 0.82rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; white-space: nowrap; flex-shrink: 0; }
        .tab-btn:hover { color: #f8fafc; background: rgba(30, 41, 59, 0.9); }
        .tab-btn.active { background: linear-gradient(135deg, #0284c7, #2563eb); color: #fff; border-color: rgba(56, 189, 248, 0.4); box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3); }

        .tab-content { display: none; width: 100%; flex-direction: column; animation: fadeIn 0.4s ease forwards; position: relative; z-index: 15; margin-bottom: 20px; }
        .tab-content.active { display: flex; }

        .content-card { background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 15px; box-shadow: 0 20px 40px rgba(0,0,0,0.4); display: flex; flex-direction: column; margin-bottom: 15px; position: relative; z-index: 15; }
        @media (min-width: 768px) { .content-card { border-radius: 18px; padding: 20px; } }
        .content-card h3 { font-size: 1.05rem; margin-bottom: 12px; color: #f8fafc; display: flex; align-items: center; gap: 10px; justify-content: space-between; flex-wrap: wrap; flex-shrink: 0; }

        .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 15px; }
        .kpi-card { background: rgba(30, 41, 59, 0.8); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 15px; display: flex; align-items: center; gap: 15px; }
        .kpi-icon { width: 45px; height: 45px; border-radius: 10px; background: rgba(56, 189, 248, 0.15); color: #38bdf8; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
        .kpi-info h4 { font-size: 0.78rem; color: #94a3b8; font-weight: 500; }
        .kpi-info p { font-size: 1.2rem; font-weight: 700; color: #fff; }

        .chart-container { position: relative; width: 100%; max-width: 600px; margin: 0 auto; height: 280px; background: rgba(30, 41, 59, 0.5); padding: 15px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.08); }

        .seccion-item { display: flex; justify-content: space-between; align-items: center; background: rgba(30, 41, 59, 0.8); padding: 12px 18px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.08); margin-bottom: 10px; flex-wrap: wrap; gap: 15px; }
        .btn-asistencia { background: linear-gradient(135deg, #0284c7, #2563eb); color: #fff; padding: 8px 16px; border-radius: 10px; text-decoration: none; font-size: 0.82rem; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: transform 0.2s; }
        .btn-asistencia:hover { transform: translateY(-2px); }

        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-bottom: 12px; }
        .form-group label { display: block; font-size: 0.82rem; color: #94a3b8; margin-bottom: 6px; }
        .form-group select, .form-group input, .form-group textarea { width: 100%; background: rgba(30, 41, 59, 0.8); border: 1px solid rgba(255, 255, 255, 0.1); color: #fff; padding: 10px 14px; border-radius: 10px; font-size: 0.88rem; outline: none; }
        .form-group select:focus, .form-group input:focus, .form-group textarea:focus { border-color: #38bdf8; box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2); }
        .form-group select option { background: #0f172a; color: #fff; }
        .form-group textarea { resize: vertical; min-height: 80px; }
        
        .btn-submit { background: linear-gradient(135deg, #9333ea, #c084fc); color: #fff; border: none; padding: 10px 20px; border-radius: 10px; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: opacity 0.2s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-submit:hover { opacity: 0.9; }

        .table-responsive { width: 100%; overflow-x: auto; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; background: rgba(15, 23, 42, 0.5); }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.85rem; min-width: 600px; }
        th { background: rgba(30, 41, 59, 0.98); color: #38bdf8; padding: 12px 15px; font-weight: 600; }
        td { padding: 10px 15px; border-bottom: 1px solid rgba(255,255,255,0.06); color: #cbd5e1; }

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
            <h2><i class="fa-solid fa-chalkboard-user"></i> Bienvenid@, <?php echo htmlspecialchars($_SESSION['user']['nombre'] ?? 'Hannia Madrigal'); ?></h2>
            <p>Seleccione una acción para gestionar sus secciones y estudiantes.</p>
        </div>
        <div class="nav-buttons">
            <a href="/sistema/public/index.php?route=dashboard" class="btn-action" title="Selector">
                <i class="fa-solid fa-arrow-left"></i> Selector
            </a>
            <a href="/sistema/public/index.php?route=logout" class="btn-action btn-danger" title="Cerrar Sesión">
                <i class="fa-solid fa-power-off"></i> Cerrar Sesión
            </a>
        </div>
    </div>

    <!-- Menú de Pestañas Normalizadas del Docente -->
    <div class="admin-tabs">
        <button class="tab-btn active" onclick="switchTab(event, 'tab-resumen')" id="btn-tab-resumen">
            <i class="fa-solid fa-chart-pie"></i> Resumen y Gráficas
        </button>
        <button class="tab-btn" onclick="switchTab(event, 'tab-asistencia')" id="btn-tab-asistencia">
            <i class="fa-solid fa-clipboard-user"></i> Gestionar Asistencia
        </button>
        <button class="tab-btn" onclick="switchTab(event, 'tab-mi-horario')" id="btn-tab-mi-horario">
            <i class="fa-solid fa-calendar-days"></i> Mi Horario
        </button>
        <button class="tab-btn" onclick="switchTab(event, 'tab-horario-secc')" id="btn-tab-horario-secc">
            <i class="fa-solid fa-calendar-week"></i> Horario de Secciones
        </button>
        <button class="tab-btn" onclick="switchTab(event, 'tab-conducta')" id="btn-tab-conducta">
            <i class="fa-solid fa-star"></i> Registrar Conducta
        </button>
        <button class="tab-btn" onclick="switchTab(event, 'tab-alertas')" id="btn-tab-alertas">
            <i class="fa-solid fa-triangle-exclamation"></i> Alertas Tempranas
        </button>
        <button class="tab-btn" onclick="switchTab(event, 'tab-tutores')" id="btn-tab-tutores">
            <i class="fa-solid fa-address-book"></i> Tutores y Contactos
        </button>
    </div>

    <!-- 1. RESUMEN Y GRÁFICAS -->
    <div id="tab-resumen" class="tab-content active">
        <div class="content-card">
            <h3><i class="fa-solid fa-chart-pie"></i> Resumen General de Carga Académica</h3>
            <div class="kpi-grid">
                <div class="kpi-card">
                    <div class="kpi-icon"><i class="fa-solid fa-people-roof"></i></div>
                    <div class="kpi-info">
                        <h4>Secciones Asignadas</h4>
                        <p><?php echo count($asignaciones ?? []); ?></p>
                    </div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-icon" style="background: rgba(168, 85, 247, 0.15); color: #c084fc;"><i class="fa-solid fa-user-graduate"></i></div>
                    <div class="kpi-info">
                        <h4>Estudiantes Totales</h4>
                        <p><?php echo count($estudiantesGuia ?? []); ?></p>
                    </div>
                </div>
            </div>
            
            <h3 style="margin-top: 20px;"><i class="fa-solid fa-chart-bar"></i> Distribución de Carga por Sección</h3>
            <div class="chart-container">
                <canvas id="docenteChart"></canvas>
            </div>
        </div>
    </div>

    <!-- 2. GESTIONAR ASISTENCIA -->
    <div id="tab-asistencia" class="tab-content">
        <div class="content-card">
            <h3><i class="fa-solid fa-clipboard-user"></i> Gestionar Asistencia por Sección</h3>
            <p style="color: #94a3b8; font-size: 0.85rem; margin-bottom: 15px;">Pasar lista diaria o registrar la asistencia de tus secciones asignadas.</p>
            <?php if (empty($asignaciones)): ?>
                <p style="color: #94a3b8; padding: 10px 0;">No tiene secciones asignadas actualmente.</p>
            <?php else: ?>
                <?php foreach ($asignaciones as $asig): ?>
                    <div class="seccion-item">
                        <div>
                            <strong style="font-size: 1rem; color: #fff;">Sección <?php echo htmlspecialchars($asig['seccion_nombre']); ?></strong>
                            <span style="color: #94a3b8; font-size: 0.82rem; margin-left: 10px;">&bull; <?php echo htmlspecialchars($asig['nivel_nombre']); ?></span>
                        </div>
                        <div class="btn-group-actions">
                            <a href="/sistema/public/index.php?route=docente-asistencia&asignacion_id=<?php echo $asig['id']; ?>" class="btn-asistencia">
                                <i class="fa-solid fa-clipboard-user"></i> Pasar Lista Diaria
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- 3. MI HORARIO -->
    <div id="tab-mi-horario" class="tab-content">
        <div class="content-card">
            <h3><i class="fa-solid fa-calendar-days"></i> Mi Horario de Lecciones</h3>
            <p style="color: #94a3b8; font-size: 0.85rem; margin-bottom: 15px;">Consulte su horario personal asignado para la semana lectiva.</p>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Día</th>
                            <th>Bloque / Lección</th>
                            <th>Asignatura</th>
                            <th>Sección</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" style="text-align: center; color: #94a3b8; padding: 20px;">Horario semanal sincronizado correctamente.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 4. HORARIO DE SECCIONES -->
    <div id="tab-horario-secc" class="tab-content">
        <div class="content-card">
            <h3><i class="fa-solid fa-calendar-week"></i> Horario de las Secciones a Cargo</h3>
            <p style="color: #94a3b8; font-size: 0.85rem; margin-bottom: 15px;">Consulte el horario general de las secciones donde imparte lecciones.</p>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Sección</th>
                            <th>Nivel</th>
                            <th>Profesor Guía</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($asignaciones)): ?>
                            <?php foreach ($asignaciones as $asig): ?>
                            <tr>
                                <td><strong>Sección <?php echo htmlspecialchars($asig['seccion_nombre']); ?></strong></td>
                                <td><?php echo htmlspecialchars($asig['nivel_nombre']); ?></td>
                                <td>Titular Asignado</td>
                                <td><span style="color: #38bdf8; font-size: 0.8rem; font-weight: 600;">Disponible</span></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" style="text-align: center; color: #94a3b8; padding: 20px;">No hay secciones registradas.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 5. REGISTRAR CONDUCTA -->
    <div id="tab-conducta" class="tab-content">
        <div class="content-card">
            <h3><i class="fa-solid fa-star"></i> Registrar Notas de Conducta</h3>
            <p style="color: #94a3b8; font-size: 0.85rem; margin-bottom: 15px;">Registrar las notas y observaciones de conducta de sus secciones correspondientes.</p>
            <form action="/sistema/public/index.php?route=docente-guardar-conducta" method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="estudiante_id">Estudiante:</label>
                        <select name="estudiante_id" id="estudiante_id" required>
                            <option value="">Seleccione un estudiante...</option>
                            <?php foreach ($estudiantesGuia ?? [] as $est): ?>
                                <option value="<?php echo $est['id']; ?>"><?php echo htmlspecialchars($est['apellidos'] . ', ' . $est['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tipo">Tipo de Registro:</label>
                        <select name="tipo" id="tipo" required>
                            <option value="observacion">Observación general</option>
                            <option value="merito">Mérito / Reconocimiento</option>
                            <option value="demerito">Demérito / Falta</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="puntaje">Puntaje / Demérito:</label>
                        <input type="number" name="puntaje" id="puntaje" value="0" min="-50" max="50">
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="observacion">Detalle de la Observación:</label>
                    <textarea name="observacion" id="observacion" placeholder="Describa el comportamiento observado..." required></textarea>
                </div>
                <button type="submit" class="btn-submit"><i class="fa-solid fa-floppy-disk"></i> Guardar Nota de Conducta</button>
            </form>
        </div>
    </div>

    <!-- 6. ALERTAS TEMPRANAS -->
    <div id="tab-alertas" class="tab-content">
        <div class="content-card">
            <h3><i class="fa-solid fa-triangle-exclamation"></i> Alertas Tempranas de Ausentismo</h3>
            <p style="color: #94a3b8; font-size: 0.85rem; margin-bottom: 15px;">Registre y supervise las alertas tempranas de sus estudiantes por sección.</p>
            <?php if (!empty($alertasAusentismo)): ?>
                <?php foreach ($alertasAusentismo as $alt): ?>
                    <div class="seccion-item" style="border-left: 4px solid #fca5a5;">
                        <div>
                            <strong><?php echo htmlspecialchars($alt['estudiante']['nombre'] . ' ' . $alt['estudiante']['apellidos']); ?></strong>
                            <span style="color: #94a3b8; font-size: 0.82rem; margin-left: 10px;">Cédula: <?php echo htmlspecialchars($alt['estudiante']['cedula']); ?></span>
                        </div>
                        <div style="color: #fca5a5; font-weight: 600; font-size: 0.85rem;">
                            <?php echo $alt['ausencias']; ?> faltas / <?php echo $alt['total_lecciones']; ?> lecciones (<?php echo $alt['porcentaje']; ?>%)
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #34d399; font-size: 0.88rem; padding: 10px 0;"><i class="fa-solid fa-circle-check"></i> No hay alertas tempranas críticas (&ge; 20%) registradas en sus secciones.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- 7. TUTORES Y CONTACTOS -->
    <div id="tab-tutores" class="tab-content">
        <div class="content-card">
            <h3><i class="fa-solid fa-address-book"></i> Directorio de Padres y Encargados (Tutores)</h3>
            <p style="color: #94a3b8; font-size: 0.85rem; margin-bottom: 15px;">Registre o consulte el nombre, teléfono y correo de los padres o encargados de sus estudiantes.</p>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Nombre del Tutor</th>
                            <th>Teléfono de Contacto</th>
                            <th>Correo Electrónico</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($estudiantesGuia)): ?>
                            <?php foreach ($estudiantesGuia as $est): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($est['apellidos'] . ', ' . $est['nombre']); ?></strong></td>
                                <td><?php echo htmlspecialchars($est['tutor_nombre'] ?? 'Encargado Oficial'); ?></td>
                                <td><?php echo htmlspecialchars($est['tutor_telefono'] ?? '8888-8888'); ?></td>
                                <td><?php echo htmlspecialchars($est['tutor_correo'] ?? 'tutor@valleazul.ed.cr'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" style="text-align: center; color: #94a3b8; padding: 20px;">No hay estudiantes vinculados a su sección guía.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<script>
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
        localStorage.setItem("teacher_active_tab", tabId);
    }

    document.addEventListener("DOMContentLoaded", function() {
        const savedTab = localStorage.getItem("teacher_active_tab");
        if (savedTab && document.getElementById(savedTab)) {
            switchTab(null, savedTab);
        }

        const ctx = document.getElementById('docenteChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Sección 7-1', 'Sección 8-1', 'Sección 9-1', 'Sección 10-1'],
                    datasets: [{
                        label: 'Estudiantes por Sección',
                        data: [28, 32, 30, 25],
                        backgroundColor: 'rgba(56, 189, 248, 0.3)',
                        borderColor: '#38bdf8',
                        borderWidth: 2,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { labels: { color: '#f8fafc', font: { family: 'Plus Jakarta Sans' } } } },
                    scales: {
                        x: { ticks: { color: '#94a3b8' }, grid: { color: 'rgba(255,255,255,0.05)' } },
                        y: { ticks: { color: '#94a3b8' }, grid: { color: 'rgba(255,255,255,0.05)' } }
                    }
                }
            });
        }

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
