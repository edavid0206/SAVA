<?php
namespace App\Controllers;

use App\Config\Database;
use App\Models\SupportModel;

class SupportController {

    private static function registrarLog($accion, $detalles) {
        try {
            $db = new Database();
            $pdo = $db->getConnection();

            $usuarioId = $_SESSION['user']['id'] ?? null;
            $nombreUsuario = $_SESSION['user']['nombre'] ?? 'Desconocido';
            $apellidosUsuario = $_SESSION['user']['apellidos'] ?? '';
            $rolUsuario = $_SESSION['user']['rol'] ?? '';
            
            $responsable = " [Realizado por: {$nombreUsuario} {$apellidosUsuario} (Rol: {$rolUsuario})]";
            $detallesCompletos = $detalles . $responsable;

            $ip = $_SERVER['HTTP_CLIENT_IP'] 
                  ?? $_SERVER['HTTP_X_FORWARDED_FOR'] 
                  ?? $_SERVER['REMOTE_ADDR'] 
                  ?? 'Desconocida';

            $stmt = $pdo->prepare("INSERT INTO system_logs (usuario_id, accion, detalles, ip_address) VALUES (?, ?, ?, ?)");
            $stmt->execute([$usuarioId, $accion, $detallesCompletos, $ip]);
        } catch (\Exception $e) {}
    }

    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header("Location: /sistema/public/index.php?route=dashboard");
            exit();
        }

        $usuarios = SupportModel::getAllUsers();
        $logs = SupportModel::getSystemLogs();
        $serverInfo = SupportModel::getServerInfo();
        $stats = SupportModel::getStats();

        $mensaje = $_GET['mensaje'] ?? '';
        $error = $_GET['error'] ?? '';

        require_once __DIR__ . '/../views/support/index.php';
    }

    public function storeUser() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header("Location: /sistema/public/index.php?route=login"); exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'cedula' => trim($_POST['cedula'] ?? ''),
                'usuario' => trim($_POST['usuario'] ?? ''),
                'nombre' => trim($_POST['nombre'] ?? ''),
                'apellidos' => trim($_POST['apellidos'] ?? ''),
                'correo' => trim($_POST['correo'] ?? ''),
                'password' => trim($_POST['password'] ?? ''),
                'rol' => trim($_POST['rol'] ?? 'profesor')
            ];

            if (empty($data['cedula']) || empty($data['usuario']) || empty($data['nombre']) || empty($data['password'])) {
                header("Location: /sistema/public/index.php?route=soporte-panel&error=" . urlencode("Complete todos los campos obligatorios."));
                exit();
            }

            try {
                SupportModel::createUser($data);
                self::registrarLog('CREAR USUARIO', "Se creó el usuario institucional: {$data['usuario']} ({$data['nombre']} {$data['apellidos']})");
                header("Location: /sistema/public/index.php?route=soporte-panel&mensaje=" . urlencode("Usuario creado exitosamente."));
            } catch (\Exception $e) {
                header("Location: /sistema/public/index.php?route=soporte-panel&error=" . urlencode("Error al crear usuario: Cédula o usuario duplicados."));
            }
            exit();
        }
    }

    public function editUser() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header("Location: /sistema/public/index.php?route=login"); exit();
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: /sistema/public/index.php?route=soporte-panel");
            exit();
        }

        $usuario = SupportModel::getUserById($id);
        if (!$usuario) {
            header("Location: /sistema/public/index.php?route=soporte-panel&error=" . urlencode("Usuario no encontrado."));
            exit();
        }

        require_once __DIR__ . '/../views/support/edit.php';
    }

    public function updateUser() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header("Location: /sistema/public/index.php?route=login"); exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $data = [
                'cedula' => trim($_POST['cedula'] ?? ''),
                'usuario' => trim($_POST['usuario'] ?? ''),
                'nombre' => trim($_POST['nombre'] ?? ''),
                'apellidos' => trim($_POST['apellidos'] ?? ''),
                'correo' => trim($_POST['correo'] ?? ''),
                'rol' => trim($_POST['rol'] ?? 'profesor'),
                'password' => trim($_POST['password'] ?? '')
            ];

            if (!$id || empty($data['cedula']) || empty($data['usuario']) || empty($data['nombre'])) {
                header("Location: /sistema/public/index.php?route=soporte-editar&id={$id}&error=" . urlencode("Complete los campos obligatorios."));
                exit();
            }

            try {
                SupportModel::updateUser($id, $data);
                self::registrarLog('ACTUALIZAR USUARIO', "Se actualizaron los datos del usuario ID {$id} ({$data['usuario']})");
                header("Location: /sistema/public/index.php?route=soporte-panel&mensaje=" . urlencode("Usuario actualizado correctamente."));
            } catch (\Exception $e) {
                header("Location: /sistema/public/index.php?route=soporte-editar&id={$id}&error=" . urlencode("Error al actualizar: Cédula o usuario duplicados."));
            }
            exit();
        }
    }

    public function toggleUser() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header("Location: /sistema/public/index.php?route=login"); exit();
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                SupportModel::toggleUserStatus($id);
                self::registrarLog('CAMBIAR ESTADO', "Se cambió el estado del usuario ID {$id}");
                header("Location: /sistema/public/index.php?route=soporte-panel&mensaje=" . urlencode("Estado del usuario modificado correctamente."));
            } catch (\Exception $e) {
                header("Location: /sistema/public/index.php?route=soporte-panel&error=" . urlencode("Error al cambiar estado."));
            }
            exit();
        }
    }

    public function deleteUser() {}
    public function backupDatabase() {}
}
