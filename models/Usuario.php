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
        $this->password_actual = $args['password_actual'] ?? "";
        $this->password_nuevo = $args['password_nuevo'] ?? "";
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

    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][] = "El Email es obligatorio" ;
        }
        else if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][] = 'El email no es valido';
        }
        if(!$this->password){
            self::$alertas['error'][] = "El password no puede estar vacio" ;
        }
        return self::$alertas;
    }

    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        else if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][] = 'El email no es valido';
        }
        return self::$alertas;
    }

    public function validarPassword(){
        if(!$this->password){
            self::$alertas['error'][] = "Ingresa el nuevo password";
        }
        else if(strlen($this->password)<6){
            self::$alertas['error'][] = "El password debe tener mas de 6 caracteres";
        }
        else if(!$this->password2){
            self::$alertas['error'][] = "ambos passwords son necesarios";
        }
        elseif($this->password !== $this->password2){
            self::$alertas['error'][] = "Los passwords deben ser iguales";
        }
        return self::$alertas;
    }

    public function validarPerfil($nombreAnterior, $emailAnterior){
        if( $this->noHuboCambio($nombreAnterior, $emailAnterior) ){
            self::$alertas['error'][] = "No hubo cambios";
            return self::$alertas;
        }
        if(!$this->nombre){
            self::$alertas['error'][] = "El nombre es obligatorio";
        }
        if(!$this->email){
            self::$alertas['error'][] = "El email es obligatorio";
        }
        elseif(!filter_var($this->email,FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][] = "El email no es valido";
        }
        
        return self::$alertas;
    }

    public function noHuboCambio($nombreAnterior, $emailAnterior):bool{
        return $nombreAnterior===$this->nombre && $emailAnterior === $this->email;
    }

    public function hashearPassword(){
        $this->password = password_hash($this->password,PASSWORD_BCRYPT);
        
    }

    public function generarToken() {
        $this->token = uniqid();
    }

    public function confirmarUsuario(){
        $this->token= null;
        $this->confirmado = 1;
        $this->guardar();
    }

    public function nuevo_password(){
        if(!$this->password_actual){
            self::$alertas['error'][] = "El password actual es obligatorio";
        }
        else if(!password_verify($this->password_actual,$this->password)){
            self::$alertas['error'][] = "El password ingresado es distinto al anterior";
        }
        else if(!$this->password_nuevo){
            self::$alertas['error'][] = "El password nuevo es obligatorio";
        }
        else if(strlen($this->password_nuevo) < 6){
            self::$alertas['error'][] = "El password debe contener al menos 6 caracteres";
        }
        return self::$alertas;
    }
}