<?php
require_once __DIR__ . '/../vo/UsuarioVO.php';

interface UsuarioDAO {
    public function agregarUsuario(UsuarioVO $usuario);
    public function obtenerUsuarioPorId($usuario_id);
    public function obtenerTodosLosUsuarios();
    public function actualizarUsuario(UsuarioVO $usuario);
    public function eliminarUsuario($usuario_id);
    public function obtenerUsuarioPorCorreo($correo);
    
    public function crearUsuario(string $usuarioId, string $nombre, string $hash, string $correo, int $telefono, string $codigoSuscripcion): bool;
    public function getPorCorreo(string $correo): ?array;  // id, nombre, correo, password (hash), telefono, codigo_suscripcion
    public function actualizarPerfil(string $usuarioId, string $nombre, int $telefono): bool;

}
?>