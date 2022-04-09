<?php 
namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController{
    public static function index(Router $router)
    {
        //debuguear($_GET);
        isAdmin();
        $fecha= $_GET['fecha'] ?? date('Y-m-d');
        //separar fecha para que salga dia mes año

        $fechas= explode('-',$fecha);
        //debuguear($fecha);
        if(!checkdate($fechas[1], $fechas[2],$fechas[0])){
            header('Location: /404');
        }
        //session_start();

        //consultar la bd

        $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuarioId=usuarios.id  ";
        $consulta .= " LEFT OUTER JOIN citasServicios ";
        $consulta .= " ON citasServicios.citaId=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citasServicios.servicioId ";
        $consulta .= " WHERE fecha =  '${fecha}' ";

        $citas=AdminCita::SQL($consulta);
       // debuguear($citas);

        $router->render('admin/index',[
            'nombre'=> $_SESSION['nombre'],
            'citas'=> $citas,
            'fecha'=>$fecha
        ]);
    }
}