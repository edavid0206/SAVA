<?php
// Vista del Dashboard con Intro y Burbujas de Niveles (SAVA)
$rolUsuario = $user['rol'] ?? 'profesor'; // 'admin', 'administrativo', 'profesor'
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SAVA &bull; Panel de Acceso Institucional</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #030712;
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            overflow-x: hidden;
            position: relative;
            padding: 20px;
        }

        /* Fondo dinámico de burbujas flotantes */
        .bubbles-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 1;
            background: linear-gradient(135deg, #0f172a, #1e1b4b, #020617);
            overflow: hidden;
            pointer-events: none;
        }

        .bubble-bg {
            position: absolute;
            bottom: -100px;
            background: radial-gradient(circle at 30% 30%, rgba(56, 189, 248, 0.35), rgba(14, 165, 233, 0.05));
            border: 1px solid rgba(56, 189, 248, 0.2);
            border-radius: 50%;
            box-shadow: 0 0 15px rgba(56, 189, 248, 0.1);
            animation: riseAndWobble linear infinite;
            z-index: 1;
        }

        @keyframes riseAndWobble {
            0% { transform: translateY(0) translateX(0) scale(1); opacity: 0; }
            15% { opacity: 0.7; }
            85% { opacity: 0.7; }
            100% { transform: translateY(-110vh) translateX(40px) scale(1.1); opacity: 0; }
        }

        /* Contenedor Principal */
        .main-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 1100px;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 90vh;
            justify-content: space-between;
            padding: 20px 0;
        }

        /* Barra superior con identidad y cierre de sesión */
        .top-bar {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 15px 25px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            animation: fadeInDown 0.8s ease forwards;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            color: #fff;
            box-shadow: 0 0 15px rgba(14, 165, 233, 0.4);
        }

        .user-details h4 {
            font-size: 0.95rem;
            color: #f8fafc;
            font-weight: 600;
        }

        .user-details span {
            font-size: 0.78rem;
            color: #38bdf8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-logout {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: rgba(239, 68, 68, 0.25);
            color: #fff;
            transform: translateY(-2px);
        }

        /* Sección de Intro / Bienvenida */
        .intro-section {
            text-align: center;
            margin: 30px 0;
            animation: fadeIn 1s ease forwards;
        }

        .intro-section h1 {
            font-size: clamp(2rem, 4vw, 2.8rem);
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #fff, #93c5fd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .intro-section p {
            font-size: 1rem;
            color: #94a3b8;
        }

        /* Contenedor de las 3 Burbujas de Niveles */
        .bubbles-nav-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            width: 100%;
            max-width: 960px;
            margin: 20px 0;
        }

        .level-bubble {
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 28px;
            padding: 35px 25px;
            text-align: center;
            position: relative;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: inherit;
        }

        /* Burbuja Activa */
        .level-bubble.active {
            border-color: rgba(56, 189, 248, 0.4);
            background: rgba(15, 23, 42, 0.75);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5), 0 0 30px rgba(56, 189, 248, 0.2);
        }

        .level-bubble.active:hover {
            transform: translateY(-8px);
            border-color: #38bdf8;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6), 0 0 40px rgba(56, 189, 248, 0.35);
        }

        /* Burbuja Deshabilitada */
        .level-bubble.disabled {
            opacity: 0.4;
            filter: grayscale(80%);
            cursor: not-allowed;
            pointer-events: none;
            border-style: dashed;
        }

        .bubble-icon {
            width: 76px;
            height: 76px;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            margin-bottom: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.2);
        }

        /* Colores por rol */
        .level-bubble.docente .bubble-icon {
            background: linear-gradient(135deg, #0284c7, #2563eb);
            color: #fff;
        }

        .level-bubble.administrativo .bubble-icon {
            background: linear-gradient(135deg, #7c3aed, #4f46e5);
            color: #fff;
        }

        .level-bubble.soporte .bubble-icon {
            background: linear-gradient(135deg, #059669, #0d9488);
            color: #fff;
        }

        .level-bubble h3 {
            font-size: 1.35rem;
            font-weight: 700;
            color: #f8fafc;
            margin-bottom: 10px;
        }

        .level-bubble p {
            font-size: 0.88rem;
            color: #94a3b8;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .badge-status {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .level-bubble.active .badge-status {
            background: rgba(14, 159, 110, 0.2);
            color: #34d399;
            border: 1px solid rgba(52, 211, 153, 0.4);
        }

        .level-bubble.disabled .badge-status {
            background: rgba(100, 116, 139, 0.2);
            color: #94a3b8;
            border: 1px solid rgba(100, 116, 139, 0.3);
        }

        footer {
            text-align: center;
            font-size: 0.8rem;
            color: #64748b;
            margin-top: 20px;
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.97); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>

    <!-- Fondo dinámico con burbujas -->
    <div class="bubbles-background" id="bubblesContainer"></div>

    <div class="main-wrapper">
        <!-- Barra Superior -->
        <div class="top-bar">
            <div class="user-info">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($user['nombre'], 0, 1)); ?>
                </div>
                <div class="user-details">
                    <h4>Bienvenid@, <?php echo htmlspecialchars($user['nombre']); ?></h4>
                    <span>Nivel: <?php echo ucfirst($rolUsuario); ?></span>
                </div>
            </div>
            <a href="/sistema/public/index.php?route=logout" class="btn-logout">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar Sesión
            </a>
        </div>

        <!-- Intro de Bienvenida -->
        <div class="intro-section">
            <h1>Sistema de Asistencia Valle Azul</h1>
            <p>Seleccione el nivel operativo habilitado para su cuenta institucional.</p>
        </div>

        <!-- Contenedor de las 3 Burbujas de Niveles -->
        <div class="bubbles-nav-container">

            <!-- 1. NIVEL DOCENTE -->
            <?php 
                $docenteActive = ($rolUsuario === 'profesor' || $rolUsuario === 'admin');
                $docenteClass = $docenteActive ? 'active docente' : 'disabled docente';
                $docenteLink = $docenteActive ? '/sistema/public/index.php?route=docente-panel' : '#';
            ?>
            <a href="<?php echo $docenteLink; ?>" class="level-bubble <?php echo $docenteClass; ?>">
                <div class="bubble-icon">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
                <h3>Nivel Docente</h3>
                <p>Gestión de asistencia diaria, registro de conducta de secciones y consulta de horarios.</p>
                <span class="badge-status">
                    <?php echo $docenteActive ? '<i class="fa-solid fa-circle-check"></i> Habilitado' : '<i class="fa-solid fa-lock"></i> Restringido'; ?>
                </span>
            </a>

            <!-- 2. NIVEL ADMINISTRATIVO -->
            <?php 
                $adminActive = ($rolUsuario === 'administrativo' || $rolUsuario === 'admin');
                $adminClass = $adminActive ? 'active administrativo' : 'disabled administrativo';
                $adminLink = $adminActive ? '/sistema/public/index.php?route=admin-panel' : '#';
            ?>
            <a href="<?php echo $adminLink; ?>" class="level-bubble <?php echo $adminClass; ?>">
                <div class="bubble-icon">
                    <i class="fa-solid fa-building-user"></i>
                </div>
                <h3>Nivel Administrativo</h3>
                <p>Supervisión de reportes institucionales, control de personal y administración de matrículas.</p>
                <span class="badge-status">
                    <?php echo $adminActive ? '<i class="fa-solid fa-circle-check"></i> Habilitado' : '<i class="fa-solid fa-lock"></i> Restringido'; ?>
                </span>
            </a>

            <!-- 3. NIVEL SOPORTE (SISTEMAS) -->
            <?php 
                $soporteActive = ($rolUsuario === 'admin');
                $soporteClass = $soporteActive ? 'active soporte' : 'disabled soporte';
                $soporteLink = $soporteActive ? '/sistema/public/index.php?route=soporte-panel' : '#';
            ?>
            <a href="<?php echo $soporteLink; ?>" class="level-bubble <?php echo $soporteClass; ?>">
                <div class="bubble-icon">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                </div>
                <h3>Nivel Soporte</h3>
                <p>Configuración general del sistema, gestión de base de datos, accesos y seguridad informática.</p>
                <span class="badge-status">
                    <?php echo $soporteActive ? '<i class="fa-solid fa-circle-check"></i> Habilitado' : '<i class="fa-solid fa-lock"></i> Restringido'; ?>
                </span>
            </a>

        </div>

        <footer>
            Colegio Valle Azul &bull; Departamento de Informática y Soporte Tecnológico &copy; 2026
        </footer>
    </div>

    <script>
        // Generador de burbujas de fondo
        document.addEventListener("DOMContentLoaded", function() {
            const container = document.getElementById('bubblesContainer');
            const count = 20;

            for (let i = 0; i < count; i++) {
                const bubble = document.createElement('div');
                bubble.classList.add('bubble-bg');

                const size = Math.floor(Math.random() * 50) + 15;
                const left = Math.random() * 100;
                const duration = Math.random() * 14 + 8;
                const delay = Math.random() * 10;

                bubble.style.width = `${size}px`;
                bubble.style.height = `${size}px`;
                bubble.style.left = `${left}%`;
                bubble.style.animationDuration = `${duration}s`;
                bubble.style.animationDelay = `${delay}s`;

                container.appendChild(bubble);
            }
        });
    </script>
</body>
</html>
