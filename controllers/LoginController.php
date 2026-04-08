<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login(Router $router){
        $alertas = [];
        isLogin();

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();

            //Pasamos las validaciones
            if(empty($alertas)){
                //Verificar que exista el usuario
                $usuario = Usuario::where('email', $auth->email);

                if($usuario){
                    //Le pasamos el password que ingreso el usuario
                    if( $usuario->comprobarPasswordYVerificado($auth->password) ){
                        //Autenticar al usuario
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . ' ' . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;
                        
                        //Redireccionar
                        if($usuario->admin === '1'){
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                            exit;
                        }else{
                            header('Location: /cita');
                            exit;
                        }
                    }
                }else{
                    $alertas = Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }

        //Obtener alertas
        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    }

    public static function logout(){
        //Verifica si la sesión no ha sido iniciada
        if(session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION = [];
        session_destroy();

        header('Location: /');
        exit;
    }

    public static function olvide(Router $router){
        $alertas = [];
        isLogin();

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)){
                $usuario = Usuario::where('email', $auth->email);
                //Verificar que exista y que tenga confirmado su cuenta
                if($usuario && $usuario->confirmado === '1'){
                    //Generar un token
                    $usuario->generarToken();
                    //Almacena el token
                    $usuario->guardar();

                    //Enviar el email para restablecer el password
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    Usuario::setAlerta('exito', 'Revisa tu correo');
                }else{
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }
            }
        }

        //Obtiene las alertas
        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-password',[
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router){
        isLogin();
        /*para no perder la refencia del token en la url en la vista del formulario no se le agrega el action */
        $alertas = [];
        $error = false;
        //Validar el token
        $token = s($_GET['token'] ?? null);
        $usuario = Usuario::where('token', $token);

        //Sino existe el usuario con ese token ocultamos el formulario
        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no válido');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //Almacenar el nuevo password
            $password = new Usuario($_POST);
            $alertas = $password->validarNuevoPassword();

            if(empty($alertas)){
                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->hashearPassword();
                $usuario->token = '';
                $resultado = $usuario->guardar();

                //Redigir para que inice sesión
                if($resultado){
                    header('Location: /');
                    exit;
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/restablecer-password',[
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router){
        isLogin();
        $usuario = new Usuario;
        $alertas = Usuario::getAlertas();

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validar();

            if(empty($alertas)){
                //Verificar si existe el usario
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                }else{
                    //No esta registrado
                    //Hashear el password
                    $usuario->hashearPassword();

                    //Generar un token unico
                    $usuario->generarToken();

                    //Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    //Registrar al usario
                    $resultado = $usuario->guardar();

                    if($resultado){
                        header('Location: /mensaje');
                        exit;
                    }

                }
            }
        }

        $router->render('auth/registrar', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function confirmar(Router $router) {
        isLogin();
        $alertas = [];
        //Cuando no este presente la variable del token, agregamos ese valor solo para que no valide y devuelva el mensaje de error
        $token = s($_GET['token'] ?? 'nqa');
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token No Válido');
        }else{
            //Modificar a confirmado
            $usuario->confirmado = '1';
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente');
        }

        //Obtener alertas
        $alertas = Usuario::getAlertas();

        //renderizar la vista
        $router->render('auth/confirmar-cuenta',[
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router){
        isLogin();
        $router->render('auth/mensaje');
    }
}