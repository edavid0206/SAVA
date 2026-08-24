<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class SupportModel {
    
    public static function getAllUsers() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT id, cedula, nombre, apellidos, usuario, correo, rol, estado, creado_en FROM usuarios ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public static function getUserById($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id, cedula, nombre, apellidos, usuario, correo, rol, estado FROM usuarios WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function createUser($data) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO usuarios (cedula, nombre, apellidos, usuario, correo, password, rol, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['cedula'],
            $data['nombre'],
            $data['apellidos'],
            $data['usuario'],
            $data['correo'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['rol'],
            1
        ]);
    }

    public static function updateUser($id, $data) {
        $db = Database::getConnection();
        
        // Si se proporciona una nueva contraseña, la actualizamos; de lo contrario, mantenemos la anterior
        if (!empty($data['password'])) {
            $stmt = $db->prepare("UPDATE usuarios SET cedula = ?, nombre = ?, apellidos = ?, usuario = ?, correo = ?, password = ?, rol = ? WHERE id = ?");
            return $stmt->execute([
                $data['cedula'],
                $data['nombre'],
                $data['apellidos'],
                $data['usuario'],
                $data['correo'],
                password_hash($data['password'], PASSWORD_DEFAULT),
                $data['rol'],
                $id
            ]);
        } else {
            $stmt = $db->prepare("UPDATE usuarios SET cedula = ?, nombre = ?, apellidos = ?, usuario = ?, correo = ?, rol = ? WHERE id = ?");
            return $stmt->execute([
                $data['cedula'],
                $data['nombre'],
                $data['apellidos'],
                $data['usuario'],
                $data['correo'],
                $data['rol'],
                $id
            ]);
        }
    }

    public static function toggleStatus($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE usuarios SET estado = IF(estado = 1, 0, 1) WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function deleteUser($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM usuarios WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function getStats() {
        $db = Database::getConnection();
        $totalUsuarios = $db->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
        $totalDocentes = $db->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'profesor'")->fetchColumn();
        $totalAdmins = $db->query("SELECT COUNT(*) FROM usuarios WHERE rol IN ('admin', 'administrativo')")->fetchColumn();
        
        return [
            'usuarios' => $totalUsuarios,
            'docentes' => $totalDocentes,
            'admins' => $totalAdmins
        ];
    }
}
