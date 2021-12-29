<?php

namespace Controllers;

use Classes\email;
use Model\Usuario;
use MVC\Router;

class LoginController{
    public static function login(Router $router){
        if($_SERVER['REQUEST_METHOD'] === "POST"){

        }

        $router->render('login/login',[
            'titulo' => 'Iniciar Sesion',

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
        if($_SERVER['REQUEST_METHOD'] === "POST"){
            
        }

        $router->render('login/olvide',[

            'titulo' => 'Olvide mi password'
        ]);
    }
    public static function reestablecer(Router $router){
        if($_SERVER['REQUEST_METHOD'] === "POST"){
            
        }

        $router->render('login/reestablecer',[

        ]);
    }
    public static function logout(){
        echo "Desde logout";
    }
    public static function mensaje(Router $router){
        $router->render('login/mensaje',[
            'titulo' => 'Cuenta creada'
        ]);
    }
    public static function confirmar(Router $router){
        $usuario= Usuario::where('token' , $_GET['token']);
        $alertas = [];
        if($usuario){
            unset($usuario->password2);
            $usuario->confirmarUsuario();
        }else{
            $alertas['errorGrave'][] = "TOKEN INVALIDO O EXPIRADO";
        }
        $router->render('login/confirmar',[
            'titulo' => 'Cuenta Confirmada',
            'alertas' => $alertas
        ]);
    }
}