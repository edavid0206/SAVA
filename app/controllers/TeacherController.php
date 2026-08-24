<?php
namespace App\Controllers;

use App\Models\TeacherModel;

class TeacherController {

    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user']) || ($_SESSION['user']['rol'] !== 'profesor' && $_SESSION['user']['rol'] !== 'admin')) {
            header("Location: /sistema/public/index.php?route=dashboard");
            exit();
        }

        $docenteId = $_SESSION['user']['id'];
        $asignaciones = TeacherModel::getAsignacionesByDocente($docenteId);

        require_once __DIR__ . '/../views/teacher/index.php';
    }

    public function tomarAsistencia() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user'])) {
            header("Location: /sistema/public/index.php?route=login");
            exit();
        }

        $asignacionId = $_GET['asignacion_id'] ?? null;
        $fecha = $_GET['fecha'] ?? date('Y-m-d');

        if (!$asignacionId) {
            header("Location: /sistema/public/index.php?route=docente-panel");
            exit();
        }

        $docenteId = $_SESSION['user']['id'];
        $asignaciones = TeacherModel::getAsignacionesByDocente($docenteId);
        $asignacionActual = null;

        foreach ($asignaciones as $asig) {
            if ($asig['asignacion_id'] == $asignacionId) {
                $asignacionActual = $asig;
                break;
            }
        }

        if (!$asignacionActual) {
            header("Location: /sistema/public/index.php?route=docente-panel");
            exit();
        }

        $mesActual = (int)date('n');
        $semestreActual = ($mesActual >= 7) ? '2' : '1';

        $estudiantes = TeacherModel::getEstudiantesBySeccion(
            $asignacionActual['seccion_id'], 
            $asignacionActual['es_subgrupo'], 
            $asignacionActual['subgrupo_asignado'], 
            $semestreActual
        );

        $asistenciaExistente = TeacherModel::getAsistenciaByFecha($asignacionId, $fecha);

        $fechaLimite = date('Y-m-d', strtotime('-30 days'));
        $permiteEditar = ($fecha >= $fechaLimite || $_SESSION['user']['rol'] === 'admin');

        $mensaje = $_GET['mensaje'] ?? '';
        $error = $_GET['error'] ?? '';

        require_once __DIR__ . '/../views/teacher/attendance.php';
    }

    public function guardarAsistencia() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $asignacionId = $_POST['asignacion_id'] ?? null;
            $fecha = $_POST['fecha'] ?? date('Y-m-d');
            $numLecciones = intval($_POST['num_lecciones'] ?? 1);
            $asistencias = $_POST['asistencia'] ?? [];

            $fechaLimite = date('Y-m-d', strtotime('-30 days'));
            if ($fecha < $fechaLimite && $_SESSION['user']['rol'] !== 'admin') {
                header("Location: /sistema/public/index.php?route=docente-asistencia&asignacion_id=$asignacionId&fecha=$fecha&error=" . urlencode("El plazo de 30 días para modificar esta asistencia ha expirado."));
                exit();
            }

            if ($asignacionId && !empty($asistencias)) {
                $exito = TeacherModel::guardarAsistencia($asignacionId, $fecha, $numLecciones, $asistencias);
                if ($exito) {
                    header("Location: /sistema/public/index.php?route=docente-asistencia&asignacion_id=$asignacionId&fecha=$fecha&mensaje=" . urlencode("Asistencia guardada correctamente."));
                } else {
                    header("Location: /sistema/public/index.php?route=docente-asistencia&asignacion_id=$asignacionId&fecha=$fecha&error=" . urlencode("Error al guardar la asistencia en la base de datos."));
                }
                exit();
            }
        }
    }

    public function verHistorial() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user'])) {
            header("Location: /sistema/public/index.php?route=login");
            exit();
        }

        $asignacionId = $_GET['asignacion_id'] ?? null;
        if (!$asignacionId) {
            header("Location: /sistema/public/index.php?route=docente-panel");
            exit();
        }

        $docenteId = $_SESSION['user']['id'];
        $asignaciones = TeacherModel::getAsignacionesByDocente($docenteId);
        $asignacionActual = null;

        foreach ($asignaciones as $asig) {
            if ($asig['asignacion_id'] == $asignacionId) {
                $asignacionActual = $asig;
                break;
            }
        }

        if (!$asignacionActual) {
            header("Location: /sistema/public/index.php?route=docente-panel");
            exit();
        }

        $historial = TeacherModel::getHistorialSesiones($asignacionId);

        require_once __DIR__ . '/../views/teacher/history.php';
    }
}
