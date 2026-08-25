<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class StudentModel {
    
    // Registrar estudiante individual con especialidad e idioma
    public static function createStudent($data) {
        $db = Database::getConnection();
        $nombre = mb_strtoupper(trim($data['nombre']), 'UTF-8');
        $apellidos = mb_strtoupper(trim($data['apellidos']), 'UTF-8');
        
        $stmt = $db->prepare("INSERT INTO estudiantes (cedula, nombre, apellidos, seccion_id, subgrupo, especialidad_tecnica, idioma) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            trim($data['cedula']),
            $nombre,
            $apellidos,
            $data['seccion_id'],
            $data['subgrupo'] ?? 'A',
            !empty($data['especialidad_tecnica']) ? trim($data['especialidad_tecnica']) : null,
            !empty($data['idioma']) ? trim($data['idioma']) : null
        ]);
    }

    // Actualizar datos del estudiante (cambio de sección, subgrupo, especialidad o idioma)
    public static function updateStudent($id, $data) {
        $db = Database::getConnection();
        $nombre = mb_strtoupper(trim($data['nombre']), 'UTF-8');
        $apellidos = mb_strtoupper(trim($data['apellidos']), 'UTF-8');

        $stmt = $db->prepare("UPDATE estudiantes SET cedula = ?, nombre = ?, apellidos = ?, seccion_id = ?, subgrupo = ?, especialidad_tecnica = ?, idioma = ? WHERE id = ?");
        return $stmt->execute([
            trim($data['cedula']),
            $nombre,
            $apellidos,
            $data['seccion_id'],
            $data['subgrupo'] ?? 'A',
            !empty($data['especialidad_tecnica']) ? trim($data['especialidad_tecnica']) : null,
            !empty($data['idioma']) ? trim($data['idioma']) : null,
            $id
        ]);
    }

    // Obtener estudiante por ID
    public static function getStudentById($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM estudiantes WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Importación masiva desde CSV (Soporta: cédula, nombre, apellidos, subgrupo, especialidad, idioma)
    public static function importCsv($filePath, $seccionId) {
        $db = Database::getConnection();
        if (!file_exists($filePath)) {
            return ['success' => 0, 'errors' => 0, 'message' => 'Archivo no encontrado.'];
        }

        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            return ['success' => 0, 'errors' => 0, 'message' => 'No se pudo abrir el archivo CSV.'];
        }

        $successCount = 0;
        $errorCount = 0;

        // Omitir cabecera
        $header = fgetcsv($handle, 1000, ",");

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Estructura CSV: cedula, nombre, apellidos, subgrupo (opcional), especialidad (opcional), idioma (opcional)
            if (count($data) >= 3) {
                $cedula = trim($data[0]);
                $nombre = mb_strtoupper(trim($data[1]), 'UTF-8');
                $apellidos = mb_strtoupper(trim($data[2]), 'UTF-8');
                $subgrupo = isset($data[3]) && !empty(trim($data[3])) ? trim($data[3]) : 'A';
                $especialidad = isset($data[4]) && !empty(trim($data[4])) ? trim($data[4]) : null;
                $idioma = isset($data[5]) && !empty(trim($data[5])) ? trim($data[5]) : null;

                if (!empty($cedula) && !empty($nombre) && !empty($apellidos)) {
                    try {
                        $stmtCheck = $db->prepare("SELECT id FROM estudiantes WHERE cedula = ? LIMIT 1");
                        $stmtCheck->execute([$cedula]);
                        $existing = $stmtCheck->fetch();

                        if ($existing) {
                            $stmtUpdate = $db->prepare("UPDATE estudiantes SET nombre = ?, apellidos = ?, seccion_id = ?, subgrupo = ?, especialidad_tecnica = ?, idioma = ? WHERE cedula = ?");
                            $stmtUpdate->execute([$nombre, $apellidos, $seccionId, $subgrupo, $especialidad, $idioma, $cedula]);
                        } else {
                            $stmtInsert = $db->prepare("INSERT INTO estudiantes (cedula, nombre, apellidos, seccion_id, subgrupo, especialidad_tecnica, idioma) VALUES (?, ?, ?, ?, ?, ?, ?)");
                            $stmtInsert->execute([$cedula, $nombre, $apellidos, $seccionId, $subgrupo, $especialidad, $idioma]);
                        }
                        $successCount++;
                    } catch (\Exception $e) {
                        $errorCount++;
                    }
                } else {
                    $errorCount++;
                }
            } else {
                $errorCount++;
            }
        }
        fclose($handle);

        return [
            'success' => $successCount,
            'errors' => $errorCount,
            'message' => "Importación completada: {$successCount} estudiantes procesados correctamente, {$errorCount} con errores o omitidos."
        ];
    }
}
