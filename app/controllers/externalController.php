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
}
