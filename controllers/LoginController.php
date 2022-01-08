<?php

namespace Controllers;

use Classes\email;
use Model\Usuario;
use MVC\Router;

class LoginController{
    public static function login(Router $router){
        $alertas= [];
        if($_SERVER['REQUEST_METHOD'] === "POST"){
            $usuario = new Usuario($_POST);
            
            $alertas = $usuario->validarLogin();

            if(empty($alertas)){
                $usuario = Usuario::where('email',$usuario->email);
                if(!$usuario || !$usuario->confirmado){
                    Usuario::setAlerta('error', 'Correo Invalido O Cuenta sin Confirmar');
                }
                else{
                    //verifico el password
                    if(!password_verify($_POST['password'], $usuario->password)){
                        Usuario::setAlerta('error', 'Password invalido');

                    }
                    else{
                        if(!isset($_SESSION)) session_start();
                        $_SESSION['login'] = true;
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;

                        //Redireccionar
                        header('Location: /dashboard');
                    }
                }
            }
        }
        
        $alertas = Usuario::getAlertas();
        $router->render('login/login',[
            'titulo' => 'Iniciar Sesion',
            'alertas' => $alertas
        ]);
    }
    public static function crear(Router $router){
        $usuario = new Usuario();
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === "POST"){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validar();
            if(empty($alertas)){
                $existeUsuario = Usuario::where('email',$usuario->email);
                if($existeUsuario){
                    Usuario::setAlerta('error','El usuario ya esta registrado');
                    $alertas = Usuario::getAlertas();

                }else {
                    //Hashear Password
                    $usuario->hashearPassword();
                    //Eliminar Password2
                    unset($usuario->password2);
                    //Crear Token
                    $usuario->generarToken();

                    $resultado = $usuario->guardar();

                    if($resultado){
                        $email = new email($usuario->nombre,$usuario->email,$usuario->token);
                        $email->enviarToken();
                        header('Location: /mensaje?email='.$usuario->email);
                    }
                }
            }
        }


        $router->render('login/crear',[
            'titulo' => 'Crear Cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
    public static function olvide(Router $router){
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === "POST"){
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alertas)){
                //buscar el usuario
                $usuario = Usuario::where('email' , $usuario->email);
                if($usuario && $usuario->confirmado){
                    //generar token
                    unset($usuario->password2);
                    $usuario->generarToken();
                    //Actualizar Usuario
                    $usuario->guardar();
                    //Enviar Email
                    $mail = new email($usuario->nombre,$usuario->email,$usuario->token);
                    $mail->enviarRecuperar();
                    //imprimir alerta
                    header('Location: /mensaje');
                }
                else{
                    Usuario::setAlerta('error', 'El usuario no existe');
                }
            }
        }
        $alertas = Usuario::getAlertas();

        $router->render('login/olvide',[
            'titulo' => 'Olvide mi password'
            ,'alertas' => $alertas
        ]);
    }
    public static function reestablecer(Router $router){
        $token = s($_GET['token']);
        if(!$token) header('Location: /');

        $mostrar = true;

        $usuario = Usuario::where('token', $token);

        if(!$usuario){
            Usuario::setAlerta('error' , 'Token Invalido');
            $mostrar = false;
        }

        if($_SERVER['REQUEST_METHOD'] === "POST"){
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarPassword();
            
            if(empty($alertas)){

                    //actualizar y hashaer password
                    $usuario->hashearPassword();
                    //eliminar token y acutalizar el password
                    $usuario->confirmarUsuario();
                    //redireccionar
                    header('Location: /');

            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('login/reestablecer',[
            'titulo' => 'reestablecer password'
            ,'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }
    public static function logout(){
        session_start();
        $_SESSION = [];
        header('Location: /');
    }
    public static function mensaje(Router $router){
        $router->render('login/mensaje',[
        ]);
    }
    public static function confirmar(Router $router){

        $token = s($_GET['token']);

        if(!$token) header('Location: /');

        $usuario= Usuario::where('token' , $token);
        if(!empty($usuario)){
            unset($usuario->password2);
            $usuario->confirmarUsuario();
        }else{
            Usuario::setAlerta('error','Token invalido');
        }
        $alertas = Usuario::getAlertas();
        $router->render('login/confirmar',[
            'titulo' => 'Cuenta Confirmada',
            'alertas' => $alertas
        ]);
    }
}