<?php

namespace App\Models;

use App\Config\Database;
use App\Models\Afiliado;

class Proyecto {
  public int $idProyecto;
  public string $proyecto;
  public float $montoRef;
  public string $estado;
  public string $fechaCreacion;
  public string $tipo; // ingreso egreso
  public int $idUsuario; // creado por
  // public float $monto;
  public function __construct($idProyecto = null) {
    if ($idProyecto != null) {
      $sql = "SELECT * FROM tblProyecto WHERE idProyecto = $idProyecto";
      $con = Database::getInstace();
      $stmt = $con->prepare($sql);
      $stmt->execute([]);
      $proyectoData = $stmt->fetch(); // Utiliza fetch en lugar de fetchAll
      if ($proyectoData) {
        $this->idProyecto = $proyectoData['idProyecto'];
        $this->proyecto = $proyectoData['proyecto'];
        $this->fechaCreacion = $proyectoData['fechaCreacion'];
        $this->idUsuario = $proyectoData['idUsuario'];
        $this->tipo = $proyectoData['tipo'];
        $this->estado = $proyectoData['estado'];
        $this->montoRef = $proyectoData['montoRef'];
      } else {
        $this->objectNull();
      }
    } else {
      $this->objectNull();
    }
  }
  public function objectNull() {
    $this->idProyecto = 0;
    $this->proyecto = '';
    $this->fechaCreacion = '';
    $this->idUsuario = 0;
    $this->tipo = '';
    $this->estado = '';
    $this->montoRef = 0.0;
  }
  public static function searchByName($name, $tipo) {
    $con = Database::getInstace();
    $sql = "SELECT * FROM tblProyecto WHERE proyecto LIKE '%$name%' AND tipo LIKE '$tipo';";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    return $rows;
  }

  public function save() {
    $res = -1;
    try {
      $con = Database::getInstace();
      if ($this->idProyecto == 0) { //insert
        $sql = "INSERT INTO tblProyecto(proyecto, idUsuario, tipo, montoRef, estado) VALUES(?, ?, ?, ?, ?);";
        $params = [$this->proyecto, $this->idUsuario, $this->tipo, $this->montoRef, $this->estado];
        $stmt = $con->prepare($sql);
        $res = $stmt->execute($params);
        if ($res) {
          $this->idProyecto = $con->lastInsertId();
          $res = $this->idProyecto;
        }
      } else { // update
        $sql = "UPDATE tblProyecto SET proyecto = ?, idUsuario = ?, tipo = ?, estado = ?, montoRef = ? WHERE idProyecto = ?";
        $params = [$this->proyecto, $this->idUsuario, $this->tipo, $this->estado, $this->montoRef, $this->idProyecto];
        $stmt = $con->prepare($sql);
        $res = $stmt->execute($params);
        if (!$res) {
          $res = -1;
        }
      }
    } catch (\Throwable $th) {
      print_r($th);
    }
    return $res;
  }

  public function delete() {
    $con = Database::getInstace();
    $sql = "DELETE FROM tblProyecto WHERE idProyecto = ?;";
    $params = [$this->idProyecto];
    $stmt = $con->prepare($sql);
    return $stmt->execute($params);
  }

  public function getPagos() {
    $con = Database::getInstace();
    $sql = "SELECT * FROM tblPago WHERE idProyecto = ?;";
    $params = [$this->idProyecto];
    $stmt = $con->prepare($sql);
    $stmt->execute($params);
    return count($stmt->fetchAll() ?? []);
  }
  public static function getAll($data) { // ingreso o egreso
    $con = Database::getInstace();
    $tipo = $data['tipo'];
    $estado = isset($data['estado']) ? "AND tp.estado LIKE '%" . $data['estado'] . "%' " : '';
    $year = isset($data['year']) ? "AND YEAR(tp.fechaCreacion) = " . $data['year'] : 'AND YEAR(tp.fechaCreacion) = ' . date('Y');
    $sql = "SELECT tp.*, tu.alias FROM tblProyecto tp INNER JOIN tblUsuario tu ON tp.idUsuario = tu.idUsuario WHERE tp.tipo LIKE '$tipo' $estado $year;";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    return $rows;
  }
  public static function getMontoPagos($data) {
    $con = Database::getInstace();
    $tipo = $data['tipo'];
    $estado = isset($data['estado']) ? "AND tp.estado LIKE '%" . $data['estado'] . "%' " : '';
    $year = isset($data['year']) ? "AND YEAR(tp.fechaCreacion) = " . $data['year'] : 'AND YEAR(tp.fechaCreacion) = ' . date('Y');
    $sql = "SELECT tp.idProyecto, SUM(tpa.monto) AS total
      FROM tblProyecto tp LEFT JOIN tblPago tpa ON tp.idProyecto = tpa.idProyecto
      WHERE tipo LIKE '$tipo' $estado $year
      GROUP BY tp.idProyecto";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    return $rows;
  }
}
