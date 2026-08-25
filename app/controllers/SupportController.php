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

        public function deleteUser() {
        if (session_status() === PHP_SESSION_NONE) session_start();
            header("Location: /sistema/public/index.php?route=login"); exit();
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                $db = new \App\Config\Database();
                $pdo = $db->getConnection();
                
                // Evitar que el administrador se elimine a sí mismo
                if ((int)$id === (int)$_SESSION['user']['id']) {
                    header("Location: /sistema/public/index.php?route=soporte-panel&error=" . urlencode("No puede eliminar su propia cuenta activa."));
                    exit();
                }

                $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
                $stmt->execute([$id]);
                self::registrarLog('ELIMINAR USUARIO', "Se eliminó el usuario ID {$id}");
                header("Location: /sistema/public/index.php?route=soporte-panel&mensaje=" . urlencode("Usuario eliminado correctamente."));
            } catch (\Exception $e) {
                header("Location: /sistema/public/index.php?route=soporte-panel&error=" . urlencode("No se puede eliminar el usuario porque tiene registros asociados."));
            }
            exit();
        }
    }
        public function backupDatabase() {
        if (session_status() === PHP_SESSION_NONE) session_start();
            header("Location: /sistema/public/index.php?route=login"); exit();
        }

        try {
            $db = new \App\Config\Database();
            $pdo = $db->getConnection();
            
            $tables = [];
            $result = $pdo->query("SHOW TABLES");
            while ($row = $result->fetch(\PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }

            $sqlScript = "-- Backup Base de Datos SAVA
-- Fecha: " . date('Y-m-d H:i:s') . "

";
            $sqlScript .= "SET FOREIGN_KEY_CHECKS=0;

";

            foreach ($tables as $table) {
                $row2 = $pdo->query("SHOW CREATE TABLE {$table}")->fetch(\PDO::FETCH_NUM);
                $sqlScript .= "

" . $row2[1] . ";

";
                
                $result = $pdo->query("SELECT * FROM {$table}");
                while ($row = $result->fetch(\PDO::FETCH_NUM)) {
                    $sqlScript .= "INSERT INTO {$table} VALUES(";
                    for ($j = 0; $j < count($row); $j++) {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = str_replace("
", "\n", $row[$j]);
                        if (isset($row[$j])) {
                            $sqlScript .= '"' . $row[$j] . '"';
                        } else {
                            $sqlScript .= '""';
                        }
                        if ($j < (count($row) - 1)) { $sqlScript .= ','; }
                    }
                    $sqlScript .= ");
";
                }
            }
            $sqlScript .= "
SET FOREIGN_KEY_CHECKS=1;";

            self::registrarLog('RESPALDO BD', "Generación y descarga de copia de seguridad .sql");

            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="backup_sava_' . date('Y-m-d_H-i-s') . '.sql"');
            echo $sqlScript;
            exit();
        } catch (\Exception $e) {
            header("Location: /sistema/public/index.php?route=soporte-panel&error=" . urlencode("Error al generar el respaldo de la base de datos."));
            exit();
        }
    }
}
