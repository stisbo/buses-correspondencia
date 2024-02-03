<?php

namespace App\Models;

use App\Config\Database;
use ReflectionClass;


class Envio {
  private static
    $sql_envio = "SELECT  
	  (SELECT lugar FROM tblLugar WHERE idLugar = e.id_lugar_origen) as origen,
	  (SELECT lugar FROM tblLugar WHERE idLugar = e.id_lugar_destino) as destino,
	  (SELECT nombre FROM tblUsuario WHERE idUsuario = e.id_usuario_envio) as usuario_envio,
	  e.*
  FROM tblEnvio e
  WHERE e.estado = 'ENVIADO'";
  private static
    $sql = "SELECT  
	  (SELECT lugar FROM tblLugar WHERE idLugar = e.id_lugar_origen) as origen,
	  (SELECT lugar FROM tblLugar WHERE idLugar = e.id_lugar_destino) as destino,
	  (SELECT nombre FROM tblUsuario WHERE idUsuario = e.id_usuario_envio) as usuario_envio,
	  e.*
  FROM tblEnvio e";
  private static $sql_recibo = "SELECT  
	  (SELECT lugar FROM tblLugar WHERE idLugar = e.id_lugar_origen) as origen,
	  (SELECT lugar FROM tblLugar WHERE idLugar = e.id_lugar_destino) as destino,
	  (SELECT nombre FROM tblUsuario WHERE idUsuario = e.id_usuario_envio) as usuario_envio,
	  e.*
  FROM tblEnvio e
  WHERE e.estado != 'ENTREGADO'";
  public int $idEnvio;
  public string $codigo;
  public int $id_usuario_envio;
  public string $usuario_envio;
  public int $id_usuario_recibe;
  public string $usuario_recibe;
  public string $estado, $detalle_envio, $fecha_envio, $fecha_llegada, $observacion_llegada, $fecha_estimada;
  public string $nombre_origen;
  public string $ci_origen;
  public string $celular_origen;
  public int $id_lugar_origen;
  public string $origen;

  public string $nombre_destino;
  public string $ci_destino;
  public int $id_lugar_destino;
  public string $celular_destino;
  public string $destino;

  public function __construct($idEnvio = 0) {
    if ($idEnvio == 0) {
      $this->objectNull();
    } else {
      $con = Database::getInstace();
      $sql = self::$sql . " WHERE e.idEnvio = $idEnvio";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $row = $stmt->fetch();
      if ($row) {
        $this->objectNull();
        $this->load($row);
      } else {
        $this->objectNull();
      }
    }
  }

  public function insert() { // nuevo envio
    try {
      $sql = "INSERT INTO tblEnvio(codigo, id_usuario_envio, estado, detalle_envio, fecha_envio, fecha_estimada, nombre_origen, ci_origen, celular_origen, id_lugar_origen, nombre_destino, ci_destino, id_lugar_destino, celular_destino)
      VALUES (:codigo, :idUsuarioEnvio, 'ENVIADO', :detalle_envio, :fecha_envio, :fecha_estimada, :nombre_origen, :ci_origen, :celular_origen, :id_lugar_origen, :nombre_destino, :ci_destino, :id_lugar_destino, :celular_destino);";
      $con = Database::getInstace();
      $stmt = $con->prepare($sql);
      $params = ['codigo' => $this->codigo, 'idUsuarioEnvio' => $this->id_usuario_envio, 'detalle_envio' => $this->detalle_envio, 'fecha_envio' => $this->fecha_envio, 'fecha_estimada' => $this->fecha_estimada, 'nombre_origen' => $this->nombre_origen, 'ci_origen' => $this->ci_origen, 'celular_origen' => $this->celular_origen, 'id_lugar_origen' => $this->id_lugar_origen, 'nombre_destino' => $this->nombre_destino, 'ci_destino' => $this->ci_destino, 'id_lugar_destino' => $this->id_lugar_destino, 'celular_destino' => $this->celular_destino];
      $res = $stmt->execute($params);
      if ($res) {
        $idEnvio = $con->lastInsertId();
        return $idEnvio;
      }
    } catch (\Exception $e) {
      print_r($e);
    }
    return 0;
  }

  public function update($anterior) {
    try {
      $cadena = "";
      $params = [];
      foreach ($this as $name => $value) {
        if ($this->$name != $anterior->$name) {
          $cadena .= "$name = :$name, ";
          $params[$name] = $this->$name;
        }
      }
      if ($cadena != "") {
        $cadena = substr($cadena, 0, -2);
        $sql = "UPDATE tblEnvio SET $cadena WHERE idEnvio = :idEnvio";
        $con = Database::getInstace();
        $stmt = $con->prepare($sql);
        $params['idEnvio'] = $this->idEnvio;
        $res = $stmt->execute($params);
        return $res;
      }
    } catch (\Throwable $th) {
      print_r($th);
    }
    return 0;
  }

  public function objectNull() {
    $this->idEnvio = 0;
    $this->codigo = "";
    $this->id_usuario_envio = 0;
    $this->usuario_envio = "";
    $this->id_usuario_recibe = 0;
    $this->usuario_recibe = "";
    $this->estado = "";
    $this->detalle_envio = "";
    $this->fecha_envio = "";
    $this->fecha_llegada = "";
    $this->observacion_llegada = "";
    $this->fecha_estimada = "";
    $this->nombre_origen = "";
    $this->ci_origen = "";
    $this->celular_origen = "";
    $this->id_lugar_origen = 0;
    $this->origen = "";
    $this->nombre_destino = "";
    $this->ci_destino = "";
    $this->id_lugar_destino = 0;
    $this->celular_destino = "";
    $this->destino = "";
  }
  public function load($row) {
    foreach ($this as $nombre => $valor) {
      if (isset($row[$nombre])) {
        $this->$nombre = $row[$nombre];
      }
    }
  }
  public function genCode() {
    return strtoupper(substr(uniqid(), -6));
  }

  public static function getRecibir($idDestino, $estado = null) {
    try {
      $sql = self::$sql_recibo . " AND e.id_lugar_destino = $idDestino";
      if ($estado != null) {
        $sql .= " AND e.estado = '$estado'";
      }
      $con = Database::getInstace();
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll();
      return $rows;
    } catch (\Throwable $th) {
      //throw $th;
      print_r($th);
    }
    return [];
  }
  public static function getEntregados($idUsuario) {
    try {
      $sql = self::$sql . " WHERE e.id_usuario_recibe = $idUsuario";
      $con = Database::getInstace();
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll();
      return $rows;
    } catch (\Throwable $th) {
      //throw $th;
    }
    return [];
  }
}
