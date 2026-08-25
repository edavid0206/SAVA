<?php
/**
 * Panel de Nivel Administrativo - SAVA Valle Azul
 */
require_once __DIR__ . '/app/config/Database.php';
use App\Config\Database;

$db = Database::getConnection();
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    if ($_POST['accion'] === 'registrar_docente') {
        $nombre = trim($_POST['nombre']);
        $email = trim($_POST['email']);
        $cedula = trim($_POST['cedula']);
        if (!empty($nombre) && !empty($email)) {
            $stmt = $db->prepare("INSERT INTO docentes (nombre, email, cedula) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE nombre=VALUES(nombre), email=VALUES(email)");
            $stmt->execute([$nombre, $email, $cedula]);
            $mensaje = "¡Docente registrado con éxito!";
        }
    }
}

$docentes = [];
try {
    $stmtDoc = $db->query("SELECT * FROM docentes ORDER BY nombre ASC");
    $docentes = $stmtDoc->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}

$secciones = [];
try {
    $stmtSec = $db->query("SELECT s.*, n.nombre as nivel_nombre FROM secciones s JOIN niveles n ON s.nivel_id = n.id ORDER BY n.id, s.nombre ASC");
    $secciones = $stmtSec->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - SAVA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans">
    <div class="min-h-screen flex flex-col">
        <header class="bg-indigo-900 text-white shadow-md">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-school text-2xl text-indigo-300"></i>
                    <div>
                        <h1 class="text-xl font-bold">Colegio Valle Azul</h1>
                        <p class="text-xs text-indigo-200">Panel Nivel Administrativo - SAVA</p>
                    </div>
                </div>
                <a href="index.php" class="bg-indigo-700 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm transition">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Volver al selector de niveles
                </a>
            </div>
        </header>

        <main class="flex-grow max-w-7xl w-full mx-auto px-4 py-8">
            <?php if (!empty($mensaje)): ?>
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg text-emerald-700 font-medium">
                    <i class="fa-solid fa-circle-check mr-2"></i><?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Secciones Activas</p>
                        <h3 class="text-2xl font-bold text-gray-800"><?php echo count($secciones); ?></h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center text-xl">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Docentes Registrados</p>
                        <h3 class="text-2xl font-bold text-gray-800"><?php echo count($docentes); ?></h3>
                    </div>
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center text-xl">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Estado del Sistema</p>
                        <h3 class="text-lg font-bold text-emerald-600 mt-1"><i class="fa-solid fa-circle text-xs mr-1 animate-pulse"></i> Operativo</h3>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center text-xl">
                        <i class="fa-solid fa-server"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-800"><i class="fa-solid fa-users-gear mr-2 text-indigo-600"></i> Gestión de Docentes y Accesos</h3>
                </div>
                <div class="p-6">
                    <form method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <input type="hidden" name="accion" value="registrar_docente">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Cédula</label>
                            <input type="text" name="cedula" required placeholder="Ej. 102340567" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Nombre Completo</label>
                            <input type="text" name="nombre" required placeholder="Ej. Juan Pérez" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Correo Institucional</label>
                            <input type="email" name="email" required placeholder="jperez@valleazul.cr" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow">
                                <i class="fa-solid fa-user-plus mr-1"></i> Guardar Docente
                            </button>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-gray-600 font-semibold">
                                <tr>
                                    <th class="px-4 py-3 text-left">Cédula</th>
                                    <th class="px-4 py-3 text-left">Nombre</th>
                                    <th class="px-4 py-3 text-left">Correo</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <?php if (empty($docentes)): ?>
                                    <tr><td colspan="3" class="px-4 py-4 text-center text-gray-400">No hay docentes registrados todavía.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($docentes as $doc): ?>
                                        <tr>
                                            <td class="px-4 py-3 text-gray-700"><?php echo htmlspecialchars($doc['cedula'] ?? ''); ?></td>
                                            <td class="px-4 py-3 font-medium text-gray-900"><?php echo htmlspecialchars($doc['nombre']); ?></td>
                                            <td class="px-4 py-3 text-gray-600"><?php echo htmlspecialchars($doc['email']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
        <footer class="bg-white border-t border-gray-100 py-4 text-center text-xs text-gray-500">
            Sistema Académico Valle Azul (SAVA) &copy; 2026
        </footer>
    </div>
</body>
</html>
