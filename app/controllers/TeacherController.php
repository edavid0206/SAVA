<?php
namespace App\Controllers;

use App\Config\Database;
use App\Models\TeacherModel;

class TeacherController {

    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'profesor') {
            header("Location: /sistema/public/index.php?route=login");
            exit();
        }

        $docenteId = $_SESSION['user']['id'];
        $db = new Database();
        $pdo = $db->getConnection();

        $seccionesAsignadas = [];
        $seccionGuia = null;
        $estudiantesGuia = [];

        try {
            // Secciones donde imparte materias
            $stmt = $pdo->prepare("
                SELECT DISTINCT s.id, n.nombre AS nivel_nombre, s.nombre AS seccion_nombre 
                FROM secciones s 
                JOIN niveles n ON s.nivel_id = n.id 
                JOIN docente_materias dm ON dm.docente_id = ?
                ORDER BY n.id, s.nombre
            ");
            // Nota: Si la asignación se hace por materias, aseguramos que vea las secciones. 
            // Si usas otra tabla de asignación de secciones, la adaptamos.
            $stmt->execute([$docenteId]);
            $seccionesAsignadas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Verificar si es Profesor Guía y si el permiso administrativo está HABILITADO (guia_habilitado = 1)
            $stmtGuia = $pdo->prepare("
                SELECT s.id, n.nombre AS nivel_nombre, s.nombre AS seccion_nombre, s.guia_habilitado 
                FROM secciones s 
                JOIN niveles n ON s.nivel_id = n.id 
                WHERE s.docente_guia_id = ? AND s.guia_habilitado = 1
            ");
            $stmtGuia->execute([$docenteId]);
            $seccionGuia = $stmtGuia->fetch(\PDO::FETCH_ASSOC);

            if ($seccionGuia) {
                // Obtener los estudiantes de la sección guía
                $stmtEst = $pdo->prepare("
                    SELECT * FROM estudiantes 
                    WHERE seccion_id = ? 
                    ORDER BY apellidos, nombre
                ");
                $stmtEst->execute([$seccionGuia['id']]);
                $estudiantesGuia = $stmtEst->fetchAll(\PDO::FETCH_ASSOC);
            }

        } catch (\Exception $e) {
            // Manejar error de BD
        }

        $mensaje = $_GET['mensaje'] ?? '';
        $error = $_GET['error'] ?? '';

        require_once __DIR__ . '/../views/teacher/index.php';
    }

    public function tomarAsistencia() {
        // Lógica de asistencia
    }

    public function guardarAsistencia() {
        // Lógica para guardar asistencia
    }

    public function verHistorial() {
        // Lógica para historial
    }
}
