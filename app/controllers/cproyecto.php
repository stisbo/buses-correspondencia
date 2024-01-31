<?php

namespace App\Controllers;

use App\Models\Proyecto;

class CProyecto {
  public function create($data, $files) {
    if (!isset($_COOKIE['user_obj'])) {
      echo json_encode(['status' => 'error', 'message' => 'Cookies de sesión necesarias']);
    } else {
      if (!isset($data['idProyecto']) || $data['idProyecto'] == '0') {
        $user = json_decode($_COOKIE['user_obj']);
        $proyecto = new Proyecto();
        $proyecto->idUsuario = $user->idUsuario;
        $proyecto->proyecto = $data['proyecto'];
        $proyecto->tipo = $data['tipo'];
        $proyecto->estado = isset($data['estado']) ? $data['estado'] : 'PENDIENTE';
        $proyecto->montoRef = isset($data['montoRef']) ? $data['montoRef'] : 0.0;
        $res = $proyecto->save();
        if ($res > 0) {
          $proyecto->idProyecto = $res;
          echo json_encode(['status' => 'success', 'message' => 'Proyecto creado con exito', 'proyecto' => json_encode($proyecto)]);
        } else {
          echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error en la creación del proyecto']);
        }
      } else {
        $proyecto = new Proyecto($data['idProyecto']);
        $proyecto->proyecto = $data['proyecto'];
        $proyecto->tipo = $data['tipo'];
        $proyecto->estado = isset($data['estado']) ? $data['estado'] : 'SIN ESTADO';
        $proyecto->montoRef = isset($data['montoRef']) ? $data['montoRef'] : 0.0;
        $res = $proyecto->save();
        if ($res > 0) {
          echo json_encode(['status' => 'success', 'message' => 'Proyecto actualizado con exito', 'proyecto' => json_encode($proyecto)]);
        } else {
          echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error en la actualizacion del proyecto']);
        }
      }
    }
  }
  public function search($data) {
    try {
      $proyects = Proyecto::searchByName($data['q'], $data['type']);
      echo json_encode(['status' => 'success', 'data' => json_encode($proyects)]);
    } catch (\Throwable $th) {
      echo json_encode(['status' => 'error', 'error' => json_encode($th)]);
    }
  }
  public function getProjects($data) {
    try {
      $projects = Proyecto::getAll($data);
      $pagos = Proyecto::getMontoPagos($data);
      echo json_encode(['status' => 'success', 'data' => json_encode($projects), 'pagos' => json_encode($pagos)]);
    } catch (\Throwable $th) {
      //throw $th;
      echo json_encode(['status' => 'error', 'error' => json_encode($th)]);
    }
  }
  public function getProjectID($data) {
    if (!isset($data['idProyecto']) || $data['idProyecto'] == 0) {
      echo json_encode(['status' => 'error', 'message' => 'El id del proyecto es requerido']);
    } else {
      try {
        $idProyecto = $data['idProyecto'];
        $proyecto = new Proyecto($idProyecto);
        echo json_encode(['status' => 'success', 'data' => json_encode($proyecto)]);
      } catch (\Throwable $th) {
        //throw $th;
        echo json_encode(['status' => 'error', 'error' => json_encode($th)]);
      }
    }
  }
  public function delete($data) {
    $idProyecto = $data['idProyecto'];
    $proyecto = new Proyecto($idProyecto);
    $nPagos = $proyecto->getPagos();
    if ($nPagos > 0) {
      echo json_encode(['status' => 'error', 'message' => 'El proyecto tiene pagos asociados, no se puede eliminar']);
    } else {
      $res = $proyecto->delete();
      if ($res) {
        echo json_encode(['status' => 'success', 'message' => 'Proyecto eliminado con exito']);
      } else {
        echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error al eliminar el proyecto']);
      }
    }
  }
}
