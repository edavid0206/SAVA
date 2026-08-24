<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SAVA &bull; Panel del Docente</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: #030712; color: #f8fafc; min-height: 100vh; display: flex; flex-direction: column; padding: 25px; }
        
        .dashboard-header { display: flex; justify-content: space-between; align-items: center; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); padding: 20px 30px; border-radius: 20px; margin-bottom: 30px; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4); flex-wrap: wrap; gap: 15px; }
        .dashboard-header h1 { font-size: 1.5rem; color: #38bdf8; display: flex; align-items: center; gap: 10px; }
        
        .logout-btn { background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; padding: 10px 18px; border-radius: 12px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s; }
        .logout-btn:hover { background: rgba(239, 68, 68, 0.3); color: #fff; }

        .dashboard-container h2 { font-size: 1.2rem; color: #94a3b8; margin-bottom: 20px; font-weight: 500; }
        
        .toolbar { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .tool-card { background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 25px; text-decoration: none; color: #fff; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.3s ease; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3); }
        .tool-card:hover { transform: translateY(-5px); background: rgba(30, 41, 59, 0.8); border-color: rgba(56, 189, 248, 0.4); box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4); }
        .tool-card .icon { font-size: 2.2rem; margin-bottom: 15px; }
        .tool-card h3 { font-size: 1.2rem; margin-bottom: 8px; color: #f8fafc; font-weight: 600; }
        .tool-card p { font-size: 0.85rem; color: #94a3b8; line-height: 1.4; }
        .tool-card.disabled { opacity: 0.5; pointer-events: none; }

        .secciones-box { background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(56, 189, 248, 0.2); border-radius: 20px; padding: 25px; }
        .secciones-box h3 { color: #38bdf8; margin-bottom: 15px; font-size: 1.1rem; display: flex; align-items: center; gap: 10px; }
        .seccion-item { display: flex; justify-content: space-between; align-items: center; background: rgba(30, 41, 59, 0.6); padding: 15px 20px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.08); margin-bottom: 10px; flex-wrap: wrap; gap: 15px; }
        .btn-group-actions { display: flex; gap: 10px; }
        .btn-asistencia { background: linear-gradient(135deg, #0284c7, #2563eb); color: #fff; padding: 10px 18px; border-radius: 10px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: transform 0.2s; }
        .btn-asistencia:hover { transform: translateY(-2px); }
        .btn-reporte { background: rgba(52, 211, 153, 0.15); border: 1px solid rgba(52, 211, 153, 0.3); color: #34d399; }
        .btn-reporte:hover { background: rgba(52, 211, 153, 0.3); color: #fff; }
    </style>
</head>
<body>

    <header class="dashboard-header">
        <h1><i class="fa-solid fa-chalkboard-user"></i> Bienvenid@, <?php echo htmlspecialchars($_SESSION['user']['nombre'] ?? 'Docente'); ?></h1>
        <a href="/sistema/public/index.php?route=logout" class="logout-btn">
            <i class="fa-solid fa-power-off"></i> Cerrar Sesión
        </a>
    </header>

    <main class="dashboard-container">
        <h2>Panel de Herramientas Docentes</h2>
        <div class="toolbar">
            <div class="tool-card" style="border-color: #38bdf8;">
                <div class="icon">🗒️</div>
                <div>
                    <h3>Gestión de Asistencia</h3>
                    <p>Seleccione su sección asignada para pasar lista diaria o registrar ausencias.</p>
                </div>
            </div>

            <a href="#" class="tool-card disabled" title="Próximamente">
                <div class="icon">💯</div>
                <div>
                    <h3>Registrar Conducta</h3>
                    <p>Notas de conducta de sus secciones correspondientes.</p>
                </div>
            </a>

            <a href="#" class="tool-card disabled" title="Próximamente">
                <div class="icon">🕒</div>
                <div>
                    <h3>Mi Horario</h3>
                    <p>Horario de lecciones asignado para la semana.</p>
                </div>
            </a>
        </div>

        <div class="secciones-box">
            <h3><i class="fa-solid fa-list-check"></i> Sus Secciones y Asignaturas Asignadas</h3>
            <?php if (empty($asignaciones)): ?>
                <p style="color: #94a3b8; padding: 15px 0;">No tiene secciones asignadas actualmente.</p>
            <?php else: ?>
                <?php foreach ($asignaciones as $asig): ?>
                    <div class="seccion-item">
                        <div>
                            <strong style="font-size: 1.05rem; color: #fff;">Sección <?php echo htmlspecialchars($asig['seccion_nombre']); ?></strong>
                            <span style="color: #94a3b8; font-size: 0.85rem; margin-left: 10px;">&bull; <?php echo htmlspecialchars($asig['materia_nombre']); ?> (<?php echo htmlspecialchars($asig['nivel_nombre']); ?>)</span>
                        </div>
                        <div class="btn-group-actions">
                            <a href="/sistema/public/index.php?route=docente-asistencia&asignacion_id=<?php echo $asig['asignacion_id']; ?>" class="btn-asistencia">
                                <i class="fa-solid fa-clipboard-user"></i> Pasar Lista
                            </a>
                            <a href="/sistema/public/index.php?route=docente-reporte-historial&asignacion_id=<?php echo $asig['asignacion_id']; ?>" class="btn-asistencia btn-reporte">
                                <i class="fa-solid fa-chart-pie"></i> Historial / Reporte
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>
