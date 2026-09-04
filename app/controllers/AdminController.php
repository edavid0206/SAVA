<?php
namespace App\Controllers;

use App\Config\Database;
use App\Models\StudentModel;
use App\Models\AcademicModel;

class AdminController {

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

            $ip = $_SERVER['REMOTE_ADDR'] ?? 'Desconocida';

            $stmt = $pdo->prepare("INSERT INTO system_logs (usuario_id, accion, detalles, ip_address) VALUES (?, ?, ?, ?)");
            $stmt->execute([$usuarioId, $accion, $detallesCompletos, $ip]);
        } catch (\Exception $e) {}
    }

    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['rol'], ['admin', 'administrativo'])) {
            header("Location: /sistema/public/index.php?route=dashboard");
            exit();
        }

        $db = new Database();
        $pdo = $db->getConnection();

        $secciones = [];
        $todosLosDocentes = [];
        $estudiantes = [];
        $materias = [];
        $niveles = [];
        $periodoActivo = AcademicModel::getPeriodoActivo();

        try {
            $stmtNiveles = $pdo->query("SELECT * FROM niveles ORDER BY id");
            $niveles = $stmtNiveles->fetchAll(\PDO::FETCH_ASSOC);

            $stmtSecciones = $pdo->query("SELECT s.id, n.nombre AS nivel_nombre, s.nombre AS seccion_nombre, s.guia_habilitado, 
                (SELECT CONCAT(u.nombre, ' ', u.apellidos) FROM usuarios u WHERE u.id = s.docente_guia_id) AS docente_guia,
                (SELECT COUNT(*) FROM estudiantes e WHERE e.seccion_id = s.id) AS total_estudiantes
                FROM secciones s 
                JOIN niveles n ON s.nivel_id = n.id 
                ORDER BY n.id, s.nombre");
            $secciones = $stmtSecciones->fetchAll(\PDO::FETCH_ASSOC);

            $stmtMaterias = $pdo->query("SELECT * FROM materias ORDER BY nombre");
            $materias = $stmtMaterias->fetchAll(\PDO::FETCH_ASSOC);

            $stmtDocentes = $pdo->query("SELECT u.* FROM usuarios u WHERE u.rol = 'profesor' AND u.estado = 1 ORDER BY u.apellidos, u.nombre");
            $todosLosDocentes = $stmtDocentes->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($todosLosDocentes as &$doc) {
                $stmtMatDoc = $pdo->prepare("SELECT m.id, m.nombre FROM materias m JOIN docente_materias dm ON m.id = dm.materia_id WHERE dm.docente_id = ?");
                $stmtMatDoc->execute([$doc['id']]);
                $doc['materias_asignadas'] = $stmtMatDoc->fetchAll(\PDO::FETCH_ASSOC);
                $doc['materias_nombres'] = implode(', ', array_column($doc['materias_asignadas'], 'nombre'));
            }
            unset($doc);

            $stmtEstudiantes = $pdo->query("SELECT e.*, s.nombre AS seccion_nombre, n.nombre AS nivel_nombre 
                FROM estudiantes e 
                JOIN secciones s ON e.seccion_id = s.id 
                JOIN niveles n ON s.nivel_id = n.id 
                ORDER BY n.id, s.nombre, e.apellidos");
            $estudiantes = $stmtEstudiantes->fetchAll(\PDO::FETCH_ASSOC);

        } catch (\Exception $e) {}

        $mensaje = $_GET['mensaje'] ?? '';
        $error = $_GET['error'] ?? '';

        require_once __DIR__ . '/../views/admin/index.php';
    }

    public function promoverEstudianteAdmin() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['rol'], ['admin', 'administrativo'])) {
            header("Location: /sistema/public/index.php?route=login"); exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $estudianteId = $_POST['estudiante_id'] ?? null;
            $nuevaSeccionId = $_POST['nueva_seccion_id'] ?? null;
            $estadoMatricula = trim($_POST['estado_matricula'] ?? 'promovido');
            $observacion = trim($_POST['observacion'] ?? 'Promoción de fin de año lectivo');
            $periodoActivo = AcademicModel::getPeriodoActivo();

            if (!$estudianteId || !$nuevaSeccionId || !$periodoActivo) {
                header("Location: /sistema/public/index.php?route=admin-panel&error=" . urlencode("Datos incompletos para procesar la promoción del estudiante."));
                exit();
            }

            try {
                $db = new Database();
                $pdo = $db->getConnection();
                
                // Obtener el nuevo nivel asociado a la sección destino
                $stmtSec = $pdo->prepare("SELECT nivel_id FROM secciones WHERE id = ? LIMIT 1");
                $stmtSec->execute([$nuevaSeccionId]);
                $secData = $stmtSec->fetch(\PDO::FETCH_ASSOC);

                if (!$secData) {
                    throw new \Exception("Sección destino no válida.");
                }

                $resultado = AcademicModel::promoverEstudiante(
                    $estudianteId, 
                    $secData['nivel_id'], 
                    $nuevaSeccionId, 
                    $periodoActivo['id'], 
                    $estadoMatricula, 
                    $observacion
                );

                if ($resultado) {
                    self::registrarLog('PROMOCIÓN ESTUDIANTE', "Se procesó la promoción/estado ({$estadoMatricula}) del estudiante ID {$estudianteId} a la nueva sección ID {$nuevaSeccionId}");
                    header("Location: /sistema/public/index.php?route=admin-panel&mensaje=" . urlencode("Estudiante promovido y registrado en historial correctamente."));
                } else {
                    header("Location: /sistema/public/index.php?route=admin-panel&error=" . urlencode("No se pudo completar la transacción de promoción."));
                }
            } catch (\Exception $e) {
                header("Location: /sistema/public/index.php?route=admin-panel&error=" . urlencode("Error en promoción: " . $e->getMessage()));
            }
            exit();
        }
    }

    public function storeTeacher() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['rol'], ['admin', 'administrativo'])) {
            header("Location: /sistema/public/index.php?route=login"); exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cedula = trim($_POST['cedula'] ?? '');
            $nombre = trim($_POST['nombre'] ?? '');
            $apellidos = trim($_POST['apellidos'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $password = password_hash($cedula, PASSWORD_DEFAULT);
            $materiasSeleccionadas = $_POST['materias'] ?? [];

            if (empty($cedula) || empty($nombre) || empty($apellidos) || empty($correo)) {
                header("Location: /sistema/public/index.php?route=admin-panel&error=" . urlencode("Todos los campos obligatorios del docente son requeridos."));
                exit();
            }

            try {
                $db = new Database();
                $pdo = $db->getConnection();

                $stmt = $pdo->prepare("INSERT INTO usuarios (cedula, nombre, apellidos, correo, password, rol, estado) VALUES (?, ?, ?, ?, ?, 'profesor', 1)");
                $stmt->execute([$cedula, $nombre, $apellidos, $correo, $password]);
                $docenteId = $pdo->lastInsertId();

                if (!empty($materiasSeleccionadas)) {
                    $stmtIns = $pdo->prepare("INSERT INTO docente_materias (docente_id, materia_id) VALUES (?, ?)");
                    foreach ($materiasSeleccionadas as $matId) {
                        $stmtIns->execute([$docenteId, $matId]);
                    }
                }

                self::registrarLog('GESTIÓN DOCENTE', "Se registró al nuevo docente {$nombre} {$apellidos} (Cédula: {$cedula})");
                header("Location: /sistema/public/index.php?route=admin-panel&mensaje=" . urlencode("Docente registrado y asignado correctamente."));
            } catch (\Exception $e) {
                header("Location: /sistema/public/index.php?route=admin-panel&error=" . urlencode("Error al registrar docente: Cédula o correo duplicado."));
            }
            exit();
        }
    }

    public function updateTeacher() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['rol'], ['admin', 'administrativo'])) {
            header("Location: /sistema/public/index.php?route=login"); exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $docenteId = $_POST['docente_id'] ?? null;
            $nombre = trim($_POST['nombre'] ?? '');
            $apellidos = trim($_POST['apellidos'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $materiasSeleccionadas = $_POST['materias'] ?? [];

            if (!$docenteId || empty($nombre) || empty($apellidos) || empty($correo)) {
                header("Location: /sistema/public/index.php?route=admin-panel&error=" . urlencode("Complete los campos requeridos del docente."));
                exit();
            }

            try {
                $db = new Database();
                $pdo = $db->getConnection();

                $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, apellidos = ?, correo = ? WHERE id = ? AND rol = 'profesor'");
                $stmt->execute([$nombre, $apellidos, $correo, $docenteId]);

                $stmtDel = $pdo->prepare("DELETE FROM docente_materias WHERE docente_id = ?");
                $stmtDel->execute([$docenteId]);

                if (!empty($materiasSeleccionadas)) {
                    $stmtIns = $pdo->prepare("INSERT INTO docente_materias (docente_id, materia_id) VALUES (?, ?)");
                    foreach ($materiasSeleccionadas as $matId) {
                        $stmtIns->execute([$docenteId, $matId]);
                    }
                }

                self::registrarLog('GESTIÓN DOCENTE', "Se actualizaron los datos y cargas del docente ID {$docenteId}");
                header("Location: /sistema/public/index.php?route=admin-panel&mensaje=" . urlencode("Docente actualizado exitosamente."));
            } catch (\Exception $e) {
                header("Location: /sistema/public/index.php?route=admin-panel&error=" . urlencode("Error al actualizar el docente."));
            }
            exit();
        }
    }

    public function deleteTeacher() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['rol'], ['admin', 'administrativo'])) {
            header("Location: /sistema/public/index.php?route=login"); exit();
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                $db = new Database();
                $pdo = $db->getConnection();
                $stmt = $pdo->prepare("UPDATE usuarios SET estado = 0 WHERE id = ? AND rol = 'profesor'");
                $stmt->execute([$id]);
                self::registrarLog('GESTIÓN DOCENTE', "Se desactivó/eliminó el docente ID {$id}");
                header("Location: /sistema/public/index.php?route=admin-panel&mensaje=" . urlencode("Docente eliminado/desactivado correctamente."));
            } catch (\Exception $e) {
                header("Location: /sistema/public/index.php?route=admin-panel&error=" . urlencode("Error al eliminar el docente."));
            }
            exit();
        }
    }

    public function storeMateria() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['rol'], ['admin', 'administrativo'])) {
            header("Location: /sistema/public/index.php?route=login"); exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $tipo = trim($_POST['tipo'] ?? 'General');

            if (empty($nombre)) {
                header("Location: /sistema/public/index.php?route=admin-panel&error=" . urlencode("El nombre de la materia es obligatorio."));
                exit();
            }

            try {
                $db = new Database();
                $pdo = $db->getConnection();
                $stmt = $pdo->prepare("INSERT INTO materias (nombre, tipo) VALUES (?, ?)");
                $stmt->execute([$nombre, $tipo]);
                self::registrarLog('GESTIÓN MATERIA', "Se creó la materia '{$nombre}'");
                header("Location: /sistema/public/index.php?route=admin-panel&mensaje=" . urlencode("Materia creada correctamente."));
            } catch (\Exception $e) {
                header("Location: /sistema/public/index.php?route=admin-panel&error=" . urlencode("Error al crear materia."));
            }
            exit();
        }
    }

    public function updateMateria() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['rol'], ['admin', 'administrativo'])) {
            header("Location: /sistema/public/index.php?route=login"); exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['materia_id'] ?? null;
            $nombre = trim($_POST['nombre'] ?? '');
            $tipo = trim($_POST['tipo'] ?? 'General');

            if (!$id || empty($nombre)) {
                header("Location: /sistema/public/index.php?route=admin-panel&error=" . urlencode("Datos incompletos para actualizar la materia."));
                exit();
            }

            try {
                $db = new Database();
                $pdo = $db->getConnection();
                $stmt = $pdo->prepare("UPDATE materias SET nombre = ?, tipo = ? WHERE id = ?");
                $stmt->execute([$nombre, $tipo, $id]);
                self::registrarLog('GESTIÓN MATERIA', "Se actualizó la materia ID {$id} a '{$nombre}'");
                header("Location: /sistema/public/index.php?route=admin-panel&mensaje=" . urlencode("Materia actualizada correctamente."));
            } catch (\Exception $e) {
                header("Location: /sistema/public/index.php?route=admin-panel&error=" . urlencode("Error al actualizar la materia."));
            }
            exit();
        }
    }

    public function deleteMateria() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['rol'], ['admin', 'administrativo'])) {
            header("Location: /sistema/public/index.php?route=login"); exit();
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                $db = new Database();
                $pdo = $db->getConnection();
                $stmt = $pdo->prepare("DELETE FROM materias WHERE id = ?");
                $stmt->execute([$id]);
                self::registrarLog('GESTIÓN MATERIA', "Se eliminó la materia ID {$id}");
                header("Location: /sistema/public/index.php?route=admin-panel&mensaje=" . urlencode("Materia eliminada correctamente."));
            } catch (\Exception $e) {
                header("Location: /sistema/public/index.php?route=admin-panel&error=" . urlencode("No se puede eliminar la materia porque está asociada a docentes."));
            }
            exit();
        }
    }

    public function updateStudentAdmin() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['rol'], ['admin', 'administrativo'])) {
            header("Location: /sistema/public/index.php?route=login"); exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['estudiante_id'] ?? null;
            $data = [
                'cedula' => trim($_POST['cedula'] ?? ''),
                'nombre' => trim($_POST['nombre'] ?? ''),
                'apellidos' => trim($_POST['apellidos'] ?? ''),
                'seccion_id' => $_POST['seccion_id'] ?? null,
                'subgrupo' => $_POST['subgrupo'] ?? 'A',
                'especialidad_tecnica' => trim($_POST['especialidad_tecnica'] ?? ''),
                'idioma' => trim($_POST['idioma'] ?? '')
            ];

            if (!$id || empty($data['cedula']) || empty($data['nombre']) || empty($data['seccion_id'])) {
                header("Location: /sistema/public/index.php?route=admin-panel&error=" . urlencode("Complete los campos obligatorios del estudiante."));
                exit();
            }

            try {
                StudentModel::updateStudent($id, $data);
                self::registrarLog('GESTIÓN ESTUDIANTE', "Se actualizaron los datos del estudiante ID {$id} ({$data['nombre']} {$data['apellidos']})");
                header("Location: /sistema/public/index.php?route=admin-panel&mensaje=" . urlencode("Estudiante actualizado correctamente."));
            } catch (\Exception $e) {
                header("Location: /sistema/public/index.php?route=admin-panel&error=" . urlencode("Error al actualizar estudiante: Cédula duplicada."));
            }
            exit();
        }
    }

    public function actualizarGuiaAdmin() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION["user"]) || !in_array($_SESSION["user"]["rol"], ["admin", "administrativo"])) {
            header("Location: /sistema/public/index.php?route=login"); exit();
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $seccionId = $_POST["seccion_id"] ?? null;
            $docenteId = $_POST["docente_guia_id"] ?? null;

            if (!$seccionId || !$docenteId) {
                header("Location: /sistema/public/index.php?route=admin-panel&error=" . urlencode("Datos incompletos para actualizar el docente guía."));
                exit();
            }

            try {
                $db = new Database();
                $pdo = $db->getConnection();
                $stmt = $pdo->prepare("UPDATE secciones SET docente_guia_id = ?, guia_habilitado = 1 WHERE id = ?");
                $stmt->execute([$docenteId, $seccionId]);
                self::registrarLog("GESTIÓN SECCIÓN", "Se actualizó el docente guía de la sección ID {$seccionId} al docente ID {$docenteId}");
                header("Location: /sistema/public/index.php?route=admin-panel&mensaje=" . urlencode("Docente guía actualizado exitosamente."));
            } catch (\Exception $e) {
                header("Location: /sistema/public/index.php?route=admin-panel&error=" . urlencode("Error al actualizar el docente guía."));
            }
            exit();
        }
    }

    public function toggleGuiaAdmin() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['rol'], ['admin', 'administrativo'])) {
            header("Location: /sistema/public/index.php?route=login"); exit();
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                $db = new Database();
                $pdo = $db->getConnection();
                $stmt = $pdo->prepare("UPDATE secciones SET guia_habilitado = IF(guia_habilitado = 1, 0, 1) WHERE id = ?");
                $stmt->execute([$id]);
                self::registrarLog('GESTIÓN SECCIÓN', "Se habilitó/deshabilitó el permiso de Profesor Guía para la sección ID {$id}");
                header("Location: /sistema/public/index.php?route=admin-panel&mensaje=" . urlencode("Permiso de profesor guía actualizado exitosamente."));
            } catch (\Exception $e) {
                header("Location: /sistema/public/index.php?route=admin-panel&error=" . urlencode("Error al modificar la sección."));
            }
            exit();
        }
    }
}
