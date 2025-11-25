<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>✅ Checklist Pruebas - Persona 3</title>
    <style>
        .test-section { margin: 20px 0; padding: 15px; border-left: 4px solid #333; }
        .test-item { margin: 10px 0; padding: 10px; background: #f5f5f5; }
        .success { background: #d4edda; border-left: 4px solid #28a745; }
        .error { background: #f8d7da; border-left: 4px solid #dc3545; }
        .btn { padding: 8px 15px; margin: 5px; text-decoration: none; border-radius: 4px; }
        .btn-test { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-danger { background: #dc3545; color: white; }
    </style>
</head>
<body>
    <h1>✅ CHECKLIST PRUEBAS - PERSONA 3</h1>
    <p><strong>Usuario actual:</strong> <?= $_SESSION['uid'] ?? 'No logueado' ?></p>
    <p><strong>Ubicación checklist:</strong> public/tests/</p>

    <!-- FASE 1: AUTENTICACIÓN -->
    <div class="test-section">
        <h2>🔐 FASE 1: Autenticación</h2>
        
        <div class="test-item">
            <h3>1. Registro de usuario</h3>
            <p>¿Pueden crearse nuevos usuarios?</p>
            <a href="../php/registro.php" class="btn btn-test" target="_blank">Probar Registro</a>
            <button onclick="marcarTest(1, 'success')" class="btn btn-success">✅ Funciona</button>
            <button onclick="marcarTest(1, 'error')" class="btn btn-danger">❌ No funciona</button>
            <div id="result-1"></div>
        </div>

        <div class="test-item">
            <h3>2. Inicio de sesión</h3>
            <p>¿Pueden los usuarios loguearse?</p>
            <a href="../php/login.php" class="btn btn-test" target="_blank">Probar Login</a>
            <button onclick="marcarTest(2, 'success')" class="btn btn-success">✅ Funciona</button>
            <button onclick="marcarTest(2, 'error')" class="btn btn-danger">❌ No funciona</button>
            <div id="result-2"></div>
        </div>

        <div class="test-item">
            <h3>3. Cierre de sesión</h3>
            <p>¿Pueden los usuarios cerrar sesión?</p>
            <a href="../php/procesar_login.php?action=logout" class="btn btn-test" target="_blank">Probar Logout</a>
            <button onclick="marcarTest(3, 'success')" class="btn btn-success">✅ Funciona</button>
            <button onclick="marcarTest(3, 'error')" class="btn btn-danger">❌ No funciona</button>
            <div id="result-3"></div>
        </div>
    </div>

    <!-- FASE 2: NAVEGACIÓN -->
    <div class="test-section">
        <h2>🧭 FASE 2: Navegación</h2>
        
        <div class="test-item">
            <h3>4. Página principal</h3>
            <a href="../php/pagina_principal.php" class="btn btn-test" target="_blank">Probar Página Principal</a>
            <button onclick="marcarTest(4, 'success')" class="btn btn-success">✅ Funciona</button>
            <button onclick="marcarTest(4, 'error')" class="btn btn-danger">❌ No funciona</button>
            <div id="result-4"></div>
        </div>

        <div class="test-item">
            <h3>5. Perfil de usuario</h3>
            <a href="../php/perfil_usuario.php" class="btn btn-test" target="_blank">Probar Perfil</a>
            <button onclick="marcarTest(5, 'success')" class="btn btn-success">✅ Funciona</button>
            <button onclick="marcarTest(5, 'error')" class="btn btn-danger">❌ No funciona</button>
            <div id="result-5"></div>
        </div>

        <div class="test-item">
            <h3>6. Ajustes de perfil</h3>
            <a href="../php/ajustes_perfil.php" class="btn btn-test" target="_blank">Probar Ajustes</a>
            <button onclick="marcarTest(6, 'success')" class="btn btn-success">✅ Funciona</button>
            <button onclick="marcarTest(6, 'error')" class="btn btn-danger">❌ No funciona</button>
            <div id="result-6"></div>
        </div>
    </div>

    <!-- FASE 3: OPERACIONES CRUD -->
    <div class="test-section">
        <h2>📝 FASE 3: Operaciones CRUD</h2>
        
        <div class="test-item">
            <h3>7. Gestión de playlists</h3>
            <a href="../php/gestionar_playlist.php" class="btn btn-test" target="_blank">Probar Playlists</a>
            <button onclick="marcarTest(7, 'success')" class="btn btn-success">✅ Funciona</button>
            <button onclick="marcarTest(7, 'error')" class="btn btn-danger">❌ No funciona</button>
            <div id="result-7"></div>
        </div>

        <div class="test-item">
            <h3>8. Ver playlist específica</h3>
            <a href="../php/playlist.php" class="btn btn-test" target="_blank">Probar Playlist</a>
            <button onclick="marcarTest(8, 'success')" class="btn btn-success">✅ Funciona</button>
            <button onclick="marcarTest(8, 'error')" class="btn btn-danger">❌ No funciona</button>
            <div id="result-8"></div>
        </div>

        <div class="test-item">
            <h3>9. Detalles de canción</h3>
            <a href="../php/detalles_cancion.php" class="btn btn-test" target="_blank">Probar Detalles</a>
            <button onclick="marcarTest(9, 'success')" class="btn btn-success">✅ Funciona</button>
            <button onclick="marcarTest(9, 'error')" class="btn btn-danger">❌ No funciona</button>
            <div id="result-9"></div>
        </div>
    </div>

    <!-- ENLACES DIRECTOS PARA VERIFICAR -->
    <div class="test-section">
        <h2>🔗 Enlaces directos para verificar</h2>
        <p>Si los enlaces de arriba no funcionan, usa estos:</p>
        <ul>
            <li><a href="/recomendador_musica/public/php/login.php" target="_blank">/recomendador_musica/public/php/login.php</a></li>
            <li><a href="/recomendador_musica/public/php/registro.php" target="_blank">/recomendador_musica/public/php/registro.php</a></li>
            <li><a href="/recomendador_musica/public/php/pagina_principal.php" target="_blank">/recomendador_musica/public/php/pagina_principal.php</a></li>
            <li><a href="/recomendador_musica/public/php/perfil_usuario.php" target="_blank">/recomendador_musica/public/php/perfil_usuario.php</a></li>
            <li><a href="/recomendador_musica/public/php/gestionar_playlist.php" target="_blank">/recomendador_musica/public/php/gestionar_playlist.php</a></li>
        </ul>
    </div>

    <div id="resumen" style="margin-top: 30px; padding: 20px; background: #e9ecef;"></div>

    <script>
        let resultados = {};
        
        function marcarTest(numero, estado) {
            resultados[numero] = estado;
            const elemento = document.getElementById('result-' + numero);
            elemento.innerHTML = estado === 'success' ? 
                '<span style="color: green;">✅ TEST COMPLETADO</span>' : 
                '<span style="color: red;">❌ TEST FALLIDO</span>';
            
            actualizarResumen();
        }
        
        function actualizarResumen() {
            const total = Object.keys(resultados).length;
            const exitosos = Object.values(resultados).filter(e => e === 'success').length;
            const fallidos = total - exitosos;
            const porcentaje = total > 0 ? Math.round((exitosos / total) * 100) : 0;
            
            document.getElementById('resumen').innerHTML = `
                <h2>📊 RESUMEN DE PRUEBAS</h2>
                <p>✅ Tests exitosos: ${exitosos}</p>
                <p>❌ Tests fallidos: ${fallidos}</p>
                <p>📈 Porcentaje de éxito: ${porcentaje}%</p>
                <p>${porcentaje >= 80 ? '🎉 ¡Sistema estable!' : '⚠️ Necesita correcciones'}</p>
            `;
        }
    </script>
</body>
</html>