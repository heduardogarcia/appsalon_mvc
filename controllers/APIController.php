<?php
namespace Controllers;

use Model\Servicio;

class APIController{
    public static function index() {
        $servicios= Servicio::all();
        //debuguear($servicios);
        
        $servicio= json_encode($servicios);
        echo $servicio;
        //debuguear (json_encode($servicios));
    }
}