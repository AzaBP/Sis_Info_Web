<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../dao/ListaDAO.php';
require_once __DIR__ . '/../dao/ListaDAOImpl.php';
require_once __DIR__ . '/../dao/PlaylistDAO.php';
require_once __DIR__ . '/../dao/PlaylistDAOImpl.php';
require_once __DIR__ . '/../lib/Validation.php';

class PlaylistController {
    private ListaDAO $listas;
    private PlaylistDAO $playlist;

    public function __construct() { 
        $database = new Database();
        $dbConnection = $database->getConnection();

        $this->listas = new ListaDAOImpl($dbConnection);
        $this->playlist = new PlaylistDAOImpl($dbConnection);
    }

    public function crearLista(string $usuarioId, string $listaId): array {
        $listaId = Validation::clean($listaId);
        if (!Validation::texto($listaId, 2, 100)) return ['ok'=>false,'errors'=>['lista_id'=>'Nombre de lista 2-100']];
        $ok = $this->listas->crearLista($listaId, $usuarioId);
        return ['ok'=>$ok];
    }

    public function listasDeUsuario(string $usuarioId): array {
        return $this->listas->obtenerListasDeUsuario($usuarioId);
    }

    public function agregarCancion(string $listaId, string $nombreCancion, string $nombreCreador): array {
        $listaId = Validation::clean($listaId);
        $cancion = Validation::clean($nombreCancion);
        $creador = Validation::clean($nombreCreador);
        if (!Validation::texto($cancion,1,100) || !Validation::texto($creador,1,50))
            return ['ok'=>false,'errors'=>['item'=>'Datos de canción/creador no válidos']];
        $ok = $this->playlist->agregarCancion($listaId, $cancion, $creador);
        return ['ok'=>$ok];
    }

    public function eliminarCancion(string $listaId, string $nombreCancion, string $nombreCreador): array {
        $ok = $this->playlist->eliminarCancion($listaId, $nombreCancion, $nombreCreador);
        return ['ok'=>$ok];
    }

    public function cancionesDeLista(string $listaId): array {
        return $this->playlist->obtenerCanciones($listaId);
    }
}
?>