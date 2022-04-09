<?php
namespace Controllers;

use Model\Servicio;
use Model\Cita;
use Model\CitaServicio;

class APIController{
    public static function index() {
        $servicios= Servicio::all();
        //debuguear($servicios);
        
        $servicio= json_encode($servicios);
        echo $servicio;
        //debuguear (json_encode($servicios));
    }
    public static function guardar(){
        // $respuesta =[
        //     'datos' => $_POST
        // ];
        // almacena la cita y devuelve el Id
        $cita= new Cita($_POST);

        $resultado=$cita->guardar();
        $id =$resultado['id'];

        //almacena la cita y el servicio

            $idservicios = explode(",",$_POST['servicios']);
            foreach($idservicios as $idServicio){
               $args=[
                    'citaId' => $id,
                    'servicioId'=>$idServicio
                ];
                $citaServicio = new CitaServicio($args);
                $citaServicio->guardar();
            }
            // $respuesta = [
            //     'resultado' => $resultado
            // ];

        // $respuesta =[
        //     'cita'=>$cita
        // ];
        echo json_encode(['resultado' => $resultado]);
    }
    public static function eliminar(){
        // debuguear($_POST);
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $id= $_POST['id'];
        //   debuguear($id);

            //debuguear($_SERVER);
            $cita= Cita::find($id);
            //debuguear($cita);
            $cita->eliminar();
            header('Location:' . $_SERVER['HTTP_REFERER']);
        }
    }
}