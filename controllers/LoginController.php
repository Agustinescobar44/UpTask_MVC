<?php

namespace Controllers;

use MVC\Router;

class LoginController{
    public static function login(Router $router){
        if($_SERVER['REQUEST_METHOD'] === "POST"){

        }
        echo "Desde LOGIN";

        $router->render('login/crear',[
            
        ]);
    }
    public static function logout(){
        echo "Desde logout";
    }
    public static function crear(Router $router){
        if($_SERVER['REQUEST_METHOD'] === "POST"){
            
        }
        echo "Desde Crear";

        $router->render('login/crear',[

        ]);
    }
    public static function olvide(Router $router){
        if($_SERVER['REQUEST_METHOD'] === "POST"){
            
        }
        echo "Desde olvide";

        $router->render('login/olvide',[
            
        ]);
    }
}