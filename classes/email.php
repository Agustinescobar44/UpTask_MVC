<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class email{

    public function __construct($nombre,$email,$token) {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->token = $token;
    }

    public function enviarToken()
    {

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '26ac97a2c1d1af';
        $mail->Password = '9eb2a7626f01c5';

        //informacion de envio
        $mail->setFrom('up@task.com', 'uptask team');
        $mail->addAddress($this->email,$this->nombre);

        //metadata
        $mail->isHTML(true); 
        $mail->Subject = 'Confirmacion de cuenta en UpTask';
        $mail->CharSet='UTF-8';
        //body
        $body="<h1>Hola <bold>".$this->nombre."</bold> !</h1>";
        $body.="<p>Estas recibiendo este correo por una registracion en nuestra web UpTask</p>";
        $body.="<p>Presiona el siguiente boton para confirmar la cuenta</p>";
        $body.="<a href='http://localhost:3000/confirmar?token=" . $this->token ."'><button>Confirmar cuenta</button></a>";
        $body.="<p>Si no fuiste tu desestima este mail</p>";
        $body.="<p>Bienvenido a UpTask! esperamos ver crecer tus proyectos</p>";

        $mail->Body = $body;

        //enviar
        $mail->send();
    }
}