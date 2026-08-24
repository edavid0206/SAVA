<?php
require_once __DIR__ . '/app/config/Database.php';
use App\Config\Database;
$db = Database::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $db->prepare("INSERT INTO docente_guia (docente_id, seccion_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE seccion_id = VALUES(seccion_id)");
        $stmt->execute([$_POST['docente_id'], $_POST['seccion_id']]);
        header("Location: admin_academico.php?msg=guia_asignado");
    } catch (\Exception $e) {
        die("Error (Recuerda que un docente solo puede guiar 1 sección y una sección solo puede tener 1 guía): " . $e->getMessage());
    }
}
?>
