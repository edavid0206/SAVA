<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAVA &bull; Panel de Dirección</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: #030712; color: #f8fafc; min-height: 100vh; display: flex; }

        /* Sidebar Moderno Lateral */
        aside { width: 280px; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(20px); border-right: 1px solid rgba(255, 255, 255, 0.08); display: flex; flex-direction: column; justify-content: space-between; padding: 30px 20px; position: fixed; height: 100vh; z-index: 10; }
        .sidebar-brand { font-size: 1.25rem; font-weight: 800; color: #38bdf8; display: flex; align-items: center; gap: 12px; margin-bottom: 35px; padding-left: 10px; }
        .sidebar-menu { display: flex; flex-direction: column; gap: 8px; flex-grow: 1; }
        .sidebar-item { color: #94a3b8; text-decoration: none; padding: 12px 16px; border-radius: 12px; font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; gap: 12px; transition: all 0.2s ease; cursor: pointer; }
        .sidebar-item:hover, .sidebar-item.active { background: rgba(56, 189, 248, 0.12); color: #38bdf8; transform: translateX(4px); }
        .sidebar-footer { padding-top: 20px; border-top: 1px solid rgba(255, 255, 255, 0.08); }
        .logout-btn { background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; padding: 12px 16px; border-radius: 12px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.3s; width: 100%; }
        .logout-btn:hover { background: rgba(239, 68, 68, 0.3); color: #fff; }

        /* Contenido Principal */
        main { margin-left: 280px; flex-grow: 1; padding: 40px; background: #030712; min-height: 100vh; }
        .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .top-header h2 { font-size: 1.6rem; color: #f8fafc; font-weight: 700; }

        .tab-content { display: none; }
        .tab-content.active { display: block; animation: fadeIn 0.3s ease-in-out; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }

        .card-panel { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); margin-bottom: 25px; }
        .card-panel h3 { color: #38bdf8; margin-bottom: 20px; font-size: 1.15rem; display: flex; align-items: center; gap: 10px; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 0.9rem; }
        th, td { padding: 14px 16px; text-align: left; border-bottom: 1px solid rgba(255, 255, 255, 0.06); }
        th { color: #94a3b8; font-weight: 600; background: rgba(30, 41, 59, 0.4); }
        tr:hover { background: rgba(30, 41, 59, 0.3); }

        .btn-action { background: rgba(56, 189, 248, 0.15); border: 1px solid rgba(56, 189, 248, 0.3); color: #38bdf8; padding: 7px 14px; border-radius: 10px; text-decoration: none; font-size: 0.8rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; transition: all 0.2s; }
        .btn-action:hover { background: rgba(56, 189, 248, 0.3); color: #fff; }

        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .form-group label { display: block; font-size: 0.85rem; color: #94a3b8; margin-bottom: 8px; font-weight: 500; }
        .form-group select, .form-group input { width: 100%; background: rgba(30, 41, 59, 0.8); border: 1px solid rgba(255, 255, 255, 0.1); color: #fff; padding: 12px 16px; border-radius: 12px; font-size: 0.9rem; outline: none; }
        .form-group select:focus, .form-group input:focus { border-color: #38bdf8; }
        .btn-submit { background: linear-gradient(135deg, #0284c7, #2563eb); color: #fff; border: none; padding: 12px 24px; border-radius: 12px; font-weight: 600; cursor: pointer; transition: opacity 0.2s; }
        .btn-submit:hover { opacity: 0.9; }

        .alert-msg { background: rgba(52, 211, 153, 0.1); border: 1px solid rgba(52, 211, 153, 0.3); color: #34d399; padding: 14px 20px; border-radius: 14px; margin-bottom: 25px; font-size: 0.9rem; transition: opacity 0.5s ease; }
        .error-msg { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; padding: 14px 20px; border-radius: 14px; margin-bottom: 25px; font-size: 0.9rem; transition: opacity 0.5s ease; }

        /* Modal */
        .modal { display: none; position: fixed; z-index: 100; left: 0; top: 0; width: 100%; height: 100%; background: rgba(3, 7, 18, 0.8); backdrop-filter: blur(5px); align-items: center; justify-content: center; }
        .modal-content { background: #0f172a; border: 1px solid rgba(56, 189, 248, 0.3); padding: 35px; border-radius: 24px; width: 100%; max-width: 480px; box-shadow: 0 25px 50px rgba(0,0,0,0.7); }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .modal-header h4 { color: #38bdf8; font-size: 1.2rem; font-weight: 700; }
        .close-modal { color: #94a3b8; font-size: 1.4rem; cursor: pointer; background: none; border: none; }
    </style>
</head>
<body>

    <!-- SIDEBAR DE NAVEGACIÓN LATERAL -->
    <aside>
        <div>
            <div class="sidebar-brand">
                <i class="fa-solid fa-graduation-cap" style="font-size: 1.6rem; color: #38bdf8;"></i>
                <span>SAVA &bull; Dirección</span>
            </div>
            <div class="sidebar-menu">
                <a class="sidebar-item active" onclick="switchSection(event, 'sec-secciones')"><i class="fa-solid fa-school"></i> Gestor de Secciones</a>
                <a class="sidebar-item" onclick="switchSection(event, 'sec-docentes')"><i class="fa-solid fa-chalkboard-user"></i> Gestor de Docentes</a>
                <a class="sidebar-item" onclick="switchSection(event, 'sec-materias')"><i class="fa-solid fa-book"></i> Gestor de Materias</a>
                <a class="sidebar-item" onclick="switchSection(event, 'sec-estudiantes')"><i class="fa-solid fa-user-graduate"></i> Gestor de Estudiantes</a>
                <a class="sidebar-item" onclick="switchSection(event, 'sec-config')"><i class="fa-solid fa-gears"></i> Configuración y Promoción</a>
                <a class="sidebar-item" onclick="switchSection(event, 'sec-reportes')"><i class="fa-solid fa-chart-pie"></i> Reportes Generales</a>
            </div>
        </div>
        <div class="sidebar-footer">
            <a href="/sistema/public/index.php?route=logout" class="logout-btn">
                <i class="fa-solid fa-power-off"></i> Cerrar Sesión
            </a>
        </div>
    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <main>
        <div class="top-header">
            <h2 id="page-title">Gestor de Secciones</h2>
            <span style="color: #94a3b8; font-size: 0.9rem;"><i class="fa-solid fa-user-shield"></i> Administrador Conectado</span>
        </div>

        <?php if (!empty($mensaje)): ?>
            <div id="notification-alert" class="alert-msg"><i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div id="notification-error" class="error-msg"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- SECCIÓN: SECCIONES -->
        <div id="sec-secciones" class="tab-content active card-panel">
            <h3><i class="fa-solid fa-school"></i> Listado de Secciones y Asignación de Docente Guía</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nivel</th>
                        <th>Sección</th>
                        <th>Docente Guía Actual</th>
                        <th>Estudiantes</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($secciones as $sec): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($sec['nivel_nombre']); ?></td>
                            <td><strong>Sección <?php echo htmlspecialchars($sec['seccion_nombre']); ?></strong></td>
                            <td><?php echo htmlspecialchars($sec['docente_guia'] ?? 'Sin docente guía asignado'); ?></td>
                            <td><?php echo $sec['total_estudiantes']; ?> matriculados</td>
                            <td>
                                <button type="button" class="btn-action" onclick="abrirModalGuia(<?php echo $sec['id']; ?>, '<?php echo htmlspecialchars($sec['nivel_nombre'] . ' - Sección ' . $sec['seccion_nombre']); ?>')">
                                    <i class="fa-solid fa-user-pen"></i> Editar Guía
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- SECCIÓN: DOCENTES -->
        <div id="sec-docentes" class="tab-content card-panel">
            <h3><i class="fa-solid fa-chalkboard-user"></i> Plantel Docente y Cargas Académicas</h3>
            <table>
                <thead>
                    <tr>
                        <th>Cédula</th>
                        <th>Nombre Completo</th>
                        <th>Correo Institucional</th>
                        <th>Materias Asignadas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($todosLosDocentes as $doc): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($doc['cedula']); ?></td>
                            <td><strong><?php echo htmlspecialchars($doc['apellidos'] . ', ' . $doc['nombre']); ?></strong></td>
                            <td><?php echo htmlspecialchars($doc['correo']); ?></td>
                            <td><?php echo htmlspecialchars($doc['materias_nombres'] ?: 'Sin materias asignadas'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- SECCIÓN: MATERIAS -->
        <div id="sec-materias" class="tab-content card-panel">
            <h3><i class="fa-solid fa-book"></i> Catálogo de Materias y Asignaturas</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre de la Materia</th>
                        <th>Tipo / Área</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($materias as $mat): ?>
                        <tr>
                            <td><?php echo $mat['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($mat['nombre']); ?></strong></td>
                            <td><?php echo htmlspecialchars($mat['tipo'] ?? 'General'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- SECCIÓN: ESTUDIANTES -->
        <div id="sec-estudiantes" class="tab-content card-panel">
            <h3><i class="fa-solid fa-user-graduate"></i> Matrícula General de Estudiantes</h3>
            <table>
                <thead>
                    <tr>
                        <th>Cédula</th>
                        <th>Apellidos y Nombre</th>
                        <th>Nivel / Sección</th>
                        <th>Subgrupo</th>
                        <th>Especialidad / Taller</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($estudiantes as $est): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($est['cedula']); ?></td>
                            <td><strong><?php echo htmlspecialchars($est['apellidos'] . ', ' . $est['nombre']); ?></strong></td>
                            <td><?php echo htmlspecialchars($est['nivel_nombre'] . ' - Secc. ' . $est['seccion_nombre']); ?></td>
                            <td>Subgrupo <?php echo htmlspecialchars($est['subgrupo'] ?? 'A'); ?></td>
                            <td><?php echo htmlspecialchars($est['especialidad_tecnica'] ?? 'General'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- SECCIÓN: CONFIGURACIÓN Y PROMOCIÓN -->
        <div id="sec-config" class="tab-content card-panel">
            <h3><i class="fa-solid fa-gears"></i> Promoción Anual y Matrícula Histórica</h3>
            <p style="color: #94a3b8; font-size: 0.9rem; margin-bottom: 25px; line-height: 1.5;">
                Proceso oficial de cierre de ciclo lectivo. Seleccione al estudiante, establezca la sección de destino y el estado de promoción para actualizar automáticamente los registros históricos.
            </p>
            <form action="/sistema/public/index.php?route=admin-promover-estudiante" method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="estudiante_id">Seleccionar Estudiante:</label>
                        <select name="estudiante_id" id="estudiante_id" required>
                            <option value="">Seleccione estudiante...</option>
                            <?php foreach ($estudiantes as $est): ?>
                                <option value="<?php echo $est['id']; ?>"><?php echo htmlspecialchars($est['apellidos'] . ', ' . $est['nombre'] . ' (' . $est['nivel_nombre'] . ' - Secc. ' . $est['seccion_nombre'] . ')'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nueva_seccion_id">Nueva Sección Destino:</label>
                        <select name="nueva_seccion_id" id="nueva_seccion_id" required>
                            <option value="">Seleccione sección destino...</option>
                            <?php foreach ($secciones as $sec): ?>
                                <option value="<?php echo $sec['id']; ?>"><?php echo htmlspecialchars($sec['nivel_nombre'] . ' - Sección ' . $sec['seccion_nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="estado_matricula">Estado de Promoción:</label>
                        <select name="estado_matricula" id="estado_matricula" required>
                            <option value="promovido">Promovido</option>
                            <option value="retiene">Retiene / Repite</option>
                            <option value="trasladado">Trasladado</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="observacion">Observación de Cierre:</label>
                    <input type="text" name="observacion" id="observacion" value="Promoción de fin de año lectivo" required>
                </div>
                <button type="submit" class="btn-submit"><i class="fa-solid fa-floppy-disk"></i> Procesar Promoción Académica</button>
            </form>
        </div>

        <!-- SECCIÓN: REPORTES -->
        <div id="sec-reportes" class="tab-content card-panel">
            <h3><i class="fa-solid fa-chart-pie"></i> Reportes y Estadísticas Institucionales</h3>
            <p style="color: #94a3b8; font-size: 0.9rem; line-height: 1.6;">
                Panel analítico destinado a la visualización de métricas de asistencia general, reportes de conducta consolidados por niveles y control administrativo de auditoría institucional.
            </p>
        </div>
    </main>

    <!-- MODAL PARA EDITAR DOCENTE GUÍA -->
    <div id="modalGuia" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="modalSeccionTitulo">Asignar Docente Guía</h4>
                <button type="button" class="close-modal" onclick="cerrarModalGuia()">&times;</button>
            </div>
            <form action="/sistema/public/index.php?route=admin-actualizar-guia" method="POST">
                <input type="hidden" name="seccion_id" id="modalSeccionId">
                <div class="form-group" style="margin-bottom: 25px;">
                    <label for="docente_guia_id">Seleccionar Profesor:</label>
                    <select name="docente_guia_id" id="docente_guia_id" required>
                        <option value="">Seleccione docente...</option>
                        <?php foreach ($todosLosDocentes as $doc): ?>
                            <option value="<?php echo $doc['id']; ?>"><?php echo htmlspecialchars($doc['apellidos'] . ', ' . $doc['nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn-submit" style="width: 100%;"><i class="fa-solid fa-floppy-disk"></i> Guardar Docente Guía</button>
            </form>
        </div>
    </div>

    <script>
        const titulosModulos = {
            'sec-secciones': 'Gestor de Secciones',
            'sec-docentes': 'Gestor de Docentes',
            'sec-materias': 'Gestor de Materias',
            'sec-estudiantes': 'Gestor de Estudiantes',
            'sec-config': 'Configuración y Promoción de Año',
            'sec-reportes': 'Reportes Generales'
        };

        function switchSection(evt, sectionId) {
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(c => c.classList.remove('active'));

            const items = document.querySelectorAll('.sidebar-item');
            items.forEach(i => i.classList.remove('active'));

            document.getElementById(sectionId).classList.add('active');
            evt.currentTarget.classList.add('active');

            document.getElementById('page-title').innerText = titulosModulos[sectionId];
        }

        function abrirModalGuia(seccionId, nombreSeccion) {
            document.getElementById('modalSeccionId').value = seccionId;
            document.getElementById('modalSeccionTitulo').innerText = 'Asignar Docente Guía: ' + nombreSeccion;
            document.getElementById('modalGuia').style.display = 'flex';
        }

        function cerrarModalGuia() {
            document.getElementById('modalGuia').style.display = 'none';
        }

        // Ocultar notificaciones automáticamente después de 3 segundos
        window.addEventListener('DOMContentLoaded', () => {
            const alertMsg = document.getElementById('notification-alert');
            const errorMsg = document.getElementById('notification-error');

            if (alertMsg) {
                setTimeout(() => {
                    alertMsg.style.opacity = '0';
                    setTimeout(() => alertMsg.remove(), 500);
                }, 3000);
            }

            if (errorMsg) {
                setTimeout(() => {
                    errorMsg.style.opacity = '0';
                    setTimeout(() => errorMsg.remove(), 500);
                }, 3000);
            }
        });
    </script>
</body>
</html>
