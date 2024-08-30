<?php

namespace App\Controllers;

use App\Config\Database;
use App\Models\External;

class ExternalController {
  public function trips_starting_today($data) {
    $con = Database::db_boletos();
    $trips = External::get_trips($con, []);
    echo json_encode(['success' => true, 'data' => $trips]);
  }
  public function trips_starting_date($query) {
    $con = Database::db_boletos();
    $date = ($query['date'] == '' || $query['date'] == null) ? date('Y-m-d') : $query['date'];
    $trips = External::get_trips($con, ['date' => $date]);
    echo json_encode(['success' => true, 'data' => $trips]);
  }
  public function total_amount_trip($query) {
    if (!isset($query['trip_id']) && !isset($query['key'])) {
      echo json_encode(['success' => false, 'message' => 'trip_id is required']);
    } else {
      $keydbname = json_decode(base64_decode($query['key']), true);
      // modificar para mas empresas
      $dbnames = ['boletos_25_diciembre' => 'correspondencia_25dic'];
      $con = Database::getInstanceX($dbnames[$keydbname['dbname']] ?? '');
      if ($con == null) {
        echo json_encode(['success' => false, 'message' => 'Error conexion interno [missing names]']);
      } else {
        $total = External::get_total_amount_trip($con, $query['trip_id']);
        echo json_encode(['success' => true, 'data' => $total]);
      }
    }
  }
}
