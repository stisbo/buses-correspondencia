<?php

namespace App\Config;

use App\Config\Accesos;

class Database {
  private static $con = null;
  private function __construct() {
  }
  public static function getInstace() {
    $base = Accesos::base();
    if ($base == null) {
      return null;
    }
    $serverName = "localhost";
    $databaseName = $base;
    $username = "";
    $password = "";
    try {
      self::$con = new \PDO("sqlsrv:Server=$serverName;Database=$databaseName;Encrypt=0;TrustServerCertificate=1", $username, $password);
      self::$con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
      self::$con = null;
      die("Error de conexión: " . $e->getMessage());
    }
    return self::$con;
  }
  public static function connectionEmpresa() {
    $serverName = "localhost";
    $databaseName = 'empresas';
    $username = "";
    $password = "";
    try {
      self::$con = new \PDO("sqlsrv:Server=$serverName;Database=$databaseName;Encrypt=0;TrustServerCertificate=1", $username, $password);
      self::$con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
      self::$con = null;
      die("Error de conexión: " . $e->getMessage());
    }
    return self::$con;
  }
}
