<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class StudentModel {
    public static function createStudent($data) {
        $db = Database::getConnection();
        // Forzar mayúsculas en nombre y apellidos por seguridad institucional
        $nombre = mb_strtoupper(trim($data['nombre']), 'UTF-8');
        $apellidos = mb_strtoupper(trim($data['apellidos']), 'UTF-8');
        
        $stmt = $db->prepare("INSERT INTO estudiantes (cedula, nombre, apellidos, seccion_id, subgrupo) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            trim($data['cedula']),
            $nombre,
            $apellidos,
            $data['seccion_id'],
            $data['subgrupo'] ?? 'A'
        ]);
    }
}
