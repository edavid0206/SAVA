<?php
require_once __DIR__ . '/app/config/Database.php';
use App\Config\Database;
$db = Database::getConnection();

$seccionId = $_POST['seccion_id'] ?? null;
$asignaturaId = $_POST['asignatura_id'] ?? null;
$docenteId = $_POST['docente_id'] ?? null;
$accionConflicto = $_POST['accion_conflicto'] ?? null;

if (!$seccionId || !$asignaturaId || !$docenteId) {
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos obligatorios.']);
    exit;
}

try {
    $stmt = $db->prepare("SELECT * FROM seccion_docente_materia WHERE seccion_id = ? AND asignatura_id = ?");
    $stmt->execute([$seccionId, $asignaturaId]);
    $existente = $stmt->fetch();

    if ($existente) {
        if ($existente['docente_id'] == $docenteId) {
            echo json_encode(['status' => 'info', 'message' => 'El docente ya imparte esta asignatura en esta sección.']);
            exit;
        }

        if (!$accionConflicto) {
            echo json_encode(['status' => 'conflict', 'message' => 'Ya existe un docente asignado a esta materia en esta sección.']);
            exit;
        }

        if ($accionConflicto === 'borrar') {
            $db->prepare("DELETE FROM asistencia WHERE seccion_id = ? AND asignatura_id = ?")->execute([$seccionId, $asignaturaId]);
        } 
        
        $db->prepare("UPDATE seccion_docente_materia SET docente_id = ? WHERE seccion_id = ? AND asignatura_id = ?")->execute([$docenteId, $seccionId, $asignaturaId]);
        echo json_encode(['status' => 'success', 'message' => 'Docente reasignado correctamente y políticas aplicadas.']);
        exit;
    }

    $db->prepare("INSERT INTO seccion_docente_materia (seccion_id, asignatura_id, docente_id) VALUES (?, ?, ?)")->execute([$seccionId, $asignaturaId, $docenteId]);
    echo json_encode(['status' => 'success', 'message' => 'Asignatura vinculada al docente en la sección exitosamente.']);

} catch (\Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
}
?>
