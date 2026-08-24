<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class TeacherModel {

    public static function getAsignacionesByDocente($docenteId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT ad.id as asignacion_id, s.id as seccion_id, s.nombre as seccion_nombre, 
                   n.nombre as nivel_nombre, m.id as materia_id, m.nombre as materia_nombre, 
                   m.es_subgrupo, ad.semestre, ad.subgrupo_asignado
            FROM asignaciones_docente ad
            JOIN secciones s ON ad.seccion_id = s.id
            JOIN niveles n ON s.nivel_id = n.id
            JOIN materias m ON ad.materia_id = m.id
            WHERE ad.docente_id = ?
        ");
        $stmt->execute([$docenteId]);
        return $stmt->fetchAll();
    }

    public static function getEstudiantesBySeccion($seccionId, $esSubgrupo, $subgrupoAsignado, $semestreActual) {
        $db = Database::getConnection();
        
        if ($esSubgrupo && $subgrupoAsignado !== 'todos') {
            $subgrupoBusqueda = $subgrupoAsignado;
            if ($semestreActual == '2') {
                $subgrupoBusqueda = ($subgrupoAsignado === 'A') ? 'B' : 'A';
            }
            
            $stmt = $db->prepare("SELECT id, cedula, nombre, apellidos, subgrupo FROM estudiantes WHERE seccion_id = ? AND subgrupo = ? ORDER BY apellidos ASC");
            $stmt->execute([$seccionId, $subgrupoBusqueda]);
        } else {
            $stmt = $db->prepare("SELECT id, cedula, nombre, apellidos, subgrupo FROM estudiantes WHERE seccion_id = ? ORDER BY apellidos ASC");
            $stmt->execute([$seccionId]);
        }
        
        return $stmt->fetchAll();
    }

    public static function getFechasAsistenciaByAsignacion($asignacionId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT DISTINCT fecha FROM sesiones_asistencia WHERE asignacion_id = ?");
        $stmt->execute([$asignacionId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function guardarAsistencia($asignacionId, $fecha, $numLecciones, $asistencias) {
        $db = Database::getConnection();
        
        try {
            $db->beginTransaction();

            $stmt = $db->prepare("SELECT id FROM sesiones_asistencia WHERE asignacion_id = ? AND fecha = ? LIMIT 1");
            $stmt->execute([$asignacionId, $fecha]);
            $sesion = $stmt->fetch();

            if ($sesion) {
                $sesionId = $sesion['id'];
                $stmtUp = $db->prepare("UPDATE sesiones_asistencia SET num_lecciones = ? WHERE id = ?");
                $stmtUp->execute([$numLecciones, $sesionId]);

                $stmtDel = $db->prepare("DELETE FROM detalle_asistencia WHERE sesion_id = ?");
                $stmtDel->execute([$sesionId]);
            } else {
                $stmtIns = $db->prepare("INSERT INTO sesiones_asistencia (asignacion_id, fecha, num_lecciones) VALUES (?, ?, ?)");
                $stmtIns->execute([$asignacionId, $fecha, $numLecciones]);
                $sesionId = $db->lastInsertId();
            }

            $stmtDet = $db->prepare("INSERT INTO detalle_asistencia (sesion_id, estudiante_id, estado) VALUES (?, ?, ?)");
            foreach ($asistencias as $estudianteId => $estado) {
                $stmtDet->execute([$sesionId, $estudianteId, $estado]);
            }

            $db->commit();
            return true;
        } catch (\Exception $e) {
            $db->rollBack();
            return false;
        }
    }

    public static function getAsistenciaByFecha($asignacionId, $fecha) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id, num_lecciones FROM sesiones_asistencia WHERE asignacion_id = ? AND fecha = ? LIMIT 1");
        $stmt->execute([$asignacionId, $fecha]);
        $sesion = $stmt->fetch();

        if (!$sesion) return null;

        $stmtDet = $db->prepare("SELECT estudiante_id, estado FROM detalle_asistencia WHERE sesion_id = ?");
        $stmtDet->execute([$sesion['id']]);
        $detalles = $stmtDet->fetchAll(PDO::FETCH_KEY_PAIR);

        return [
            'sesion_id' => $sesion['id'],
            'num_lecciones' => $sesion['num_lecciones'],
            'detalles' => $detalles
        ];
    }

    public static function getHistorialSesiones($asignacionId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT sa.id as sesion_id, sa.fecha, sa.num_lecciones, sa.creado_en,
                   (SELECT COUNT(*) FROM detalle_asistencia da WHERE da.sesion_id = sa.id AND da.estado = 'presente') as total_presentes,
                   (SELECT COUNT(*) FROM detalle_asistencia da WHERE da.sesion_id = sa.id AND da.estado != 'presente') as total_ausentes
            FROM sesiones_asistencia sa
            WHERE sa.asignacion_id = ?
            ORDER BY sa.fecha DESC
        ");
        $stmt->execute([$asignacionId]);
        return $stmt->fetchAll();
    }
}
