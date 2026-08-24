<?php
namespace App\Controllers;

use App\Models\User;

class AuthController {
    
    public function showLogin() {
        $error = $_GET['error'] ?? '';
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function processLogin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $usuario = $_POST['usuario'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($usuario) || empty($password)) {
            header("Location: /sistema/public/index.php?route=login&error=" . urlencode("Por favor, complete todos los campos."));
            exit();
        }

        $user = User::authenticate($usuario, $password);

        if ($user) {
            $_SESSION['user'] = $user;
            header("Location: /sistema/public/index.php?route=dashboard");
            exit();
        } else {
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
        session_destroy();
        header("Location: /sistema/public/index.php?route=login");
        exit();
    }
}
