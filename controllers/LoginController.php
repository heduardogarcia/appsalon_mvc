<?php
namespace Controllers{

    use Model\Usuario;
    use MVC\Router;

    class LoginController{
        public static function login(Router $router){
                $router->render('auth/login');
        }
        public static function logout(){
            echo "desde logout";
        }
        public static function olvide( Router $router){
            $router->render('auth/olvide-password',[]);
        }
        public static function recuperar(){
            echo "desde recuperar";
        }
        public static function crear(Router $router){
           // debuguear($_SERVER);

           $usuario = new Usuario;

           $alertas=[];
            if($_SERVER['REQUEST_METHOD']==='POST'){
                //echo "Enviaste el formulario";
               // $usuario = new Usuario($_POST);
                //debuguear($usuario);
                $usuario->sincronizar($_POST);
                $alertas=$usuario->validarNuevaCuenta();

                if(empty($alertas)){
                    //echo "pasaste la validacion";
                    $resultado=$usuario->existeUsuario();
                    if($resultado->num_rows){
                        $alertas = Usuario::getAlertas();
                    }
                    else{
                        
                        debuguear($usuario);
                    }
                }

               // debuguear($alertas);
            }
        $router->render('auth/crear-cuenta',['usuario'=>$usuario,
        'alertas' => $alertas
            ]);                        
        }
    }
}