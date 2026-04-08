<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

function esUltimo(string $actual, string $proximo): bool{
    if($actual !== $proximo){
        return true;
    }
    return false;
}

//Funcion que revisa si el usuario esta autenticado
function isAuth(){
    if(!isset($_SESSION['login'])){
        header('Location: /');
        exit;
    }
}

//Funcion que revisa si el usuario es administrador
function isAdmin(){
    if(!isset($_SESSION['admin'])){
        header('Location: /');
        exit;
    }
}

function isLogin(){
    //Devuelve true si ya esta logueado el usurio
    if(isset($_SESSION['login'])){
        //Verificamos si es admin
        if(isset($_SESSION['admin'])){
            header('Location: /admin');
            exit;
        }else{
            //Regresa a la cita sino es admin
            header('Location: /cita');
            exit;
        }
    }
}