<?php
namespace App\Controllers;

use App\Models\SupportModel;

class SupportController {
    
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header("Location: /sistema/public/index.php?route=dashboard");
            exit();
        }

        $user = $_SESSION['user'];
        $stats = SupportModel::getStats();
        $usuarios = SupportModel::getAllUsers();
        $mensaje = $_GET['mensaje'] ?? '';
        $error = $_GET['error'] ?? '';

        require_once __DIR__ . '/../views/support/index.php';
    }

    public function storeUser() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header("Location: /sistema/public/index.php?route=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'cedula' => trim($_POST['cedula'] ?? ''),
                'nombre' => trim($_POST['nombre'] ?? ''),
                'apellidos' => trim($_POST['apellidos'] ?? ''),
                'usuario' => trim($_POST['usuario'] ?? ''),
                'correo' => trim($_POST['correo'] ?? ''),
                'password' => trim($_POST['password'] ?? ''),
                'rol' => $_POST['rol'] ?? 'profesor'
            ];

            if (empty($data['cedula']) || empty($data['nombre']) || empty($data['usuario']) || empty($data['password'])) {
                header("Location: /sistema/public/index.php?route=soporte-panel&error=" . urlencode("Complete los campos obligatorios."));
                exit();
            }

            try {
                SupportModel::createUser($data);
                header("Location: /sistema/public/index.php?route=soporte-panel&mensaje=" . urlencode("Usuario registrado correctamente en la base de datos."));
            } catch (\Exception $e) {
                header("Location: /sistema/public/index.php?route=soporte-panel&error=" . urlencode("Error: La cédula, usuario o correo ya existen."));
            }
            exit();
        }
    }

    public function editUser() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header("Location: /sistema/public/index.php?route=login");
            exit();
        }

        $id = $_GET['id'] ?? null;
        $usuarioEditar = SupportModel::getUserById($id);

        if (!$usuarioEditar) {
            header("Location: /sistema/public/index.php?route=soporte-panel&error=" . urlencode("Usuario no encontrado."));
            exit();
        }

        $mensaje = $_GET['mensaje'] ?? '';
        $error = $_GET['error'] ?? '';

        require_once __DIR__ . '/../views/support/edit.php';
    }

    public function updateUser() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header("Location: /sistema/public/index.php?route=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $data = [
                'cedula' => trim($_POST['cedula'] ?? ''),
                'nombre' => trim($_POST['nombre'] ?? ''),
                'apellidos' => trim($_POST['apellidos'] ?? ''),
                'usuario' => trim($_POST['usuario'] ?? ''),
                'correo' => trim($_POST['correo'] ?? ''),
                'password' => trim($_POST['password'] ?? ''),
                'rol' => $_POST['rol'] ?? 'profesor'
            ];

            if (!$id || empty($data['cedula']) || empty($data['nombre']) || empty($data['usuario'])) {
                header("Location: /sistema/public/index.php?route=soporte-editar&id=$id&error=" . urlencode("Complete los campos obligatorios."));
                exit();
            }

            try {
                SupportModel::updateUser($id, $data);
                header("Location: /sistema/public/index.php?route=soporte-panel&mensaje=" . urlencode("Usuario actualizado correctamente en la base de datos."));
            } catch (\Exception $e) {
                header("Location: /sistema/public/index.php?route=soporte-editar&id=$id&error=" . urlencode("Error al actualizar: Cédula o usuario duplicados."));
            }
            exit();
        }
    }

    public function toggleUser() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header("Location: /sistema/public/index.php?route=login");
            exit();
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            SupportModel::toggleStatus($id);
            header("Location: /sistema/public/index.php?route=soporte-panel&mensaje=" . urlencode("Estado actualizado en la base de datos."));
            exit();
        }
    }

    public function deleteUser() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header("Location: /sistema/public/index.php?route=login");
            exit();
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            if ($id == $_SESSION['user']['id']) {
                header("Location: /sistema/public/index.php?route=soporte-panel&error=" . urlencode("No puedes eliminar tu propio usuario activo."));
                exit();
            }

            SupportModel::deleteUser($id);
            header("Location: /sistema/public/index.php?route=soporte-panel&mensaje=" . urlencode("Usuario eliminado de la base de datos."));
            exit();
        }
    }
}
