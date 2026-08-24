<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAVA &bull; Historial de Asistencia</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: #030712; color: #f8fafc; min-height: 100vh; display: flex; flex-direction: column; padding: 25px; }
        
        .header-bar { display: flex; justify-content: space-between; align-items: center; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); padding: 20px 30px; border-radius: 20px; margin-bottom: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.4); flex-wrap: wrap; gap: 15px; }
        .header-title h2 { font-size: 1.4rem; color: #38bdf8; display: flex; align-items: center; gap: 10px; }
        .header-title p { font-size: 0.85rem; color: #94a3b8; }
        
        .nav-buttons { display: flex; gap: 12px; }
        .btn-action { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #fff; padding: 10px 18px; border-radius: 12px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s; cursor: pointer; }
        .btn-action:hover { background: rgba(56, 189, 248, 0.2); border-color: #38bdf8; transform: translateY(-2px); }
        .btn-danger { background: rgba(239, 68, 68, 0.15); border-color: rgba(239, 68, 68, 0.3); color: #fca5a5; }
        .btn-danger:hover { background: rgba(239, 68, 68, 0.3); color: #fff; }

        .content-card { background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.4); }
        .content-card h3 { font-size: 1.15rem; margin-bottom: 20px; color: #f8fafc; display: flex; align-items: center; gap: 10px; }

        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem; }
        th { background: rgba(30, 41, 59, 0.8); color: #38bdf8; padding: 12px 15px; font-weight: 600; }
        td { padding: 12px 15px; border-bottom: 1px solid rgba(255,255,255,0.06); color: #cbd5e1; }
        tr:hover td { background: rgba(255,255,255,0.02); }

        .badge-count { padding: 4px 10px; border-radius: 8px; font-size: 0.78rem; font-weight: 700; display: inline-block; }
        .badge-pres { background: rgba(52, 211, 153, 0.2); color: #34d399; border: 1px solid rgba(52, 211, 153, 0.3); }
        .badge-aus { background: rgba(239, 68, 68, 0.2); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.3); }

        .btn-ver { background: rgba(56, 189, 248, 0.2); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); padding: 6px 12px; border-radius: 8px; font-size: 0.8rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; }
        .btn-ver:hover { opacity: 0.8; }
    </style>
</head>
<body>

    <div class="header-bar">
        <div class="header-title">
            <h2><i class="fa-solid fa-chart-pie"></i> Historial de Asistencia &bull; Sección <?php echo htmlspecialchars($asignacionActual['seccion_nombre']); ?></h2>
            <p>Asignatura: <strong><?php echo htmlspecialchars($asignacionActual['materia_nombre']); ?></strong></p>
        </div>
        <div class="nav-buttons">
            <a href="/sistema/public/index.php?route=docente-panel" class="btn-action">
                <i class="fa-solid fa-arrow-left"></i> Volver a Secciones
            </a>
            <a href="/sistema/public/index.php?route=logout" class="btn-action btn-danger">
                <i class="fa-solid fa-power-off"></i> Salir
            </a>
        </div>
    </div>

    <div class="content-card">
        <h3><i class="fa-solid fa-calendar-check"></i> Listado de Sesiones Registradas</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Fecha de la Clase</th>
                        <th>Lecciones Impartidas</th>
                        <th>Estudiantes Presentes</th>
                        <th>Ausencias / Incidencias</th>
                        <th>Fecha de Registro</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($historial)): ?>
                        <tr><td colspan="6" style="text-align: center; padding: 40px; color: #94a3b8;">No hay registros de asistencia guardados para esta sección todavía.</td></tr>
                    <?php else: ?>
                        <?php foreach ($historial as $ses): ?>
                            <tr>
                                <td><strong><?php echo date('d/m/Y', strtotime($ses['fecha'])); ?></strong></td>
                                <td><?php echo $ses['num_lecciones']; ?> Lección(es)</td>
                                <td><span class="badge-count badge-pres"><?php echo $ses['total_presentes']; ?> Presentes</span></td>
                                <td><span class="badge-count badge-aus"><?php echo $ses['total_ausentes']; ?> Ausencias/Escapes</span></td>
                                <td style="color: #94a3b8; font-size: 0.82rem;"><?php echo $ses['creado_en']; ?></td>
                                <td>
                                    <a href="/sistema/public/index.php?route=docente-asistencia&asignacion_id=<?php echo $asignacionActual['asignacion_id']; ?>&fecha=<?php echo $ses['fecha']; ?>" class="btn-ver">
                                        <i class="fa-solid fa-pen-to-square"></i> Ver / Editar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
