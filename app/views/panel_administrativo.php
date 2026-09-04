<?php
require_once __DIR__ . '/app/config/Database.php';
use App\Config\Database;
$db = Database::getConnection();

// Extraer datos reales para los formularios
$docentes = $db->query("SELECT * FROM docentes WHERE activo = 1")->fetchAll();
$secciones = $db->query("SELECT * FROM secciones ORDER BY nivel_id ASC, nombre ASC")->fetchAll();
$asignaturas = $db->query("SELECT * FROM asignaturas")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SAVA - Panel Académico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestión Académica SAVA</h2>
        <span class="badge bg-primary fs-6">Colegio Valle Azul</span>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success">¡Operación completada exitosamente!</div>
    <?php endif; ?>

    <ul class="nav nav-tabs mb-4" id="adminTab" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#docentes" type="button">1. Docentes y Materias</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#asignacion" type="button">2. Asignación por Secciones</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#guia" type="button">3. Lección Guía (Homeroom)</button></li>
    </ul>

    <div class="tab-content" id="adminTabContent">
        <!-- PESTAÑA 1: Registrar Docente -->
        <div class="tab-pane fade show active" id="docentes">
            <div class="card shadow-sm p-4">
                <h4 class="mb-3">Registro de Docentes e Habilitación de Asignaturas</h4>
                <form action="guardar_docente.php" method="POST">
                    <div class="row g-3">
                        <div class="col-md-3"><label class="form-label">Cédula</label><input type="text" class="form-control" name="cedula" required></div>
                        <div class="col-md-4"><label class="form-label">Nombre(s)</label><input type="text" class="form-control" name="nombre" style="text-transform: uppercase;" required></div>
                        <div class="col-md-5"><label class="form-label">Apellidos</label><input type="text" class="form-control" name="apellidos" style="text-transform: uppercase;" required></div>
                        <div class="col-md-12 mt-3">
                            <label class="form-label fw-bold">Asignaturas que imparte:</label>
                            <div class="row row-cols-2 row-cols-md-4 g-2">
                                <?php foreach($asignaturas as $asig): ?>
                                    <div class="col"><div class="form-check"><input class="form-check-input" type="checkbox" name="asignaturas[]" value="<?= $asig['id'] ?>"><label class="form-check-label"><?= $asig['nombre'] ?></label></div></div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="col-12 mt-3"><button type="submit" class="btn btn-success">Guardar Docente</button></div>
                    </div>
                </form>
            </div>
        </div>

        <!-- PESTAÑA 2: Asignaciones de Sección -->
        <div class="tab-pane fade" id="asignacion">
            <div class="card shadow-sm p-4">
                <h4 class="mb-3">Asignar Docentes a Secciones</h4>
                <p class="text-muted">Si asignas a un docente donde ya hay otro impartiendo la misma materia, el sistema te preguntará qué hacer con el historial.</p>
                <form id="formAsignacionSeccion">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Sección</label>
                            <select class="form-select" name="seccion_id" required>
                                <option value="">-- Seleccione --</option>
                                <?php foreach($secciones as $sec): ?><option value="<?= $sec['id'] ?>"><?= $sec['nombre'] ?></option><?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Asignatura</label>
                            <select class="form-select" name="asignatura_id" required>
                                <option value="">-- Seleccione --</option>
                                <?php foreach($asignaturas as $asig): ?><option value="<?= $asig['id'] ?>"><?= $asig['nombre'] ?></option><?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Docente</label>
                            <select class="form-select" name="docente_id" required>
                                <option value="">-- Seleccione --</option>
                                <?php foreach($docentes as $doc): ?><option value="<?= $doc['id'] ?>"><?= $doc['nombre'] . ' ' . $doc['apellidos'] ?></option><?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 mt-3"><button type="button" onclick="verificarYAsignar()" class="btn btn-primary">Vincular Docente a Sección</button></div>
                    </div>
                </form>
            </div>
        </div>

        <!-- PESTAÑA 3: Lección Guía -->
        <div class="tab-pane fade" id="guia">
            <div class="card shadow-sm p-4">
                <h4 class="mb-3">Asignación de Lección Guía (Homeroom)</h4>
                <form action="guardar_guia.php" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Docente Guía</label>
                            <select class="form-select" name="docente_id" required>
                                <option value="">-- Seleccionar --</option>
                                <?php foreach($docentes as $doc): ?><option value="<?= $doc['id'] ?>"><?= $doc['nombre'] . ' ' . $doc['apellidos'] ?></option><?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sección a Cargo</label>
                            <select class="form-select" name="seccion_id" required>
                                <option value="">-- Seleccionar --</option>
                                <?php foreach($secciones as $sec): ?><option value="<?= $sec['id'] ?>"><?= $sec['nombre'] ?></option><?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 mt-3"><button type="submit" class="btn btn-dark">Habilitar Lección Guía</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Conflicto -->
<div class="modal fade" id="modalConflicto" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">¡Docente Existente!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Ya existe un docente impartiendo esta materia en esta sección.</p>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="opcionConflicto" id="op1" value="borrar" checked>
                    <label class="form-check-label" for="op1">Borrar la asistencia anterior e iniciar desde cero con el nuevo docente.</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="opcionConflicto" id="op2" value="mantener">
                    <label class="form-check-label" for="op2">Traspasar el grupo manteniendo la asistencia histórica.</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="confirmarReasignacion()">Reasignar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function verificarYAsignar(accion = null) {
    let formData = new FormData(document.getElementById('formAsignacionSeccion'));
    if(accion) formData.append('accion_conflicto', accion);

    fetch('controlador_asignacion.php', { method: 'POST', body: formData })
    .then(r => r.json())
    .then(data => {
        if(data.status === 'conflict') {
            new bootstrap.Modal(document.getElementById('modalConflicto')).show();
        } else if(data.status === 'success' || data.status === 'info') {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message);
        }
    });
}
function confirmarReasignacion() {
    let opcion = document.querySelector('input[name="opcionConflicto"]:checked').value;
    bootstrap.Modal.getInstance(document.getElementById('modalConflicto')).hide();
    verificarYAsignar(opcion);
}
</script>
</body>
</html>
