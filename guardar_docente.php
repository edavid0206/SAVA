<?php
require_once __DIR__ . '/app/config/Database.php';
use App\Config\Database;
$db = Database::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db->beginTransaction();
        $stmt = $db->prepare("INSERT INTO docentes (cedula, nombre, apellidos) VALUES (?, UPPER(?), UPPER(?))");
        $stmt->execute([$_POST['cedula'], $_POST['nombre'], $_POST['apellidos']]);
        $docenteId = $db->lastInsertId();

        if (!empty($_POST['asignaturas'])) {
            $stmtAsig = $db->prepare("INSERT INTO docente_asignaturas (docente_id, asignatura_id) VALUES (?, ?)");
            foreach ($_POST['asignaturas'] as $asigId) {
                $stmtAsig->execute([$docenteId, $asigId]);
            }
        }
        $db->commit();
        header("Location: admin_academico.php?msg=docente_creado");
    } catch (\Exception $e) {
        $db->rollBack();
        die("Error al guardar docente: " . $e->getMessage());
    }
}
?>
