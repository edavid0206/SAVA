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
        
        .alert-box { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 16px; padding: 20px; margin-bottom: 25px; }
        .alert-box h3 { color: #fca5a5; font-size: 1.05rem; margin-bottom: 10px; display: flex; align-items: center; gap: 8px; }
        .alert-item { background: rgba(15, 23, 42, 0.6); padding: 10px 15px; border-radius: 10px; margin-bottom: 8px; font-size: 0.9rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }

        .secciones-box { background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(56, 189, 248, 0.2); border-radius: 20px; padding: 25px; margin-bottom: 25px; }
        .secciones-box h3 { color: #38bdf8; margin-bottom: 15px; font-size: 1.1rem; display: flex; align-items: center; gap: 10px; }
        .seccion-item { display: flex; justify-content: space-between; align-items: center; background: rgba(30, 41, 59, 0.6); padding: 15px 20px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.08); margin-bottom: 10px; flex-wrap: wrap; gap: 15px; }
        .btn-group-actions { display: flex; gap: 10px; }
        .btn-asistencia { background: linear-gradient(135deg, #0284c7, #2563eb); color: #fff; padding: 10px 18px; border-radius: 10px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: transform 0.2s; }
        .btn-asistencia:hover { transform: translateY(-2px); }

        .conducta-form-box { background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(168, 85, 247, 0.3); border-radius: 20px; padding: 25px; }
        .conducta-form-box h3 { color: #c084fc; margin-bottom: 15px; font-size: 1.1rem; display: flex; align-items: center; gap: 10px; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px; margin-bottom: 15px; }
        .form-group label { display: block; font-size: 0.85rem; color: #94a3b8; margin-bottom: 6px; }
        .form-group select, .form-group input, .form-group textarea { width: 100%; background: rgba(30, 41, 59, 0.8); border: 1px solid rgba(255, 255, 255, 0.1); color: #fff; padding: 10px 14px; border-radius: 10px; font-size: 0.9rem; }
        .form-group textarea { resize: vertical; min-height: 80px; }
        .btn-submit { background: linear-gradient(135deg, #9333ea, #c084fc); color: #fff; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 600; cursor: pointer; transition: opacity 0.2s; }
        .btn-submit:hover { opacity: 0.9; }
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
        <h2>Panel de Gestión Académica y Docente</h2>

        <?php if (!empty($alertasAusentismo)): ?>
            <div class="alert-box">
                <h3><i class="fa-solid fa-triangle-exclamation"></i> Alertas Tempranas de Ausentismo (&ge; 20% en Sección Guía)</h3>
                <?php foreach ($alertasAusentismo as $alt): ?>
                    <div class="alert-item">
                        <span><strong><?php echo htmlspecialchars($alt['estudiante']['nombre'] . ' ' . $alt['estudiante']['apellidos']); ?></strong> (Cédula: <?php echo htmlspecialchars($alt['estudiante']['cedula']); ?>)</span>
                        <span style="color: #fca5a5; font-weight: 600;"><?php echo $alt['ausencias']; ?> ausencias de <?php echo $alt['total_lecciones']; ?> lecciones (<?php echo $alt['porcentaje']; ?>%)</span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="secciones-box">
            <h3><i class="fa-solid fa-list-check"></i> Carga Académica y Asistencias</h3>
            <?php if (empty($asignaciones)): ?>
                <p style="color: #94a3b8; padding: 10px 0;">No tiene secciones asignadas actualmente.</p>
            <?php else: ?>
                <?php foreach ($asignaciones as $asig): ?>
                    <div class="seccion-item">
                        <div>
                            <strong style="font-size: 1.05rem; color: #fff;">Sección <?php echo htmlspecialchars($asig['seccion_nombre']); ?></strong>
                            <span style="color: #94a3b8; font-size: 0.85rem; margin-left: 10px;">&bull; <?php echo htmlspecialchars($asig['nivel_nombre']); ?></span>
                        </div>
                        <div class="btn-group-actions">
                            <a href="/sistema/public/index.php?route=docente-asistencia&asignacion_id=<?php echo $asig['id']; ?>" class="btn-asistencia">
                                <i class="fa-solid fa-clipboard-user"></i> Pasar Lista
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if ($seccionGuia && !empty($estudiantesGuia)): ?>
            <div class="conducta-form-box">
                <h3><i class="fa-solid fa-pen-to-square"></i> Registro de Conducta / Observaciones (Profesor Guía: Sección <?php echo htmlspecialchars($seccionGuia['seccion_nombre']); ?>)</h3>
                <form action="/sistema/public/index.php?route=docente-guardar-conducta" method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="estudiante_id">Estudiante:</label>
                            <select name="estudiante_id" id="estudiante_id" required>
                                <option value="">Seleccione un estudiante...</option>
                                <?php foreach ($estudiantesGuia as $est): ?>
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
                        <textarea name="observacion" id="observacion" placeholder="Describa el motivo o comportamiento observado..." required></textarea>
                    </div>
                    <button type="submit" class="btn-submit"><i class="fa-solid fa-floppy-disk"></i> Guardar Registro de Conducta</button>
                </form>
            </div>
        <?php endif; ?>
    </main>

</body>
</html>
