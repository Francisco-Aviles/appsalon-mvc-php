<?php
namespace Controllers;

use Model\CitasServicios;
use Model\Servicio;
use Model\Cita;

class APIController {
    public static function index(){
        $servicios = Servicio::all();

        echo json_encode($servicios);
    }

    public static function guardar(){

        //Almacena la cita  y devuelve el id
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();

        //Obtiene el id de la cita
        $id = $resultado['id'];

        //Crea un arreglo con los id de los servicios
        $idServicios = explode(',', $_POST['servicios']);

        //Iteramos el arreglo de servicios para crear un registro de cada servicio con el id de la cita
        foreach($idServicios as $idServicio){
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];

            $citaServicio = new CitasServicios($args);
            $citaServicio->guardar();
        }

        echo json_encode(['resultado' => $resultado]);
    }

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = $_POST['id'] ?? null;

            //Buscar la cita y eliminarla
            $cita = Cita::find($id);
            $cita->eliminar();

            //Redirigir a la pagina de donde viene
            header('Location: '. $_SERVER['HTTP_REFERER']);
            exit;
        }
    }
}