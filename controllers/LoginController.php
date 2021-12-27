<?php

namespace Controllers;

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
        if($_SERVER['REQUEST_METHOD'] === "POST"){
            
        }

        $router->render('login/crear',[
            'titulo' => 'Crear Cuenta',

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
        $router->render('login/confirmar',[
            'titulo' => 'Cuenta Confirmada'
        ]);
    }
}