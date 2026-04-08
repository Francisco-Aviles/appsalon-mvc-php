<?php
namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController {
    public static function index(Router $router){
        //Verifica si la sesión no ha sido iniciada
        if(session_status() === PHP_SESSION_NONE) session_start();
        isAdmin();

        //Sino hay una fecha en la url se agrega la fecha actual
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $partes = explode('-', $fecha);

        //Verifica que la fecha sea valida y sino redirigir
        if(!checkdate($partes[1], $partes[2], $partes[0])){
            header('Location: /404');
            exit;
        }

        $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuarioId=usuarios.id  ";
        $consulta .= " LEFT OUTER JOIN citasservicios ";
        $consulta .= " ON citasservicios.citaId=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citasservicios.servicioId ";
        $consulta .= " WHERE fecha =  '{$fecha}' ";

        $citas = AdminCita::SQL($consulta);

        $nombre = $_SESSION['nombre'];
        $router->render('admin/index', [
            'nombre' => $nombre,
            'citas' => $citas,
            'fecha' => $fecha
        ]);
    }
}