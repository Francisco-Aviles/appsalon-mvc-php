<?php
namespace Model;
use Model\ActiveRecord;

class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id','nombre','apellido','email', 'telefono', 'password', 'admin', 'confirmado', 'token'];
    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $telefono;
    public $password;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = []) {
        $this->id = $args['id'] ??  null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    //Mensajes de Validacion para creacion de la cuenta
    public function validar()
    {
        if(!$this->nombre){
            self::$alertas['error'][] = "El nombre es obligatorio";
        }

        if(!$this->apellido){
            self::$alertas['error'][] = "El apellido es obligatorio";
        }

        if(!$this->email){
            self::$alertas['error'][] = "El correo es obligatorio";
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][] = "El correo no es válido";
        }

        if(!$this->telefono){
            self::$alertas['error'][] = "El telefono es obligatorio";
        }

        if(strlen($this->telefono) !== 10){
            self::$alertas['error'][] = "El telefono no es válido";
        }

        if(!$this->password){
            self::$alertas['error'][] = "La contraseña es obligatoria";
        }

        if(mb_strlen($this->password) < 6 ){
            self::$alertas['error'][] = "La contraseña debe tener al menos 6 caracteres";
        }

        return self::$alertas;
    }

    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][] = "El correo es obligatorio";
        }

        if(!$this->password){
            self::$alertas['error'][] = "La contraseña es obligatoria";
        }

        return self::$alertas;
    }

    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][] = "El correo es obligatorio";
        }

        return self::$alertas;
    }

    public function validarNuevoPassword(){
        if(!$this->password){
            self::$alertas['error'][] = "La contraseña es obligatoria";
        }

        if(mb_strlen($this->password) < 6 ){
            self::$alertas['error'][] = "La contraseña debe tener al menos 6 caracteres";
        }

        return self::$alertas;
    }

    public function existeUsuario(){
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";
        $resultado = self::$db->query($query);

        if($resultado->num_rows){
            self::$alertas['error'][] = "EL usuario ya esta registrado";
        }

        return $resultado;
    }

    public function hashearPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function generarToken(){
        $this->token = uniqid();
    }

    public function comprobarPasswordYVerificado($password) {
        $resultado = password_verify($password, $this->password);

        if(!$resultado || !$this->confirmado){
            self::$alertas['error'][] = "Contraseña incorecta o tu cuenta no ha sido verificada";
            return false;
        }else{
            return true;
        }
    }
}