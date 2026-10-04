<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SAVA &bull; Sistema de Asistencia Valle Azul</title>
    <!-- Google Fonts & FontAwesome -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-color: #030712;
            --gradient-bg: linear-gradient(135deg, #0f172a, #1e1b4b, #020617);
            --card-bg: rgba(15, 23, 42, 0.65);
            --card-border: rgba(255, 255, 255, 0.12);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --input-bg: rgba(30, 41, 59, 0.5);
            --input-border: rgba(255, 255, 255, 0.08);
            --input-text: #fff;
            --bubble-grad: radial-gradient(circle at 30% 30%, rgba(56, 189, 248, 0.45), rgba(14, 165, 233, 0.05));
            --bubble-border: rgba(56, 189, 248, 0.25);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            position: relative;
            padding: 20px;
        }

        /* Fondo dinámico de burbujas interactivas */
        .bubbles-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 1;
            background: var(--gradient-bg);
            overflow: hidden;
            pointer-events: none;
        }

        .bubble {
            position: absolute;
            bottom: -100px;
            background: var(--bubble-grad);
            border: 1px solid var(--bubble-border);
            border-radius: 50%;
            box-shadow: 0 0 20px rgba(56, 189, 248, 0.15), inset 0 0 15px rgba(255, 255, 255, 0.25);
            animation: riseAndWobble linear infinite;
            backdrop-filter: blur(2px);
            z-index: 1;
            pointer-events: auto;
            cursor: pointer;
            transition: transform 0.1s ease, opacity 0.3s ease;
        }

        .bubble.pop {
            transform: scale(1.6) !important;
            opacity: 0 !important;
            filter: brightness(2);
            transition: transform 0.2s ease-out, opacity 0.2s ease-out;
        }

        @keyframes riseAndWobble {
            0% { transform: translateY(0) translateX(0) scale(1); opacity: 0; }
            15% { opacity: 0.85; }
            85% { opacity: 0.85; }
            100% { transform: translateY(-110vh) translateX(50px) scale(1.1); opacity: 0; }
        }

        /* Tarjeta de Inicio de Sesión */
        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            background: var(--card-bg);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid var(--card-border);
            border-radius: 28px;
            padding: 40px 32px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            animation: fadeInScale 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes fadeInScale {
            0% { opacity: 0; transform: translateY(20px) scale(0.95); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        .login-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .login-header .logo-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            border-radius: 20px;
            font-size: 1.8rem;
            color: #fff;
            margin-bottom: 18px;
            box-shadow: 0 10px 25px rgba(14, 165, 233, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .login-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }

        .login-header p {
            font-size: 0.88rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 12px;
            border-radius: 12px;
            font-size: 0.85rem;
            margin-bottom: 20px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        .form-control {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 14px;
            padding: 14px 16px 14px 48px;
            color: var(--input-text);
            font-size: 0.95rem;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #38bdf8;
            box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.15);
        }

        .form-control:focus + i {
            color: #38bdf8;
        }

        .forgot-password-container {
            text-align: right;
            margin-bottom: 15px;
            margin-top: -5px;
        }

        .forgot-password-link {
            font-size: 0.8rem;
            color: #38bdf8;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .forgot-password-link:hover {
            color: #7dd3fc;
            text-decoration: underline;
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #0284c7, #2563eb);
            color: #fff;
            border: none;
            border-radius: 14px;
            padding: 15px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
            margin-top: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #0284c7, #1d4ed8);
            box-shadow: 0 15px 25px rgba(37, 99, 235, 0.4);
        }

        .login-footer {
            text-align: center;
            margin-top: 25px;
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        /* Estilos para el Modal de Recuperación */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(3, 7, 18, 0.85);
            backdrop-filter: blur(8px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
            animation: fadeIn 0.3s ease;
        }
        .modal-overlay.active {
            display: flex;
        }
        .modal-card {
            background: rgba(15, 23, 42, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            width: 100%;
            max-width: 400px;
            padding: 30px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.7);
        }
    </style>
</head>
<body>

    <!-- Fondo dinámico con burbujas interactivas -->
    <div class="bubbles-background" id="bubblesContainer"></div>

    <!-- Contenedor del Login -->
    <div class="login-container">
        <div class="login-header">
            <div class="logo-badge">
                <i class="fa-solid fa-fingerprint"></i>
            </div>
            <h2>Sistema de Asistencia Valle Azul</h2>
            <p>Plataforma institucional de control y gestión académica.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert-error">
                <i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="/sistema/public/index.php?route=login-process" method="POST">
            <div class="form-group">
                <label for="usuario">Usuario o Correo Institucional</label>
                <div class="input-wrapper">
                    <input type="text" id="usuario" name="usuario" class="form-control" placeholder="Ej. nombre.apellido" required autocomplete="username">
                    <i class="fa-solid fa-user"></i>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 10px;">
                <label for="password">Contraseña de Acceso</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••••••" required autocomplete="current-password">
                    <i class="fa-solid fa-lock"></i>
                </div>
            </div>

            <div class="forgot-password-container">
                <a href="#" onclick="abrirModalRecuperar(event)" class="forgot-password-link">¿Olvidaste tu contraseña?</a>
            </div>

            <button type="submit" class="btn-submit">
                <span>Iniciar Sesión</span>
                <i class="fa-solid fa-arrow-right-to-bracket"></i>
            </button>
        </form>

        <div class="login-footer">
            Colegio Valle Azul &bull; Departamento de Informática &copy; 2026
        </div>
    </div>

    <!-- MODAL DE RECUPERACIÓN DE CONTRASEÑA -->
    <div id="modalRecuperar" class="modal-overlay">
        <div class="modal-card">
            <h3 style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; color: #38bdf8; font-size: 1.15rem;">
                <span><i class="fa-solid fa-key"></i> Recuperar Contraseña</span>
                <button type="button" onclick="cerrarModalRecuperar()" style="background: none; border: none; color: #94a3b8; font-size: 1.25rem; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
            </h3>
            <p style="color: #94a3b8; font-size: 0.85rem; margin-bottom: 20px; line-height: 1.4;">
                Por motivos de seguridad institucional, comuníquese con la dirección o con el Departamento de Informática para restablecer sus credenciales de acceso.
            </p>
            <button type="button" onclick="cerrarModalRecuperar()" class="btn-submit" style="margin-top: 0;">
                <i class="fa-solid fa-check">></i> Entendido
            </button>
        </div>
    </div>

    <script>
        function abrirModalRecuperar(e) {
            e.preventDefault();
            document.getElementById('modalRecuperar').classList.add('active');
        }

        function cerrarModalRecuperar() {
            document.getElementById('modalRecuperar').classList.remove('active');
        }

        // Lógica de burbujas interactivas
        document.addEventListener("DOMContentLoaded", function() {
            const container = document.getElementById('bubblesContainer');
            const bubbleCount = 26;

            for (let i = 0; i < bubbleCount; i++) {
                const bubble = document.createElement('div');
                bubble.classList.add('bubble');

                const size = Math.floor(Math.random() * 65) + 20;
                const leftPos = Math.random() * 100;
                const duration = Math.random() * 12 + 8;
                const delay = Math.random() * 10;

                bubble.style.width = `${size}px`;
                bubble.style.height = `${size}px`;
                bubble.style.left = `${leftPos}%`;
                bubble.style.animationDuration = `${duration}s`;
                bubble.style.animationDelay = `${delay}s`;

                bubble.addEventListener('mouseenter', function() { popBubble(bubble); });
                bubble.addEventListener('touchstart', function(e) { e.preventDefault(); popBubble(bubble); });

                container.appendChild(bubble);
            }

            function popBubble(bubble) {
                if (bubble.classList.contains('pop')) return;
                bubble.classList.add('pop');
                setTimeout(() => {
                    bubble.remove();
                    createNewBubble(container);
                }, 300);
            }

            function createNewBubble(parent) {
                const bubble = document.createElement('div');
                bubble.classList.add('bubble');
                const size = Math.floor(Math.random() * 65) + 20;
                const leftPos = Math.random() * 100;
                const duration = Math.random() * 12 + 8;

                bubble.style.width = `${size}px`;
                bubble.style.height = `${size}px`;
                bubble.style.left = `${leftPos}%`;
                bubble.style.animationDuration = `${duration}s`;
                bubble.style.animationDelay = `0s`;

                bubble.addEventListener('mouseenter', function() { popBubble(bubble); });
                bubble.addEventListener('touchstart', function(e) { e.preventDefault(); popBubble(bubble); });

                parent.appendChild(bubble);
            }
        });
    </script>
</body>
</html>
