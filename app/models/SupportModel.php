<?php
namespace App\Models;

use App\Config\Database;

class SupportModel {

    public static function getAllUsers() {
        $db = new Database();
        $pdo = $db->getConnection();
        $stmt = $pdo->query("SELECT * FROM usuarios ORDER BY id DESC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getUserById($id) {
        $db = new Database();
        $pdo = $db->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function createUser($data) {
        $db = new Database();
        $pdo = $db->getConnection();
        $stmt = $pdo->prepare("INSERT INTO usuarios (cedula, usuario, nombre, apellidos, correo, password, rol, estado) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        return $stmt->execute([
            $data['cedula'],
            $data['usuario'],
            $data['nombre'],
            $data['apellidos'],
            $data['correo'],
            $hashedPassword,
            $data['rol']
        ]);
    }

    public static function updateUser($id, $data) {
        $db = new Database();
        $pdo = $db->getConnection();
        
        if (!empty($data['password'])) {
            $stmt = $pdo->prepare("UPDATE usuarios SET cedula = ?, usuario = ?, nombre = ?, apellidos = ?, correo = ?, rol = ?, password = ? WHERE id = ?");
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            return $stmt->execute([
                $data['cedula'],
                $data['usuario'],
                $data['nombre'],
                $data['apellidos'],
                $data['correo'],
                $data['rol'],
                $hashedPassword,
                $id
            ]);
        } else {
            $stmt = $pdo->prepare("UPDATE usuarios SET cedula = ?, usuario = ?, nombre = ?, apellidos = ?, correo = ?, rol = ? WHERE id = ?");
            return $stmt->execute([
                $data['cedula'],
                $data['usuario'],
                $data['nombre'],
                $data['apellidos'],
                $data['correo'],
                $data['rol'],
                $id
            ]);
        }
    }

    public static function toggleUserStatus($id) {
        $db = new Database();
        $pdo = $db->getConnection();
        $stmt = $pdo->prepare("UPDATE usuarios SET estado = NOT estado WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function getSystemLogs() {
        $db = new Database();
        $pdo = $db->getConnection();
        $stmt = $pdo->query("
            SELECT sl.*, u.nombre, u.apellidos, u.usuario as username 
            FROM system_logs sl 
            LEFT JOIN usuarios u ON sl.usuario_id = u.id 
            ORDER BY sl.id DESC LIMIT 200
        ");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getServerInfo() {
        $diskTotal = @disk_total_space("/");
        $diskFree = @disk_free_space("/");
        return [
            'php_version' => PHP_VERSION,
            'disk_total' => $diskTotal ? round($diskTotal / 1024 / 1024 / 1024, 2) . ' GB' : 'N/A',
            'disk_free' => $diskFree ? round($diskFree / 1024 / 1024 / 1024, 2) . ' GB' : 'N/A'
        ];
    }

    public static function getStats() {
        $db = new Database();
        $pdo = $db->getConnection();
        
        $total = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
        $docentes = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'profesor'")->fetchColumn();
        $administrativos = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'administrativo'")->fetchColumn();
        $soporte = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE rol IN ('admin', 'soporte')")->fetchColumn();
        
        $dbSize = 0;
        try {
            $sizeQuery = $pdo->query("SELECT SUM(data_length + index_length) FROM information_schema.tables WHERE table_schema = DATABASE()");
            $bytes = $sizeQuery->fetchColumn();
            $dbSize = round($bytes / 1024 / 1024, 2);
        } catch (\Exception $e) {}

        return [
            'total' => $total,
            'docentes' => $docentes,
            'administrativos' => $administrativos,
            'soporte' => $soporte,
            'db_size' => $dbSize
        ];
    }
}
