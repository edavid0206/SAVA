<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class User {
    public static function authenticate($username, $password) {
        $db = Database::getConnection();
        
        // Usamos parámetros posicionales (?) para evitar cualquier conflicto de nombres
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE (usuario = ? OR correo = ?) AND estado = 1 LIMIT 1");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return [
                'id' => $user['id'],
                'username' => $user['usuario'],
                'nombre' => $user['nombre'] . ' ' . $user['apellidos'],
                'rol' => $user['rol']
            ];
        }
        return false;
    }
}
