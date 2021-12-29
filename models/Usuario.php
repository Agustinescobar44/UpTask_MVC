<?php

namespace Model;

class Usuario extends ActiveRecord{

    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id','nombre','email','password','token','confirmado'];

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? "";
        $this->email = $args['email'] ?? "";
        $this->password = $args['password'] ?? "";
        $this->password2 = $args['password2'] ?? "";
        $this->token = $args['token'] ?? "";
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    public function validar(){
        if(!$this->nombre){
            self::$alertas['error'][] = "El nombre de usuario es obligatorio" ;
        }
        if(!$this->email){
            self::$alertas['error'][] = "El Email es obligatorio" ;
        }
        if(!$this->password){
            self::$alertas['error'][] = "El password no puede estar vacio" ;
        }
        else if(strlen($this->password)<6){
            self::$alertas['error'][] = "Password demasiado corto, tienen que ser mas de 6 caracteres" ;
        }
        else if($this->password !== $this->password2){
            self::$alertas['error'][] = "Los passwords no son identicos" ;
        }
        return self::$alertas;
    }

    public function hashearPassword()
    {
        $this->password = password_hash($this->password,PASSWORD_BCRYPT);
        
    }
    public function generarToken()
    {
        $this->token = uniqid();
    }
    public function confirmarUsuario()
    {
        $this->token= "";
        $this->confirmado = 1;
        $this->guardar();
    }
}