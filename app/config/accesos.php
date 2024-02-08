<?php

namespace App\Config;

class Accesos {
  public static function setAccesos($pin) { // seteamos las cookies para un nuevo ingreso login
    // Nos conectamos a la base de datos para obtener la base, permisos, etc
    // $con = Database::connectionEmpresa();
    // $sql = "SELECT * FROM empresas WHERE pin = ?";
    // $stmt = $con->prepare($sql);
    // $stmt->execute([$pin]);
    // $empresa = $stmt->fetch();
    // echo 'PASO UNONNNNNNN ---  ' . $pin;
    $empresas = [
      'bolivar' => ['base' => 'correspondencia', 'dominio' => 'bolivar', 'permisos' => []],
      'illimani' => ['base' => 'correpondencia2', 'dominio' => 'illimani', 'permisos' => []],
    ];
    $empresa = isset($empresas[$pin]) ? $empresas[$pin] : null;
    if ($empresa) {
      $_SESSION['base'] = $empresa['base'];
      $_SESSION['dominio'] = $empresa['dominio'];
      $_SESSION['permisos'] = $empresa['permisos'];
      session_write_close();
      setcookie('base', $empresa['base'], time() + 64800, '/', false);
      setcookie('permisos', json_encode($empresa['permisos']), time() + 64800, '/', false);
      setcookie('_emp', json_encode(array('dominio' => $empresa['dominio'])), time() + 64800, '/', false);
      return 1;
    } else { // no existe el PIN
      return -1;
    }
  }
  public static function getPermisos() {
    return $_COOKIE['permisos'];
  }
  public static function delAccesos() {
    unset($_COOKIE['base']);
    unset($_COOKIE['permisos']);
    unset($_COOKIE['dominio']);
    unset($_SESSION['base']);
    unset($_SESSION['permisos']);
    setcookie('base', null, -1, '/', false);
    setcookie('permisos', null, -1, '/', false);
    setcookie('_emp', null, -1, '/', false);
    session_destroy();
  }
  public static function base() {
    if (isset($_COOKIE['base'])) {
      // echo 'EXISTE BASE?????? ' . $_COOKIE['base'];
      $base = $_COOKIE['base'];
    } else if (isset($_SESSION['base'])) {
      $base = $_SESSION['base'];
    } else {
      $base = null;
    }
    return $base;
  }
  public static function dominio() {
    if (isset($_COOKIE['dominio'])) {
      $domain = $_COOKIE['dominio'];
    } else if (isset($_SESSION['dominio'])) {
      $domain = $_SESSION['dominio'];
    } else {
      $domain = null;
    }
    return $domain;
  }
}
