<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

function esUltimo(string $actual, string $proximo): bool{
    if($actual !==$proximo){
        return true;
    }
    return false;
}
// verificar si esta autenticado para proteger las rutas

function isAuth():void{
    if(!isset($_SESSION['login'])){
        header('Location:/');
    }

}
