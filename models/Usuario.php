<?php
namespace Model;

class Usuario extends ActiveRecord{

    protected static $tabla ='usuarios';
    protected static $columnasDB =['id','nombre','apellido','email','password',
    'telefono','admin','confirmado','token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;
    
    public function __construct ($args =[]){
        $this->id = $args['id'] ?? null;
        $this->nombre=$args['nombre'] ?? '';
        $this->apellido=$args['apellido'] ?? '';
        $this->email=$args['email'] ?? '';
        $this->password=$args['password'] ?? '';
        $this->telefono=$args['telefono'] ?? '';
        $this->admin=$args['admin'] ?? 0;
        $this->confirmado=$args['confirmado'] ?? 0;
        $this->token=$args['token'] ?? '';
    }

    //Mensajes de validacion para la creacion de una cuenta

    public function validarNuevaCuenta(){
        if(!$this->nombre){
            self::$alertas['error'][] ="El nombre del Cliente es obligatorio";
        }
        if(!$this->apellido){
            self::$alertas['error'][] ="El apellido del Cliente es obligatorio";
        }
        if(!$this->telefono){
            self::$alertas['error'][] ="El telefono del Cliente es obligatorio";
        }
        if(!$this->email){
            self::$alertas['error'][] ="El email del Cliente es obligatorio";
        }
        if(!$this->password){
            self::$alertas['error'][] ="El password del Cliente es obligatorio";
        }
        if(strlen($this->password)<6){
            self::$alertas['error'][] ="El password debe tener mas de 6 caracteres";
        }
        return self::$alertas;

    }
    public function existeUsuario(){
        $query ="SELECT * FROM " .self::$tabla ." WHERE email='".$this->email."' LIMIT 1";
        //debuguear($query);

        $resultado = self::$db->query($query);
        if($resultado->num_rows){
            self::$alertas['error'][] ='El Usuario ya esta registrado';
        }
        return $resultado;
        //debuguear($resultado);
    }

    public function hashPassword(){
        $this->password= password_hash($this->password,PASSWORD_BCRYPT);
    }

    public function crearToken(){
        

        $this->token = uniqid();
    }

    public function validarLogin(){
        
    }

}