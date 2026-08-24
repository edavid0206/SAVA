<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAVA &bull; Control de Asistencia</title>
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

        .content-card { background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.4); margin-bottom: 25px; }
        .content-card h3 { font-size: 1.15rem; margin-bottom: 15px; color: #f8fafc; display: flex; align-items: center; gap: 10px; }

        .alert-msg { padding: 12px 18px; border-radius: 12px; font-size: 0.88rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: rgba(14, 159, 110, 0.2); border: 1px solid rgba(52, 211, 153, 0.4); color: #34d399; }
        .alert-error { background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5; }

        .form-toolbar { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; margin-bottom: 20px; background: rgba(30, 41, 59, 0.5); padding: 15px 20px; border-radius: 14px; border: 1px solid rgba(255,255,255,0.06); }
        .form-group-inline { display: flex; align-items: center; gap: 10px; }
        .form-control { background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(255,255,255,0.15); border-radius: 10px; padding: 8px 12px; color: #fff; font-size: 0.9rem; outline: none; }
        .form-control:focus { border-color: #38bdf8; }

        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem; }
        th { background: rgba(30, 41, 59, 0.8); color: #38bdf8; padding: 12px 15px; font-weight: 600; }
        td { padding: 12px 15px; border-bottom: 1px solid rgba(255,255,255,0.06); color: #cbd5e1; }
        tr:hover td { background: rgba(255,255,255,0.02); }

        .radio-group { display: flex; gap: 15px; align-items: center; flex-wrap: wrap; }
        .radio-label { display: inline-flex; align-items: center; gap: 5px; font-size: 0.85rem; cursor: pointer; font-weight: 500; }
        .radio-label input { accent-color: #38bdf8; cursor: pointer; }
        
        .badge-sub { background: rgba(217, 119, 6, 0.2); color: #fcd34d; padding: 3px 8px; border-radius: 6px; font-size: 0.75rem; border: 1px solid rgba(252, 211, 77, 0.3); }

        .btn-save { background: linear-gradient(135deg, #0284c7, #2563eb); border: none; padding: 12px 30px; color: #fff; border-radius: 12px; font-weight: 600; font-size: 0.95rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: transform 0.2s; margin-top: 20px; }
        .btn-save:hover { transform: translateY(-2px); }
    </style>
</head>
<body>

    <!-- Cabecera -->
    <div class="header-bar">
        <div class="header-title">
            <h2><i class="fa-solid fa-clipboard-user"></i> Sección <?php echo htmlspecialchars($asignacionActual['seccion_nombre']); ?> &bull; <?php echo htmlspecialchars($asignacionActual['materia_nombre']); ?></h2>
            <p>Nivel: <strong><?php echo htmlspecialchars($asignacionActual['nivel_nombre']); ?></strong></p>
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

    <!-- Mensajes de Estado -->
    <?php if (!empty($mensaje)): ?>
        <div class="alert-msg alert-success"><i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert-msg alert-error"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Formulario de Asistencia -->
    <div class="content-card">
        <form action="/sistema/public/index.php?route=docente-guardar-asistencia" method="POST">
            <input type="hidden" name="asignacion_id" value="<?php echo $asignacionActual['asignacion_id']; ?>">

            <div class="form-toolbar">
                <div class="form-group-inline">
                    <label for="fecha"><i class="fa-solid fa-calendar-days"></i> Fecha de la Clase:</label>
                    <input type="date" id="fecha" name="fecha" class="form-control" value="<?php echo htmlspecialchars($fecha); ?>" onchange="cambiarFecha(this.value)">
                </div>

                <div class="form-group-inline">
                    <label for="num_lecciones"><i class="fa-solid fa-clock"></i> Cantidad de Lecciones:</label>
                    <select id="num_lecciones" name="num_lecciones" class="form-control">
                        <?php for($i=1; $i<=6; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo (($asistenciaExistente['num_lecciones'] ?? 1) == $i) ? 'selected' : ''; ?>><?php echo $i; ?> <?php echo ($i == 1) ? 'Lección' : 'Lecciones'; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div>
                    <button type="button" class="btn-action" onclick="marcarTodos('presente')"><i class="fa-solid fa-check-double"></i> Todos Presentes</button>
                </div>
            </div>

            <?php if (!$permiteEditar): ?>
                <div class="alert-msg alert-error">
                    <i class="fa-solid lock"></i> El plazo de 30 días para modificar este registro de asistencia ha expirado. Está en modo de solo lectura.
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cédula</th>
                            <th>Estudiante</th>
                            <?php if ($asignacionActual['es_subgrupo']): ?>
                                <th>Subgrupo</th>
                            <?php endif; ?>
                            <th>Estado de Asistencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($estudiantes)): ?>
                            <tr><td colspan="5" style="text-align: center; padding: 30px; color: #94a3b8;">No hay estudiantes matriculados en esta sección o subgrupo.</td></tr>
                        <?php else: ?>
                            <?php $contador = 1; foreach ($estudiantes as $est): ?>
                                <?php 
                                    $estId = $est['id'];
                                    $estadoActual = $asistenciaExistente['detalles'][$estId] ?? 'presente';
                                ?>
                                <tr>
                                    <td><?php echo $contador++; ?></td>
                                    <td><code><?php echo htmlspecialchars($est['cedula']); ?></code></td>
                                    <td><strong><?php echo htmlspecialchars($est['apellidos'] . ', ' . $est['nombre']); ?></strong></td>
                                    <?php if ($asignacionActual['es_subgrupo']): ?>
                                        <td><span class="badge-sub">Grupo <?php echo htmlspecialchars($est['subgrupo']); ?></span></td>
                                    <?php endif; ?>
                                    <td>
                                        <div class="radio-group">
                                            <label class="radio-label" style="color: #34d399;">
                                                <input type="radio" name="asistencia[<?php echo $estId; ?>]" value="presente" <?php echo ($estadoActual === 'presente') ? 'checked' : ''; ?> <?php echo !$permiteEditar ? 'disabled' : ''; ?>> Presente
                                            </label>
                                            <label class="radio-label" style="color: #f87171;">
                                                <input type="radio" name="asistencia[<?php echo $estId; ?>]" value="ausente_injustificada" <?php echo ($estadoActual === 'ausente_injustificada') ? 'checked' : ''; ?> <?php echo !$permiteEditar ? 'disabled' : ''; ?>> Ausente (AI)
                                            </label>
                                            <label class="radio-label" style="color: #facc15;">
                                                <input type="radio" name="asistencia[<?php echo $estId; ?>]" value="ausente_justificada" <?php echo ($estadoActual === 'ausente_justificada') ? 'checked' : ''; ?> <?php echo !$permiteEditar ? 'disabled' : ''; ?>> Justificada (AJ)
                                            </label>
                                            <label class="radio-label" style="color: #c084fc;">
                                                <input type="radio" name="asistencia[<?php echo $estId; ?>]" value="escape" <?php echo ($estadoActual === 'escape') ? 'checked' : ''; ?> <?php echo !$permiteEditar ? 'disabled' : ''; ?>> Escape (E)
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($permiteEditar && !empty($estudiantes)): ?>
                <button type="submit" class="btn-save">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar Asistencia de la Sección
                </button>
            <?php endif; ?>
        </form>
    </div>

    <script>
        function cambiarFecha(nuevaFecha) {
            let asignacionId = "<?php echo $asignacionActual['asignacion_id']; ?>";
            window.location.href = `/sistema/public/index.php?route=docente-asistencia&asignacion_id=${asignacionId}&fecha=${nuevaFecha}`;
        }

        function marcarTodos(estado) {
            let radios = document.querySelectorAll(`input[type="radio"][value="${estado}"]`);
            radios.forEach(radio => {
                if (!radio.disabled) {
                    radio.checked = true;
                }
            });
        }
    </script>
</body>
</html>
