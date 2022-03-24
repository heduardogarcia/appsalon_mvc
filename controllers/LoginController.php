<?php
namespace Controllers{

use Classes\Email;

    use Model\Usuario;
    use MVC\Router;

    class LoginController{
        public static function login(Router $router){
            $alertas =[];
            if($_SERVER['REQUEST_METHOD']==='POST'){
                $auth= new Usuario($_POST);
                $alertas =$auth->validarLogin();
              //  debuguear($auth);

              if (empty($alertas)){
                  $usuario = Usuario::where ('email', $auth->email);
              
              if($usuario){
                
                 if($usuario->comprobarPasswordAndVerificado($auth->password)){
                    //autenticar el usuario
                    session_start();
                    $_SESSION['id']= $usuario->id;
                    $_SESSION['nombre']= $usuario->nombre . " ". $usuario->apellido;
                    $_SESSION['email']=$usuario->email;
                    $_SESSION['login']=true;
                    //debuguear($_SESSION);

                    if($usuario->admin ==="1"){
                        //debuguear('Es admin');
                        $_SESSION['admin']=$usuario->admin??null;

                        header('Location: /admin');
                    }
                    else{
                        header('Location: /cita');
                    }
                  //  debuguear($_SESSION);
                 }
              }
              else{
                  Usuario::setAlerta('error','Usuario no encontrado');
              }
            }

              $alertas = Usuario::getAlertas();

              //debuguear($usuario);
            }
                $router->render('auth/login',[
                    'alertas'=>$alertas
                ]);
        }
        public static function logout(){
            echo "desde logout";
        }
        public static function olvide( Router $router){

            $alertas= [];

            if($_SERVER['REQUEST_METHOD']==='POST'){
                $auth= new Usuario($_POST);
                $alertas=$auth->validarEmail();

                if(empty($alertas)){
                    $usuario = Usuario::where('email', $auth->email);
                    if($usuario && $usuario->confirmado==="1"){
                        
                        // Generar token
                        $usuario->crearToken();
                       // debuguear($usuario);

                        $usuario->guardar();

                        //Enviar instrucciones

                        $email= new Email($usuario->email, $usuario->nombre, $usuario->token);
                        $email->enviarInstrucciones();

                        Usuario::setAlerta('exito', 'Revisa tu email');
                       
                    }
                    else{
                        Usuario::setAlerta('error','El Usuario no existe o no esta confirmado');
                       
                    }
                }
                //debuguear($auth);
            }
            $alertas= Usuario::getAlertas();
            $router->render('auth/olvide-password',[
                'alertas'=>$alertas

            ]);
        }
        public static function recuperar(Router $router){
            
            $alertas=[];
            $error= false;
            $token = s($_GET['token']);
            
            //debuguear($token);
            //buscar usuario por su token

            $usuario = Usuario::where ('token',$token);

            if(empty($usuario)){
                Usuario::setAlerta('error','Token No válido');
                $error=true;
            }

           // debuguear($usuario);
           if($_SERVER['REQUEST_METHOD']==='POST'){
               //LEER EL NUEVO PASSWORD
               $password= new Usuario($_POST);
               $alertas= $password->validarPassword();
              // debuguear($alertas);
           
            if(empty($alertas)){
                $usuario->password= null;
                $usuario->password= $password->password;
               // debuguear($password);
                $usuario->hashPassword();
                $usuario->token= null;
                $resultado=$usuario->guardar();
                if($resultado){

                       // debuguear($resultado);
                    header('Location: /');
                }
            }

              // debuguear($usuario);
           
        }
            $alertas= Usuario::getAlertas();

            $router->render('auth/recuperar-password',[
                'alertas'=>$alertas,
                'error'=>$error
            ]);
        }
        public static function crear(Router $router) {
            $usuario = new Usuario;
    
            // Alertas vacias
            $alertas = [];
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $usuario->sincronizar($_POST);
                $alertas = $usuario->validarNuevaCuenta();
                
                // Revisar que alerta este vacio
                if(empty($alertas)) {
                    // Verificar que el usuario no este registrado
                    $resultado = $usuario->existeUsuario();
                    
                    if($resultado->num_rows) {
                        $alertas = Usuario::getAlertas();
                    } else {
                        // Hashear el Password
                        $usuario->hashPassword();
                        
                        // Generar un Token único
                        $usuario->crearToken();
                        
                        // Enviar el Email
                        $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                        $email->enviarConfirmacion();
                        
                        //debuguear($usuario);
                        // Crear el usuario
                        $resultado = $usuario->guardar();

                        //debuguear ($resultado);
                        // debuguear($usuario);
                        if($resultado) {
                            header('Location: /mensaje');
                        }
                    }
                }
            }
            
            $router->render('auth/crear-cuenta', [
                'usuario' => $usuario,
                'alertas' => $alertas
            ]);
        }
               
        public static function mensaje(Router $router){
            $router->render('auth/mensaje');
        }
        public static function confirmar(Router $router){
            $alertas= [];

            $token = s($_GET['token']);

            $usuario = Usuario::where('token',$token);

            //debuguear($usuario);

            if(empty ($usuario)){
                Usuario::setAlerta('error','Token No Válido');
            }
            else{

                $usuario->confirmado="1";
                $usuario->token=null;
                $usuario->guardar();
                Usuario::setAlerta('exito','Cuenta Comprobada Correctamente');
                //debuguear($usuario);


            }
            $alertas= Usuario::getAlertas();
            $router->render('auth/confirmar-cuenta',[
                'alertas' => $alertas
            ]);
        }

    
    }

}