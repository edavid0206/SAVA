<?php
// Front Controller SAVA - Patrón MVC
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$baseDir = dirname(__DIR__);

require_once $baseDir . '/app/config/Database.php';
require_once $baseDir . '/app/models/User.php';
require_once $baseDir . '/app/models/SupportModel.php';
require_once $baseDir . '/app/models/TeacherModel.php';
require_once $baseDir . '/app/controllers/AuthController.php';
require_once $baseDir . '/app/controllers/SupportController.php';
require_once $baseDir . '/app/controllers/TeacherController.php';

use App\Controllers\AuthController;
use App\Controllers\SupportController;
use App\Controllers\TeacherController;

$route = $_GET['route'] ?? 'login';

$authController = new AuthController();
$supportController = new SupportController();
$teacherController = new TeacherController();

switch ($route) {
    case 'login':
        $authController->showLogin();
        break;
    case 'login-process':
        $authController->processLogin();
        break;
    case 'dashboard':
        $authController->dashboard();
        break;
    case 'soporte-panel':
        $supportController->index();
        break;
    case 'soporte-crear-usuario':
        $supportController->storeUser();
        break;
    case 'soporte-editar':
        $supportController->editUser();
        break;
    case 'soporte-actualizar-usuario':
        $supportController->updateUser();
        break;
    case 'soporte-cambiar-estado':
        $supportController->toggleUser();
        break;
    case 'soporte-eliminar':
        $supportController->deleteUser();
        break;
    case 'docente-panel':
        $teacherController->index();
        break;
    case 'docente-seleccionar-seccion':
        $teacherController->index();
        break;
    case 'docente-reporte-historial':
        $teacherController->verHistorial();
        break;
    case 'docente-asistencia':
        $teacherController->tomarAsistencia();
        break;
    case 'docente-guardar-asistencia':
        $teacherController->guardarAsistencia();
        break;
    case 'admin-panel':
        if (!isset($_SESSION['user'])) { header("Location: /sistema/public/index.php?route=login"); exit(); }
        echo "<div style='background:#030712; color:#fff; height:100vh; display:flex; flex-direction:column; justify-content:center; align-items:center; font-family:sans-serif;'>";
        echo "<h1>Panel Nivel Administrativo</h1><p>Módulo de reportes y gestión en desarrollo.</p><br>";
        echo "<a href='/sistema/public/index.php?route=dashboard' style='color:#38bdf8; text-decoration:none;'>&larr; Volver al selector de niveles</a></div>";
        break;
    case 'logout':
        $authController->logout();
        break;
    default:
        http_response_code(404);
        echo "<div style='background:#030712; color:#fff; height:100vh; display:flex; justify-content:center; align-items:center; font-family:sans-serif;'>";
        echo "<h1>404 - Página no encontrada</h1>";
        echo "</div>";
        break;
}
