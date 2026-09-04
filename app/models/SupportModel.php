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
        $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO usuarios (cedula, usuario, nombre, apellidos, correo, password, rol, estado) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
        return $stmt->execute([
            $data['cedula'],
            $data['usuario'],
            $data['nombre'],
            $data['apellidos'],
            $data['correo'],
            $passwordHash,
            $data['rol']
        ]);
    }

    public static function updateUser($id, $data) {
        $db = new Database();
        $pdo = $db->getConnection();
        
        if (!empty($data['password'])) {
            $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE usuarios SET cedula = ?, usuario = ?, nombre = ?, apellidos = ?, correo = ?, rol = ?, password = ? WHERE id = ?");
            return $stmt->execute([
                $data['cedula'],
                $data['usuario'],
                $data['nombre'],
                $data['apellidos'],
                $data['correo'],
                $data['rol'],
                $passwordHash,
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
        $stmt = $pdo->prepare("UPDATE usuarios SET estado = IF(estado = 1, 0, 1) WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function getSystemLogs() {
        $db = new Database();
        $pdo = $db->getConnection();
        try {
            $stmt = $pdo->query("SELECT * FROM system_logs ORDER BY fecha DESC");
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return [];
        }
    }

    public static function getStats() {
        $db = new Database();
        $pdo = $db->getConnection();
        $stats = ['total' => 0, 'usuarios' => 0, 'docentes' => 0, 'admins' => 0, 'db_size' => 0];
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as t FROM usuarios");
            $count = $stmt->fetch(\PDO::FETCH_ASSOC)['t'] ?? 0; 
            $stats['total'] = $count; 
            $stats['usuarios'] = $count;
            
            $stmt = $pdo->query("SELECT COUNT(*) as t FROM usuarios WHERE rol = 'profesor'");
            $stats['docentes'] = $stmt->fetch(\PDO::FETCH_ASSOC)['t'] ?? 0;
            
            $stmt = $pdo->query("SELECT COUNT(*) as t FROM usuarios WHERE rol IN ('admin', 'administrativo')");
            $stats['admins'] = $stmt->fetch(\PDO::FETCH_ASSOC)['t'] ?? 0;
            
            $stmt = $pdo->query("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size FROM information_schema.TABLES WHERE table_schema = 'sava_db'");
            $stats['db_size'] = $stmt->fetch(\PDO::FETCH_ASSOC)['size'] ?? 0;
        } catch (\Exception $e) {}
        return $stats;
    }

    public static function getServerInfo() {
        $disk_free = @disk_free_space("/") ? round(@disk_free_space("/") / 1024 / 1024 / 1024, 2) : 0;
        $disk_total = @disk_total_space("/") ? round(@disk_total_space("/") / 1024 / 1024 / 1024, 2) : 0;
        return [
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Apache / Linux',
            'database' => 'MySQL / MariaDB',
            'system_time' => date('Y-m-d H:i:s'),
            'disk_free' => $disk_free,
            'disk_total' => $disk_total
        ];
    }

    public static function clearSystemLogs() {
        $db = new Database();
        $pdo = $db->getConnection();
        $stmt = $pdo->prepare("DELETE FROM system_logs");
        return $stmt->execute();
    }
}
