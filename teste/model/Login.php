<?php

class Login {

  private $foto_perfil;
  private $ultimo_acesso;

  function __construct($foto_perfil) {
    $this->foto_perfil = $foto_perfil;
  }
  
  public static function naoLogado() {
    if (empty($_SESSION['usuario'])) {
        header("Location: login.php");
        exit();
    }
  }

  public function getFoto() {
    return $this->foto_perfil;
  }
  public function setFoto($foto_perfil){
    $this->foto_perfil = $foto_perfil;
  }

}

