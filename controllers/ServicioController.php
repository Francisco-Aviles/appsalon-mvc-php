<?php
namespace Controllers;

use Model\Servicio;
use MVC\Router;

class ServicioController {

    public static function index(Router $router){
        if(session_status() === PHP_SESSION_NONE) session_start();
        isAdmin();
        $nombre = $_SESSION['nombre'] ?? '';
        $servicios = Servicio::all();
        $router->render('/servicios/index', [
            'nombre' => $nombre,
            'servicios' => $servicios
        ]);
    }

    public static function crear(Router $router){
        if(session_status() === PHP_SESSION_NONE) session_start();
        isAdmin();
        $nombre = $_SESSION['nombre'] ?? '';

        $servicio = new Servicio;
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $servicio->sincronizar($_POST);
            $alertas = $servicio->validar();

            if(empty($alertas)){
                $servicio->guardar();
                header('Location: /servicios');
                exit;
            }
        }

        $router->render('/servicios/crear', [
            'nombre' => $nombre,
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function actualizar(Router $router){
        if(session_status() === PHP_SESSION_NONE) session_start();
        isAdmin();
        $nombre = $_SESSION['nombre'] ?? '';
        $alertas = [];
        $id = $_GET['id'] ?? null;
        if(!is_numeric($id)){
            header('Location: /servicios');
            exit;
        }

        $servicio = Servicio::find($id);
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $servicio->sincronizar($_POST);
            $alertas = $servicio->sincronizar();

            if(empty($alertas)){
                $servicio->guardar();
                header('Location: /servicios');
                exit;
            }
        }

        $router->render('/servicios/actualizar', [
            'nombre' => $nombre,
            'alertas' => $alertas,
            'servicio' => $servicio
        ]);
    }
    public static function eliminar(){
        if(session_status() === PHP_SESSION_NONE) session_start();
        isAdmin();

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = $_POST['id'];
            $servicio = Servicio::find($id);
            $servicio->eliminar();
            header('Location: /servicios');
            exit;
        }
    }
}
