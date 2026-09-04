<?php
namespace App\Controllers;

use App\Config\Database;
use App\Models\TeacherModel;
use App\Models\AcademicModel;

class TeacherController {

    private static function registrarLog($accion, $detalles) {
        try {
            $db = new Database();
            $pdo = $db->getConnection();
            $usuarioId = $_SESSION['user']['id'] ?? null;
            $nombre = $_SESSION['user']['nombre'] ?? 'Desconocido';
            $apellidos = $_SESSION['user']['apellidos'] ?? '';
            $rol = $_SESSION['user']['rol'] ?? '';
            $responsable = " [Realizado por: {$nombre} {$apellidos} (Rol: {$rol})]";
            $detallesCompletos = $detalles . $responsable;
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'Desconocida';

            $stmt = $pdo->prepare("INSERT INTO system_logs (usuario_id, accion, detalles, ip_address) VALUES (?, ?, ?, ?)");
            $stmt->execute([$usuarioId, $accion, $detallesCompletos, $ip]);
        } catch (\Exception $e) {}
    }

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

        $asignaciones = [];
        $seccionGuia = null;
        $estudiantesGuia = [];
        $alertasAusentismo = [];
        $periodoActivo = AcademicModel::getPeriodoActivo();

        try {
            // Asignaciones de materias del docente
            $stmt = $pdo->prepare("
                SELECT ad.id, s.id as seccion_id, s.nombre AS seccion_nombre, n.nombre AS nivel_nombre, m.nombre AS materia_nombre
                FROM asignaciones_docente ad
                JOIN secciones s ON ad.seccion_id = s.id
                JOIN niveles n ON s.nivel_id = n.id
                JOIN materias m ON ad.materia_id = m.id
                WHERE ad.docente_id = ?
                ORDER BY n.id, s.nombre
            ");
            $stmt->execute([$docenteId]);
            $asignaciones = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Verificar si es Profesor Guía y si el permiso administrativo está HABILITADO (guia_habilitado = 1)
            $stmtGuia = $pdo->prepare("
                SELECT s.id, n.nombre AS nivel_nombre, s.nombre AS seccion_nombre, s.guia_habilitado 
                FROM secciones s 
                JOIN niveles n ON s.nivel_id = n.id 
                WHERE s.docente_guia_id = ? AND s.guia_habilitado = 1
            ");
            $stmtGuia->execute([$docenteId]);
            $seccionGuia = $stmtGuia->fetch(\PDO::FETCH_ASSOC);

            if ($seccionGuia && $periodoActivo) {
                // Obtener los estudiantes de la sección guía
                $stmtEst = $pdo->prepare("
                    SELECT * FROM estudiantes 
                    WHERE seccion_id = ? 
                    ORDER BY apellidos, nombre
                ");
                $stmtEst->execute([$seccionGuia['id']]);
                $estudiantesGuia = $stmtEst->fetchAll(\PDO::FETCH_ASSOC);

                // Calcular alertas tempranas de ausentismo (20%)
                $alertasAusentismo = AcademicModel::verificarAlertasAusentismo($seccionGuia['id'], $periodoActivo['id']);
            }

        } catch (\Exception $e) {}

        $mensaje = $_GET['mensaje'] ?? '';
        $error = $_GET['error'] ?? '';

        require_once __DIR__ . '/../views/teacher/index.php';
    }

    public function guardarConducta() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'profesor') {
            header("Location: /sistema/public/index.php?route=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $estudianteId = $_POST['estudiante_id'] ?? null;
            $puntaje = $_POST['puntaje'] ?? 0;
            $tipo = trim($_POST['tipo'] ?? 'observacion');
            $observacion = trim($_POST['observacion'] ?? '');
            $docenteId = $_SESSION['user']['id'];
            $periodoActivo = AcademicModel::getPeriodoActivo();

            if (!$estudianteId || empty($observacion) || !$periodoActivo) {
                header("Location: /sistema/public/index.php?route=docente-panel&error=" . urlencode("Complete los datos requeridos para registrar la conducta."));
                exit();
            }

            try {
                AcademicModel::guardarConducta($estudianteId, $docenteId, $periodoActivo['id'], $puntaje, $observacion, $tipo);
                self::registrarLog('REGISTRO CONDUCTA', "Se registró una observación/conducta para el estudiante ID {$estudianteId} (Tipo: {$tipo})");
                header("Location: /sistema/public/index.php?route=docente-panel&mensaje=" . urlencode("Registro de conducta guardado correctamente."));
            } catch (\Exception $e) {
                header("Location: /sistema/public/index.php?route=docente-panel&error=" . urlencode("Error al guardar el registro de conducta."));
            }
            exit();
        }
    }

    public function tomarAsistencia() {
        // Lógica de asistencia existente
    }

    public function guardarAsistencia() {
        // Lógica para guardar asistencia existente
    }

    public function verHistorial() {
        // Lógica para historial
    }
}
