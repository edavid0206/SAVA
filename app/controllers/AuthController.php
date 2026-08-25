<?php
namespace App\Controllers;

use App\Models\User;
use App\Config\Database;

class AuthController {

    private static function registrarLog($accion, $detalles, $userId = null, $userInfo = null) {
        try {
            $db = new Database();
            $pdo = $db->getConnection();

            $usuarioId = $userId ?? ($_SESSION['user']['id'] ?? null);
            $nombre = $userInfo['nombre'] ?? ($_SESSION['user']['nombre'] ?? 'Sistema');
            $apellidos = $userInfo['apellidos'] ?? ($_SESSION['user']['apellidos'] ?? '');
            $rol = $userInfo['rol'] ?? ($_SESSION['user']['rol'] ?? 'invitado');
            
            $responsable = " [Usuario: {$nombre} {$apellidos} (Rol: {$rol})]";
            $detallesCompletos = $detalles . $responsable;

            $ip = $_SERVER['HTTP_CLIENT_IP'] 
                  ?? $_SERVER['HTTP_X_FORWARDED_FOR'] 
                  ?? $_SERVER['REMOTE_ADDR'] 
                  ?? 'Desconocida';

            $stmt = $pdo->prepare("INSERT INTO system_logs (usuario_id, accion, detalles, ip_address) VALUES (?, ?, ?, ?)");
            $stmt->execute([$usuarioId, $accion, $detallesCompletos, $ip]);
        } catch (\Exception $e) {
            // Silenciar error
        }
    }

    public function showLogin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user'])) {
            header("Location: /sistema/public/index.php?route=dashboard");
            exit();
        }
        $error = $_GET['error'] ?? '';
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function processLogin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $usuario = trim($_POST['usuario'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($usuario) || empty($password)) {
            header("Location: /sistema/public/index.php?route=login&error=" . urlencode("Por favor, complete todos los campos."));
            exit();
        }

        $user = User::authenticate($usuario, $password);

        if ($user) {
            if (isset($user['estado']) && $user['estado'] == 0) {
                header("Location: /sistema/public/index.php?route=login&error=" . urlencode("Su cuenta está inactiva. Contacte al administrador."));
                exit();
            }

            $_SESSION['user'] = $user;

            // Registrar log de inicio de sesión exitoso
            self::registrarLog('INICIO DE SESIÓN', "El usuario {$user['usuario']} inició sesión correctamente en el sistema.", $user['id'], $user);

            header("Location: /sistema/public/index.php?route=dashboard");
            exit();
        } else {
            self::registrarLog('LOGIN FALLIDO', "Intento fallido de acceso con el usuario/cédula: {$usuario}");
            header("Location: /sistema/public/index.php?route=login&error=" . urlencode("Credenciales incorrectas. Verifique sus datos."));
            exit();
        }
    }

    public function dashboard() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: /sistema/public/index.php?route=login");
            exit();
        }

        $user = $_SESSION['user'];
        require_once __DIR__ . '/../views/dashboard/index.php';
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user'])) {
            // Registrar log de cierre de sesión antes de destruir la sesión
            self::registrarLog('CIERRE DE SESIÓN', "El usuario {$_SESSION['user']['usuario']} cerró sesión y salió del sistema.");
        }

        session_destroy();
        header("Location: /sistema/public/index.php?route=login");
        exit();
    }
}
