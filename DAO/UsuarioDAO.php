<?php
require_once __DIR__ . '/../VO php/UsuarioVO.php';

interface UsuarioDAO {
    public function agregarUsuario(UsuarioVO $usuario);
    public function obtenerUsuarioPorId($usuario_id);
    public function obtenerTodosLosUsuarios();
    public function actualizarUsuario(UsuarioVO $usuario);
    public function eliminarUsuario($usuario_id);
    public function obtenerUsuarioPorCorreo($correo);
}
?>