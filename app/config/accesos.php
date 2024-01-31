<?php

namespace App\Config;

use App\Config\Database;

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
      'bolivar' => ['base' => 'correpondencia', 'permisos' => []],
      'illimani' => ['base' => 'correpondencia2', 'permisos' => []],
    ];
    $empresa = isset($empresas[$pin]) ? $empresas[$pin] : null;
    if ($empresa) {
      $_SESSION['base'] = $empresa['base'];
      $_SESSION['permisos'] = $empresa['permisos'];
      session_write_close();
      setcookie('base', $empresa['base'], time() + 64800, '/', false);
      setcookie('permisos', json_encode($empresa['permisos']), time() + 64800, '/', false);
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
    unset($_SESSION['base']);
    unset($_SESSION['permisos']);
    setcookie('base', null, -1, '/', false);
    setcookie('permisos', null, -1, '/', false);
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
}
