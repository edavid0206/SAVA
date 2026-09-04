<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class AcademicModel {

    // Obtener el período lectivo activo actual
    public static function getPeriodoActivo() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM periodos_lectivos WHERE estado = 'activo' LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Registrar o actualizar conducta del estudiante por parte del docente guía
    public static function guardarConducta($estudianteId, $docenteId, $periodoId, $puntaje, $observacion, $tipo) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO conducta_estudiantes (estudiante_id, docente_id, periodo_lectivo_id, puntaje, observacion, tipo) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$estudianteId, $docenteId, $periodoId, $puntaje, $observacion, $tipo]);
    }

    // Obtener historial de conducta de un estudiante
    public static function getConductaByEstudiante($estudianteId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT c.*, CONCAT(u.nombre, ' ', u.apellidos) AS docente_nombre 
            FROM conducta_estudiantes c
            JOIN usuarios u ON c.docente_id = u.id
            WHERE c.estudiante_id = ?
            ORDER BY c.fecha DESC
        ");
        $stmt->execute([$estudianteId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Calcular ausentismo y verificar alerta del 20%
    public static function verificarAlertasAusentismo($seccionId, $periodoId) {
        $db = Database::getConnection();
        
        // Total de lecciones impartidas en la sección durante el período
        // (Calculado a través de las sesiones de asistencia de las asignaciones de la sección)
        $stmtTotal = $db->prepare("
            SELECT SUM(sa.num_lecciones) as total_impartidas
            FROM sesiones_asistencia sa
            JOIN asignaciones_docente ad ON sa.asignacion_id = ad.id
            WHERE ad.seccion_id = ?
        ");
        $stmtTotal->execute([$seccionId]);
        $totalLecciones = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total_impartidas'] ?? 0;

        if ($totalLecciones == 0) return [];

        $limiteAusencias = $totalLecciones * 0.20;

        // Obtener ausencias por estudiante en la sección
        $stmtAusencias = $db->prepare("
            SELECT e.id, e.cedula, e.nombre, e.apellidos,
                   COUNT(CASE WHEN da.estado IN ('ausente', 'justificado') THEN 1 END) as total_ausencias
            FROM estudiantes e
            LEFT JOIN detalle_asistencia da ON e.id = da.estudiante_id
            LEFT JOIN sesiones_asistencia sa ON da.sesion_id = sa.id
            LEFT JOIN asignaciones_docente ad ON sa.asignacion_id = ad.id
            WHERE e.seccion_id = ? AND (ad.seccion_id = ? OR ad.seccion_id IS NULL)
            GROUP BY e.id
        ");
        $stmtAusencias->execute([$seccionId, $seccionId]);
        $estudiantes = $stmtAusencias->fetchAll(PDO::FETCH_ASSOC);

        $alertas = [];
        foreach ($estudiantes as $est) {
            if ($est['total_ausencias'] >= $limiteAusencias && $limiteAusencias > 0) {
                $porcentaje = ($est['total_ausencias'] / $totalLecciones) * 100;
                $alertas[] = [
                    'estudiante' => $est,
                    'ausencias' => $est['total_ausencias'],
                    'total_lecciones' => $totalLecciones,
                    'porcentaje' => round($porcentaje, 2)
                ];

                // Registrar o actualizar alerta en la tabla alertas_ausentismo
                $stmtCheckAlerta = $db->prepare("SELECT id FROM alertas_ausentismo WHERE estudiante_id = ? AND periodo_lectivo_id = ? LIMIT 1");
                $stmtCheckAlerta->execute([$est['id'], $periodoId]);
                if (!$stmtCheckAlerta->fetch()) {
                    $stmtInsAlerta = $db->prepare("INSERT INTO alertas_ausentismo (estudiante_id, periodo_lectivo_id, porcentaje_ausencia, estado) VALUES (?, ?, ?, 'pendiente')");
                    $stmtInsAlerta->execute([$est['id'], $periodoId, round($porcentaje, 2)]);
                }
            }
        }
        return $alertas;
    }

    // Promoción anual de estudiantes (Dirección)
    public static function promoverEstudiante($estudianteId, $nuevoNivelId, $nuevaSeccionId, $periodoId, $estadoMatricula, $observacion) {
        $db = Database::getConnection();
        try {
            $db->beginTransaction();

            // 1. Guardar historial de la matrícula actual antes de mover
            $stmtHist = $db->prepare("SELECT seccion_id FROM estudiantes WHERE id = ?");
            $stmtHist->execute([$estudianteId]);
            $estActual = $stmtHist->fetch(PDO::FETCH_ASSOC);

            if ($estActual) {
                $stmtMatHist = $db->prepare("INSERT INTO matricula_historica (estudiante_id, periodo_lectivo_id, seccion_id, estado_matricula, observacion) VALUES (?, ?, ?, ?, ?)");
                $stmtMatHist->execute([$estudianteId, $periodoId, $estActual['seccion_id'], $estadoMatricula, $observacion]);
            }

            // 2. Actualizar estudiante a la nueva sección / nivel
            $stmtUp = $db->prepare("UPDATE estudiantes SET seccion_id = ? WHERE id = ?");
            $stmtUp->execute([$nuevaSeccionId, $estudianteId]);

            $db->commit();
            return true;
        } catch (\Exception $e) {
            $db->rollBack();
            return false;
        }
    }
}
