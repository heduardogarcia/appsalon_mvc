<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email{
    public $email;
    public $nombre;
    public $token;

    public function __construct($email,$nombre,$token){
        $this->email=$email;
        $this->nombre=$nombre;
        $this->token=$token;
    }
    
    public function enviarConfirmacion(){
        $mail= new PHPMailer();

          //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
 
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '3c06370a9d3d3f';
        $mail->Password = '30b06986d4fb6f';   

        $mail->setFrom('admin@bienesraices.com');
        $mail->addAddress('admin@bienesraices.com','AppSalon.com');
        $mail->Subject='Confirma tu cuenta';

        $mail->isHTML(TRUE);
        $mail->CharSet='UTF-8';

        $contenido = "<html>";
        $contenido .="<p><strong>Hola " .$this->nombre ."</strong> Has creado tu cuenta en App Salon, solo debes confirmar presionando el siguiente enlace</p>";
        $contenido .="<p> Presiona aqui: <a href='http://localhost:3000/confirmar-cuenta?token=". $this->token ."'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";
        //debuguear($contenido);
        $mail->Body = $contenido;

        $mail->send();
        
    }
}