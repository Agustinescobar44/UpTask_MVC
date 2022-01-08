<?php

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;

class DashBoardController{
    public static function index(Router $router){

        session_start();

        isAuth();

        $proyectos = Proyecto::whereAll('usuarioId', $_SESSION['id']);

        $router->render('dashboard/index',[
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }
    public static function crear(Router $router){

        session_start();
        isAuth();

        $alertas = [];

        if(esPost()){
            $proyecto = new Proyecto($_POST);
            //validacion
            $alertas = $proyecto->validarProyecto();
            if(empty($alertas)){
                //guardar el proyecto
                $proyecto->generarUrl();

                //Almacenar el propietario
                $proyecto->usuarioId = $_SESSION['id'];

                //Almacenar el proyecto
                $proyecto->guardar();
                //redireccionar
                header('Location: /proyecto?url='.$proyecto->url);
            }
        }

        $router->render('dashboard/crear',[
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }
    public static function perfil(Router $router){

        session_start();
        isAuth();

        $usuario = Usuario::find($_SESSION['id']);
        $alertas = [];

        if(esPost()){
            $nombreAnterior = $usuario->nombre;
            $emailAnterior = $usuario->email;
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarPerfil($nombreAnterior,$emailAnterior);

            if(empty($alertas)){

                $existeUSuario = Usuario::where('email',$usuario->email);

                if($existeUSuario && $existeUSuario->id !== $usuario->id){
                    //mensaje de error
                    Usuario::setAlerta('error', 'Email no valido o ya pertenece a otra cuenta');
                    $alertas=Usuario::getAlertas();
                } else {
                    //guardar el usuario
                    $usuario->guardar();

                    //agregar la alerta de exito
                    Usuario::setAlerta('exito', 'Actualizado correctamente!');
                    $alertas=Usuario::getAlertas();

                    //actualizar la session
                    $_SESSION['nombre'] = $usuario->nombre;
                    $_SESSION['email'] = $usuario->email;
                }

                
            }

        }

        $router->render('dashboard/perfil',[
            'titulo' => 'Perfil',
            'alertas' => $alertas,
            "usuario" => $usuario
        ]);
    }

    public static function proyecto(Router $router){

        session_start();

        isAuth();

        $token = $_GET['url'];
        if(!$token) header('Location: /dashboard');

        //comparar id de la session  con el del usuarioId del proyecto
        $url = s($_GET['url']);
        $proyecto = Proyecto::where('url' , $url);

        if($proyecto->usuarioId !== $_SESSION['id']) header('Location: /dashboard');

        if($_SERVER['REQUEST_METHOD'] === "POST"){
            
        }


        $router->render('dashboard/proyecto',[
            'titulo' => $proyecto->proyecto
        ]);
    }
    public static function cambiar_password(Router $router){
        session_start();
        isAuth();
        $alertas = [];

        if(esPost()){
            $usuario = Usuario::find($_SESSION['id']);

            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevo_password();

            if(empty($alertas)){
                //asignar nuevo password
                $usuario->password = $usuario->password_nuevo;
                
                //Eliminar atributos innecesarios
                unset($usuario->password_actual);
                unset($usuario->password_nuevo);
                unset($usuario->password2);

                //hashear password
                $usuario->hashearPassword();

                //Actualizar el registro
                $resultado = $usuario->guardar();

                if($resultado){
                    Usuario::setAlerta('exito', 'Password actualizado correctamente');
                } else { 
                    Usuario::setAlerta('error', 'El password no se pudo actualizar, intentalo nuevamente');
                }
            }

        }
        $alertas = Usuario::getAlertas();

        $router->render('dashboard/cambiar-password',[
            'titulo' => "Cambiar Password",
            'alertas' => $alertas,

        ]);
    }
}