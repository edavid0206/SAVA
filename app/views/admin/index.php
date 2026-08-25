<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAVA &bull; Nivel Administrativo</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: #030712; color: #f8fafc; min-height: 100vh; display: flex; flex-direction: column; padding: 20px; }
        
        .header-bar { display: flex; justify-content: space-between; align-items: center; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); padding: 20px 25px; border-radius: 20px; margin-bottom: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.4); flex-wrap: wrap; gap: 15px; }
        .header-title h2 { font-size: 1.4rem; color: #38bdf8; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .header-title p { font-size: 0.85rem; color: #94a3b8; }
        
        .nav-buttons { display: flex; gap: 10px; flex-wrap: wrap; }
        .btn-action { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #fff; padding: 9px 16px; border-radius: 12px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s; cursor: pointer; white-space: nowrap; }
        .btn-action:hover { background: rgba(56, 189, 248, 0.2); border-color: #38bdf8; transform: translateY(-2px); }
        .btn-danger { background: rgba(239, 68, 68, 0.15); border-color: rgba(239, 68, 68, 0.3); color: #fca5a5; }
        .btn-danger:hover { background: rgba(239, 68, 68, 0.3); color: #fff; }

        .admin-tabs { display: flex; gap: 10px; width: 100%; margin-bottom: 25px; overflow-x: auto; padding-bottom: 5px; }
        .tab-btn { background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.08); color: #94a3b8; padding: 12px 20px; border-radius: 14px; font-size: 0.9rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 10px; transition: all 0.3s ease; white-space: nowrap; flex-shrink: 0; }
        .tab-btn:hover { color: #f8fafc; background: rgba(30, 41, 59, 0.8); }
        .tab-btn.active { background: linear-gradient(135deg, #0284c7, #2563eb); color: #fff; border-color: rgba(56, 189, 248, 0.4); box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3); }

        .tab-content { display: none; width: 100%; animation: fadeIn 0.4s ease forwards; }
        .tab-content.active { display: block; }

        .content-card { background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.4); margin-bottom: 25px; }
        .content-card h3 { font-size: 1.1rem; margin-bottom: 20px; color: #f8fafc; display: flex; align-items: center; gap: 15px; justify-content: space-between; flex-wrap: wrap; }

        /* Estructura responsiva optimizada para tarjetas de docentes */
        .docentes-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; }
        .materia-card { background: rgba(30, 41, 59, 0.4); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 16px; padding: 16px; transition: transform 0.3s; display: flex; flex-direction: column; justify-content: space-between; }
        .materia-card:hover { border-color: rgba(56, 189, 248, 0.3); transform: translateY(-2px); }
        .materia-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); gap: 10px; }
        .materia-title { font-size: 0.98rem; font-weight: 700; color: #38bdf8; display: flex; align-items: center; gap: 8px; word-break: break-word; }
        
        .docente-item { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.04); font-size: 0.85rem; gap: 8px; }
        .docente-item:last-child { border-bottom: none; }
        .docente-info { overflow: hidden; flex-grow: 1; }
        .docente-name { color: #f8fafc; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; max-width: 170px; }
        .docente-mail { color: #94a3b8; font-size: 0.75rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; max-width: 170px; }
        
        .docente-actions { display: flex; align-items: center; gap: 6px; flex-shrink: 0; }

        .search-box { position: relative; width: 100%; max-width: 320px; }
        .search-box input { width: 100%; background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 10px 15px 10px 40px; color: #fff; font-size: 0.88rem; outline: none; }
        .search-box input:focus { border-color: #38bdf8; box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2); }
        .search-box i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.9rem; }

        .alert-msg { padding: 12px 18px; border-radius: 12px; font-size: 0.88rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; transition: opacity 0.5s ease; }
        .alert-success { background: rgba(14, 159, 110, 0.2); border: 1px solid rgba(52, 211, 153, 0.4); color: #34d399; }
        .alert-error { background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5; }

        .table-responsive { overflow-x: auto; width: 100%; }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem; min-width: 600px; }
        th { background: rgba(30, 41, 59, 0.8); color: #38bdf8; padding: 12px 15px; font-weight: 600; }
        td { padding: 12px 15px; border-bottom: 1px solid rgba(255,255,255,0.06); color: #cbd5e1; }
        tr:hover td { background: rgba(255,255,255,0.02); }

        .badge { padding: 3px 8px; border-radius: 6px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; white-space: nowrap; }
        .badge-active { background: rgba(52, 211, 153, 0.2); color: #34d399; border: 1px solid rgba(52, 211, 153, 0.3); }
        .badge-inactive { background: rgba(239, 68, 68, 0.2); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.3); }
        .badge-count { background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); padding: 3px 9px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; white-space: nowrap; }
        
        .badge-subgrupo-a { background: rgba(56, 189, 248, 0.2); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.4); padding: 3px 10px; border-radius: 6px; font-weight: 700; font-size: 0.75rem; }
        .badge-subgrupo-b { background: rgba(168, 85, 247, 0.2); color: #c084fc; border: 1px solid rgba(168, 85, 247, 0.4); padding: 3px 10px; border-radius: 6px; font-weight: 700; font-size: 0.75rem; }

        .btn-table { padding: 5px 10px; border-radius: 8px; font-size: 0.75rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; cursor: pointer; border: none; white-space: nowrap; }
        .btn-edit { background: rgba(52, 211, 153, 0.2); color: #34d399; border: 1px solid rgba(52, 211, 153, 0.3); }
        .btn-toggle { background: rgba(56, 189, 248, 0.2); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Ajustes responsivos móviles avanzados */
        @media (max-width: 768px) {
            body { padding: 12px; }
            .header-bar { padding: 15px; }
            .docente-name, .docente-mail { max-width: 120px; }
        }
    </style>
</head>
<body>

    <div class="header-bar">
        <div class="header-title">
            <h2><i class="fa-solid fa-school"></i> Nivel Administrativo &bull; Colegio Valle Azul</h2>
            <p>Gestión integral de secciones, cargas docentes, matrícula de estudiantes, especialidades e idiomas.</p>
        </div>
        <div class="nav-buttons">
            <a href="/sistema/public/index.php?route=dashboard" class="btn-action">
                <i class="fa-solid fa-arrow-left"></i> Selector
            </a>
            <a href="/sistema/public/index.php?route=logout" class="btn-action btn-danger">
                <i class="fa-solid fa-power-off"></i> Salir
            </a>
        </div>
    </div>

    <?php if (!empty($mensaje)): ?>
        <div id="alertMessage" class="alert-msg alert-success"><i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div id="alertError" class="alert-msg alert-error"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Menú de Pestañas -->
    <div class="admin-tabs">
        <button class="tab-btn active" onclick="switchTab(event, 'tab-secciones')" id="btn-tab-secciones">
            <i class="fa-solid fa-chalkboard"></i> 1. Secciones
        </button>
        <button class="tab-btn" onclick="switchTab(event, 'tab-docentes')" id="btn-tab-docentes">
            <i class="fa-solid fa-user-tie"></i> 2. Docentes
        </button>
        <button class="tab-btn" onclick="switchTab(event, 'tab-estudiantes')" id="btn-tab-estudiantes">
            <i class="fa-solid fa-user-graduate"></i> 3. Estudiantes
        </button>
        <button class="tab-btn" onclick="switchTab(event, 'tab-materias')" id="btn-tab-materias">
            <i class="fa-solid fa-book"></i> 4. Materias
        </button>
    </div>

    <!-- PESTAÑA 1: GESTIÓN DE SECCIONES -->
    <div id="tab-secciones" class="tab-content active">
        <div class="content-card">
            <h3>
                <span><i class="fa-solid fa-chalkboard"></i> Listado Oficial de Secciones</span>
            </h3>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nivel</th>
                            <th>Sección</th>
                            <th>Profesor Guía</th>
                            <th>Permiso Guía</th>
                            <th>Estudiantes</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($secciones as $s): ?>
                        <tr>
                            <td>#<?php echo $s['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($s['nivel_nombre']); ?></strong></td>
                            <td><code><?php echo htmlspecialchars($s['seccion_nombre']); ?></code></td>
                            <td><?php echo htmlspecialchars($s['docente_guia'] ?? 'Sin asignar'); ?></td>
                            <td>
                                <span class="badge <?php echo $s['guia_habilitado'] ? 'badge-active' : 'badge-inactive'; ?>">
                                    <?php echo $s['guia_habilitado'] ? 'Habilitado' : 'Bloqueado'; ?>
                                </span>
                            </td>
                            <td><?php echo $s['total_estudiantes']; ?></td>
                            <td>
                                <a href="/sistema/public/index.php?route=admin-toggle-guia&id=<?php echo $s['id']; ?>" class="btn-table btn-toggle">
                                    <i class="fa-solid fa-shield-halved"></i> Guía
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- PESTAÑA 2: GESTIÓN DE DOCENTES -->
    <div id="tab-docentes" class="tab-content">
        <div class="content-card">
            <h3>
                <span><i class="fa-solid fa-user-tie"></i> Cargas Académicas por Materia</span>
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchDocenteInput" placeholder="Buscar docente..." onkeyup="filterDocentesCards()">
                </div>
            </h3>
            <div class="docentes-grid" id="docentesGridContainer">
                <?php if (!empty($docentesAgrupados)): ?>
                    <?php foreach ($docentesAgrupados as $materia => $listaDocs): ?>
                    <div class="materia-card" data-materia="<?php echo strtolower($materia); ?>">
                        <div class="materia-header">
                            <span class="materia-title"><i class="fa-solid fa-bookmark" style="color: #38bdf8;"></i> <?php echo htmlspecialchars($materia); ?></span>
                            <span class="badge-count"><?php echo count($listaDocs); ?> <?php echo count($listaDocs) == 1 ? 'doc.' : 'doc.'; ?></span>
                        </div>
                        <div>
                            <?php foreach ($listaDocs as $doc): ?>
                            <div class="docente-item searchable-docente" data-nombre="<?php echo strtolower($doc['nombre'] . ' ' . $doc['apellidos']); ?>" data-correo="<?php echo strtolower($doc['correo']); ?>">
                                <div class="docente-info">
                                    <span class="docente-name" title="<?php echo htmlspecialchars($doc['nombre'] . ' ' . $doc['apellidos']); ?>"><?php echo htmlspecialchars($doc['nombre'] . ' ' . $doc['apellidos']); ?></span>
                                    <span class="docente-mail" title="<?php echo htmlspecialchars($doc['correo']); ?>"><?php echo htmlspecialchars($doc['correo']); ?></span>
                                </div>
                                <div class="docente-actions">
                                    <span class="badge <?php echo $doc['estado'] ? 'badge-active' : 'badge-inactive'; ?>">
                                        <?php echo $doc['estado'] ? 'Activo' : 'Inactivo'; ?>
                                    </span>
                                    <button onclick="openDocenteModal(<?php echo htmlspecialchars(json_encode($doc)); ?>, <?php echo htmlspecialchars(json_encode($materias)); ?>)" class="btn-table btn-edit" title="Asignar Materias">
                                        <i class="fa-solid fa-pen-to-square"></i> Carga
                                    </button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: #94a3b8; padding: 20px;">No hay registros de docentes disponibles.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- PESTAÑA 3: GESTIÓN DE ESTUDIANTES -->
    <div id="tab-estudiantes" class="tab-content">
        <div class="content-card">
            <h3>
                <span><i class="fa-solid fa-user-graduate"></i> Matrícula y Subgrupos</span>
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchEstudiante" placeholder="Filtrar estudiante..." onkeyup="filterTable('searchEstudiante', 'tableEstudiantes')">
                </div>
            </h3>
            <div class="table-responsive">
                <table id="tableEstudiantes">
                    <thead>
                        <tr>
                            <th>Cédula</th>
                            <th>Nombre Completo</th>
                            <th>Sección</th>
                            <th>Subgrupo</th>
                            <th>Especialidad</th>
                            <th>Idioma</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($estudiantes as $est): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($est['cedula']); ?></td>
                            <td><strong><?php echo htmlspecialchars($est['nombre'] . ' ' . $est['apellidos']); ?></strong></td>
                            <td><?php echo htmlspecialchars($est['nivel_nombre'] . ' (' . $est['seccion_nombre'] . ')'); ?></td>
                            <td>
                                <?php $sub = strtoupper($est['subgrupo'] ?? 'A'); ?>
                                <span class="<?php echo $sub === 'B' ? 'badge-subgrupo-b' : 'badge-subgrupo-a'; ?>">
                                    <?php echo $sub; ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($est['especialidad_tecnica'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($est['idioma'] ?? 'N/A'); ?></td>
                            <td>
                                <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($est)); ?>)" class="btn-table btn-edit">
                                    <i class="fa-solid fa-pen-to-square"></i> Editar
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- PESTAÑA 4: GESTIÓN DE MATERIAS -->
    <div id="tab-materias" class="tab-content">
        <div class="content-card">
            <h3>
                <span><i class="fa-solid fa-book"></i> Catálogo de Materias</span>
            </h3>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre de la Materia</th>
                            <th>Tipo / Categoría</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($materias as $mat): ?>
                        <tr>
                            <td>#<?php echo $mat['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($mat['nombre']); ?></strong></td>
                            <td><code><?php echo htmlspecialchars($mat['tipo'] ?? 'General'); ?></code></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL DE ASIGNACIÓN DE MATERIAS A DOCENTE -->
    <div id="docenteModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); backdrop-filter:blur(5px); z-index:1000; justify-content:center; align-items:center; padding:15px;">
        <div style="background:#0f172a; border:1px solid rgba(255,255,255,0.15); padding:25px; border-radius:20px; width:100%; max-width:500px; box-shadow:0 20px 40px rgba(0,0,0,0.5);">
            <h3 style="color:#38bdf8; margin-bottom:10px;"><i class="fa-solid fa-chalkboard-user"></i> Gestionar Carga Académica</h3>
            <p id="docenteModalNombre" style="color:#94a3b8; font-size:0.9rem; margin-bottom:20px;"></p>
            
            <form action="/sistema/public/index.php?route=admin-actualizar-docente-materias" method="POST">
                <input type="hidden" id="modalDocenteId" name="docente_id">
                
                <div style="margin-bottom:20px;">
                    <label style="font-size:0.85rem; color:#38bdf8; display:block; margin-bottom:10px; font-weight:600;">Seleccione la(s) materia(s) que imparte:</label>
                    <div id="materiasCheckboxList" style="max-height:220px; overflow-y:auto; display:grid; grid-template-columns:1fr; gap:8px; background:rgba(30,41,59,0.4); padding:15px; border-radius:12px; border:1px solid rgba(255,255,255,0.08);">
                    </div>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:10px;">
                    <button type="button" onclick="closeDocenteModal()" class="btn-action" style="background:rgba(255,255,255,0.1);">Cancelar</button>
                    <button type="submit" class="btn-action" style="background:linear-gradient(135deg, #0284c7, #2563eb); border:none;">Guardar Carga</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DE EDICIÓN DE ESTUDIANTE -->
    <div id="editModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); backdrop-filter:blur(5px); z-index:1000; justify-content:center; align-items:center; padding:15px;">
        <div style="background:#0f172a; border:1px solid rgba(255,255,255,0.15); padding:25px; border-radius:20px; width:100%; max-width:500px; box-shadow:0 20px 40px rgba(0,0,0,0.5);">
            <h3 style="color:#38bdf8; margin-bottom:20px;"><i class="fa-solid fa-user-pen"></i> Editar Estudiante</h3>
            <form action="/sistema/public/index.php?route=admin-actualizar-estudiante" method="POST">
                <input type="hidden" id="modalEstId" name="estudiante_id">
                
                <div style="margin-bottom:15px;">
                    <label style="font-size:0.85rem; color:#94a3b8; display:block; margin-bottom:5px;">Cédula</label>
                    <input type="text" id="modalCedula" name="cedula" style="width:100%; background:rgba(30,41,59,0.6); border:1px solid rgba(255,255,255,0.1); border-radius:10px; padding:10px; color:#fff;" required>
                </div>
                
                <div style="margin-bottom:15px;">
                    <label style="font-size:0.85rem; color:#94a3b8; display:block; margin-bottom:5px;">Nombre</label>
                    <input type="text" id="modalNombre" name="nombre" style="width:100%; background:rgba(30,41,59,0.6); border:1px solid rgba(255,255,255,0.1); border-radius:10px; padding:10px; color:#fff;" required>
                </div>

                <div style="margin-bottom:15px;">
                    <label style="font-size:0.85rem; color:#94a3b8; display:block; margin-bottom:5px;">Apellidos</label>
                    <input type="text" id="modalApellidos" name="apellidos" style="width:100%; background:rgba(30,41,59,0.6); border:1px solid rgba(255,255,255,0.1); border-radius:10px; padding:10px; color:#fff;" required>
                </div>

                <div style="margin-bottom:15px;">
                    <label style="font-size:0.85rem; color:#94a3b8; display:block; margin-bottom:5px;">Sección</label>
                    <select id="modalSeccion" name="seccion_id" style="width:100%; background:rgba(30,41,59,0.6); border:1px solid rgba(255,255,255,0.1); border-radius:10px; padding:10px; color:#fff;" required>
                        <?php foreach ($secciones as $sec): ?>
                            <option value="<?php echo $sec['id']; ?>"><?php echo htmlspecialchars($sec['nivel_nombre'] . ' - ' . $sec['seccion_nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="margin-bottom:15px;">
                    <label style="font-size:0.85rem; color:#94a3b8; display:block; margin-bottom:5px;">Subgrupo</label>
                    <select id="modalSubgrupo" name="subgrupo" style="width:100%; background:rgba(30,41,59,0.6); border:1px solid rgba(255,255,255,0.1); border-radius:10px; padding:10px; color:#fff;">
                        <option value="A">A</option>
                        <option value="B">B</option>
                    </select>
                </div>

                <div style="margin-bottom:15px;">
                    <label style="font-size:0.85rem; color:#94a3b8; display:block; margin-bottom:5px;">Especialidad Técnica</label>
                    <input type="text" id="modalEspecialidad" name="especialidad_tecnica" placeholder="Ej. Contabilidad, Informática" style="width:100%; background:rgba(30,41,59,0.6); border:1px solid rgba(255,255,255,0.1); border-radius:10px; padding:10px; color:#fff;">
                </div>

                <div style="margin-bottom:20px;">
                    <label style="font-size:0.85rem; color:#94a3b8; display:block; margin-bottom:5px;">Idioma</label>
                    <input type="text" id="modalIdioma" name="idioma" placeholder="Ej. Inglés, Francés" style="width:100%; background:rgba(30,41,59,0.6); border:1px solid rgba(255,255,255,0.1); border-radius:10px; padding:10px; color:#fff;">
                </div>

                <div style="display:flex; justify-content:flex-end; gap:10px;">
                    <button type="button" onclick="closeEditModal()" class="btn-action" style="background:rgba(255,255,255,0.1);">Cancelar</button>
                    <button type="submit" class="btn-action" style="background:linear-gradient(135deg, #0284c7, #2563eb); border:none;">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const alertMsg = document.getElementById('alertMessage');
            const alertErr = document.getElementById('alertError');
            
            if (alertMsg || alertErr) {
                setTimeout(() => {
                    if (alertMsg) {
                        alertMsg.style.opacity = '0';
                        setTimeout(() => alertMsg.remove(), 500);
                    }
                    if (alertErr) {
                        alertErr.style.opacity = '0';
                        setTimeout(() => alertErr.remove(), 500);
                    }
                }, 4000);
            }

            const savedTab = localStorage.getItem('admin_active_tab');
            if (savedTab && document.getElementById(savedTab)) {
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.getElementById(savedTab).classList.add('active');
                const btnId = 'btn-' + savedTab;
                if (document.getElementById(btnId)) document.getElementById(btnId).classList.add('active');
            }
        });

        function filterTable(inputId, tableId) {
            const input = document.getElementById(inputId);
            const filter = input.value.toLowerCase();
            const table = document.getElementById(tableId);
            const tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                let visible = false;
                const td = tr[i].getElementsByTagName('td');
                for (let j = 0; j < td.length; j++) {
                    if (td[j]) {
                        const txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toLowerCase().indexOf(filter) > -1) {
                            visible = true;
                            break;
                        }
                    }
                }
                tr[i].style.display = visible ? '' : 'none';
            }
        }

        function filterDocentesCards() {
            const query = document.getElementById('searchDocenteInput').value.toLowerCase();
            const tarjetas = document.querySelectorAll('.materia-card');

            tarjetas.forEach(card => {
                let cardVisible = false;
                const items = card.querySelectorAll('.searchable-docente');

                items.forEach(item => {
                    const nombre = item.getAttribute('data-nombre');
                    const correo = item.getAttribute('data-correo');
                    if (nombre.includes(query) || correo.includes(query)) {
                        item.style.display = '';
                        cardVisible = true;
                    } else {
                        item.style.display = 'none';
                    }
                });

                card.style.display = cardVisible ? '' : 'none';
            });
        }

        function switchTab(evt, tabId) {
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
            evt.currentTarget.classList.add('active');
            localStorage.setItem('admin_active_tab', tabId);
        }

        function openDocenteModal(docente, todasMaterias) {
            document.getElementById('modalDocenteId').value = docente.id;
            document.getElementById('docenteModalNombre').textContent = `Docente: ${docente.nombre} ${docente.apellidos}`;

            const container = document.getElementById('materiasCheckboxList');
            container.innerHTML = '';

            const asignadasIds = docente.materias_asignadas ? docente.materias_asignadas.map(m => m.id.toString()) : [];

            todasMaterias.forEach(mat => {
                const isChecked = asignadasIds.includes(mat.id.toString()) ? 'checked' : '';
                const div = document.createElement('div');
                div.style.display = 'flex';
                div.style.alignItems = 'center';
                div.style.gap = '8px';
                div.innerHTML = `
                    <input type="checkbox" name="materias[]" value="${mat.id}" id="mat_${mat.id}" ${isChecked} style="accent-color:#0284c7; width:16px; height:16px;">
                    <label for="mat_${mat.id}" style="color:#cbd5e1; font-size:0.85rem; cursor:pointer;">${mat.nombre}</label>
                `;
                container.appendChild(div);
            });

            document.getElementById('docenteModal').style.display = 'flex';
        }

        function closeDocenteModal() {
            document.getElementById('docenteModal').style.display = 'none';
        }

        function openEditModal(est) {
            document.getElementById('modalEstId').value = est.id;
            document.getElementById('modalCedula').value = est.cedula;
            document.getElementById('modalNombre').value = est.nombre;
            document.getElementById('modalApellidos').value = est.apellidos;
            document.getElementById('modalSeccion').value = est.seccion_id;
            document.getElementById('modalSubgrupo').value = est.subgrupo || 'A';
            document.getElementById('modalEspecialidad').value = est.especialidad_tecnica || '';
            document.getElementById('modalIdioma').value = est.idioma || '';
            document.getElementById('editModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
    </script>
</body>
</html>
