<?php
namespace Controllers;

use Model\Proyecto;
use Model\Tarea;

class TareaController {
    public static function index(){
        $proyectoUrl = $_GET['url'];
        if(!$proyectoUrl) 
            header('Location: /dashboard');

        $proyecto = Proyecto::where('url', $proyectoUrl);
        session_start();
        if(!$proyecto || $proyecto->usuarioId !== $_SESSION['id'])
             header('Location: /404');

        $tareas = Tarea::whereAll('proyectoId',$proyecto->id);

        echo json_encode([
            'tareas' =>  $tareas,
        ]);

        if($_SERVER['REQUEST_METHOD'] === "POST"){
        }
    }
    public static function crear(){
        if($_SERVER['REQUEST_METHOD'] === "POST"){

            session_start();
            $proyectoId = $_POST['proyectoId'];

            $proyecto = Proyecto::where('url', $proyectoId);

            if(!$proyecto || $proyecto->usuarioId !== $_SESSION['id'] ){
                $respuesta=[
                    'tipo' => 'error',
                    'mensaje' => 'hubo un error al agregar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            } 
            //todo ok, instanciar y guardar la tarea
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();
            
            $respuesta=[
                'tipo' => 'exito',
                'mensaje' => 'Tarea agregada correctamente!',
                'id' => $resultado['id'],
                'proyectoId' => $proyecto->id
            ];

            echo json_encode($respuesta);
        }
    }
    public static function actualizar(){
        if($_SERVER['REQUEST_METHOD'] === "POST"){
            session_start();

            //ver que exista el proyecto

            $proyecto = Proyecto::where('url',$_POST['proyectoId']);
            if(!$proyecto || $proyecto->usuarioId !== $_SESSION['id'] ){
                $respuesta=[
                    'tipo' => 'error',
                    'mensaje' => 'hubo un error al actualizar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            } 

            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;

            $resultado = $tarea->guardar();
            if($resultado){
                $respuesta = [
                    'tipo' => 'exito',
                    'id' => $tarea->id,
                    'proyectoId' => $proyecto->id,
                    'mensaje' => "Tarea actualizada correctamente"
                ];
                echo json_encode(['respuesta' => $respuesta]);
            }

        }
    }
    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] === "POST"){
            session_start();

            //ver que exista el proyecto

            $proyecto = Proyecto::where('url',$_POST['proyectoId']);
            if(!$proyecto || $proyecto->usuarioId !== $_SESSION['id'] ){
                $respuesta=[
                    'tipo' => 'error',
                    'mensaje' => 'hubo un error al actualizar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            } 

            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;

            $respuesta = $tarea -> eliminar();

            $resultado = [
                'respuesta' => $respuesta,
                'mensaje' => "Tarea eliminada correctamente",
                'tipo' => 'exito'
            ];

            echo json_encode($resultado);
        }
    }
}