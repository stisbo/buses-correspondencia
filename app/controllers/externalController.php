<?php

namespace App\Controllers;

use App\Config\Database;
use App\Models\External;

class ExternalController {
  public function trips_starting_today($data) {
    // $con = Database::db_boletos();
    $con = Database::db_boletos_sucursal();
    // next_days --> indica que obtenga todos los viajes mayor iguales a hoy
    $trips = External::get_trips($con, ['next_days' => true]);
    echo json_encode(['success' => true, 'data' => $trips]);
  }
  public function trips_starting_date($query) {
    // $con = Database::db_boletos();
    $con = Database::db_boletos_sucursal();
    $date = ($query['date'] == '' || $query['date'] == null) ? date('Y-m-d') : $query['date'];
    $trips = External::get_trips($con, ['date' => $date]);
    echo json_encode(['success' => true, 'data' => $trips]);
  }
  public function total_amount_trip($query) {
    if (!isset($query['trip_id']) && !isset($query['location'])) {
      echo json_encode(['success' => false, 'message' => 'trip_id is required']);
    } else {
      $keydbname = 'boletos_25_diciembre';

      // TODO: modificar para mas empresas
      $dbnames = ['boletos_25_diciembre' => 'correspondencia_25dic'];
      $con = Database::getInstanceX($dbnames[$keydbname] ?? '');
      if ($con == null) {
        echo json_encode(['success' => false, 'message' => 'Error conexion interno [missing names]']);
      } else {
        $total = External::get_total_amount_trip($con, $query['trip_id'], $query['location']);
        echo json_encode(['success' => true, 'data' => $total]);
      }
    }
  }
}
